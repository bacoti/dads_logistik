<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use App\Exports\TotalSummaryExport;
use App\Exports\BOQCompleteExport;

// Mock data berdasarkan data yang terlihat di gambar
$mockData = [
    [
        'material_name' => 'Abc',
        'received_quantity' => 1064,
        'actual_usage' => 1064,
        'boq_actual_quantity' => 3910.44,
        'remaining_stock' => 150  // TOTAL SISA
    ],
    [
        'material_name' => 'Acrylic Tag for LN',
        'received_quantity' => 4932,
        'actual_usage' => 4932,
        'boq_actual_quantity' => 8156.94,
        'remaining_stock' => 200  // TOTAL SISA
    ],
    [
        'material_name' => 'Bracket J Type',
        'received_quantity' => 4040,
        'actual_usage' => 4040,
        'boq_actual_quantity' => 2830.79,
        'remaining_stock' => 300  // TOTAL SISA
    ],
    [
        'material_name' => 'Buldog Grip',
        'received_quantity' => 1842,
        'actual_usage' => 1842,
        'boq_actual_quantity' => 8990.88,
        'remaining_stock' => 100  // TOTAL SISA
    ],
    [
        'material_name' => 'Bulldogrip',
        'received_quantity' => 47532,
        'actual_usage' => 47532,
        'boq_actual_quantity' => 53581.48,
        'remaining_stock' => 500  // TOTAL SISA
    ]
];

echo "=== Final Test: TOTAL SISA Column Verification ===\n\n";

try {
    echo "📊 Testing TotalSummaryExport with TOTAL SISA data...\n";
    $totalSummaryExport = new TotalSummaryExport($mockData);

    $arrayData = $totalSummaryExport->array();
    $headings = $totalSummaryExport->headings();

    echo "✓ Export berhasil diinstansiasi\n";
    echo "✓ Data materials: " . count($arrayData) . "\n";
    echo "✓ Headers: " . count($headings) . " rows\n\n";

    echo "📋 Preview Total Summary (dengan TOTAL SISA yang fixed):\n";
    echo "═══════════════════════════════════════════════════════════════════════════════════════════\n";

    // Display headers
    foreach ($headings as $index => $headerRow) {
        if ($index == 3) { // Column headers row
            echo "HEADERS: " . implode(' | ', $headerRow) . "\n";
            echo "───────────────────────────────────────────────────────────────────────────────────────────\n";
        }
    }

    // Display data with focus on TOTAL SISA
    foreach ($arrayData as $index => $row) {
        $materialName = isset($row[1]) ? substr($row[1], 0, 20) : 'N/A';
        $totalDo = isset($row[2]) ? number_format($row[2]) : '0';
        $totalTerpakai = isset($row[3]) ? number_format($row[3]) : '0';
        $totalBoqActual = isset($row[4]) ? number_format($row[4], 2) : '0';
        $totalSisa = isset($row[5]) ? number_format($row[5]) : '0';

        printf("%-3d | %-20s | %8s | %8s | %12s | %8s\n",
               $row[0], $materialName, $totalDo, $totalTerpakai, $totalBoqActual, $totalSisa);
    }

    echo "═══════════════════════════════════════════════════════════════════════════════════════════\n\n";

    // Verify TOTAL SISA has data
    $totalSisaValues = array_column($arrayData, 5);
    $totalSisaSum = array_sum($totalSisaValues);

    echo "🔍 TOTAL SISA Verification:\n";
    echo "✓ Individual TOTAL SISA values: " . implode(', ', $totalSisaValues) . "\n";
    echo "✓ Sum of all TOTAL SISA: " . number_format($totalSisaSum) . "\n";

    if ($totalSisaSum > 0) {
        echo "✅ KOLOM TOTAL SISA BERHASIL DIPERBAIKI!\n";
    } else {
        echo "❌ TOTAL SISA masih bermasalah\n";
    }

    echo "\n📈 Testing Multi-Sheet Export...\n";
    $completeExport = new BOQCompleteExport($mockData);
    $sheets = $completeExport->sheets();

    echo "✓ Multi-sheet export ready\n";
    echo "✓ Sheets: " . implode(', ', array_keys($sheets)) . "\n";

    echo "\n🎯 HASIL EXPECTED DI EXCEL:\n";
    echo "• Sheet 'Total Summary' akan menampilkan:\n";
    echo "  - Kolom TOTAL SISA dengan nilai yang benar\n";
    echo "  - Data aggregation per material\n";
    echo "  - Formatting yang proper (borders, colors)\n";
    echo "  - BOQ Actual highlighted kuning\n";
    echo "  - TOTAL SISA dengan background putih yang jelas\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
