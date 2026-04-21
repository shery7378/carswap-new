<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

echo "Testing Registration Password Validation Rules...\n\n";

$controller = new RegisterController();

$testCases = [
    [
        'name' => 'Too short password (5 chars)',
        'data' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '123456',
            'email' => 'test@example.com',
            'password' => 'Abcd1',
            'password_confirmation' => 'Abcd1',
        ]
    ],
    [
        'name' => 'No capital letter start',
        'data' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '123456',
            'email' => 'test@example.com',
            'password' => 'abcd1@',
            'password_confirmation' => 'abcd1@',
        ]
    ],
    [
        'name' => 'No special character',
        'data' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '123456',
            'email' => 'test@example.com',
            'password' => 'Abcd12',
            'password_confirmation' => 'Abcd12',
        ]
    ],
    [
        'name' => 'Valid password',
        'data' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '123456',
            'email' => 'valid_' . time() . '@example.com',
            'password' => 'Abcd1@',
            'password_confirmation' => 'Abcd1@',
        ]
    ]
];

foreach ($testCases as $case) {
    echo "--- Case: " . $case['name'] . " ---\n";
    $request = new Request($case['data']);
    
    try {
        $response = $controller->register($request);
        $content = json_decode($response->getContent(), true);
        if (isset($content['success']) && $content['success']) {
            echo "✅ Success (Expected for valid case)\n";
        } else {
            echo "❌ Failed: " . json_encode($content['errors'] ?? $content['message'], JSON_UNESCAPED_UNICODE) . "\n";
        }
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
