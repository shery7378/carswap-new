<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlans extends Controller
{
    public function index()
    {
        return view('content.apps.subscription.plans');
    }
}
