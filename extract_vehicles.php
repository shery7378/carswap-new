<?php
// Improved Extraction Script - Streamlined for Large Files
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlFile = 'wordpress_carswap.sql';
if (!file_exists($sqlFile))
    die("SQL file not found.\n");

function parse_inserts($file, $table, $callback)
{
    echo "Processing $table...\n";
    $handle = fopen($file, "r");
    $parsing = false;
    $buffer = '';

    while (($line = fgets($handle)) !== false) {
        if (str_contains($line, "INSERT INTO `$table`")) {
            $parsing = true;
            $line = substr($line, strpos($line, "VALUES") + 6);
        }

        if ($parsing) {
            $buffer .= $line;
            if (str_contains($line, ");")) {
                $parsing = false;
                $buffer = trim($buffer);
                $buffer = rtrim($buffer, ";");

                // Match each (row) using a regex instead of str_getcsv which fails on multi-row
                preg_match_all("/\((.*?)\)(?:,|$)/s", $buffer, $matches);
                foreach ($matches[1] as $row) {
                    $values = str_getcsv($row, ",", "'");
                    $callback($values);
                }
                $buffer = '';
            }
        }
    }
    fclose($handle);
}

// 1. Process Brands
parse_inserts($sqlFile, 'wp_terms', function ($v) {
    if (isset($v[1])) {
        DB::table('brands')->updateOrInsert(['name' => $v[1]], ['created_at' => now()]);
    }
});

// 2. Process Vehicles
$count = 0;
parse_inserts($sqlFile, 'wp_posts', function ($v) use (&$count) {
    // ID(0), title(5), content(4), status(7), type(20)
    // Common vehicle listing types: 'listings', 'cars', 'post'
    if (isset($v[20]) && in_array($v[20], ['listings', 'cars', 'st_cars', 'product'])) {
        DB::table('vehicles')->insert([
            'user_id' => 1,
            'title' => substr($v[5], 0, 191),
            'description' => $v[4] ?? '',
            'status' => $v[7] == 'publish' ? 'active' : 'inactive',
            'created_at' => (strlen($v[2]) > 5) ? $v[2] : now(),
            'updated_at' => now(),
        ]);
        $count++;
    }
});

echo "\nSUCCESS: $count vehicles imported into Laravel.\n";
