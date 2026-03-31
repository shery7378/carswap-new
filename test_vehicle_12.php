<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vehicle = \App\Models\Vehicle::find(12);
if ($vehicle) {
    echo "Vehicle found! user_id: " . $vehicle->user_id . "\n";
    $user = $vehicle->user; // try to get user
    if ($user) {
        echo "User found! ID: {$user->id}, Email: {$user->email}\n";
    } else {
        echo "User relationship returned null. Let's try querying user directly: \n";
        $directUser = \App\Models\User::find($vehicle->user_id);
        if ($directUser) {
            echo "Direct query found user: {$directUser->id}, Email: {$directUser->email}\n";
        } else {
            echo "Direct query could not find user with ID {$vehicle->user_id} in users table.\n";
        }
    }
} else {
    echo "Vehicle 12 not found in DB\n";
}
