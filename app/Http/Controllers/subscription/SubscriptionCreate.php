<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionCreate extends Controller
{
    public function index()
    {
        return view('content.app.subscription.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'nullable|numeric',
        ]);

        \App\Models\Plan::create([
            'name' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'price' => $request->monthly_price,
            'yearly_price' => $request->yearly_price,
            'active_ads_limit' => $request->active_ads_limit,
            'garage_ads_limit' => $request->garage_ads_limit,
            'expandable_slots' => $request->expandable_slots,
            'highlight_ads' => $request->highlight_ads,
            'hd_images' => $request->hd_images,
            'features' => $request->features,
            'billing_period' => 'monthly', // default
            'color' => 'primary', // default
        ]);

        return redirect()->route('app-subscription-plans')->with('success', 'Plan created successfully');
    }
}
