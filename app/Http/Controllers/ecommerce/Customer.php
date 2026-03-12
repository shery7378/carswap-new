<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use App\Models\User;

class Customer extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'vertical'];
        $customers = User::role('subscriber')->get();
        return view('content.apps.ecommerce.ecommerce-customer', [
            'pageConfigs' => $pageConfigs,
            'customers' => $customers
        ]);
    }
}
