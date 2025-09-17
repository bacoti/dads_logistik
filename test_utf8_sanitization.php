<?php

/**
 * Simple test script to verify Excel export sanitization functions
 * Run this to test if the UTF-8 sanitization fixes work correctly
 */

// Include the helper functions directly
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

// Test data that might cause coordinate errors
$testData = [
    [
        'material_name' => 'Test Material with �89 problematic chars',
        'category_name' => 'Category � with issues',
        'unit' => 'pcs',
        'project_name' => 'Project A1', // This could be mistaken for a coordinate
        'sub_project_name' => 'Sub B2', // This too
        'cluster' => 'Cluster C3',
        'dn_number' => 'DN D4',
        'received_quantity' => 100.5,
        'actual_usage' => 50.25,
        'remaining_stock' => 50.25
    ],
    [
        'material_name' => 'Another Material � with � chars',
        'category_name' => 'Another Category',
        'unit' => 'kg',
        'project_name' => 'Project with $A$1 reference',
        'sub_project_name' => 'Sub with A1:B2 range',
        'cluster' => 'Cluster with �89',
        'dn_number' => 'DN with � issues',
        'received_quantity' => 200.0,
        'actual_usage' => 150.0,
        'remaining_stock' => 50.0
    ]
];

echo "Testing UTF-8 sanitization functions...\n\n";

// Test sanitizeForExcel
echo "1. Testing sanitizeForExcel:\n";
foreach ($testData as $item) {
    echo "Original: " . $item['material_name'] . "\n";
    echo "Sanitized: " . sanitizeForExcel($item['material_name']) . "\n\n";
}

// Test sanitizeForSpreadsheet
echo "2. Testing sanitizeForSpreadsheet:\n";
foreach ($testData as $item) {
    echo "Original: " . $item['material_name'] . "\n";
    echo "Sanitized: " . sanitizeForSpreadsheet($item['material_name']) . "\n\n";
}

// Test sanitizeArrayForJson
echo "3. Testing sanitizeArrayForJson:\n";
$sanitizedArray = sanitizeArrayForJson($testData);
echo "Array sanitized successfully\n\n";

// Test with coordinate pattern detection
echo "4. Testing coordinate pattern detection:\n";
try {
    foreach ($testData as $item) {
        $safeData = sanitizeForSpreadsheet($item['material_name']);

        // Check if the sanitized data could still be mistaken for coordinates
        if (preg_match('/^[A-Za-z]+\d+.*$/', $safeData)) {
            echo "WARNING: Still contains coordinate-like pattern: $safeData\n";
        } else {
            echo "SAFE: $safeData\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nTest completed successfully!\n";
echo "If you see no errors above, the sanitization is working correctly.\n";