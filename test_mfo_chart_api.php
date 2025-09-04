<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING MFO CHART API ENDPOINTS ===\n\n";

// Simulate HTTP request to chart API
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\MfoRequestController;

// Test 1: Test Chart Data API
echo "1. Testing Chart Data API:\n";
try {
    $controller = new MfoRequestController();
    
    // Create a mock request
    $request = new Request();
    $request->merge([
        'group_by' => 'month',
        'start' => '2025-01-01',
        'end' => '2025-12-31'
    ]);
    
    $response = $controller->getChartData($request);
    $data = json_decode($response->getContent(), true);
    
    echo "   ✓ API Response Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Data Points: " . count($data['data']) . "\n";
    
    if (!empty($data['data'])) {
        echo "   ✓ Sample Data:\n";
        foreach (array_slice($data['data'], 0, 3) as $point) {
            echo "     - Period: {$point['period']}, Count: {$point['count']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Test Chart Details API
echo "2. Testing Chart Details API:\n";
try {
    $request = new Request();
    $request->merge([
        'period' => '2025-09-01',
        'group_by' => 'month',
        'per_page' => 5,
        'page' => 1
    ]);
    
    $response = $controller->getChartDetails($request);
    $data = json_decode($response->getContent(), true);
    
    echo "   ✓ API Response Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Records Found: " . count($data['data']) . "\n";
    echo "   ✓ Total Records: " . ($data['meta']['total'] ?? 0) . "\n";
    echo "   ✓ Current Page: " . ($data['meta']['current_page'] ?? 1) . "\n";
    
    if (!empty($data['data'])) {
        echo "   ✓ Sample Record:\n";
        $record = $data['data'][0];
        echo "     - ID: {$record['id']}\n";
        echo "     - User: {$record['user']}\n";
        echo "     - Project: {$record['project']}\n";
        echo "     - Date: {$record['request_date']}\n";
        echo "     - Status: {$record['status']}\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test Different Grouping Options
echo "3. Testing Different Grouping Options:\n";

$groupings = ['day', 'week', 'month'];

foreach ($groupings as $groupBy) {
    try {
        $request = new Request();
        $request->merge([
            'group_by' => $groupBy,
            'start' => '2025-08-01',
            'end' => '2025-09-04'
        ]);
        
        $response = $controller->getChartData($request);
        $data = json_decode($response->getContent(), true);
        
        echo "   ✓ {$groupBy} grouping: " . count($data['data']) . " data points\n";
        
    } catch (Exception $e) {
        echo "   ✗ {$groupBy} grouping error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== API TEST COMPLETE ===\n";
