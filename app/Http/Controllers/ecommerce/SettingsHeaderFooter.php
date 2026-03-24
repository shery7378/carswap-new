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
            'header_logo',
            'header_sticky',
            'header_contact_number',
            'header_email',
            'social_facebook',
            'social_instagram',
            'social_linkedin',
            'social_youtube',
            'footer_logo',
            'footer_description',
            'footer_address',
            'footer_copyright',
            'footer_phone',
            'footer_email',
            'footer_mailing_list_title',
            'footer_mailing_list_description',
            'footer_link_contact',
            'footer_link_faq',
            'footer_link_tips',
            'footer_link_offer',
            'footer_link_partners',
            'footer_link_admins',
            'footer_link_subscriptions',
            'footer_link_terms',
            'footer_link_privacy',
            'header_bg_color',
            'header_text_color',
            'footer_bg_color',
            'footer_text_color',
            'theme_primary_color',
            'body_font_size',
            'body_font_family',
            'footer_mailing_list_bg',
            'footer_mailing_list_btn_bg',
            'footer_mailing_list_btn_text',
            'footer_widget_title_font_size',
            'footer_text_font_size',
            'footer_bottom_bar_bg'
        ])->pluck('value', 'key')->toArray();

        return view('content.apps.ecommerce.ecommerce-settings-header-footer', compact('settings'));
    }

    public function store(Request $request)
    {
        $fields = [
            'header_sticky',
            'header_contact_number',
            'header_email',
            'social_facebook',
            'social_instagram',
            'social_linkedin',
            'social_youtube',
            'footer_description',
            'footer_address',
            'footer_copyright',
            'footer_phone',
            'footer_email',
            'footer_mailing_list_title',
            'footer_mailing_list_description',
            'footer_link_contact',
            'footer_link_faq',
            'footer_link_tips',
            'footer_link_offer',
            'footer_link_partners',
            'footer_link_admins',
            'footer_link_subscriptions',
            'footer_link_terms',
            'footer_link_privacy',
            'header_bg_color',
            'header_text_color',
            'footer_bg_color',
            'footer_text_color',
            'theme_primary_color',
            'body_font_size',
            'body_font_family',
            'footer_mailing_list_bg',
            'footer_mailing_list_btn_bg',
            'footer_mailing_list_btn_text',
            'footer_widget_title_font_size',
            'footer_text_font_size',
            'footer_bottom_bar_bg'
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field)]
            );
        }

        // Handle Logos
        if ($request->hasFile('header_logo')) {
            $path = $request->file('header_logo')->store('logos', 'public');
            Setting::updateOrCreate(
                ['key' => 'header_logo'],
                ['value' => Storage::url($path)]
            );
        }

        if ($request->hasFile('footer_logo')) {
            $path = $request->file('footer_logo')->store('logos', 'public');
            Setting::updateOrCreate(
                ['key' => 'footer_logo'],
                ['value' => Storage::url($path)]
            );
        }

        return back()->with('success', 'Header and Footer settings updated successfully!');
    }
}
