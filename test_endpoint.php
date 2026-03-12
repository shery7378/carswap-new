<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Http\Controllers\dashboard\AdminVehicleController;

echo "Testing getModelsByBrand endpoint...\n\n";

$controller = new AdminVehicleController();

// Get Toyota (ID: 6)
$brandId = 6;
$models = VehicleModel::where('brand_id', $brandId)->get();

echo "Brand ID: $brandId\n";
echo "Models found: " . count($models) . "\n\n";

foreach ($models as $model) {
    echo "  - {$model->name} (ID: {$model->id})\n";
}

echo "\nJSON Response:\n";
echo json_encode($models, JSON_PRETTY_PRINT) . "\n";
?>
