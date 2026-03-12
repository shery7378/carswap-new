<?php

namespace App\Http\Controllers\subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionList extends Controller
{
    public function index()
    {
        return view('content.app.subscription.list');
    }
}
