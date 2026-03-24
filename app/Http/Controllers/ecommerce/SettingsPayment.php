<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsPayment extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::whereIn('key', [
            'stripe_public_key',
            'stripe_secret_key',
            'paypal_client_id',
            'paypal_secret'
        ])->pluck('value', 'key')->toArray();

        return view('content.apps.ecommerce.ecommerce-settings-payment', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_secret' => 'nullable|string',
        ]);

        $keys = ['stripe_public_key', 'stripe_secret_key', 'paypal_client_id', 'paypal_secret'];

        foreach ($keys as $key) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        return back()->with('success', 'Payment credentials updated successfully!');
    }
}
