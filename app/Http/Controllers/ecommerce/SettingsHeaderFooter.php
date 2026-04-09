<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsHeaderFooter extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('key', [
            'footer_name',
            'footer_description'
        ])->pluck('value', 'key')->toArray();

        return view('content.apps.ecommerce.ecommerce-settings-header-footer', compact('settings'));
    }

    public function store(Request $request)
    {
        $fields = [
            'footer_name',
            'footer_description'
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field)]
            );
        }


        // Clear public settings cache
        \Illuminate\Support\Facades\Cache::forget('carswap_settings_public');

        return back()->with('success', 'Footer settings updated successfully!');
    }
}
