<?php

namespace App\Console\Commands\Subscriptions;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Automatically renew active subscriptions that have reached their end date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for subscriptions that need renewal...');

        // Find active subscriptions that have expired
        $expiredSubs = Subscription::with('plan')
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
            if (!$plan) {
                continue;
            }

            // Determine if it was a yearly or monthly cycle
            $isYearly = $sub->duration === 'Yearly' || str_contains(strtolower($plan->slug), 'year');
            $newEndsAt = $isYearly ? now()->addYear() : now()->addMonth();

            try {
                \Illuminate\Support\Facades\DB::transaction(function () use ($sub, $newEndsAt) {
                    /** @var \App\Models\Subscription $sub */
                    // Update current subscription term
                    $sub->update([
                        'starts_at'       => now(),
                        'next_billing_at' => $newEndsAt,
                        'ends_at'         => $newEndsAt,
                        // Note: If you want to log history, you could create a Payment record here
                    ]);
                });

                Log::info("CarSwap: Auto-renewed subscription ID {$sub->id} for user {$sub->user_id} (Plan: {$plan->name})");
                $this->info("Successfully renewed subscription for User ID: {$sub->user_id}");
                $count++;
            } catch (\Exception $e) {
                Log::error("CarSwap: Failed to auto-renew subscription ID {$sub->id}: " . $e->getMessage());
                $this->error("Failed to renew ID {$sub->id}");
            }
        }

        $this->info("Renewal process completed. {$count} subscriptions renewed.");
    }
}
