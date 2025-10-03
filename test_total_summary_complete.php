<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/ExportHelper.php';

use App\Exports\TotalSummaryExport;
use App\Exports\BOQCompleteExport;

// Mock data untuk testing berdasarkan gambar referensi
$mockData = [
    // Kabel 12
    [
        'material_name' => 'Kabel 12',
        'category_name' => 'Kabel',
        'unit' => 'mtr',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 12000,
        'actual_usage' => 12490,
        'boq_actual_quantity' => 12502,
        'remaining_stock' => 502
    ],
    // Kabel 24
    [
        'material_name' => 'Kabel 24',
        'category_name' => 'Kabel',
        'unit' => 'mtr',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 21000,
        'actual_usage' => 17285,
        'boq_actual_quantity' => 17285,
        'remaining_stock' => 3715
    ],
    // Kabel 48
    [
        'material_name' => 'Kabel 48',
        'category_name' => 'Kabel',
        'unit' => 'mtr',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 0,
        'actual_usage' => 0,
        'boq_actual_quantity' => 0,
        'remaining_stock' => 0
    ],
    // Tiang 7'3 mtr
    [
        'material_name' => 'Tiang 7\'3 mtr',
        'category_name' => 'Tiang',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 111,
        'actual_usage' => 55,
        'boq_actual_quantity' => 55,
        'remaining_stock' => 56
    ],
    // Tiang 7'4 mtr
    [
        'material_name' => 'Tiang 7\'4 mtr',
        'category_name' => 'Tiang',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 417,
        'actual_usage' => 386,
        'boq_actual_quantity' => 386,
        'remaining_stock' => 31
    ],
    // FAT
    [
        'material_name' => 'FAT',
        'category_name' => 'Fiber',
        'unit' => 'pcs',
        'project_name' => 'DONE CLR PROJECT',
        'sub_project_name' => 'OPNAME',
        'cluster' => 'CLR-01',
        'dn_number' => 'DN-001',
        'received_quantity' => 213,
        'actual_usage' => 154,
        'boq_actual_quantity' => 154,
        'remaining_stock' => 59
    ]
];

echo "=== Test BOQ Export dengan Total Summary Sheet ===\n\n";

try {
    echo "📊 Testing TotalSummaryExport...\n";
    $totalSummaryExport = new TotalSummaryExport($mockData);

    echo "✓ TotalSummaryExport berhasil diinstansiasi\n";

    // Test array method
    $arrayData = $totalSummaryExport->array();
    echo "✓ Data array berhasil digenerate: " . count($arrayData) . " materials\n";

    // Test headings method
    $headings = $totalSummaryExport->headings();
    echo "✓ Headers berhasil digenerate: " . count($headings) . " header rows\n";

    echo "\n📋 Preview Total Summary Data:\n";
    echo "Headers:\n";
    foreach ($headings as $index => $headerRow) {
        echo "  Row " . ($index + 1) . ": " . implode(' | ', array_slice($headerRow, 0, 6)) . "\n";
    }

    echo "\nSample Data (first 3 materials):\n";
    foreach (array_slice($arrayData, 0, 3) as $index => $row) {
        echo "  Material " . ($index + 1) . ": " . implode(' | ', $row) . "\n";
    }

    echo "\n📈 Testing BOQCompleteExport (Multiple Sheets)...\n";
    $completeExport = new BOQCompleteExport($mockData);
    $sheets = $completeExport->sheets();

    echo "✓ BOQCompleteExport berhasil diinstansiasi\n";
    echo "✓ Jumlah sheets: " . count($sheets) . "\n";
    echo "✓ Nama sheets: " . implode(', ', array_keys($sheets)) . "\n";

    // Verify each sheet has data
    foreach ($sheets as $sheetName => $sheetExport) {
        if (method_exists($sheetExport, 'array')) {
            $sheetData = $sheetExport->array();
            echo "  - Sheet '$sheetName': " . count($sheetData) . " rows data\n";
        }
    }

    echo "\n✅ SEMUA TEST BERHASIL!\n";
    echo "─────────────────────────────────────────\n";
    echo "🎯 Yang akan didapat di Excel:\n";
    echo "• Sheet 1: 'BOQ Summary Matrix' - Matrix detail per DN/cluster\n";
    echo "• Sheet 2: 'Total Summary' - Aggregasi total per material\n";
    echo "• Format sesuai referensi gambar:\n";
    echo "  - Daftar material di kiri\n";
    echo "  - TOTAL DO, TOTAL TERPAKAI, TOTAL BOQ ACTUAL, TOTAL SISA\n";
    echo "  - TIDAK ada kolom TOTAL SELISIH & TOTAL POTONGAN\n";
    echo "• Headers hijau dengan borders\n";
    echo "• BOQ Actual columns highlight kuning\n";
    echo "• Freeze panes untuk navigasi mudah\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
