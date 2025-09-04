<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\MfoRequestController;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUGGING MFO CHART DISPLAY ISSUE ===\n\n";

// Test 1: Check JavaScript Console Errors
echo "1. Checking Chart.js Availability:\n";
echo "   → Pastikan CDN Chart.js dapat diakses: https://cdn.jsdelivr.net/npm/chart.js\n";
echo "   → Buka browser developer tools (F12) dan cek Console tab untuk error JavaScript\n\n";

// Test 2: Check Route Registration
echo "2. Testing Route Registration:\n";
try {
    $routes = app('router')->getRoutes();
    $chartRouteFound = false;
    $detailsRouteFound = false;
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'mfo-requests/chart-data') !== false && !strpos($uri, 'details')) {
            $chartRouteFound = true;
            echo "   ✓ Chart data route found: " . $uri . "\n";
        }
        if (strpos($uri, 'mfo-requests/chart-data/details') !== false) {
            $detailsRouteFound = true;
            echo "   ✓ Chart details route found: " . $uri . "\n";
        }
    }
    
    if (!$chartRouteFound) {
        echo "   ✗ Chart data route NOT found!\n";
    }
    if (!$detailsRouteFound) {
        echo "   ✗ Chart details route NOT found!\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error checking routes: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test API Response directly
echo "3. Testing API Response:\n";
try {
    
    $controller = new MfoRequestController();
    $request = new Request();
    $request->merge(['group_by' => 'month']);
    
    $response = $controller->getChartData($request);
    $statusCode = $response->getStatusCode();
    $content = $response->getContent();
    
    echo "   ✓ API Status Code: $statusCode\n";
    echo "   ✓ API Response: $content\n";
    
    $data = json_decode($content, true);
    if ($data && isset($data['data'])) {
        echo "   ✓ Data structure is valid\n";
        echo "   ✓ Data count: " . count($data['data']) . "\n";
    } else {
        echo "   ✗ Invalid data structure\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ API Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Check DOM Elements
echo "4. Chart DOM Element Check:\n";
echo "   → Check if these elements exist in the HTML:\n";
echo "     - Canvas element with id='mfoChart'\n";
echo "     - Select element with id='mfo_group_by'\n";
echo "     - Input elements with id='mfo_start' and 'mfo_end'\n";
echo "     - Button element with id='mfo_refresh'\n";
echo "     - No data div with id='mfo_no_data'\n\n";

// Test 5: Generate Debug HTML
echo "5. Generating Debug HTML for Chart:\n";

$debugHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>MFO Chart Debug</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .chart-container { width: 800px; height: 400px; margin: 20px 0; }
        .controls { margin: 20px 0; }
        .controls input, .controls select, .controls button { 
            margin: 5px; padding: 8px; 
        }
    </style>
</head>
<body>
    <h1>MFO Chart Debug Test</h1>
    
    <div class="controls">
        <select id="mfo_group_by">
            <option value="day">Harian</option>
            <option value="week">Mingguan</option>
            <option value="month" selected>Bulanan</option>
        </select>
        <input type="date" id="mfo_start" />
        <input type="date" id="mfo_end" />
        <button id="mfo_refresh">Refresh</button>
    </div>
    
    <div class="chart-container">
        <canvas id="mfoChart" width="800" height="400"></canvas>
    </div>
    
    <div id="debug-info"></div>

    <script>
        console.log('Chart.js version:', Chart.version);
        
        const canvas = document.getElementById('mfoChart');
        const ctx = canvas.getContext('2d');
        
        // Test with sample data
        const sampleData = {
            labels: ['2025-08', '2025-09'],
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: [0, 1],
                fill: true,
                backgroundColor: 'rgba(99,102,241,0.18)',
                borderColor: 'rgba(99,102,241,1)',
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(99,102,241,1)',
                pointRadius: 3,
                tension: 0.25,
            }]
        };
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: sampleData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        grid: { display: false },
                        ticks: { color: '#374151' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(15,23,42,0.06)' },
                        ticks: { color: '#374151' }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(17,24,39,0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }
            }
        });
        
        console.log('Chart created successfully:', chart);
        document.getElementById('debug-info').innerHTML = '<p>✓ Chart created successfully with sample data</p>';
        
        // Test API call
        async function testApiCall() {
            try {
                const response = await fetch('/admin/mfo-requests/chart-data?group_by=month');
                const data = await response.json();
                console.log('API Response:', data);
                document.getElementById('debug-info').innerHTML += '<p>✓ API call successful</p>';
            } catch (error) {
                console.error('API Error:', error);
                document.getElementById('debug-info').innerHTML += '<p>✗ API call failed: ' + error.message + '</p>';
            }
        }
        
        testApiCall();
    </script>
</body>
</html>
HTML;

file_put_contents('debug_mfo_chart.html', $debugHtml);
echo "   ✓ Debug HTML file created: debug_mfo_chart.html\n";
echo "   → Open this file in your browser to test chart functionality\n\n";

echo "=== DEBUGGING COMPLETE ===\n";
echo "\nNext steps:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Verify Chart.js CDN is accessible\n";
echo "3. Check if routes are properly registered\n";
echo "4. Test the debug HTML file in browser\n";
echo "5. Check Laravel logs for PHP errors\n";
