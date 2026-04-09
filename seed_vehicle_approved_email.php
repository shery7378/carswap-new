<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$template = [
    'slug' => 'vehicle-approved',
    'name' => 'Vehicle Approved',
    'subject' => 'Great news! Your vehicle listing has been approved 🚀',
    'body' => "
<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'>
    <h2>Verification Successful! ✅</h2>
</div>
<div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'>
    <p>Hello <strong>[first_name]</strong>,</p>
    <p>We are excited to inform you that your vehicle listing <strong>[vehicle_name]</strong> has been reviewed and approved by our moderation team.</p>
    <p>It is now live on the marketplace and visible to thousands of potential buyers and traders.</p>
    <h3>What happens next?</h3>
    <ul>
        <li><strong>📈 Visibility:</strong> Your car is now showing up in search results.</li>
        <li><strong>💬 Inquiries:</strong> Keep an eye on your dashboard for messages and viewing requests.</li>
        <li><strong>🔄 Updates:</strong> You can edit your listing details or upload more photos at any time.</li>
    </ul>
</div>
<div style='text-align: center; margin-top: 30px;'>
    <a href='[vehicle_url]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>View Your Listing</a>
</div>
<p style='margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px; color: #777; font-size: 13px;'>
    If you have any questions, feel free to reply to this email or contact our support team.
</p>",
    'shortcodes' => ['first_name', 'vehicle_name', 'vehicle_url'],
    'category' => 'vehicles'
];

App\Models\EmailTemplate::updateOrCreate(['slug' => 'vehicle-approved'], $template);
echo "Vehicle Approved template created successfully.\n";
