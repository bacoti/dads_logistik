<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use Illuminate\Http\Request;
use App\Exports\BOQSummaryMatrixExport;
use Maatwebsite\Excel\Facades\Excel;

// Simulate a basic export test
echo "Testing Excel Export Process...\n\n";

try {
    // Create mock data similar to what the controller would generate
    $mockData = [
        [
            'material_name' => 'Test Material 1',
            'category_name' => 'Test Category',
            'unit' => 'pcs',
            'project_name' => 'Test Project',
            'sub_project_name' => 'Test Sub Project',
            'cluster' => 'Cluster A',
            'dn_number' => 'DN001',
            'received_quantity' => 100,
            'actual_usage' => 80,
            'boq_actual_quantity' => 90,
            'remaining_stock' => 20
        ],
        [
            'material_name' => 'Test Material with � problematic chars �89',
            'category_name' => 'Another Category™',
            'unit' => 'kg',
            'project_name' => 'Project with A1 B2 coordinates',
            'sub_project_name' => 'Sub Project',
            'cluster' => 'Cluster B',
            'dn_number' => 'DN002',
            'received_quantity' => 200,
            'actual_usage' => 150,
            'boq_actual_quantity' => 180,
            'remaining_stock' => 50
        ]
    ];

    echo "Mock data created with " . count($mockData) . " records\n";

    // Test the export class
    $export = new BOQSummaryMatrixExport($mockData);
    $headings = $export->headings();
    $data = $export->array();

    echo "Export headings generated: " . count($headings) . " rows\n";
    echo "Export data generated: " . count($data) . " rows\n";

    // Test Excel download (without actually saving file)
    echo "Testing Excel facade...\n";
    $tempFile = tempnam(sys_get_temp_dir(), 'test_export');
    Excel::store($export, basename($tempFile) . '.xlsx', 'local');

    echo "✓ Excel export test completed successfully!\n";
    echo "✓ No UTF-8 errors encountered\n";
    echo "✓ No coordinate parsing errors\n";

    // Clean up
    if (file_exists($tempFile . '.xlsx')) {
        unlink($tempFile . '.xlsx');
    }
    unlink($tempFile);

} catch (Exception $e) {
    echo "✗ Error during export test: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
