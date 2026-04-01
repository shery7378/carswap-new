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
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'nullable|numeric',
        ]);

        Plan::create([
            'name' => $request->title,
            'slug' => Str::slug($request->title),
            'price' => $request->monthly_price,
            'yearly_price' => $request->yearly_price,
            'active_ads_limit' => $request->active_ads_limit ?? 0,
            'garage_ads_limit' => $request->garage_ads_limit ?? 0,
            'expandable_slots' => $request->expandable_slots ?? 0,
            'highlight_ads' => $request->has('highlight_ads') ? 1 : 0,
            'hd_images' => $request->has('hd_images') ? 1 : 0,
            'is_popular' => $request->has('is_popular'),
            'features' => $request->features ? array_values(array_filter(array_map('trim', $request->features))) : [],
            'billing_period' => $request->billing_period ?? 'monthly',
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
            'stripe_price_id_yearly' => $request->stripe_price_id_yearly,
        ]);

        return redirect()->route('app-subscription-plans')->with('success', 'Plan created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'nullable|numeric',
        ]);

        $plan = Plan::findOrFail($id);

        $plan->update([
            'name' => $request->title,
            'slug' => Str::slug($request->title),
            'price' => $request->monthly_price,
            'yearly_price' => $request->yearly_price,
            'active_ads_limit' => $request->active_ads_limit ?? 0,
            'garage_ads_limit' => $request->garage_ads_limit ?? 0,
            'expandable_slots' => $request->expandable_slots ?? 0,
            'highlight_ads' => $request->has('highlight_ads') ? 1 : 0,
            'hd_images' => $request->has('hd_images') ? 1 : 0,
            'is_popular' => $request->has('is_popular'),
            'features' => $request->features ? array_values(array_filter(array_map('trim', $request->features))) : [],
            'billing_period' => $request->billing_period ?? 'monthly',
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
            'stripe_price_id_yearly' => $request->stripe_price_id_yearly,
        ]);

        return redirect()->route('app-subscription-plans')->with('success', 'Plan updated successfully');
    }
}
