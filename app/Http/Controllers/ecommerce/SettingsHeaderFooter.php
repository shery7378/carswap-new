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
        $defaults = [
            'footer_mailing_list_title' => 'Csatlakozz a levelezőlistánkhoz!',
            'footer_mailing_list_subtitle' => 'Join our mailing list! Stay updated with our latest offers.',
            'footer_mailing_list_note' => 'Hetente néhány levél, semmi felesleges.'
        ];

        $dbSettings = Setting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();
        $settings = array_merge($defaults, $dbSettings);

        return view('content.apps.ecommerce.ecommerce-settings-header-footer', compact('settings'));
    }

    public function store(Request $request)
    {
        $fields = [
            'footer_mailing_list_title',
            'footer_mailing_list_subtitle',
            'footer_mailing_list_note'
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
