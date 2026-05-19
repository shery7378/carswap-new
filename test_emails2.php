<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$slugs = ['email-verification-link', 'welcome', 'email-verification-otp'];
$templates = DB::table('email_templates')->whereIn('slug', $slugs)->get();
foreach ($templates as $t) {
    echo "Slug: " . $t->slug . "\n";
    echo "Body:\n" . $t->body . "\n";
    echo "----------\n";
}
