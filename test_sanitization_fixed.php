<?php

require_once __DIR__ . '/app/Helpers/ExportHelper.php';

// Test data that previously caused issues
$testData = [
    'normal_string' => 'Normal Material Name',
    'problematic_string' => 'Material with � invalid chars �89 coordinate',
    'coordinate_like' => 'A1 B2 C3 Material',
    'special_chars' => 'Material™®© with special chars',
    'empty_string' => '',
    'null_value' => null,
    'numeric_value' => 123.45,
];

echo "Testing UTF-8 Sanitization Functions:\n\n";

foreach ($testData as $key => $value) {
    echo "Original ($key): " . var_export($value, true) . "\n";

    try {
        $sanitized = sanitizeForExcel($value);
        echo "Sanitized: '$sanitized'\n";

        $spreadsheetSafe = sanitizeForSpreadsheet($value);
        echo "Spreadsheet Safe: '$spreadsheetSafe'\n";

        echo "✓ Success\n\n";
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "All tests completed!\n";