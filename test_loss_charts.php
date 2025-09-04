<?php

// Simple test untuk API loss charts tanpa Laravel bootstrap
echo "=== Testing Loss Reports Chart API ===\n";

// Test 1: Check if we can make HTTP request
echo "1. Testing HTTP request to chart endpoint...\n";

$url = 'http://localhost:8000/admin/loss-reports/chart-data';
echo "URL: $url\n";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n";
    echo "\nSuggestion: Make sure Laravel server is running with: php artisan serve\n";
    exit;
}

echo "HTTP Status Code: $httpCode\n";

if ($httpCode !== 200) {
    echo "❌ HTTP Error - Status: $httpCode\n";
    echo "Response: $response\n";
    exit;
}

echo "✅ HTTP request successful!\n";
echo "Response received: " . strlen($response) . " characters\n\n";

// Test 2: Parse JSON response
echo "2. Testing JSON parsing...\n";
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "❌ JSON Parse Error: " . json_last_error_msg() . "\n";
    echo "Raw response: $response\n";
    exit;
}

echo "✅ JSON parsed successfully!\n";
echo "Data structure:\n";
print_r(array_keys($data));
echo "\n";

// Test 3: Check required data structure
echo "3. Testing data structure...\n";
$requiredKeys = ['projectBarData', 'monthlyLineData', 'projectDonutData'];

foreach ($requiredKeys as $key) {
    if (isset($data[$key])) {
        echo "✅ $key: Found\n";
        if (is_array($data[$key]) && count($data[$key]) > 0) {
            echo "   - Array with " . count($data[$key]) . " items\n";
            echo "   - Sample item: " . json_encode($data[$key][0] ?? []) . "\n";
        } else {
            echo "   - Empty array\n";
        }
    } else {
        echo "❌ $key: Missing\n";
    }
}

echo "\n=== Full Response ===\n";
echo json_encode($data, JSON_PRETTY_PRINT);

echo "\n=== Test Complete ===\n";
echo "If all tests pass, the issue might be in the JavaScript code.\n";
echo "Check browser console for JavaScript errors.\n";

?>
