<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsGeneral extends Controller
{
    public function index()
    {
        return view('content.apps.ecommerce.ecommerce-settings-general');
    }
}
