<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function __construct()
    {
        $secret = Setting::where('key', 'stripe_secret_key')->value('value') ?: config('services.stripe.secret');
        Stripe::setApiKey($secret);
    }

    // =========================================================================
    // POST /api/subscriptions/checkout
    // Creates a Stripe Checkout Session and returns the checkout URL.
    // Body: { "plan_id": 2, "billing": "monthly" }
    // =========================================================================
    public function createCheckout(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'billing' => 'required|in:monthly,yearly',

            // New Billing Fields from Form
            'full_name' => 'required|string|max:191',
            'company_name' => 'nullable|string|max:191',
            'my_name' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'address' => 'required|string|max:255',

            // Checkbox Enforcements
            'accepted_terms' => 'accepted',
            'accepted_privacy' => 'accepted',
            'accepted_recurring' => 'accepted',
        ]);

        $user = $request->user();
        $plan = Plan::findOrFail($request->plan_id);

        // Prevent re-subscribing to the same plan
        $currentSub = $user->activeSubscription;
        if ($currentSub && $currentSub->plan_id == $plan->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to this plan.',
            ], 422);
        }

        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');
        $amount = $request->billing === 'yearly' ? $plan->yearly_price : $plan->price;

        // --- CUSTOMER PERSISTENCE LOGIC ---
        // Try to find if this user already has a Stripe Customer ID in our records
        $lastSub = Subscription::where('user_id', $user->id)
            ->whereNotNull('stripe_customer_id')
            ->latest()
            ->first();

        try {
            $sessionParams = [
                'mode' => 'payment',
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                ],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'huf',
                            'product_data' => [
                                'name' => $plan->name . ($request->billing === 'yearly' ? ' (Yearly)' : ' (Monthly)'),
                                'description' => 'Subscription for ' . $plan->name,
                            ],
                            'unit_amount' => (int) ($amount * 100),
                        ],
                        'quantity' => 1,
                    ]
                ],
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'billing_period' => $request->billing,
                    'full_name' => $request->full_name,
                    'company' => $request->company_name,
                    'city' => $request->city,
                    'address' => $request->address,
                ],
                'success_url' => $frontendUrl . '/account/billing?status=success&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $frontendUrl . '/account/billing?status=cancelled',
            ];

            if ($lastSub) {
                // Reuse existing customer
                $sessionParams['customer'] = $lastSub->stripe_customer_id;
            } else {
                // Create a new customer and link to email
                $sessionParams['customer_email'] = $user->email;
                $sessionParams['customer_creation'] = 'always';
            }

            $session = StripeSession::create($sessionParams);

            // Create a pending subscription record with ALL info
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $amount,
                'status' => 'pending',
                'starts_at' => now(),
                'next_billing_at' => $request->billing === 'yearly' ? now()->addYear() : now()->addMonth(),
                'ends_at' => $request->billing === 'yearly' ? now()->addYear() : now()->addMonth(),
                'duration' => $request->billing === 'yearly' ? 'Yearly' : 'Monthly',
                'stripe_session_id' => $session->id,

                // Saving Billing Info
                'billing_full_name' => $request->full_name,
                'billing_company_name' => $request->company_name,
                'billing_my_name' => $request->my_name,
                'billing_city' => $request->city,
                'billing_address' => $request->address,
                'accepted_terms' => $request->boolean('accepted_terms'),
                'accepted_privacy' => $request->boolean('accepted_privacy'),
                'accepted_recurring' => $request->boolean('accepted_recurring'),
            ]);

            // Update session with subscription_id for easier lookup in webhook
            $session->metadata['subscription_id'] = $subscription->id;
            // Note: Update to already created session isn't directly possible like this, 
            // but we can pass it during creation. Let's fix the creation part.

            // Re-creating the logic to include subscription_id in metadata from start
            // Actually, we can't get ID before create, but we can update the session metadata if needed.
            // However, Stripe Sessions are immutable. So let's just make sure we capture it in handleWebhook.

            // OPTIMIZATION: We already have stripe_session_id in our DB. So that is enough.
            // But we can also add it to the payment intent metadata for redundancy.

            return response()->json([
                'success' => true,
                'message' => 'Checkout session created.',
                'checkout_url' => $session->url,
                'session_id' => $session->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create checkout session. Please try again.',
            ], 500);
        }
    }

    // =========================================================================
    // POST /api/webhooks/stripe  (public, no auth — verified via signature)
    // Stripe calls this after a successful payment.
    // =========================================================================
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = Setting::where('key', 'stripe_webhook_secret')->value('value') ?: config('services.stripe.webhook_secret');

        Log::info("CarSwap Webhook: Received request.");

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::warning('CarSwap Webhook: Signature mismatch: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info("CarSwap Webhook: Processing event type: " . $event->type);

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->activateSubscription($session->id, $session->customer, $session->payment_intent);
                break;

            case 'payment_intent.succeeded':
                $intent = $event->data->object;
                Log::info("CarSwap Webhook: PaymentIntent succeeded: " . $intent->id);
                // Try to find subscription by session if available, or other metadata
                // Usually PaymentIntent has the session ID in metadata if created via Checkout
                $sessionId = $intent->metadata->stripesessionid ?? null;
                if ($sessionId) {
                    $this->activateSubscription($sessionId, $intent->customer, $intent->id);
                }
                break;

            case 'customer.subscription.deleted':
                $stripeSub = $event->data->object;
                Subscription::where('stripe_subscription_id', $stripeSub->id)
                    ->update(['status' => 'expired']);
                Log::info("CarSwap Webhook: Subscription deleted: " . $stripeSub->id);
                break;
        }

        return response()->json(['received' => true]);
    }

    /**
     * Helper to activate a subscription
     */
    private function activateSubscription($sessionId, $customerId, $paymentIntentId)
    {
        Log::info("CarSwap Webhook: Attempting to activate subscription for session: " . $sessionId);

        $subscription = Subscription::where('stripe_session_id', $sessionId)->first();

        if (!$subscription) {
            Log::warning("CarSwap Webhook: Subscription not found for session: " . $sessionId);
            return;
        }

        if ($subscription->status === 'active') {
            Log::info("CarSwap Webhook: Subscription already active: " . $subscription->id);
            return;
        }

        $updateData = [
            'status' => 'active',
            'stripe_customer_id' => $customerId,
        ];

        // Capture Payment Method for off-session billing
        if ($paymentIntentId) {
            try {
                $intent = PaymentIntent::retrieve($paymentIntentId);
                if ($intent->payment_method) {
                    $pm = PaymentMethod::retrieve($intent->payment_method);
                    $updateData['stripe_payment_method_id'] = $pm->id;
                    $updateData['card_brand'] = $pm->card->brand;
                    $updateData['card_last_four'] = $pm->card->last4;
                    $updateData['card_exp_month'] = $pm->card->exp_month;
                    $updateData['card_exp_year'] = $pm->card->exp_year;
                    Log::info("CarSwap Webhook: Card details captured for subscription: " . $subscription->id);
                }
            } catch (\Exception $e) {
                Log::error("CarSwap Webhook: Failed to retrieve payment method: " . $e->getMessage());
            }
        }

        $subscription->update($updateData);
        Log::info("CarSwap Webhook: Subscription activated: " . $subscription->id);

        // Deactivate any previous active subscription for this user
        Subscription::where('user_id', $subscription->user_id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);
    }
}
