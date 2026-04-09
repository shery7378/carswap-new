<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Setting::all() as $setting) {
    echo "Key: " . $setting->key . " | Value: " . $setting->value . "\n";
}
