<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Str;

class SubscriptionCreate extends Controller
{
    public function index()
    {
        return view('content.app.subscription.create');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('content.app.subscription.create', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'monthly_price' => 'nullable|numeric',
            'yearly_price' => 'nullable|numeric',
            'billing_period' => 'required|string|in:monthly,yearly,both',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            if ($request->billing_period == 'both') {
                // Create Monthly Plan
                $this->createPlan($request, 'monthly');
                // Create Yearly Plan
                $this->createPlan($request, 'yearly');
            } else {
                $this->createPlan($request, $request->billing_period);
            }
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('app-subscription-plans')->with('success', 'Plan(s) created successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Subscription creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create plans: ' . $e->getMessage());
        }
    }

    private function createPlan(Request $request, $period)
    {
        $prefix = ($request->billing_period == 'both' && $period == 'yearly') ? 'yearly_' : '';
        $name = $request->title;
        $baseSlug = Str::slug($name);
        
        // Generate initial slug with suffix
        $slug = $baseSlug . '-' . (in_array($period, ['month', 'monthly']) ? 'monthly' : 'yearly');
        
        // Collision check to prevent Unique URL errors on Live/Local
        $count = 1;
        while (Plan::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . (in_array($period, ['month', 'monthly']) ? 'monthly' : 'yearly') . '-' . $count++;
        }

        $monthly_val = $request->monthly_price ?? 0;
        $yearly_val = $request->yearly_price ?? 0;
        $display_price = ($period == 'yearly') ? $yearly_val : $monthly_val;

        Plan::create([
            'name' => $name,
            'slug' => $slug,
            'price' => $display_price,
            'yearly_price' => $yearly_val,
            'active_ads_limit' => $request->input($prefix . 'active_ads_limit') ?? 0,
            'garage_ads_limit' => $request->input($prefix . 'garage_ads_limit') ?? 0,
            'expandable_slots' => $request->has($prefix . 'expandable_slots') ? 1 : 0,
            'highlight_ads' => $request->has($prefix . 'highlight_ads') ? 1 : ($request->has('highlight_ads') ? 1 : 0),
            'highlight_ad_count' => $request->input($prefix . 'highlight_ad_count') ?? 0,
            'hd_images' => $request->input($prefix . 'hd_images') ?? 0,

            // Special HD Logic
            'hd_images_count' => $request->input($prefix . 'hd_images_count') ?? 6,
            'hd_images_normal_count' => $request->input($prefix . 'hd_images_normal_count') ?? 6,
            'hd_images_ad_count' => $request->input($prefix . 'hd_images_ad_count') ?? 1,

            'is_popular' => $request->has('is_popular'),
            'features' => $request->input($prefix . 'features') ? array_values(array_filter(array_map('trim', $request->input($prefix . 'features')))) : [],
            'billing_period' => $period,
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
            'stripe_price_id_yearly' => $request->stripe_price_id_yearly,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'monthly_price' => 'nullable|numeric',
            'yearly_price' => 'nullable|numeric',
            'billing_period' => 'required|string|in:monthly,yearly,both',
        ]);

        $plan = Plan::findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Identify the base package name to find all siblings (Monthly/Yearly)
            $baseSlug = preg_replace('/-(month|monthly|year|yearly|both)$/i', '', strtolower($plan->slug));
            
            if ($request->billing_period == 'both') {
                // Handle Dual Packages: Update current and find/create counterpart
                $this->updateOrCreatePackage($request, $baseSlug, 'monthly');
                $this->updateOrCreatePackage($request, $baseSlug, 'yearly');
            } else {
                // Handle Single Package (Monthly or Yearly Only)
                $this->updatePlan($request, $plan, $request->billing_period);
            }

            \Illuminate\Support\Facades\DB::commit();

            // Clear plans cache for the API/Frontend
            \Illuminate\Support\Facades\Cache::forget('carswap_subscription_plans');

            return redirect()->route('app-subscription-plans')->with('success', 'Plans updated successfully');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Subscription update failed: ' . $e->getMessage());
            return back()->with('error', 'Error updating plan: ' . $e->getMessage());
        }
    }

    /**
     * Specialized helper to find and update, or create a package sibling
     */
    private function updateOrCreatePackage(Request $request, $baseSlug, $period)
    {
        $targetSlug = $baseSlug . '-' . $period;
        $existing = Plan::where('slug', $targetSlug)->first();

        // Fallback search: if slug changed, search by name + period
        if (!$existing) {
            $existing = Plan::where('name', $request->title)->where('billing_period', $period)->first();
        }

        if ($existing) {
            $this->updatePlan($request, $existing, $period);
        } else {
            // Create a brand new counterpart
            $this->createPlan($request, $period);
        }
    }

    private function updatePlan(Request $request, Plan $plan, $period)
    {
        $prefix = ($request->billing_period == 'both' && $period == 'yearly') ? 'yearly_' : '';
        $monthly_val = $request->monthly_price ?? 0;
        $yearly_val = $request->yearly_price ?? 0;
        $display_price = ($period == 'yearly') ? $yearly_val : $monthly_val;
        
        // Generate a standard slug base based on the TITLE to keep them clean
        $baseSlug = Str::slug($request->title);
        $newSlug = $baseSlug . '-' . (in_array($period, ['month', 'monthly']) ? 'monthly' : 'yearly');
        
        // Only change the slug if the new one is different AND not taken by ANOTHER plan
        // This prevents re-naming conflicts during "Both" updates
        $isTaken = Plan::where('slug', $newSlug)->where('id', '!=', $plan->id)->exists();
        $slugToSave = $isTaken ? $plan->slug : $newSlug;

        $plan->update([
            'name' => $request->title,
            'slug' => $slugToSave,
            'price' => $display_price,
            'yearly_price' => $yearly_val,
            'active_ads_limit' => $request->input($prefix . 'active_ads_limit') ?? 0,
            'garage_ads_limit' => $request->input($prefix . 'garage_ads_limit') ?? 0,
            'expandable_slots' => $request->has($prefix . 'expandable_slots') ? 1 : 0,
            'highlight_ads' => $request->has($prefix . 'highlight_ads') ? 1 : ($request->has('highlight_ads') ? 1 : 0),
            'highlight_ad_count' => $request->input($prefix . 'highlight_ad_count') ?? 0,
            'hd_images' => $request->input($prefix . 'hd_images') ?? 0,
            'hd_images_count' => $request->input($prefix . 'hd_images_count') ?? 0,
            'hd_images_normal_count' => $request->input($prefix . 'hd_images_normal_count') ?? 0,
            'hd_images_ad_count' => $request->input($prefix . 'hd_images_ad_count') ?? 0,
            'is_popular' => $request->has('is_popular'),
            'features' => $request->input($prefix . 'features') ? array_values(array_filter(array_map('trim', $request->input($prefix . 'features')))) : [],
            'billing_period' => $period,
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
            'stripe_price_id_yearly' => $request->stripe_price_id_yearly,
        ]);
    }
}
