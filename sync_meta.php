<?php
// Final Metadata Sync Script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlFile = 'wordpress_carswap.sql';
echo "Syncing Prices, Mileage, and Images from wp_postmeta...\n";

$metaMapping = [
    'price' => 'price',
    'stm_genuine_price' => 'price',
    'mileage' => 'mileage',
    'registration_date' => 'year',
    'ca-year' => 'year'
];

$handle = fopen($sqlFile, "r");
$parsing = false;
$buffer = '';

while (($line = fgets($handle)) !== false) {
    if (str_contains($line, "INSERT INTO `wp_postmeta`")) {
        $parsing = true;
        $line = substr($line, strpos($line, "VALUES") + 6);
    }

    if ($parsing) {
        $buffer .= $line;
        if (str_contains($line, ");")) {
            $parsing = false;
            $buffer = rtrim(trim($buffer), ";");
            preg_match_all("/\((.*?)\)(?:,|$)/s", $buffer, $matches);

            foreach ($matches[1] as $row) {
                $v = str_getcsv($row, ",", "'");
                // meta_id(0), post_id(1), meta_key(2), meta_value(3)
                if (count($v) >= 4) {
                    $postId = $v[1];
                    $key = $v[2];
                    $val = $v[3];

                    if ($key == 'price' || $key == 'stm_genuine_price') {
                        DB::table('vehicles')->where('id', $postId)->update(['price' => floatval($val)]);
                    } elseif ($key == 'mileage') {
                        DB::table('vehicles')->where('id', $postId)->update(['mileage' => intval($val)]);
                    } elseif ($key == 'registration_date' || $key == 'ca-year' || $key == 'year') {
                        DB::table('vehicles')->where('id', $postId)->update(['year' => intval($val)]);
                    } elseif ($key == '_thumbnail_id') {
                        DB::table('vehicles')->where('id', $postId)->update(['main_image' => $val]);
                    }
                }
            }
            $buffer = '';
        }
    }
}
fclose($handle);

echo "Metadata sync complete.\n";
