<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

// Create a mock user object using User model
$mockUser = new User();
$mockUser->email = 'test@example.com';
$mockUser->first_name = 'Test';

$token = 'test-token-12345';

// Create the mailable
$mailable = new ResetPasswordMail($mockUser, $token);

// Try to build it
try {
    $message = $mailable->build();
    echo "✅ SUCCESS: Mail class built without view errors!\n\n";
    
    // Get the HTML content
    echo "📧 Email Subject: " . $mailable->subject . "\n";
    echo "📧 Email From: " . config('mail.from.name') . " <" . config('mail.from.address') . ">\n";
    echo "📧 Email To: " . $mockUser->email . "\n\n";
    
    // Check if it has HTML content
    if (method_exists($message, 'getHtmlPart')) {
        echo "✅ HTML content present and ready to send\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error building mail: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}
?>
