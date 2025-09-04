<?php
// Debug script untuk chart API loss reports
require_once 'vendor/autoload.php';

echo "<h2>Loss Reports Chart API Debug & Test</h2>";

// Test API endpoint
echo "<h3>1. API Response Test</h3>";
$apiUrl = 'http://localhost:8000/admin/loss-reports/chart-data';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest'
        ]
    ]
]);

$response = @file_get_contents($apiUrl, false, $context);

if ($response === false) {
    echo "<p style='color: red'>❌ API Request Failed - Check if server is running</p>";
} else {
    echo "<pre>" . $response . "</pre>";
    
    $data = json_decode($response, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "<p style='color: green'>✅ API Response: SUCCESS</p>";
        
        if (isset($data['projectData'])) {
            echo "<p style='color: green'>✅ Project Data: " . count($data['projectData']) . " projects found</p>";
        }
        
        if (isset($data['monthlyData'])) {
            echo "<p style='color: green'>✅ Monthly Data: " . count($data['monthlyData']) . " periods found</p>";
        }
        
        if (isset($data['donutData'])) {
            echo "<p style='color: green'>✅ Donut Data: " . count($data['donutData']) . " items found</p>";
        }
        
        if (isset($data['summary'])) {
            echo "<p style='color: green'>✅ Summary: " . ($data['summary']['totalReports'] ?? 0) . " total reports</p>";
        }
        
        if (isset($data['error'])) {
            echo "<p style='color: red'>❌ API Error: " . $data['message'] . "</p>";
        }
    } else {
        echo "<p style='color: red'>❌ Invalid JSON Response</p>";
    }
}

// Test Chart.js availability
echo "<h3>2. Chart.js Test</h3>";
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<canvas id="testChart" width="400" height="200"></canvas>

<script>
console.log('Chart.js available:', typeof Chart !== 'undefined');
if (typeof Chart !== 'undefined') {
    document.write('<p style="color: green">✅ Chart.js: Loaded successfully</p>');
    
    // Test chart creation
    try {
        const ctx = document.getElementById('testChart');
        const testChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Test'],
                datasets: [{
                    label: 'Test Data',
                    data: [1],
                    backgroundColor: '#8B5CF6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        document.write('<p style="color: green">✅ Chart Creation: SUCCESS</p>');
    } catch (error) {
        document.write('<p style="color: red">❌ Chart Creation Error: ' + error.message + '</p>');
    }
} else {
    document.write('<p style="color: red">❌ Chart.js: Not loaded</p>');
}
</script>
</body>
</html>
