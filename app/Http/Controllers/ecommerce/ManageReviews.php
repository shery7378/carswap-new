<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageReviews extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'vertical'];
        return view('content.apps.ecommerce.ecommerce-manage-reviews', ['pageConfigs' => $pageConfigs]);
    }
}
