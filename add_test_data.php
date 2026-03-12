<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\VehicleModel;

echo "Adding test brands and models...\n\n";

// Create test brands
$brands_data = [
    ['name' => 'Toyota', 'models' => ['Camry', 'Corolla', 'Civic', 'Accord']],
    ['name' => 'Honda', 'models' => ['CR-V', 'Pilot', 'Odyssey']],
    ['name' => 'BMW', 'models' => ['X5', 'X7', '3 Series', '5 Series']],
    ['name' => 'Mercedes', 'models' => ['E-Class', 'S-Class', 'C-Class', 'GLA']],
    ['name' => 'Audi', 'models' => ['A4', 'A6', 'Q5', 'Q7']],
];

foreach ($brands_data as $brand_info) {
    $brand = Brand::firstOrCreate(['name' => $brand_info['name']]);
    echo "✅ Brand: {$brand->name} (ID: {$brand->id})\n";
    
    foreach ($brand_info['models'] as $model_name) {
        $model = VehicleModel::firstOrCreate([
            'name' => $model_name,
            'brand_id' => $brand->id
        ]);
        echo "   ├─ Model: {$model->name}\n";
    }
}

echo "\n✅ Done! Now try selecting a brand in the dropdown.\n";
?>
