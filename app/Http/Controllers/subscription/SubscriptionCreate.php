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

        if ($request->billing_period == 'both') {
            // Create Monthly Plan
            $this->createPlan($request, 'monthly');
            // Create Yearly Plan
            $this->createPlan($request, 'yearly');
            
            return redirect()->route('app-subscription-plans')->with('success', 'Monthly and Yearly plans created successfully');
        }

        $this->createPlan($request, $request->billing_period);

        return redirect()->route('app-subscription-plans')->with('success', 'Plan created successfully');
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
            'billing_period' => 'required|string|in:monthly,yearly',
        ]);

        $plan = Plan::findOrFail($id);
        
        $monthly_val = $request->monthly_price ?? 0;
        $yearly_val = $request->yearly_price ?? 0;
        $display_price = ($request->billing_period == 'yearly') ? $yearly_val : $monthly_val;
        
        $slug = Str::slug($request->title);
        // If it already had a suffix, keep it or update it?
        // Let's just make sure it's unique but descriptive
        if ($plan->billing_period == 'yearly' || $request->billing_period == 'yearly') {
             // check if it needs suffix
             if (!Str::endsWith($slug, '-yearly')) {
                 $slug .= '-yearly';
             }
        } elseif ($plan->billing_period == 'monthly' || $request->billing_period == 'monthly') {
             if (!Str::endsWith($slug, '-monthly')) {
                 $slug .= '-monthly';
             }
        }

        $plan->update([
            'name' => $request->title,
            'slug' => $slug,
            'price' => $display_price,
            'yearly_price' => $yearly_val,
            'active_ads_limit' => $request->active_ads_limit ?? 0,
            'garage_ads_limit' => $request->garage_ads_limit ?? 0,
            'expandable_slots' => $request->expandable_slots ?? 0,
            'highlight_ads' => $request->has('highlight_ads') ? 1 : 0,
            'hd_images' => $request->hd_images ?? 0,
            'is_popular' => $request->has('is_popular'),
            'features' => $request->features ? array_values(array_filter(array_map('trim', $request->features))) : [],
            'billing_period' => $request->billing_period,
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
            'stripe_price_id_yearly' => $request->stripe_price_id_yearly,
        ]);

        return redirect()->route('app-subscription-plans')->with('success', 'Plan updated successfully');
    }
}
