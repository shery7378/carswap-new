<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlans extends Controller
{
    public function index()
    {
        // Get all plans ordered by most recently updated to keep relevant versions
        $allPlans = \App\Models\Plan::orderBy('updated_at', 'desc')->get();
        
        // Group and deduplicate to prevent "month vs monthly" issues
        $plans = $allPlans->groupBy(function($item) {
             return preg_replace('/-(month|monthly|year|yearly|both)(-\d+)?$/i', '', strtolower($item->slug));
        })->map(function($group) {
             // For each group, only keep the latest version of each period type
             $deduplicated = collect();
             
             $monthly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['month', 'monthly']))->first();
             if ($monthly) $deduplicated->push($monthly);
             
             $yearly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['year', 'yearly']))->first();
             if ($yearly) $deduplicated->push($yearly);
             
             // If no monthly/yearly found, keep whatever was in there
             return $deduplicated->isNotEmpty() ? $deduplicated : $group;
        });

        return view('content.apps.subscription.plans', compact('plans'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $plan = \App\Models\Plan::findOrFail($id);
            $plan->is_active = !$plan->is_active;
            $plan->save();

            // Clear plans cache
            \Illuminate\Support\Facades\Cache::forget('carswap_subscription_plans');

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
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
        ]);

        $plan = \App\Models\Plan::findOrFail($id);
        
        $data = $request->except('features');
        
        if ($request->has('features') && is_array($request->features)) {
            $featuresArray = array_values(array_filter(array_map(function($f) { return strip_tags(trim($f)); }, $request->features)));
            $data['features'] = empty($featuresArray) ? [] : $featuresArray;
        } else {
            $data['features'] = [];
        }

        if ($request->has('description')) {
            $data['description'] = strip_tags($request->description);
        }

        // Handle checkboxes (since they don't send anything if unchecked)
        $data['is_popular'] = $request->has('is_popular');
        $data['highlight_ads'] = $request->has('highlight_ads');
        $data['hd_images'] = $request->has('hd_images');

        $plan->update($data);

        // Clear plans cache for the API
        \Illuminate\Support\Facades\Cache::forget('carswap_subscription_plans');

        return redirect()->back()->with('success', 'Plan updated successfully!');
    }
    public function destroy($id)
    {
        try {
            $plan = \App\Models\Plan::findOrFail($id);
            $plan->delete();

            // Clear plans cache
            \Illuminate\Support\Facades\Cache::forget('carswap_subscription_plans');

            return response()->json(['success' => true, 'message' => 'Plan deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete plan.'], 500);
        }
    }
}
