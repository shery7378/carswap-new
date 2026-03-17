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
}
