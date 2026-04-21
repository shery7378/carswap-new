<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

echo "Testing OTP Registration Flow...\n\n";

$controller = new RegisterController();
$email = 'test_otp_' . time() . '@example.com';

// 1. Initial Registration Request
echo "Step 1: Sending Registration Request for $email...\n";
$regRequest = new Request([
    'first_name' => 'OTP',
    'last_name' => 'Tester',
    'phone' => '0987654321',
    'email' => $email,
    'password' => 'Test@123456',
    'password_confirmation' => 'Test@123456',
]);

try {
    $regResponse = $controller->register($regRequest);
    $regContent = json_decode($regResponse->getContent(), true);
    echo "Response: " . json_encode($regContent) . "\n";
    
    if (isset($regContent['success']) && $regContent['success']) {
        echo "✅ Step 1 Success: OTP Sent.\n";
    } else {
        echo "❌ Step 1 Failed.\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Step 1 Exception: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check if user exists (should NOT exist)
echo "\nStep 2: Checking if user exists in database (should NOT)...\n";
$userExists = User::where('email', $email)->exists();
if (!$userExists) {
    echo "✅ Step 2 Success: User NOT created yet.\n";
} else {
    echo "❌ Step 2 Failed: User was created prematurely!\n";
    exit(1);
}

// 3. Verify OTP
echo "\nStep 3: Verifying OTP...\n";
$otp = Cache::get('registration_otp_' . $email);
echo "Retrieved OTP from cache: $otp\n";

$verifyRequest = new Request([
    'email' => $email,
    'otp' => $otp,
]);

try {
    $verifyResponse = $controller->verifyOtp($verifyRequest);
    $verifyContent = json_decode($verifyResponse->getContent(), true);
    echo "Response: " . json_encode($verifyContent) . "\n";
    
    if (isset($verifyContent['success']) && $verifyContent['success']) {
        echo "✅ Step 3 Success: OTP verified and account created.\n";
    } else {
        echo "❌ Step 3 Failed.\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Step 3 Exception: " . $e->getMessage() . "\n";
    exit(1);
}

// 4. Final Verification
echo "\nStep 4: Final verification - checking if user exists now...\n";
$user = User::where('email', $email)->first();
if ($user) {
    echo "✅ Step 4 Success: User exists with ID: " . $user->id . "\n";
} else {
    echo "❌ Step 4 Failed: User still does not exist!\n";
    exit(1);
}

echo "\n🎉 All tests passed!\n";
