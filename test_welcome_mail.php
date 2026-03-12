<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\WelcomeMail;
use App\Models\User;

// Create a mock user object using User model
$mockUser = new User();
$mockUser->first_name = 'Ahmed';
$mockUser->email = 'ahmed@example.com';

// Create the mailable
$mailable = new WelcomeMail($mockUser);

// Try to build it
try {
    $message = $mailable->build();
    echo "✅ SUCCESS: Welcome Mail class built without view errors!\n\n";
    
    // Get the details
    echo "📧 Email Subject: " . $mailable->subject . "\n";
    echo "📧 Email To: " . $mockUser->email . "\n";
    echo "📧 Email From: " . config('mail.from.name') . " <" . config('mail.from.address') . ">\n\n";
    
    echo "✅ Welcome email is ready to be sent to new users!\n";
    
} catch (Exception $e) {
    echo "❌ Error building welcome mail: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}
?>
