<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use App\Exports\TotalSummaryExport;

// Mock data yang mencerminkan data real dari gambar
$mockData = [
    [
        'material_name' => 'Abc',
        'received_quantity' => 1064,
        'actual_usage' => 1064,
        'boq_actual_quantity' => 3910.44,
        'remaining_stock' => 150
    ],
    [
        'material_name' => 'Acrylic Tag for LN',
        'received_quantity' => 4932,
        'actual_usage' => 4932,
        'boq_actual_quantity' => 8156.94,
        'remaining_stock' => 200
    ],
    [
        'material_name' => 'Bracket J Type',
        'received_quantity' => 4040,
        'actual_usage' => 4040,
        'boq_actual_quantity' => 2830.79,
        'remaining_stock' => 300
    ],
    [
        'material_name' => 'Buldog Grip',
        'received_quantity' => 1842,
        'actual_usage' => 1842,
        'boq_actual_quantity' => 8990.88,
        'remaining_stock' => 100
    ]
];

echo "=== Test Fixed Structure untuk TOTAL SISA ===\n\n";

try {
    $totalSummaryExport = new TotalSummaryExport($mockData);

    // Test the new unified array structure
    $arrayData = $totalSummaryExport->array();
    $headings = $totalSummaryExport->headings();

    echo "📊 Structure Analysis:\n";
    echo "✓ Headings method returns: " . (empty($headings) ? "Empty (correct)" : "Data (incorrect)") . "\n";
    echo "✓ Array method returns: " . count($arrayData) . " total rows\n";
    echo "✓ Expected: " . (count($mockData) + 4) . " rows (4 headers + " . count($mockData) . " data)\n\n";

    echo "📋 Complete Export Structure:\n";
    echo "═══════════════════════════════════════════════════════════════════════════════════\n";

    foreach ($arrayData as $index => $row) {
        $rowType = ($index < 4) ? "HEADER" : "DATA  ";
        echo sprintf("%s Row %2d: %s\n", $rowType, $index + 1, implode(' | ', $row));

        // Special focus on TOTAL SISA for data rows
        if ($index >= 4 && isset($row[5])) {
            echo sprintf("           ^^^ TOTAL SISA: %s (Column F)\n", $row[5]);
        }
    }

    echo "═══════════════════════════════════════════════════════════════════════════════════\n\n";

    // Verify TOTAL SISA column specifically
    echo "🔍 TOTAL SISA Column Verification:\n";
    $dataRows = array_slice($arrayData, 4); // Skip headers
    $totalSisaValues = array_column($dataRows, 5);

    echo "✓ TOTAL SISA values extracted: " . implode(', ', $totalSisaValues) . "\n";
    echo "✓ All values numeric: " . (array_reduce($totalSisaValues, function($carry, $val) {
        return $carry && is_numeric($val);
    }, true) ? "YES" : "NO") . "\n";
    echo "✓ Sum of TOTAL SISA: " . array_sum($totalSisaValues) . "\n";

    if (array_sum($totalSisaValues) > 0) {
        echo "✅ TOTAL SISA DATA FOUND! Structure fixed correctly.\n";
    } else {
        echo "❌ TOTAL SISA still has issues\n";
    }

    echo "\n🎯 Expected Excel Output:\n";
    echo "• Row 1-4: Headers with green background\n";
    echo "• Row 5+: Data with TOTAL SISA values in column F\n";
    echo "• BOQ Actual (column E): Yellow highlight\n";
    echo "• TOTAL SISA (column F): White background with borders\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
