<?php

namespace App\Console\Commands\Subscriptions;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Notifications\SubscriptionRenewed;
use App\Notifications\SubscriptionRenewalFailed;

class RenewSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renew-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically renew active subscriptions in HUF using stored payment methods.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->info('Checking for subscriptions that need renewal (HUF Manual Mode)...');

        // Find active subscriptions that have reached or passed their end date
        $expiredSubs = Subscription::with(['plan', 'user'])
            ->where('status', 'active')
            ->where('ends_at', '<=', now())
            ->get();

        if ($expiredSubs->isEmpty()) {
            $this->info('No subscriptions require renewal at this time.');
            return;
        }

        $count = 0;
        foreach ($expiredSubs as $sub) {
            $plan = $sub->plan;
            $user = $sub->user;

            if (!$plan || !$user) {
                continue;
            }

            // Determine the price based on current plan configuration
            $isYearly = $sub->duration === 'Yearly';
            $amount = $isYearly ? $plan->yearly_price : $plan->price;

            // If no payment method is stored, we cannot auto-renew
            if (!$sub->stripe_payment_method_id || !$sub->stripe_customer_id) {
                $this->expireSubscription($sub, "No payment method stored.");
                continue;
            }

            try {
                $this->info("Attempting to charge User ID {$sub->user_id}: {$amount} HUF");

                // 1. Create and confirm a PaymentIntent off-session
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int)($amount * 100),
                    'currency' => 'huf',
                    'customer' => $sub->stripe_customer_id,
                    'payment_method' => $sub->stripe_payment_method_id,
                    'off_session' => true,
                    'confirm' => true,
                    'description' => "Auto-renewal: {$plan->name} ({$sub->duration})",
                ]);

                if ($paymentIntent->status === 'succeeded') {
                    // 2. Update subscription dates on success
                    $newEndsAt = $isYearly ? now()->addYear() : now()->addMonth();

                    DB::transaction(function () use ($sub, $newEndsAt, $amount) {
                        $sub->update([
                            'starts_at'       => now(),
                            'next_billing_at' => $newEndsAt,
                            'ends_at'         => $newEndsAt,
                            'amount'          => $amount, // Update amount in case it changed in plan
                        ]);
                    });

                    // 3. Send Success Notification
                    try {
                        $user->notify(new SubscriptionRenewed($sub, $amount));
                    } catch (\Exception $e) {
                        Log::error("CarSwap: Failed to send renewal notification to user {$user->id}: " . $e->getMessage());
                    }

                    Log::info("CarSwap: Auto-renewed subscription ID {$sub->id} for user {$sub->user_id} (Amount: {$amount} HUF)");
                    $this->info("Successfully renewed subscription for User ID: {$sub->user_id}");
                    $count++;
                } else {
                    // This handles cases like 'requires_action' which shouldn't happen off-session if set up correctly
                    $this->expireSubscription($sub, "Payment requires action (Status: {$paymentIntent->status})");
                }

            } catch (\Stripe\Exception\CardException $e) {
                // Specific card error (e.g., insufficient funds)
                $this->expireSubscription($sub, "Card Error: " . $e->getMessage());
            } catch (\Exception $e) {
                // Generic error
                Log::error("CarSwap: Failed to auto-renew subscription ID {$sub->id}: " . $e->getMessage());
                $this->error("Failed to renew ID {$sub->id}: " . $e->getMessage());
            }
        }

        $this->info("Renewal process completed. {$count} subscriptions renewed.");
    }

    /**
     * Expire the subscription and move user to Free plan
     */
    protected function expireSubscription($sub, $reason)
    {
        $this->warn("Expiring subscription ID {$sub->id} for user {$sub->user_id}. Reason: {$reason}");
        
        DB::transaction(function () use ($sub) {
            // Mark current as expired
            $sub->update([
                'status' => 'expired',
            ]);

            // Create/Assign FREE plan (ID 8 as per database check)
            $freePlan = Plan::where('slug', 'free')->first();
            $freePlanId = $freePlan ? $freePlan->id : 8;

            Subscription::create([
                'user_id' => $sub->user_id,
                'plan_id' => $freePlanId,
                'amount' => 0,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => null, // Free plan usually doesn't end
                'next_billing_at' => null,
                'duration' => 'Lifetime',
            ]);
        });

        // Send Failure Notification
        if ($sub->user) {
            try {
                $sub->user->notify(new SubscriptionRenewalFailed($sub, $reason));
            } catch (\Exception $e) {
                Log::error("CarSwap: Failed to send failure notification to user {$sub->user_id}: " . $e->getMessage());
            }
        }

        Log::warning("CarSwap: Subscription ID {$sub->id} expired and moved to Free plan. Reason: {$reason}");
    }
}
