<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = EmailTemplate::all()->groupBy('category');
        
        $selectedId = $request->get('template');
        $selectedTemplate = $selectedId 
            ? EmailTemplate::findOrFail($selectedId) 
            : EmailTemplate::first();

        // Get TinyMCE API key from settings
        $tinymce_api_key = \App\Models\Setting::where('key', 'tinymce_api_key')->first()->value ?? 'zhq60pp8shp0wjkatmio4l9686eu1aqofwzmu475rtgnd098';

        return view('content.apps.email-templates.index', compact('templates', 'selectedTemplate', 'tinymce_api_key'));
    }

    public function updateEditorSettings(Request $request)
    {
        $request->validate([
            'tinymce_api_key' => 'nullable|string|max:255',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'tinymce_api_key'],
            ['value' => $request->tinymce_api_key]
        );

        return back()->with('success', 'Editor settings updated successfully!');
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('admin.email-templates.index', ['template' => $template->id])
            ->with('success', __('Email template updated successfully!'));
    }

    private function getMockDataForShortcodes(array $shortcodes)
    {
        $mockData = [
            'first_name' => 'Ahmed',
            'last_name' => 'Doe',
            'user_name' => 'Ahmed Doe',
            'user_email' => 'ahmed.doe@example.com',
            'sender_name' => 'Jane Smith',
            'sender_email' => 'jane.smith@example.com',
            'sender_phone' => '+1 (555) 019-2834',
            'phone' => '+1 (555) 019-2834',
            'dealer_name' => 'Apex Motors',
            'user_contact' => 'Ahmed (ahmed.doe@example.com, +123456789)',
            'subject' => 'Inquiry about Audi R8',
            'message' => 'Hi, is this car still available? I would like to schedule a viewing.',
            'message_content' => 'I am very interested in this vehicle. Please call me back as soon as possible.',
            'car_model' => 'Tesla Model S',
            'date_requested' => date('Y-m-d', strtotime('+2 days')),
            'requested_date' => date('Y-m-d', strtotime('+2 days')),
            'requested_time' => '14:30',
            'car_details' => '2022 BMW 3-Series (Black, Sedan)',
            'contact_info' => 'Email: client@example.com, Phone: +1-202-555-0143',
            'offer_amount' => '$32,000',
            'buyer_name' => 'Alice Johnson',
            'offer_details' => 'Offering $28,000 cash and immediate pickup.',
            'sender_car' => '2018 Ford Mustang GT',
            'target_car' => '2021 Porsche Cayman',
            'listing_url' => url('/listings/mock-123'),
            'live_url' => url('/listings/mock-123'),
            'car_title' => '2020 Audi R8 V10',
            'listing_details' => '2020 Audi R8 V10 - Premium Listing (30 Days)',
            'payment_status' => 'Completed / Paid',
            'review_id' => '#9843',
            'reporter_info' => 'Admin Moderator (admin@carswap.com)',
            'reason' => 'Inappropriate language in review description',
            'receipt_url' => url('/receipts/rec-9382'),
            'amount_paid' => '$49.00',
            'valuation_amount' => '$42,500',
            'car_specs' => '2021 Mercedes-Benz E-Class, 25,000 miles, Excellent condition',
            'review_time_estimate' => '24 hours',
            'reply_link' => url('/messages/reply/982'),
            'comment' => 'I would love to trade this vehicle as soon as possible. My car is in excellent condition.',
            'offered_car_title' => '2019 Toyota RAV4 Hybrid',
            'offered_car_brand' => 'Toyota',
            'offered_car_model' => 'RAV4',
            'offered_car_year' => '2019',
            'offered_car_odometer' => '45,000 km',
            'offered_car_fuel' => 'Hybrid',
            'offered_car_gearbox' => 'Automatic',
            'offered_car_ext_color' => 'Silver Metallic',
            'offered_car_int_color' => 'Black Leather',
            'offered_car_drive' => 'AWD',
            'offered_car_chassis' => 'JTMRF7FV7KD123456',
            'target_car_title' => '2022 Ford F-150',
            'exterior_condition' => 'Excellent, no scratches',
            'interior_condition' => 'Very good, minor wear on driver seat',
            'is_accident' => 'None / No accidents reported',
            'frontend_url' => env('FRONTEND_URL', 'https://carswap.example.com'),
            'login_url' => env('FRONTEND_URL', 'https://carswap.example.com') . '/login',
            'reset_link' => env('FRONTEND_URL', 'https://carswap.example.com') . '/reset-password?token=mock_token_abc123',
            'verification_link' => url('/api/verify-email?token=mock_token_abc123'),
            'confirmation_link' => url('/api/verify-email?token=mock_token_abc123'),
            'otp' => '654321',
            'email' => 'ahmed.doe@example.com',
            'make' => 'Audi',
            'model' => 'A6',
            'stm_year' => '2020',
            'transmission' => 'Automatic',
            'mileage' => '32,000 mi',
            'vin' => 'WAUZZZ4GZHN123456',
            'exterior_color' => 'Mythos Black',
            'interior_color' => 'Rock Gray',
            'owner' => 'First Owner',
            'accident' => 'No accidents',
            'comments' => 'Well maintained, full service history.',
            'car' => '2020 Audi A6',
        ];

        $result = [];
        foreach ($shortcodes as $code) {
            $trimmedCode = trim($code);
            $result[$trimmedCode] = $mockData[$trimmedCode] ?? "[{$trimmedCode}]";
        }
        return $result;
    }

    public function previewTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $shortcodes = $template->shortcodes;
        if (!is_array($shortcodes)) {
            $shortcodes = $shortcodes ? explode(',', $shortcodes) : [];
        }
        
        $mockData = $this->getMockDataForShortcodes($shortcodes);
        $rendered = $template->render($mockData);
        
        $body = $rendered['body'];
        if (strpos($body, '<html') === false && strpos($body, '<body') === false) {
            $body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <style>
                    body { font-family: 'Public Sans', sans-serif; background-color: #f4f6f9; padding: 20px; color: #333; }
                    .email-preview-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                </style>
            </head>
            <body>
                <div class='email-preview-container'>
                    {$body}
                </div>
            </body>
            </html>
            ";
        }
        
        return response($body)->header('Content-Type', 'text/html');
    }

    public function sendTestEmail(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        try {
            $template = EmailTemplate::findOrFail($id);
            
            $shortcodes = $template->shortcodes;
            if (!is_array($shortcodes)) {
                $shortcodes = $shortcodes ? explode(',', $shortcodes) : [];
            }
            
            $mockData = $this->getMockDataForShortcodes($shortcodes);
            
            // Using EmailService or direct DynamicMail facade
            \Illuminate\Support\Facades\Mail::to($request->email)->send(
                new \App\Mail\DynamicMail($template->slug, $mockData)
            );
            
            return response()->json([
                'success' => true,
                'message' => __('Test email for template ":name" successfully sent to :email!', [
                    'name' => $template->name,
                    'email' => $request->email
                ])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to send test email: ') . $e->getMessage()
            ], 500);
        }
    }
}
