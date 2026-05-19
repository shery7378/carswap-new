<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$templates = DB::table('email_templates')->get();
foreach ($templates as $t) {
    echo "Slug: " . $t->slug . "\n";
    echo "Subject: " . $t->subject . "\n";
    echo "Body: " . substr($t->body, 0, 200) . "...\n";
    echo "----------\n";
}
