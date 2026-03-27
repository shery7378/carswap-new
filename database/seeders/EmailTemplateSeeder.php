<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            ['name' => 'Welcome', 'slug' => 'welcome', 'subject' => 'Welcome to CarSwap! 🎉', 'category' => 'General', 'shortcodes' => ['first_name', 'frontend_url', 'login_url']],
            ['name' => 'New User', 'slug' => 'new-user', 'subject' => 'New User Registration', 'category' => 'Auth', 'shortcodes' => ['user_name', 'user_email']],
            ['name' => 'New User Email Confirmation', 'slug' => 'new-user-email-confirmation', 'subject' => 'Please confirm your email address', 'category' => 'Auth', 'shortcodes' => ['first_name', 'confirmation_link']],
            ['name' => 'Password Recovery', 'slug' => 'password-recovery', 'subject' => 'Reset Your Password', 'category' => 'Auth', 'shortcodes' => ['first_name', 'reset_link']],
            ['name' => 'Request For a Dealer', 'slug' => 'request-for-dealer', 'subject' => 'New Dealer Inquiry', 'category' => 'Inquiry', 'shortcodes' => ['dealer_name', 'user_contact', 'message']],
            ['name' => 'Test Drive', 'slug' => 'test-drive', 'subject' => 'Test Drive Request', 'category' => 'Trade', 'shortcodes' => ['first_name', 'car_model', 'date_requested']],
            ['name' => 'Request Price', 'slug' => 'request-price', 'subject' => 'Price Quote Request', 'category' => 'Inquiry', 'shortcodes' => ['first_name', 'car_details', 'contact_info']],
            ['name' => 'Trade In', 'slug' => 'trade-in', 'subject' => 'Autó beszámítási kérelem', 'category' => 'Trade', 'shortcodes' => ['car', 'first_name', 'last_name', 'email', 'phone', 'make', 'model', 'stm_year', 'transmission', 'mileage', 'vin', 'exterior_color', 'interior_color', 'owner', 'exterior_condition', 'interior_condition', 'accident', 'comments']],
            ['name' => 'Trade Offer', 'slug' => 'trade-offer', 'subject' => 'New Trade Offer Received', 'category' => 'Trade', 'shortcodes' => ['offer_amount', 'car_details', 'buyer_name']],
            ['name' => 'Add a Car', 'slug' => 'add-car', 'subject' => 'Your listing is live!', 'category' => 'Listings', 'shortcodes' => ['listing_url', 'car_title']],
            ['name' => 'Update a Car', 'slug' => 'update-car', 'subject' => 'Listing Updated Successfully', 'category' => 'Listings', 'shortcodes' => ['car_title', 'listing_url']],
            ['name' => 'Update a Pay Per Listing', 'slug' => 'update-pay-per-listing', 'subject' => 'Paid Listing Update', 'category' => 'Payments', 'shortcodes' => ['listing_details', 'payment_status']],
            ['name' => 'Report Review', 'slug' => 'report-review', 'subject' => 'A Review has been reported', 'category' => 'Moderation', 'shortcodes' => ['review_id', 'reporter_info', 'reason']],
            ['name' => 'Pay Per Listing', 'slug' => 'pay-per-listing', 'subject' => 'Listing Payment Confirmation', 'category' => 'Payments', 'shortcodes' => ['receipt_url', 'amount_paid', 'car_title']],
            ['name' => 'Value My Car', 'slug' => 'value-my-car', 'subject' => 'Your Car Valuation Report', 'category' => 'Inquiry', 'shortcodes' => ['first_name', 'valuation_amount', 'car_specs']],
            ['name' => 'User listing waiting', 'slug' => 'user-listing-waiting', 'subject' => 'Listing Pending Approval', 'category' => 'Listings', 'shortcodes' => ['car_title', 'review_time_estimate']],
            ['name' => 'User listing approved', 'slug' => 'user-listing-approved', 'subject' => 'Your Listing is Approved!', 'category' => 'Listings', 'shortcodes' => ['car_title', 'live_url']],
            ['name' => 'Message to Dealer', 'slug' => 'message-to-dealer', 'subject' => 'New Message from Buyer', 'category' => 'Communication', 'shortcodes' => ['sender_name', 'message_content', 'reply_link']],
            ['name' => 'Vehicle Inquiry', 'slug' => 'vehicle-inquiry', 'subject' => 'New Inquiry about your vehicle: [car_title]', 'category' => 'Inquiry', 'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'message_content']],
            ['name' => 'Arrange Viewing Time', 'slug' => 'arrange-viewing-time', 'subject' => 'Viewing Request for [car_title]', 'category' => 'Inquiry', 'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'requested_date', 'requested_time', 'message_content']],
        ];

        foreach ($templates as $template) {
            
            // Generate basic body structure for new seed records
            $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>\n<h2>{$template['subject']}</h2>\n<p>Hello,</p>\n<p>This is a dynamic email specifically for {$template['name']}. You can use the shortcodes below:</p>\n<ul>";
            
            foreach($template['shortcodes'] as $code) {
                $body .= "<li><strong>{$code}:</strong> [{$code}]</li>\n";
            }
            $body .= "</ul>\n<p>Regards,<br>CarSwap Team</p>\n</div>";

            // If it's Welcome or Password, keep original seeded HTML if extending
            if ($template['slug'] == 'welcome') {
                $body = "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>Welcome to CarSwap! 🎉</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>Hello <strong>[first_name]</strong>,</p><p>Thank you for joining CarSwap!</p><h3>Get Started with CarSwap:</h3><ul><li><strong>📋 Complete Your Profile:</strong> Add a profile picture and detailed information.</li><li><strong>🚗 Browse Listings:</strong> Explore thousands of vehicles from verified sellers.</li><li><strong>💬 Connect with Traders:</strong> Message sellers and buyers directly.</li></ul></div><p style='text-align: center;'><a href='[frontend_url]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;'>Start Browsing</a></p>";
            } else if ($template['slug'] == 'password-recovery') {
                $body = "<p>Hello [first_name],</p><p>You are receiving this email because we received a password reset request for your account.</p><p style='text-align: center;'><a href='[reset_link]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;'>Reset Password</a></p><p>If you did not request a password reset, no further action is required.</p>";
            }

            \App\Models\EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                [
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'category' => $template['category'],
                    'shortcodes' => $template['shortcodes'],
                    'body' => $body
                ]
            );
        }
    }
}
