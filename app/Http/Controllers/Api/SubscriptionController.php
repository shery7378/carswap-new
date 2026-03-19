<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $plans = \App\Models\Plan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $activeSubscription = null;
        if ($request->user('sanctum')) {
            $activeSubscription = $request->user('sanctum')->load('activeSubscription.plan')->activeSubscription;
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $plans,
            'active_subscription' => $activeSubscription
        ]);
    }

    public function mySubscription(Request $request)
    {
        $user = $request->user();
        $user->load('activeSubscription.plan');
        $subscription = $user->activeSubscription;

        if (!$subscription || !$subscription->plan) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have an active subscription plan.',
                'data'    => null,
            ]);
        }

        $plan = $subscription->plan;

        // ── Active Ads Usage ──────────────────────────────────────────────────
        $activeAdsLimit   = (int) $plan->active_ads_limit;
        $isUnlimitedAds   = $activeAdsLimit <= 0;
        $usedActiveAds    = \App\Models\Vehicle::where('user_id', $user->id)
            ->whereIn('ad_status', ['published', 'pending'])
            ->count();
        $remainingActiveAds = $isUnlimitedAds ? null : max(0, $activeAdsLimit - $usedActiveAds);

        // ── Garage / Draft Ads Usage ──────────────────────────────────────────
        $garageAdsLimit   = (int) ($plan->garage_ads_limit ?? 0);
        $isUnlimitedGarage = $garageAdsLimit <= 0;
        $usedGarageAds    = \App\Models\Vehicle::where('user_id', $user->id)
            ->where('ad_status', 'draft')
            ->count();
        $remainingGarageAds = $isUnlimitedGarage ? null : max(0, $garageAdsLimit - $usedGarageAds);

        // ── Days Remaining ────────────────────────────────────────────────────
        $daysRemaining = null;
        if ($subscription->ends_at) {
            $daysRemaining = max(0, now()->diffInDays($subscription->ends_at, false));
        }

        return response()->json([
            'success' => true,
            'message' => 'Current subscription retrieved successfully.',
            'data'    => [
                // Subscription info
                'id'              => $subscription->id,
                'status'          => $subscription->status,
                'amount'          => $subscription->amount,
                'duration'        => $subscription->duration,
                'starts_at'       => $subscription->starts_at?->toDateTimeString(),
                'ends_at'         => $subscription->ends_at?->toDateTimeString(),
                'next_billing_at' => $subscription->next_billing_at?->toDateTimeString(),
                'days_remaining'  => $daysRemaining,

                // Plan info
                'plan' => [
                    'id'               => $plan->id,
                    'name'             => $plan->name,
                    'slug'             => $plan->slug,
                    'price'            => $plan->price,
                    'billing_period'   => $plan->billing_period,
                    'description'      => $plan->description,
                    'features'         => $plan->features,
                    'highlight_ads'    => (bool) $plan->highlight_ads,
                    'hd_images'        => (bool) $plan->hd_images,
                    'expandable_slots' => (bool) $plan->expandable_slots,
                ],

                // Usage breakdown
                'usage' => [
                    'active_ads' => [
                        'limit'     => $isUnlimitedAds ? 'Unlimited' : $activeAdsLimit,
                        'used'      => $usedActiveAds,
                        'remaining' => $isUnlimitedAds ? 'Unlimited' : $remainingActiveAds,
                        'unlimited' => $isUnlimitedAds,
                    ],
                    'garage_ads' => [
                        'limit'     => $isUnlimitedGarage ? 'Unlimited' : $garageAdsLimit,
                        'used'      => $usedGarageAds,
                        'remaining' => $isUnlimitedGarage ? 'Unlimited' : $remainingGarageAds,
                        'unlimited' => $isUnlimitedGarage,
                    ],
                ],
            ],
        ]);
    }
}
