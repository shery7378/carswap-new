<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::take(5)->get();
echo "Users in DB:\n";
foreach($users as $u) {
    echo "ID: {$u->id}, Email: {$u->email}\n";
}
