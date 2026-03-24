<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get all public settings (header, footer, social, etc.)
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        // Filter out sensitive settings if any (e.g. stripe secret keys)
        $publicKeys = [
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
            'footer_bottom_bar_bg',
            'stripe_public_key'
        ];

        $filteredSettings = $settings->only($publicKeys);

        return response()->json([
            'success' => true,
            'data' => $filteredSettings
        ]);
    }
}
