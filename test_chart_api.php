<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Http\Request;

echo "Testing Chart API...\n";

// Create controller instance
$controller = new TransactionController();

// Create mock request
$request = new Request();

try {
    // Call getChartData method
    $response = $controller->getChartData($request);
    $data = $response->getData(true);
    
    echo "Chart API Response:\n";
    echo "==================\n";
    echo "Project Data: " . json_encode($data['projectData'], JSON_PRETTY_PRINT) . "\n\n";
    echo "Location Data: " . json_encode($data['locationData'], JSON_PRETTY_PRINT) . "\n\n";
    echo "Totals: " . json_encode($data['totals'], JSON_PRETTY_PRINT) . "\n\n";
    echo "Summary: " . json_encode($data['summary'], JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
