<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsGeneral extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('content.apps.ecommerce.ecommerce-settings-general', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', 'storeLogo', 'storeFavicon']);
        
        // Handle Logo Upload
        if ($request->hasFile('storeLogo')) {
            $path = $request->file('storeLogo')->store('settings', 'public');
            $data['storeLogo'] = $path;
        }

        // Handle Favicon Upload
        if ($request->hasFile('storeFavicon')) {
            $path = $request->file('storeFavicon')->store('settings', 'public');
            $data['storeFavicon'] = $path;
        }

        // Handle checkbox for maintenance mode (since unchecked checkboxes aren't sent)
        if (!isset($data['maintenanceMode'])) {
            $data['maintenanceMode'] = '0';
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        // Clear the cache so new settings are immediately applied
        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }
}
