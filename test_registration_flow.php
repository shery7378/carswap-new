<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

echo "Testing registration flow...\n\n";

try {
    // Create a test user
    $testEmail = 'testuser_' . time() . '@example.com';
    
    echo "Creating user with email: $testEmail\n";
    
    $user = User::create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'email' => $testEmail,
        'password' => Hash::make('Test@123456'),
        'role' => 'user',
        'has_whatsapp' => false,
        'has_viber' => false,
    ]);
    
    echo "✅ User created successfully: {$user->email}\n\n";
    
    // Test sending welcome email
    echo "Attempting to send welcome email...\n";
    try {
        Mail::to($user->email)->send(new WelcomeMail($user));
        echo "✅ Welcome email sent successfully!\n";
    } catch (\Exception $e) {
        echo "❌ Email sending failed: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}
?>
