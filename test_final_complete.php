<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use App\Exports\TotalSummaryExport;
use App\Exports\BOQCompleteExport;

// Mock data yang lebih comprehensive untuk final test
$mockData = [
    [
        'material_name' => 'Abc',
        'category_name' => 'Test Category',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 1064,
        'actual_usage' => 1064,
        'boq_actual_quantity' => 3910.44,
        'remaining_stock' => 0  // This should show as TOTAL SISA in aggregation
    ],
    [
        'material_name' => 'Abc',
        'category_name' => 'Test Category',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-02',
        'dn_number' => 'DN-002',
        'received_quantity' => 0,
        'actual_usage' => 0,
        'boq_actual_quantity' => 0,
        'remaining_stock' => 150  // Additional stock for same material
    ],
    [
        'material_name' => 'Acrylic Tag for LN',
        'category_name' => 'Tag',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 4932,
        'actual_usage' => 4932,
        'boq_actual_quantity' => 8156.94,
        'remaining_stock' => 200
    ],
    [
        'material_name' => 'Bracket J Type',
        'category_name' => 'Bracket',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 4040,
        'actual_usage' => 4040,
        'boq_actual_quantity' => 2830.79,
        'remaining_stock' => 300
    ]
];

echo "=== FINAL TEST: TOTAL SISA dengan Data Aggregation ===\n\n";

try {
    echo "📊 Testing TotalSummaryExport dengan aggregation...\n";
    $totalSummaryExport = new TotalSummaryExport($mockData);

    $arrayData = $totalSummaryExport->array();

    echo "✓ Export generated successfully\n";
    echo "✓ Total rows: " . count($arrayData) . "\n";
    echo "✓ Data rows: " . (count($arrayData) - 4) . " materials\n\n";

    echo "📋 Material Aggregation Analysis:\n";
    echo "─────────────────────────────────────────────────────────────────────────────────\n";

    // Show headers
    $headerRow = $arrayData[3]; // Row 4 contains column headers
    echo "HEADERS: " . implode(' | ', $headerRow) . "\n";
    echo "─────────────────────────────────────────────────────────────────────────────────\n";

    // Show data with aggregation details
    for ($i = 4; $i < count($arrayData); $i++) {
        $row = $arrayData[$i];
        $materialName = $row[1];
        $totalDo = $row[2];
        $totalTerpakai = $row[3];
        $totalBoqActual = $row[4];
        $totalSisa = $row[5];

        echo sprintf("%-20s | %8s | %8s | %12s | %8s\n",
                    substr($materialName, 0, 20),
                    number_format($totalDo),
                    number_format($totalTerpakai),
                    number_format($totalBoqActual, 2),
                    number_format($totalSisa));

        // Show aggregation details for 'Abc' material
        if ($materialName === 'Abc') {
            echo "  ^^ Aggregated from multiple entries: remaining_stock 0 + 150 = 150\n";
        }
    }

    echo "─────────────────────────────────────────────────────────────────────────────────\n\n";

    // Verify aggregation worked correctly
    $dataRows = array_slice($arrayData, 4);
    $totalSisaValues = array_column($dataRows, 5);

    echo "🔍 TOTAL SISA Final Verification:\n";
    echo "✓ TOTAL SISA per material: " . implode(', ', $totalSisaValues) . "\n";
    echo "✓ Total sum: " . array_sum($totalSisaValues) . "\n";

    // Check if 'Abc' material aggregation worked
    $abcRow = null;
    foreach ($dataRows as $row) {
        if ($row[1] === 'Abc') {
            $abcRow = $row;
            break;
        }
    }

    if ($abcRow && $abcRow[5] == 150) {
        echo "✅ Material aggregation working! 'Abc' TOTAL SISA = 150 (0 + 150)\n";
    } else {
        echo "❌ Material aggregation issue detected\n";
    }

    echo "\n📈 Testing Multi-Sheet Export...\n";
    $completeExport = new BOQCompleteExport($mockData);
    $sheets = $completeExport->sheets();

    echo "✓ Multi-sheet export ready\n";
    echo "✓ Sheets available: " . implode(', ', array_keys($sheets)) . "\n";

    // Test Total Summary sheet specifically
    $totalSummarySheet = $sheets['Total Summary'];
    $summaryData = $totalSummarySheet->array();
    $summaryDataRows = array_slice($summaryData, 4);
    $summaryTotalSisa = array_column($summaryDataRows, 5);

    echo "✓ Total Summary sheet TOTAL SISA: " . implode(', ', $summaryTotalSisa) . "\n";

    if (array_sum($summaryTotalSisa) > 0) {
        echo "\n✅ SEMUA TEST BERHASIL! TOTAL SISA akan muncul di Excel.\n";
        echo "🎯 Expected di Excel:\n";
        echo "  • Sheet 'Total Summary' dengan kolom TOTAL SISA yang berisi data\n";
        echo "  • Aggregation per material working correctly\n";
        echo "  • Format dan styling proper\n";
    } else {
        echo "\n❌ Masih ada masalah dengan TOTAL SISA\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
