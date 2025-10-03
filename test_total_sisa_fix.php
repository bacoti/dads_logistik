<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use App\Exports\TotalSummaryExport;

// Mock data untuk testing dengan nilai TOTAL SISA yang jelas
$mockData = [
    [
        'material_name' => 'Kabel 12',
        'received_quantity' => 1000,
        'actual_usage' => 800,
        'boq_actual_quantity' => 850,
        'remaining_stock' => 200  // Should show as TOTAL SISA
    ],
    [
        'material_name' => 'Kabel 24',
        'received_quantity' => 2000,
        'actual_usage' => 1500,
        'boq_actual_quantity' => 1600,
        'remaining_stock' => 500  // Should show as TOTAL SISA
    ],
    [
        'material_name' => 'Tiang 7\'3 mtr',
        'received_quantity' => 111,
        'actual_usage' => 55,
        'boq_actual_quantity' => 55,
        'remaining_stock' => 56   // Should show as TOTAL SISA
    ]
];

echo "=== Test TOTAL SISA Column Fix ===\n\n";

try {
    $totalSummaryExport = new TotalSummaryExport($mockData);

    // Test array method
    $arrayData = $totalSummaryExport->array();
    $headings = $totalSummaryExport->headings();

    echo "📋 Headers:\n";
    foreach ($headings as $index => $headerRow) {
        echo "  Row " . ($index + 1) . ": " . implode(' | ', $headerRow) . "\n";
    }

    echo "\n📊 Data dengan TOTAL SISA:\n";
    echo "Format: No | Material | TOTAL DO | TOTAL TERPAKAI | TOTAL BOQ ACTUAL | TOTAL SISA\n";
    echo "─────────────────────────────────────────────────────────────────────────────────\n";

    foreach ($arrayData as $index => $row) {
        echo "Row " . ($index + 1) . ": " . implode(' | ', $row) . "\n";

        // Verify TOTAL SISA column (index 5) has data
        if (isset($row[5]) && is_numeric($row[5])) {
            echo "  ✓ TOTAL SISA: " . $row[5] . " (OK)\n";
        } else {
            echo "  ❌ TOTAL SISA: Missing or invalid data\n";
        }
        echo "\n";
    }

    // Verify column structure
    echo "🔍 Verification:\n";
    $sampleRow = $arrayData[0] ?? [];
    if (count($sampleRow) >= 6) {
        echo "✓ Data has " . count($sampleRow) . " columns (correct)\n";
        echo "✓ Columns: No, Material, TOTAL DO, TOTAL TERPAKAI, TOTAL BOQ ACTUAL, TOTAL SISA\n";

        // Check if TOTAL SISA has values
        $totalSisaValues = array_column($arrayData, 5);
        $nonZeroSisa = array_filter($totalSisaValues, function($val) { return $val > 0; });

        if (count($nonZeroSisa) > 0) {
            echo "✅ TOTAL SISA column memiliki data: " . implode(', ', $nonZeroSisa) . "\n";
        } else {
            echo "❌ TOTAL SISA column masih kosong atau nol semua\n";
        }
    } else {
        echo "❌ Data structure tidak lengkap. Expected 6 columns, got " . count($sampleRow) . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
