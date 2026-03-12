<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\VehicleModel;

echo "=== Testing API Data ===\n\n";

// Get all brands
$brands = Brand::all();
echo "✅ Total Brands: " . $brands->count() . "\n";
foreach ($brands as $brand) {
    echo "  Brand ID: {$brand->id} = {$brand->name}\n";
}

echo "\n";

// Test with brand ID 6 (Toyota)
$brandId = 6;
$models = VehicleModel::where('brand_id', $brandId)->get();
echo "✅ Models for Brand ID $brandId: " . $models->count() . "\n";
foreach ($models as $model) {
    echo "  - {$model->name} (brand_id: {$model->brand_id})\n";
}

echo "\n=== Testing Direct JSON Response ===\n\n";
echo "API Response for /api/brands/$brandId/models:\n";
echo json_encode($models, JSON_PRETTY_PRINT);
?>
