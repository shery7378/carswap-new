<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPayments extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with(['user', 'plan'])->latest()->get();
        return view('content.app.subscription.payments', compact('payments'));
    }
}
