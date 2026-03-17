<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'FREE',
                'slug' => 'free',
                'price' => 0,
                'billing_period' => 'month',
                'color' => 'secondary',
                'is_popular' => false,
                'active_ads_limit' => 2,
                'garage_ads_limit' => 2,
                'features' => [
                    '2 active ads',
                    '2 spaces in the garage',
                    'Exchange offer function',
                    'Free image upload'
                ],
                'description' => 'Start for free'
            ],
            [
                'name' => 'PARTNER PACKAGE',
                'slug' => 'partner-package',
                'price' => 3000,
                'billing_period' => 'month',
                'color' => 'primary',
                'is_popular' => false,
                'features' => [
                    'Brief introduction',
                    'Price list / services',
                    '5 pictures',
                    'Opening hours',
                    'Contact details',
                    'Price list and list of services'
                ],
                'description' => 'For partners'
            ],
            [
                'name' => 'I HAVE SEVERAL CARS.',
                'slug' => 'several-cars',
                'price' => 22000,
                'billing_period' => 'month',
                'color' => 'info',
                'is_popular' => true,
                'active_ads_limit' => 5,
                'garage_ads_limit' => 10,
                'highlight_ads' => 2,
                'hd_images' => 2,
                'features' => [
                    '5 Active Ads',
                    '10 garage slots',
                    'Expandable active ad slots',
                    'HD images, 12 instead of 6 — usable for 2 ads per month',
                    'Highlighting — usable for 2 ads per month'
                ],
                'description' => 'For individual enthusiasts'
            ],
            [
                'name' => 'DEALER PACKAGE',
                'slug' => 'dealer-package',
                'price' => 40000,
                'billing_period' => 'month',
                'color' => 'success',
                'is_popular' => false,
                'highlight_ads' => 5,
                'hd_images' => 5,
                'features' => [
                    'Brief introduction of the trader',
                    'Bonus: Highlight – can be used for 5 ads per month',
                    'Bonus: HD images, 12 instead of 6',
                    'Unlimited garage space',
                    'Unlimited active ad space'
                ],
                'description' => 'For professional dealers'
            ],
        ];

        foreach ($plans as $planData) {
            \App\Models\Plan::updateOrCreate(['slug' => $planData['slug']], $planData);
        }

        $user = \App\Models\User::first();
        if ($user) {
            $freePlan = \App\Models\Plan::where('slug', 'free')->first();
            $partnerPlan = \App\Models\Plan::where('slug', 'partner-package')->first();

            \App\Models\Subscription::updateOrCreate(
                ['user_id' => $user->id, 'plan_id' => $freePlan->id],
                [
                    'amount' => $freePlan->price,
                    'status' => 'active',
                    'starts_at' => now(),
                    'next_billing_at' => now()->addMonth(),
                    'duration' => '12 months'
                ]
            );

            \App\Models\Payment::updateOrCreate(
                ['transaction_id' => 'TRX001234'],
                [
                    'user_id' => $user->id,
                    'plan_id' => $freePlan->id,
                    'amount' => $freePlan->price,
                    'status' => 'completed',
                    'payment_method' => 'credit_card'
                ]
            );

            \App\Models\Payment::updateOrCreate(
                ['transaction_id' => 'TRX001235'],
                [
                    'user_id' => $user->id,
                    'plan_id' => $partnerPlan->id,
                    'amount' => $partnerPlan->price,
                    'status' => 'completed',
                    'payment_method' => 'paypal'
                ]
            );
        }
    }
}
