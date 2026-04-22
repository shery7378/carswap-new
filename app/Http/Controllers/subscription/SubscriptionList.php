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
        $plans = Plan::where('is_active', true)->get()->unique(function ($item) {
            $period = strtolower($item->billing_period);
            if (str_contains($period, 'month')) $period = 'monthly';
            if (str_contains($period, 'year')) $period = 'yearly';
            return $item->name . $item->price . $period;
        });

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
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric',
            'starts_at' => 'required',
            'ends_at' => 'required',
            'status' => 'required|string',
        ]);

        $subscription = Subscription::findOrFail($id);
        $plan = Plan::find($request->plan_id);
        
        // Parse dates safely
        $startsAt = \Carbon\Carbon::parse($request->starts_at);
        $endsAt = \Carbon\Carbon::parse($request->ends_at);
        
        $duration = $request->input('duration', $subscription->duration);
        
        if (!$request->duration && $plan) {
            $duration = (in_array(strtolower($plan->billing_period), ['year', 'yearly'])) ? 'Yearly' : 'Monthly';
            
            // Smarter fallback: if the dates span a year, it's Yearly
            if ($startsAt->diffInMonths($endsAt) >= 11) {
                $duration = 'Yearly';
            }
        }

        $subscription->update([
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'next_billing_at' => $endsAt,
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
