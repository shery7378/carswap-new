<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
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
        Stripe::setApiKey(config('services.stripe.secret'));
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

        try {
            $session = StripeSession::create([
                'mode' => 'payment',
                'customer_email' => $user->email,
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
                            'unit_amount' => (int)($amount * 100),
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
            ]);

            // Create a pending subscription record with ALL info
            Subscription::create([
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
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::warning('Stripe webhook signature mismatch: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {

            // ── Payment succeeded → activate subscription & save card ─────
            case 'checkout.session.completed':
                $session = $event->data->object;
                $sessionId = $session->id;

                $subscription = Subscription::where('stripe_session_id', $sessionId)->first();

                if ($subscription) {
                    $updateData = [
                        'status' => 'active',
                        'stripe_customer_id' => $session->customer,
                    ];

                    // Capture Payment Method for off-session billing
                    if ($session->payment_intent) {
                        try {
                            $intent = PaymentIntent::retrieve($session->payment_intent);
                            if ($intent->payment_method) {
                                $pm = PaymentMethod::retrieve($intent->payment_method);
                                $updateData['stripe_payment_method_id'] = $pm->id;
                                $updateData['card_brand'] = $pm->card->brand;
                                $updateData['card_last_four'] = $pm->card->last4;
                                $updateData['card_exp_month'] = $pm->card->exp_month;
                                $updateData['card_exp_year'] = $pm->card->exp_year;
                            }
                        } catch (\Exception $e) {
                            Log::error("CarSwap: Failed to retrieve payment method from session {$sessionId}: " . $e->getMessage());
                        }
                    }

                    $subscription->update($updateData);

                    // Deactivate any previous active subscription for this user
                    Subscription::where('user_id', $subscription->user_id)
                        ->where('id', '!=', $subscription->id)
                        ->where('status', 'active')
                        ->update(['status' => 'expired']);
                }
                break;

            // ── Subscription cancelled / expired ──────────────────────────
            case 'customer.subscription.deleted':
                $stripeSub = $event->data->object;
                $stripeSubId = $stripeSub->id;

                Subscription::where('stripe_subscription_id', $stripeSubId)
                    ->update(['status' => 'expired']);
                break;
        }

        return response()->json(['received' => true]);
    }
}
