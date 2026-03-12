<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

try {
    $user = User::where('email', 'admin@example.com')->first();
    if (!$user) {
        echo "❌ No admin user found\n";
        exit(1);
    }
    
    $token = 'test_token_12345';
    
    Mail::to('mehtabali001@gmail.com')->send(new ResetPasswordMail($user, $token));
    
    echo "✅ Test reset email sent successfully!\n";
    echo "📧 Check mehtabali001@gmail.com for the email\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n📋 Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
