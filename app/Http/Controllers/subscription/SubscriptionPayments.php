<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPayments extends Controller
{
    public function index()
    {
        return view('content.app.subscription.payments');
    }
}
