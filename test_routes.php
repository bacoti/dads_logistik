<?php

// Simple route test script
// Jalankan dengan: php test_routes.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROUTE TESTING ===\n\n";

try {
    // Get all routes
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $adminRoutes = [];
    $poMaterialRoutes = [];

    foreach ($routes as $route) {
        $name = $route->getName();
        $uri = $route->uri();
        $method = implode('|', $route->methods());

        if (str_contains($name ?? '', 'admin')) {
            $adminRoutes[] = [
                'name' => $name,
                'uri' => $uri,
                'method' => $method
            ];
        }

        if (str_contains($name ?? '', 'po-materials') || str_contains($uri, 'po-materials')) {
            $poMaterialRoutes[] = [
                'name' => $name,
                'uri' => $uri,
                'method' => $method
            ];
        }
    }

    echo "1. Total routes: " . count($routes) . "\n";
    echo "2. Admin routes found: " . count($adminRoutes) . "\n";
    echo "3. PO Material routes found: " . count($poMaterialRoutes) . "\n\n";

    if (!empty($adminRoutes)) {
        echo "=== ADMIN ROUTES ===\n";
        foreach (array_slice($adminRoutes, 0, 10) as $route) {
            echo "- {$route['method']} {$route['uri']} ({$route['name']})\n";
        }
        echo "\n";
    }

    if (!empty($poMaterialRoutes)) {
        echo "=== PO MATERIAL ROUTES ===\n";
        foreach ($poMaterialRoutes as $route) {
            echo "- {$route['method']} {$route['uri']} ({$route['name']})\n";
        }
        echo "\n";
    }

    // Test specific route
    echo "=== TESTING SPECIFIC ROUTE ===\n";
    try {
        $url = route('admin.po-materials.update-status', ['poMaterial' => 1]);
        echo "✅ Route 'admin.po-materials.update-status' exists: {$url}\n";
    } catch (Exception $e) {
        echo "❌ Route 'admin.po-materials.update-status' not found: {$e->getMessage()}\n";
    }

    try {
        $url = route('admin.po-materials.index');
        echo "✅ Route 'admin.po-materials.index' exists: {$url}\n";
    } catch (Exception $e) {
        echo "❌ Route 'admin.po-materials.index' not found: {$e->getMessage()}\n";
    }

    try {
        $url = route('admin.po-materials.show', ['poMaterial' => 1]);
        echo "✅ Route 'admin.po-materials.show' exists: {$url}\n";
    } catch (Exception $e) {
        echo "❌ Route 'admin.po-materials.show' not found: {$e->getMessage()}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
