<?php

use App\Models\EmailTemplate;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$templates = [
    'contact-us' => [
        'name' => 'Contact Us Form Submission',
        'subject' => 'New Support Request: [subject]',
        'body' => "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>New Support Request 📩</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>A new visitor has sent a message through the Contact Us form:</p><ul><li><strong>Name:</strong> [sender_name]</li><li><strong>Email:</strong> [sender_email]</li><li><strong>Phone:</strong> [sender_phone]</li><li><strong>Subject:</strong> [subject]</li></ul><div style='background-color: #fff9e6; padding: 15px; border-left: 4px solid #dcb377; margin-top: 15px;'><strong>Message Content:</strong><p>[message_content]</p></div></div>",
        'shortcodes' => ['sender_name', 'sender_email', 'sender_phone', 'subject', 'message_content'],
    ],
    'vehicle-inquiry' => [
        'name' => 'Vehicle Inquiry',
        'subject' => 'New Inquiry for [car_title]',
        'body' => "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>Inquiry for Your Asset! 🚗</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>Someone is interested in your vehicle: <strong>[car_title]</strong></p><ul><li><strong>Sender Name:</strong> [sender_name]</li><li><strong>Email:</strong> [sender_email]</li><li><strong>Phone:</strong> [sender_phone]</li></ul><div style='background-color: #fff9e6; padding: 15px; border-left: 4px solid #dcb377; margin-top: 15px;'><strong>Inquiry Message:</strong><p>[message_content]</p></div></div><p style='text-align: center;'><a href='[frontend_url]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;'>View Listing</a></p>",
        'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'message_content', 'frontend_url'],
    ],
    'arrange-viewing-time' => [
        'name' => 'Arrange Viewing Request',
        'subject' => 'Viewing Request for [car_title]',
        'body' => "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>Viewing Request! 📅</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>Someone wants to view your vehicle: <strong>[car_title]</strong></p><ul><li><strong>Requested Date:</strong> [requested_date]</li><li><strong>Requested Time:</strong> [requested_time]</li></ul><h3>Sender Contact:</h3><ul><li><strong>Name:</strong> [sender_name]</li><li><strong>Email:</strong> [sender_email]</li><li><strong>Phone:</strong> [sender_phone]</li></ul><div style='background-color: #fff9e6; padding: 15px; border-left: 4px solid #dcb377; margin-top: 15px;'><strong>Additional Note:</strong><p>[message_content]</p></div></div>",
        'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'requested_date', 'requested_time', 'message_content'],
    ],
    'trade-offer-received' => [
        'name' => 'Trade Offer Received',
        'subject' => 'New Trade Offer for [target_car_title]',
        'body' => "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>New Trade Offer Received! 🔄</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>You have received a new trade offer for your vehicle: <strong>[target_car_title]</strong></p><h3>Offered Jármű Details:</h3><ul><li><strong>Brand:</strong> [offered_car_brand]</li><li><strong>Model:</strong> [offered_car_model]</li><li><strong>Year:</strong> [offered_car_year]</li></ul><h3>Sender Information:</h3><ul><li><strong>Name:</strong> [sender_name]</li><li><strong>Email:</strong> [sender_email]</li><li><strong>Phone:</strong> [sender_phone]</li></ul><div style='background-color: #fff9e6; padding: 15px; border-left: 4px solid #dcb377; margin-top: 15px;'><strong>Comment:</strong><p>[comment]</p></div></div>",
        'shortcodes' => ['target_car_title', 'offered_car_brand', 'offered_car_model', 'offered_car_year', 'sender_name', 'sender_email', 'sender_phone', 'comment'],
    ],
];

foreach ($templates as $slug => $data) {
    EmailTemplate::updateOrCreate(['slug' => $slug], $data);
    echo "Updated template: $slug\n";
}
