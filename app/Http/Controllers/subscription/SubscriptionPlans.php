<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlans extends Controller
{
    public function index()
    {
        $plans = \App\Models\Plan::all();
        return view('content.apps.subscription.plans', compact('plans'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $plan = \App\Models\Plan::findOrFail($id);
            $plan->is_active = !$plan->is_active;
            $plan->save();

            return response()->json([
                'success' => true,
                'message' => 'Plan status updated successfully',
                'is_active' => $plan->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plan status'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $id,
            'price' => 'required|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'billing_period' => 'required|string|in:monthly,yearly',
            'description' => 'nullable|string',
            'active_ads_limit' => 'nullable|integer',
            'garage_ads_limit' => 'nullable|integer',
            'expandable_slots' => 'nullable|integer',
        ]);

        $plan = \App\Models\Plan::findOrFail($id);
        
        $data = $request->all();
        // Handle checkboxes (since they don't send anything if unchecked)
        $data['is_popular'] = $request->has('is_popular');
        $data['highlight_ads'] = $request->has('highlight_ads');
        $data['hd_images'] = $request->has('hd_images');

        $plan->update($data);

        return redirect()->back()->with('success', 'Plan updated successfully!');
    }
}
