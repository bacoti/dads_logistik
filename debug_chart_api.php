<?php
// Debug Chart API
echo "<h1>Monthly Reports Chart API Debug</h1>";

// Simulate the controller logic
try {
    require_once 'vendor/autoload.php';
    
    $app = require_once 'bootstrap/app.php';
    
    // Test basic connection
    echo "<h2>1. Database Connection Test</h2>";
    
    // Test route
    echo "<h2>2. Route Test</h2>";
    echo "<p>Chart API URL: /admin/monthly-reports/chart-data</p>";
    
    // Test data structure
    echo "<h2>3. Expected Data Structure</h2>";
    echo "<pre>";
    echo json_encode([
        'projectBarData' => [
            ['name' => 'Project A', 'count' => 10]
        ],
        'monthlyLineData' => [
            ['period' => 'January', 'count' => 5]
        ],
        'subProjectStackedData' => [
            'Project A' => ['Sub A' => 3, 'Sub B' => 2]
        ],
        'projectDonutData' => [
            ['name' => 'Project A', 'count' => 10]
        ],
        'summary' => [
            'totalReports' => 15,
            'totalUsers' => 5,
            'totalProjects' => 3,
            'filtersApplied' => false
        ]
    ], JSON_PRETTY_PRINT);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Next Steps</h2>";
echo "<ul>";
echo "<li>Check if you can access: <a href='/admin/monthly-reports/chart-data' target='_blank'>/admin/monthly-reports/chart-data</a></li>";
echo "<li>Check browser console for JavaScript errors</li>";
echo "<li>Verify authentication (logged in as admin)</li>";
echo "</ul>";
?>
