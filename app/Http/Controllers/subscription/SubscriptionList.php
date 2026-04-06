<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Payment;

class SubscriptionList extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])->latest()->get();
        return view('content.app.subscription.list', compact('subscriptions'));
    }

    public function show($id)
    {
        $subscription = Subscription::with(['user', 'plan'])->findOrFail($id);
        $payments = Payment::where('user_id', $subscription->user_id)
            ->latest()
            ->get();
        $plans = Plan::where('is_active', true)->get();

        if (request()->ajax()) {
            return view('content.app.subscription.invoice-modal-content', compact('subscription', 'payments', 'plans'));
        }

        return view('content.app.subscription.view', compact('subscription', 'payments', 'plans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status = $request->status;
        $subscription->save();

        return response()->json(['success' => true, 'message' => 'Subscription status updated to ' . $request->status]);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        
        $plan = Plan::find($request->plan_id);
        $duration = $subscription->duration;
        $next_billing_at = $subscription->next_billing_at;
        
        if ($plan) {
            $duration = (in_array(strtolower($plan->billing_period), ['year', 'yearly'])) ? 'Yearly' : 'Monthly';
            
            // If they change the plan, we might want to reset next_billing_at 
            // especially if it's currently unset or if you want to reflect the new period starting now.
            // For now, let's just make sure duration matches the plan.
        }

        $subscription->update([
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'next_billing_at' => $request->ends_at, // Sync billing date with the end of the term
            'duration' => $duration,
            'status' => $request->status,
            'billing_full_name' => $request->billing_full_name,
            'billing_company_name' => $request->billing_company_name,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
        ]);

        return back()->with('success', 'Subscription updated successfully.');
    }
}
