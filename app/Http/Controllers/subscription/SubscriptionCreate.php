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
        $slug = Str::slug($name);
        
        if ($request->billing_period == 'both') {
            $slug .= '-' . $period;
        }

        // Determine price column - the list view uses 'price' as the primary display price
        $monthly_val = $request->monthly_price ?? 0;
        $yearly_val = $request->yearly_price ?? 0;
        
        // If it's a yearly plan, the display price (price column) should be the yearly value
        $display_price = ($period == 'yearly') ? $yearly_val : $monthly_val;

        Plan::create([
            'name' => $name,
            'slug' => $slug,
            'price' => $display_price,
            'yearly_price' => $yearly_val,
            'active_ads_limit' => $request->input($prefix . 'active_ads_limit') ?? 0,
            'garage_ads_limit' => $request->input($prefix . 'garage_ads_limit') ?? 0,
            'expandable_slots' => $request->input($prefix . 'expandable_slots') ?? 0,
            'highlight_ads' => $request->has('highlight_ads') ? 1 : 0,
            'hd_images' => $request->input($prefix . 'hd_images') ?? 0,
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
        
        if ($request->billing_period == 'both') {
            // Find or create the other one
            // We update the current one, and either find its sibling or create a new one
            $this->handleBothUpdate($request, $plan);
            
            return redirect()->route('app-subscription-plans')->with('success', 'Plans updated successfully');
        }

        $this->updatePlan($request, $plan, $request->billing_period);
        return redirect()->route('app-subscription-plans')->with('success', 'Plan updated successfully');
    }

    private function handleBothUpdate(Request $request, Plan $currentPlan)
    {
        // 1. Update the Current Plan
        // If current plan is monthly, we update it as monthly
        // If current plan is yearly, we update it as yearly
        $currentPeriod = $currentPlan->billing_period;
        $this->updatePlan($request, $currentPlan, $currentPeriod);

        // 2. Find or Create the Counterpart
        $counterPartPeriod = ($currentPeriod == 'monthly') ? 'yearly' : 'monthly';
        $counterPartSlug = Str::slug($request->title) . '-' . $counterPartPeriod;
        
        $counterPart = Plan::where('slug', $counterPartSlug)->first();
        
        if ($counterPart) {
            $this->updatePlan($request, $counterPart, $counterPartPeriod);
        } else {
            // Create it from scratch using createPlan
            $this->createPlan($request, $counterPartPeriod);
        }
    }

    private function updatePlan(Request $request, Plan $plan, $period)
    {
        $prefix = ($request->billing_period == 'both' && $period == 'yearly') ? 'yearly_' : '';
        $monthly_val = $request->monthly_price ?? 0;
        $yearly_val = $request->yearly_price ?? 0;
        $display_price = ($period == 'yearly') ? $yearly_val : $monthly_val;
        
        $slug = Str::slug($request->title);
        if (!Str::endsWith($slug, '-' . $period)) {
            $slug .= '-' . $period;
        }

        $plan->update([
            'name' => $request->title,
            'slug' => $slug,
            'price' => $display_price,
            'yearly_price' => $yearly_val,
            'active_ads_limit' => $request->input($prefix . 'active_ads_limit') ?? 0,
            'garage_ads_limit' => $request->input($prefix . 'garage_ads_limit') ?? 0,
            'expandable_slots' => $request->input($prefix . 'expandable_slots') ?? 0,
            'highlight_ads' => $request->has('highlight_ads') ? 1 : 0,
            'hd_images' => $request->input($prefix . 'hd_images') ?? 0,
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
