<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\VehicleModel;

echo "=== Checking Database Data ===\n\n";

$brands = Brand::all();
echo "Total Brands: " . count($brands) . "\n";
if (count($brands) > 0) {
    echo "\nBrands:\n";
    foreach ($brands as $brand) {
        $models = VehicleModel::where('brand_id', $brand->id)->get();
        echo "  - {$brand->name} (ID: {$brand->id}) - {$models->count()} models\n";
        if ($models->count() > 0) {
            foreach ($models as $model) {
                echo "      • {$model->name}\n";
            }
        }
    }
} else {
    echo "\n❌ No brands found in database!\n";
}

$totalModels = VehicleModel::count();
echo "\nTotal Vehicle Models: " . $totalModels . "\n";

if ($totalModels === 0) {
    echo "\n⚠️  WARNING: No models found! You need to create brands and models first.\n";
    echo "   Go to Admin Panel > Vehicle Settings > Brands to add brands\n";
    echo "   Go to Admin Panel > Vehicle Settings > Models to add models\n";
}
?>
