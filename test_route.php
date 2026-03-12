<?php
// Test if the route/URL is accessible
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

$routes = $app['router']->getRoutes();

echo "=== Checking for '/app/vehicles/models-by-brand' routes ===\n\n";

$found = false;
foreach ($routes as $route) {
    if (strpos($route->uri, 'models-by-brand') !== false) {
        $found = true;
        echo "✅ FOUND Route:\n";
        echo "   URI: " . $route->uri . "\n";
        echo "   Methods: " . implode(', ', $route->methods) . "\n";
        echo "   Action: " . (is_array($route->action) ? ($route->action['controller'] ?? 'Closure') : $route->action) . "\n";
        echo "   Name: " . ($route->name ?? 'N/A') . "\n\n";
    }
}

if (!$found) {
    echo "❌ NO ROUTE FOUND for 'models-by-brand'\n\n";
    echo "Available vehicle routes:\n";
    foreach ($routes as $route) {
        if (strpos($route->uri, 'vehicles') !== false) {
            echo "   - " . $route->uri . "\n";
        }
    }
}
?>
