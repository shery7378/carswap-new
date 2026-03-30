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
        // Define default values for each setting
        $defaults = [
            // General Settings
            'storeName' => 'CarSwap',
            'storeEmail' => 'contact@carswap.com',
            'storePhone' => '',
            'storeWebsite' => '',
            'storeAddress' => '',
            'storeLogo' => null,
            'storeFavicon' => null,
            'defaultCurrency' => 'HUF',
            'currencyPosition' => 'right_space',
            'thousandSeparator' => '.',
            'decimalSeparator' => ',',
            'timezone' => 'Europe/Budapest',
            'dateFormat' => 'Y-m-d',
            'language' => 'hu',
            'metaTitle' => '',
            'metaDescription' => '',
            'metaKeywords' => '',
            'googleAnalytics' => '',
            'maintenanceMode' => '0',
            'maintenanceMessage' => '',
            
            // Header / Footer Settings
            'header_logo' => null,
            'header_sticky' => '1',
            'header_contact_number' => '',
            'header_email' => '',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_linkedin' => '',
            'social_youtube' => '',
            'footer_logo' => null,
            'footer_description' => 'CarSwap - Premium Vehicle Marketplace',
            'footer_address' => '123 Enterprise Way, London, UK',
            'footer_copyright' => '© 2024-2025 CARSWAP | All rights reserved.',
            'footer_phone' => '',
            'footer_email' => '',
            'footer_mailing_list_title' => 'Join our mailing list!',
            'footer_mailing_list_description' => 'Stay updated with our latest offers.',
            'footer_link_contact' => '#',
            'footer_link_faq' => '#',
            'footer_link_tips' => '#',
            'footer_link_offer' => '#',
            'footer_link_partners' => '#',
            'footer_link_admins' => '#',
            'footer_link_subscriptions' => '#',
            'footer_link_terms' => '#',
            'footer_link_privacy' => '#',
            'header_bg_color' => '#ffffff',
            'header_text_color' => '#333333',
            'footer_bg_color' => '#000000',
            'footer_text_color' => '#ffffff',
            'theme_primary_color' => '#696cff',
            'body_font_size' => '16',
            'body_font_family' => 'Inter',
            'footer_mailing_list_bg' => '#ffffff',
            'footer_mailing_list_btn_bg' => '#D2B48C',
            'footer_mailing_list_btn_text' => '#ffffff',
            'footer_widget_title_font_size' => '18',
            'footer_text_font_size' => '14',
            'footer_bottom_bar_bg' => '#000000',
            'stripe_public_key' => null
        ];

        // Fetch all values currently in DB
        $dbValues = Setting::all()->pluck('value', 'key')->toArray();

        // Format Logo / Favicon as full URLs
        if (!empty($dbValues['storeLogo'])) {
            $dbValues['storeLogo'] = asset('storage/' . $dbValues['storeLogo']);
        }
        if (!empty($dbValues['storeFavicon'])) {
            $dbValues['storeFavicon'] = asset('storage/' . $dbValues['storeFavicon']);
        }

        // Merge DB values into defaults (DB overwrites defaults)
        $finalSettings = array_merge($defaults, $dbValues);

        // Filter for public keys only (in case DB has private keys like stripe_secret_key)
        $publicKeys = array_keys($defaults);
        $filteredSettings = array_intersect_key($finalSettings, array_flip($publicKeys));

        return response()->json([
            'success' => true,
            'data' => $filteredSettings
        ]);
    }
}
