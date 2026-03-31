<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = \App\Models\Admin::find(1);
if ($admin) {
    echo "Admin 1: {$admin->email}\n";
} else {
    echo "Admin 1 not found.\n";
}
