<?php

use App\Http\Controllers\Api\MasterDataController;
use Illuminate\Http\Request;

// Test API endpoints
echo "Testing Master Data API Endpoints...\n\n";

// Test 1: Init endpoint
echo "1. Testing /api/master-data/init\n";
try {
    $controller = new MasterDataController();
    $response = $controller->init();
    $data = $response->getData(true);
    echo "   ✅ Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "   📊 Vendors: " . count($data['vendors']) . "\n";
    echo "   📊 Projects: " . count($data['projects']) . "\n";
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 2: Store vendor endpoint
echo "\n2. Testing vendor creation\n";
try {
    $request = new Request();
    $request->merge(['name' => 'Test Vendor ' . time()]);
    $controller = new MasterDataController();
    $response = $controller->storeVendor($request);
    $data = $response->getData(true);
    echo "   ✅ Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    if (isset($data['vendor'])) {
        echo "   📋 Created vendor: " . $data['vendor']['name'] . "\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 3: Store project endpoint
echo "\n3. Testing project creation\n";
try {
    $request = new Request();
    $request->merge(['name' => 'Test Project ' . time()]);
    $controller = new MasterDataController();
    $response = $controller->storeProject($request);
    $data = $response->getData(true);
    echo "   ✅ Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    if (isset($data['project'])) {
        echo "   📋 Created project: " . $data['project']['name'] . " (" . $data['project']['code'] . ")\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✨ API testing completed!\n";
