<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sub = \App\Models\Subscription::find(14);
if ($sub) {
    if ($sub->plan_id == 15) { // PARTNER PACKAGE YEARLY
        $sub->update([
            'duration' => 'Yearly',
            'next_billing_at' => $sub->starts_at->addYear(),
        ]);
        echo "Fixed Subscription 14: Duration set to Yearly and next_billing_at set to " . $sub->next_billing_at->toDateTimeString() . "\n";
    } else {
        echo "Subscription 14 is not for Plan 15 (it is for Plan {$sub->plan_id}). No change made.\n";
    }
} else {
    echo "Subscription 14 not found.\n";
}
