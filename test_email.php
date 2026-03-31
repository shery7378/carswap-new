<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

try {
    Mail::raw('This is a test email from your CarSwap backend with SMTP Gmail configuration. If you received this, your email setup is working correctly!', function (Message $message) {
        $message->to('mehtabali001@gmail.com')
            ->subject('✅ Test Email - CarSwap Backend SMTP Working');
    });

    echo "✅ Test email sent successfully to mehtabali001@gmail.com\n";
    echo "📧 Check your email inbox for the test message.\n";
    echo "\n✨ Your SMTP configuration is working correctly!\n";

} catch (\Exception $e) {
    echo "❌ Error sending email:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
