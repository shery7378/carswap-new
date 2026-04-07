<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    // NEW Cancel Method
    public function cancel(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('activeSubscription.plan');
        $currentSub = $user->activeSubscription;

        if (!$currentSub) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found.',
            ], 404);
        }

        $currentPlan = $currentSub->plan;

        // If user is already on free plan, don't allow "cancellation" back to free plan
        if ($currentPlan && strtolower($currentPlan->slug) === 'free') {
            return response()->json([
                'success' => false,
                'message' => 'You are already on the Free plan.',
            ], 422);
        }

        // Cancel the current subscription record
        $currentSub->update(['status' => 'cancelled']);

        // Assign FREE package to user (Look for slug 'free' or ID 8)
        $freePlan = Plan::where('slug', 'free')->first() ?? Plan::find(8);
        
        if ($freePlan) {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $freePlan->id,
                'amount' => 0,
                'status' => 'active',
                'starts_at' => now(),
                'next_billing_at' => now()->addYears(10),
                'ends_at' => now()->addYears(10),
                'duration' => 'Lifetime (Free)'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your subscription has been cancelled and you have been switched to the Free plan.',
        ]);
    }

    public function index(Request $request)
    {
        // Cache the base plan structure for 24 hours
        $baseGroupedPlans = Cache::remember('carswap_subscription_plans', 86400, function () {
            // Get only active plans, ordered by price (monthly)
            $allPlans = \App\Models\Plan::where('is_active', true)
                ->orderBy('price', 'asc')
                ->get();

            // Group and structure for frontend "Dual Pricing" cards (without user-specific data)
            return $allPlans->groupBy(function ($item) {
                return preg_replace('/-(month|monthly|year|yearly|both)$/i', '', strtolower($item->slug));
            })->map(function ($group) {
                $monthly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['month', 'monthly']))->first();
                $yearly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['year', 'yearly']))->first();
                $main = $monthly ?? ($yearly ?? $group->first());

                return [
                    'slug_base' => preg_replace('/-(month|monthly|year|yearly|both)$/i', '', strtolower($main->slug)),
                    'name' => $main->name,
                    'description' => $main->description,
                    'color' => $main->color,
                    'is_popular' => (bool) $main->is_popular,
                    'monthly_raw' => $monthly, // Keep raw for processing
                    'yearly_raw' => $yearly,   // Keep raw for processing
                ];
            })->values();
        });

        $user = $request->user('sanctum');
        $activePlanId = null;
        if ($user) {
            $user->load('activeSubscription');
            $activePlanId = $user->activeSubscription?->plan_id;
        }

        // Apply user-specific "is_current" logic on top of cached base plans
        $finalPlans = $baseGroupedPlans->map(function ($plan) use ($activePlanId) {
            $formatted = $plan;
            $formatted['monthly'] = $plan['monthly_raw'] ? $this->formatPlanData($plan['monthly_raw'], $activePlanId) : null;
            $formatted['yearly'] = $plan['yearly_raw'] ? $this->formatPlanData($plan['yearly_raw'], $activePlanId) : null;
            
            unset($formatted['monthly_raw']);
            unset($formatted['yearly_raw']);
            
            return $formatted;
        });

        return response()->json([
            'success' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $finalPlans,
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
                'data' => null,
            ]);
        }

        $plan = $subscription->plan;

        // ── Active Ads Usage ──────────────────────────────────────────────────
        $activeAdsLimit = (int) $plan->active_ads_limit;
        $isUnlimitedAds = $activeAdsLimit <= 0;
        $usedActiveAds = \App\Models\Vehicle::where('user_id', $user->id)
            ->whereIn('ad_status', ['published', 'pending'])
            ->count();
        $remainingActiveAds = $isUnlimitedAds ? null : max(0, $activeAdsLimit - $usedActiveAds);

        // ── Garage (Inventory) Usage ──────────────────────────────────────────
        $garageAdsLimit = (int) ($plan->garage_ads_limit ?? 0);
        $isUnlimitedGarage = $garageAdsLimit <= 0;
        // Inventory includes published, pending, draft, and rejected.
        $usedGarageAds = \App\Models\Vehicle::where('user_id', $user->id)
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
            'data' => [
                // Subscription info
                'id' => $subscription->id,
                'status' => $subscription->status,
                'amount' => $subscription->amount,
                'duration' => $subscription->duration ?: (in_array(strtolower($plan->billing_period), ['year', 'yearly']) ? 'Yearly' : 'Monthly'),
                'starts_at' => $subscription->starts_at?->toDateTimeString(),
                'ends_at' => $subscription->ends_at?->toDateTimeString(),
                'next_billing_at' => $subscription->next_billing_at?->toDateTimeString(),
                'days_remaining' => $daysRemaining,

                // Plan info
                'plan' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'billing_period' => $plan->billing_period,
                    'description' => $plan->description,
                    'features' => $plan->features,
                    'highlight_ads' => (bool) $plan->highlight_ads,
                    'hd_images' => (bool) $plan->hd_images,
                    'expandable_slots' => (bool) $plan->expandable_slots,
                ],

                // Usage breakdown
                'usage' => [
                    'active_ads' => [
                        'limit' => $isUnlimitedAds ? 'Unlimited' : $activeAdsLimit,
                        'used' => $usedActiveAds,
                        'remaining' => $isUnlimitedAds ? 'Unlimited' : $remainingActiveAds,
                        'unlimited' => $isUnlimitedAds,
                    ],
                    'garage_ads' => [
                        'limit' => $isUnlimitedGarage ? 'Unlimited' : $garageAdsLimit,
                        'used' => $usedGarageAds,
                        'remaining' => $isUnlimitedGarage ? 'Unlimited' : $remainingGarageAds,
                        'unlimited' => $isUnlimitedGarage,
                    ],
                ],
            ],
        ]);
    }
}
