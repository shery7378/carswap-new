<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Subscription extends Controller
{
    public function list()
    {
        $pageConfigs = ['myLayout' => 'vertical'];
        return view('content.apps.ecommerce.subscription-list', ['pageConfigs' => $pageConfigs]);
    }

    public function create()
    {
        $pageConfigs = ['myLayout' => 'vertical'];
        return view('content.apps.ecommerce.subscription-create', ['pageConfigs' => $pageConfigs]);
    }
}
