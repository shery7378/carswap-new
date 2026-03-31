<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vehicle = \App\Models\Vehicle::find(12);
if ($vehicle) {
    echo "Vehicle 12 owner_type: {$vehicle->owner_type}, user_id: {$vehicle->user_id}\n";
    if ($vehicle->owner_type === 'admin') {
        $admin = \App\Models\Admin::find($vehicle->user_id);
        if ($admin) {
            echo "Admin found: {$admin->email}\n";
        }
    }
}
