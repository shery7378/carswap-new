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
                'name' => 'INGYENES',
                'slug' => 'free',
                'price' => 0,
                'billing_period' => 'month',
                'color' => 'secondary',
                'is_popular' => false,
                'active_ads_limit' => 2,
                'garage_ads_limit' => 2,
                'features' => [
                    '2 aktív hirdetés',
                    '2 hely a garázsban',
                    'Csere ajánlat funkció',
                    "Ingyenes képfeltöltés'
                ],
                'description' => 'Ingyenes kezdés'
            ],
            [
                'name' => 'PARTNER CSOMAG',
                'slug' => 'partner-package',
                'price' => 3000,
                'billing_period' => 'month',
                'color' => 'primary',
                'is_popular' => false,
                'features' => [
                    'Rövid bemutatkozás',
                    'Árlista / szolgáltatások',
                    '5 fénykép',
                    'Nyitvatartási idő',
                    'Kapcsolatfelvételi adatok',
                    'Árlista és szolgáltatások listája'
                ],
                'description' => 'Partnereknek'
            ],
            [
                'name' => 'TÖBB AUTÓM VAN.',
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
                    '5 Aktív hirdetés',
                    '10 garázs hely',
                    'Bővíthető aktív hirdetési helyek',
                    'HD képek, 12 helyett 6 — havi 2 hirdetéshez használható',
                    'Kiemelés — havi 2 hirdetéshez használható'
                ],
                'description' => 'Egyéni rajongóknak'
            ],
            [
                'name' => 'KERESKEDŐI CSOMAG',
                'slug' => 'dealer-package',
                'price' => 40000,
                'billing_period' => 'month',
                'color' => 'success',
                'is_popular' => false,
                'highlight_ads' => 5,
                'hd_images' => 5,
                'features' => [
                    'Rövid bemutatkozás a kereskedőről',
                    'Bónusz: Kiemelés – havi 5 hirdetéshez használható',
                    'Bónusz: HD képek, 12 helyett 6',
                    'Korlátlan garázs hely',
                    'Korlátlan aktív hirdetési hely'
                ],
                'description' => 'Professzionális kereskedőknek'
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
