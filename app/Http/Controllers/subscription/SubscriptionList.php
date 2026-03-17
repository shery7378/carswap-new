<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionList extends Controller
{
    public function index()
    {
        $subscriptions = \App\Models\Subscription::with(['user', 'plan'])->latest()->get();
        return view('content.app.subscription.list', compact('subscriptions'));
    }
}
