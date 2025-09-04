<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\MfoRequest;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

// Initialize the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MFO CHART TEST ===\n\n";

// Test 1: Check if MFO Request model exists and has data
echo "1. Testing MFO Request Model:\n";
try {
    $totalMfoRequests = MfoRequest::count();
    echo "   ✓ Total MFO Requests: {$totalMfoRequests}\n";
    
    if ($totalMfoRequests > 0) {
        $latestRequest = MfoRequest::latest()->first();
        echo "   ✓ Latest Request Date: " . $latestRequest->request_date . "\n";
        echo "   ✓ Latest Request Status: " . $latestRequest->status . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check database connection and MFO requests table
echo "2. Testing Database Connection:\n";
try {
    $dbConnection = DB::connection()->getPdo();
    echo "   ✓ Database connection successful\n";
    
    // Check if mfo_requests table exists
    $tables = DB::select("SHOW TABLES LIKE 'mfo_requests'");
    if (count($tables) > 0) {
        echo "   ✓ mfo_requests table exists\n";
        
        // Get table structure
        $columns = DB::select("DESCRIBE mfo_requests");
        echo "   ✓ Table columns: ";
        foreach ($columns as $column) {
            echo $column->Field . " ";
        }
        echo "\n";
    } else {
        echo "   ✗ mfo_requests table does not exist\n";
    }
} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test Chart Data Query (similar to controller method)
echo "3. Testing Chart Data Query:\n";
try {
    // Test monthly grouping
    $monthlyData = MfoRequest::selectRaw("DATE_FORMAT(request_date, '%Y-%m-01') as period, COUNT(*) as count")
                      ->groupBy('period')
                      ->orderBy('period')
                      ->get();
    
    echo "   ✓ Monthly data query successful\n";
    echo "   ✓ Found " . $monthlyData->count() . " monthly periods\n";
    
    if ($monthlyData->count() > 0) {
        echo "   ✓ Sample data:\n";
        foreach ($monthlyData->take(5) as $data) {
            echo "     - Period: {$data->period}, Count: {$data->count}\n";
        }
    }
    
    // Test daily grouping for last 30 days
    $dailyData = MfoRequest::selectRaw("DATE(request_date) as period, COUNT(*) as count")
                     ->where('request_date', '>=', now()->subDays(30))
                     ->groupBy('period')
                     ->orderBy('period')
                     ->get();
    
    echo "   ✓ Daily data query (last 30 days): " . $dailyData->count() . " periods\n";
    
} catch (Exception $e) {
    echo "   ✗ Chart query error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Test Status Distribution
echo "4. Testing Status Distribution:\n";
try {
    $statuses = MfoRequest::selectRaw('status, COUNT(*) as count')
                          ->groupBy('status')
                          ->get();
    
    echo "   ✓ Status distribution:\n";
    foreach ($statuses as $status) {
        echo "     - {$status->status}: {$status->count}\n";
    }
} catch (Exception $e) {
    echo "   ✗ Status distribution error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Check if relationships are working
echo "5. Testing Model Relationships:\n";
try {
    $requestWithRelations = MfoRequest::with(['user', 'project', 'subProject'])->first();
    
    if ($requestWithRelations) {
        echo "   ✓ Relationships loaded successfully\n";
        echo "   ✓ User: " . ($requestWithRelations->user->name ?? 'N/A') . "\n";
        echo "   ✓ Project: " . ($requestWithRelations->project->name ?? 'N/A') . "\n";
        echo "   ✓ Sub Project: " . ($requestWithRelations->subProject->name ?? 'N/A') . "\n";
    } else {
        echo "   ✗ No MFO request found to test relationships\n";
    }
} catch (Exception $e) {
    echo "   ✗ Relationship test error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
