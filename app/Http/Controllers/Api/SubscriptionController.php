<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Get only active plans, ordered by price (monthly)
        $allPlans = \App\Models\Plan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $user = $request->user('sanctum');
        $activePlanId = null;
        if ($user) {
            $user->load('activeSubscription');
            $activePlanId = $user->activeSubscription?->plan_id;
        }

        // Group and structure for frontend "Dual Pricing" cards
        $groupedPlans = $allPlans->groupBy(function($item) {
             return preg_replace('/-(month|monthly|year|yearly|both)$/i', '', strtolower($item->slug));
        })->map(function($group) use ($activePlanId) {
             $monthly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['month', 'monthly']))->first();
             $yearly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['year', 'yearly']))->first();
             $main = $monthly ?? ($yearly ?? $group->first());

             return [
                 'slug_base' => preg_replace('/-(month|monthly|year|yearly|both)$/i', '', strtolower($main->slug)),
                 'name' => $main->name,
                 'description' => $main->description,
                 'color' => $main->color,
                 'is_popular' => (bool) $main->is_popular,
                 'monthly' => $monthly ? $this->formatPlanData($monthly, $activePlanId) : null,
                 'yearly' => $yearly ? $this->formatPlanData($yearly, $activePlanId) : null,
             ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $groupedPlans,
        ]);
    }

    /**
     * Helper to format individual plan data for the API
     */
    private function formatPlanData($plan, $activePlanId)
    {
        $isCurrent = $activePlanId && $plan->id === $activePlanId;
        
        return [
            'id' => $plan->id,
            'slug' => $plan->slug,
            'price' => (float) $plan->price,
            'billing_period' => $plan->billing_period,
            'active_ads_limit' => $plan->active_ads_limit,
            'garage_ads_limit' => $plan->garage_ads_limit,
            'hd_images' => $plan->hd_images,
            'expandable_slots' => $plan->expandable_slots,
            'features' => is_string($plan->features) ? json_decode($plan->features, true) : $plan->features,
            'is_current' => $isCurrent,
            'status_message' => $isCurrent ? 'Current Plan' : null,
            'upgrade_url' => '/account/billing?upgrade=' . $plan->slug
        ];
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

        // ── Garage (Inventory) Usage ──────────────────────────────────────────
        $garageAdsLimit   = (int) ($plan->garage_ads_limit ?? 0);
        $isUnlimitedGarage = $garageAdsLimit <= 0;
        // Inventory includes published, pending, draft, and rejected.
        $usedGarageAds    = \App\Models\Vehicle::where('user_id', $user->id)
            ->whereIn('ad_status', ['published', 'pending', 'draft', 'rejected'])
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
