<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsTax extends Controller
{
    public function index()
    {
        return view('content.app.ecommerce.settings.tax');
    }
}
