<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plan;

$plans = Plan::all();
foreach ($plans as $plan) {
    echo "ID: {$plan->id} | Name: {$plan->name} | Slug: {$plan->slug} | Active Ads: {$plan->active_ads_limit} | Updated At: {$plan->updated_at}\n";
    echo "Features: " . json_encode($plan->features) . "\n";
    echo "--------------------------------------------------\n";
}
