<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;

echo "Testing Registration Controller...\n\n";

// Create a mock request
$request = new Request([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '1234567890',
    'email' => 'newuser_' . time() . '@example.com',
    'password' => 'Test@123456',
    'password_confirmation' => 'Test@123456',
    'role' => 'user',
    'has_whatsapp' => false,
    'has_viber' => false,
]);

$controller = new RegisterController();

try {
    $response = $controller->register($request);
    $statusCode = $response->getStatusCode();
    $content = json_decode($response->getContent());
    
    echo "Status Code: $statusCode\n";
    echo "Response: " . json_encode($content, JSON_PRETTY_PRINT) . "\n\n";
    
    if ($statusCode === 201) {
        echo "✅ Registration successful!\n";
    } else {
        echo "❌ Registration failed with status $statusCode\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}
?>
