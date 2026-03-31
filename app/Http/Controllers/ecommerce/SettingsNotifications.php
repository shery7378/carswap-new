<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsNotifications extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('content.apps.ecommerce.ecommerce-settings-notifications', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function testConnectivity(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'nullable',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required',
        ]);

        try {
            // Backup current configuration
            $backup = config('mail');

            // Set temporary configuration
            config([
                'mail.default' => $request->mail_mailer,
                'mail.mailers.smtp' => [
                    'transport' => 'smtp',
                    'host' => $request->mail_host,
                    'port' => $request->mail_port,
                    'encryption' => $request->mail_encryption === 'none' ? null : $request->mail_encryption,
                    'username' => $request->mail_username,
                    'password' => $request->mail_password,
                    'timeout' => null,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
                ],
                'mail.from' => [
                    'address' => $request->mail_from_address,
                    'name' => $request->mail_from_name,
                ],
            ]);

            // Purge existing mailer to apply new config
            \Illuminate\Support\Facades\Mail::purge();

            // Send test email
            \Illuminate\Support\Facades\Mail::to($request->mail_from_address)->send(new \App\Mail\DynamicTemplateMail(
                'SMTP Connectivity Test',
                '<h3>Hello!</h3><p>This is a successful test of your SMTP configuration on CarSwap.</p>'
            ));

            // Restore original configuration
            config(['mail' => $backup]);

            return response()->json([
                'success' => true,
                'message' => 'Connection successful! Test email sent to ' . $request->mail_from_address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
