<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsPayment extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('key', [
            'stripe_public_key',
            'stripe_secret_key'
        ])->pluck('value', 'key')->toArray();

        return view('content.apps.ecommerce.ecommerce-settings-payment', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
        ]);

        $keys = ['stripe_public_key', 'stripe_secret_key'];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        return back()->with('success', 'Stripe credentials updated successfully!');
    }
}
