<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $plans = \App\Models\Plan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $activeSubscription = null;
        if ($request->user('sanctum')) {
            $activeSubscription = $request->user('sanctum')->load('activeSubscription.plan')->activeSubscription;
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $plans,
            'active_subscription' => $activeSubscription
        ]);
    }
}
