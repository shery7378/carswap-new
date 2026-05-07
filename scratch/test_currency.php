<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Number;

echo "HUF (hu): " . Number::currency(3000, in: 'HUF', locale: 'hu') . PHP_EOL;
echo "HUF (en): " . Number::currency(3000, in: 'HUF', locale: 'en') . PHP_EOL;
echo "Manual: " . number_format(3000, 0, ',', ' ') . " Ft" . PHP_EOL;
