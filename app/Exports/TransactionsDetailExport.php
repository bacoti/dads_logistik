<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\BOQActual;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Support\Collection;

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

class TransactionsDetailExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $projectId;
    protected $location;
    protected $cluster;

    public function __construct($startDate = null, $endDate = null, $projectId = null, $location = null, $cluster = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
        $this->location = $location;
        $this->cluster = $cluster;
    }

    public function collection()
    {
        // Get regular transactions
        $query = Transaction::with(['user', 'project', 'vendor', 'subProject', 'details.material.category']);

        if ($this->startDate) {
            $query->whereDate('transaction_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('transaction_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        if ($this->location) {
            $query->where('location', 'like', '%' . $this->location . '%');
        }

        if ($this->cluster) {
            $query->where('cluster', 'like', '%' . $this->cluster . '%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Get BOQ Actual data (pemakaian material)
        $boqQuery = BOQActual::with(['user', 'project', 'subProject', 'material.category']);

        if ($this->startDate) {
            $boqQuery->whereDate('usage_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $boqQuery->whereDate('usage_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $boqQuery->where('project_id', $this->projectId);
        }

        if ($this->cluster) {
            $boqQuery->where('cluster', 'like', '%' . $this->cluster . '%');
        }

        $boqActuals = $boqQuery->orderBy('usage_date', 'desc')->get();

        // Convert BOQ Actuals to transaction-like format
        $boqAsTransactions = $boqActuals->map(function($boq) {
            return (object) [
                'id' => 'BOQ-' . $boq->id,
                'transaction_date' => $boq->usage_date,
                'type' => 'pemakaian',
                'user' => $boq->user,
                'project' => $boq->project,
                'subProject' => $boq->subProject,
                'location' => 'BOQ Actual',
                'cluster' => $boq->cluster,
                'vendor' => null,
                'vendor_name' => null,
                'return_destination' => null,
                'delivery_order_no' => null,
                'delivery_note_no' => $boq->dn_number,
                'delivery_return_no' => null,
                'site_id' => null,
                'notes' => $boq->notes,
                'created_at' => $boq->created_at,
                'details' => collect([
                    (object) [
                        'material' => $boq->material,
                        'quantity' => $boq->actual_quantity
                    ]
                ])
            ];
        });

        // Combine both collections
        $allData = $transactions->concat($boqAsTransactions);

        // Sort by date (newest first)
        $sortedData = $allData->sortByDesc(function($item) {
            return $item->transaction_date ?? $item->created_at;
        })->values();

        $exportData = collect();
        $no = 1; // Start numbering from 1

        foreach ($sortedData as $transaction) {
            if ($transaction->details->count() > 0) {
                // Untuk setiap detail material, buat baris terpisah
                foreach ($transaction->details as $index => $detail) {
                    $isFirstDetail = $index === 0;

                    $vendorDestination = '';
                    if ($transaction->type == 'pengembalian' && $transaction->return_destination) {
                        $vendorDestination = 'Tujuan: ' . sanitizeForSpreadsheet($transaction->return_destination);
                    } elseif ($transaction->type == 'pemakaian') {
                        $vendorDestination = 'BOQ Actual - ' . sanitizeForSpreadsheet($transaction->cluster ?? '');
                    } elseif ($transaction->vendor) {
                        $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor->name);
                    } elseif ($transaction->vendor_name) {
                        $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor_name);
                    }

                    $exportData->push([
                        'no' => $isFirstDetail ? $no : '', // Nomor hanya di baris pertama
                        'transaction_id' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->id) : '',
                        'transaction_date' => $isFirstDetail ? ($transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '') : '',
                        'transaction_time' => $isFirstDetail ? ($transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '') : '',
                        'type' => $isFirstDetail ? ($transaction->type == 'pemakaian' ? 'Pemakaian Material' : ucfirst(sanitizeForSpreadsheet($transaction->type ?? ''))) : '',
                        'user_name' => $isFirstDetail ? ($transaction->user ? sanitizeForSpreadsheet($transaction->user->name) : '') : '',
                        'project_name' => $isFirstDetail ? ($transaction->project ? sanitizeForSpreadsheet($transaction->project->name) : '') : '',
                        'sub_project_name' => $isFirstDetail ? ($transaction->subProject ? sanitizeForSpreadsheet($transaction->subProject->name) : '') : '',
                        'location' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->location ?? '') : '',
                        'cluster' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->cluster ?? '') : '',
                        'vendor_destination' => $isFirstDetail ? $vendorDestination : '',
                        'delivery_order_no' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->delivery_order_no ?? '') : '',
                        'delivery_note_no' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->delivery_note_no ?? '') : '',
                        'delivery_return_no' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->delivery_return_no ?? '') : '',
                        'site_id' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->site_id ?? '') : '',
                        'material_category' => $detail->material && $detail->material->category ? sanitizeForSpreadsheet($detail->material->category->name) : '-',
                        'material_name' => $detail->material ? sanitizeForSpreadsheet($detail->material->name) : 'Unknown Material',
                        'quantity' => number_format($detail->quantity),
                        'unit' => $detail->material && $detail->material->unit ? sanitizeForSpreadsheet($detail->material->unit) : 'unit',
                        'notes' => $isFirstDetail ? sanitizeForSpreadsheet($transaction->notes ?? '') : '',
                        'created_at' => $isFirstDetail ? ($transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : '') : ''
                    ]);
                }
            } else {
                // Jika tidak ada detail material
                $vendorDestination = '';
                if ($transaction->type == 'pengembalian' && $transaction->return_destination) {
                    $vendorDestination = 'Tujuan: ' . sanitizeForSpreadsheet($transaction->return_destination);
                } elseif ($transaction->type == 'pemakaian') {
                    $vendorDestination = 'BOQ Actual - ' . sanitizeForSpreadsheet($transaction->cluster ?? '');
                } elseif ($transaction->vendor) {
                    $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor->name);
                } elseif ($transaction->vendor_name) {
                    $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor_name);
                }

                $exportData->push([
                    'no' => $no,
                    'transaction_id' => sanitizeForSpreadsheet($transaction->id),
                    'transaction_date' => $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
                    'transaction_time' => $transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '',
                    'type' => $transaction->type == 'pemakaian' ? 'Pemakaian Material' : ucfirst(sanitizeForSpreadsheet($transaction->type ?? '')),
                    'user_name' => $transaction->user ? sanitizeForSpreadsheet($transaction->user->name) : '',
                    'project_name' => $transaction->project ? sanitizeForSpreadsheet($transaction->project->name) : '',
                    'sub_project_name' => $transaction->subProject ? sanitizeForSpreadsheet($transaction->subProject->name) : '',
                    'location' => sanitizeForSpreadsheet($transaction->location ?? ''),
                    'cluster' => sanitizeForSpreadsheet($transaction->cluster ?? ''),
                    'vendor_destination' => $vendorDestination,
                    'delivery_order_no' => sanitizeForSpreadsheet($transaction->delivery_order_no ?? ''),
                    'delivery_note_no' => sanitizeForSpreadsheet($transaction->delivery_note_no ?? ''),
                    'delivery_return_no' => sanitizeForSpreadsheet($transaction->delivery_return_no ?? ''),
                    'site_id' => sanitizeForSpreadsheet($transaction->site_id ?? ''),
                    'material_category' => '-',
                    'material_name' => 'No Materials',
                    'quantity' => '-',
                    'unit' => '-',
                    'notes' => sanitizeForSpreadsheet($transaction->notes ?? ''),
                    'created_at' => $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : ''
                ]);
            }
            $no++; // Increment numbering
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Transaksi',
            'Tanggal Transaksi',
            'Waktu',
            'Tipe Transaksi',
            'User',
            'Project',
            'Sub Project',
            'Location',
            'Cluster',
            'Vendor/Tujuan',
            'Delivery Order No',
            'Delivery Note No',
            'Delivery Return No',
            'Site ID',
            'Kategori Material',
            'Nama Material',
            'Quantity',
            'Satuan',
            'Keterangan',
            'Created At'
        ];
    }

    public function title(): string
    {
        return 'Transaksi Detail';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function styles(Worksheet $sheet)
    {
        // Set up page layout first
        $this->setupPageLayout($sheet);

        // Add summary information at the top
        $this->addSummarySection($sheet);

        // Enhanced header styling with gradient
        $this->applyHeaderStyling($sheet);

        // Data rows styling
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 4) {
            $this->applyDataRowStyling($sheet, $highestRow);
            $this->applyEnhancedConditionalFormatting($sheet, $highestRow);
            $this->setupNavigationFeatures($sheet);
        }

        return [];
    }

    private function setupPageLayout(Worksheet $sheet)
    {
        // Set page margins for better printing
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.5);

        // Set page orientation to landscape for better fit
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set print area to fit all data
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getPageSetup()->setPrintArea('A1:' . $highestColumn . $highestRow);
    }

    private function addSummarySection(Worksheet $sheet)
    {
        // Add title with better spacing
        $sheet->setCellValue('A1', '📊 LAPORAN TRANSAKSI DETAIL LOGISTIK');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1F2937']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->mergeCells('A1:U1');
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Add export info with better layout
        $sheet->setCellValue('A2', '📅 Tanggal Export: ' . now()->format('d/m/Y H:i:s'));
        $sheet->setCellValue('A3', '👤 Diekspor oleh: ' . (auth()->user() ? auth()->user()->name : 'System'));
        $sheet->getStyle('A2:A3')->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '6B7280']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->mergeCells('A2:U2');
        $sheet->mergeCells('A3:U3');
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Add summary statistics on the right
        $totalRows = $sheet->getHighestRow() - 4;
        $sheet->setCellValue('R2', '📈 Total Records:');
        $sheet->setCellValue('S2', $totalRows);
        $sheet->setCellValue('R3', '🎯 Periode:');
        $sheet->setCellValue('S3', ($this->startDate ?? 'All') . ' - ' . ($this->endDate ?? 'All'));

        $sheet->getStyle('R2:S3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => '059669']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Merge cells for better layout
        $sheet->mergeCells('S2:T2');
        $sheet->mergeCells('S3:T3');
    }

    private function applyHeaderStyling(Worksheet $sheet)
    {
        // Enhanced header styling with gradient and icons
        $sheet->getStyle('A4:U4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => '1D4ED8'],
                'endColor' => ['rgb' => '2563EB']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Add icons to header cells
        $headers = [
            'A4' => '🔢 No',
            'B4' => '🆔 ID Transaksi',
            'C4' => '📅 Tanggal Transaksi',
            'D4' => '⏰ Waktu',
            'E4' => '📂 Tipe Transaksi',
            'F4' => '👤 User',
            'G4' => '🏗️ Project',
            'H4' => '🏗️ Sub Project',
            'I4' => '📍 Location',
            'J4' => '📊 Cluster',
            'K4' => '🚚 Vendor/Tujuan',
            'L4' => '📄 Delivery Order No',
            'M4' => '📄 Delivery Note No',
            'N4' => '📄 Delivery Return No',
            'O4' => '📍 Site ID',
            'P4' => '📦 Kategori Material',
            'Q4' => '📦 Nama Material',
            'R4' => '🔢 Quantity',
            'S4' => '📏 Satuan',
            'T4' => '📝 Keterangan',
            'U4' => '📅 Created At'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Set header row height
        $sheet->getRowDimension(4)->setRowHeight(40);
    }

    private function applyDataRowStyling(Worksheet $sheet, $highestRow)
    {
        // Apply consistent styling to all data rows
        $sheet->getStyle('A5:U' . $highestRow)->applyFromArray([
            'font' => [
                'size' => 9,
                'name' => 'Calibri'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set optimal column widths
        $this->setOptimalColumnWidths($sheet);

        // Set consistent row heights for data rows
        for ($row = 5; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }
    }

    private function setOptimalColumnWidths(Worksheet $sheet)
    {
        // More refined column widths for better fit
        $columnWidths = [
            'A' => 6,   // No (smaller)
            'B' => 14,  // ID Transaksi
            'C' => 11,  // Tanggal
            'D' => 8,   // Waktu
            'E' => 16,  // Tipe
            'F' => 18,  // User
            'G' => 22,  // Project
            'H' => 22,  // Sub Project
            'I' => 18,  // Location
            'J' => 12,  // Cluster
            'K' => 22,  // Vendor/Tujuan
            'L' => 15,  // DO No
            'M' => 15,  // DN No
            'N' => 15,  // DR No
            'O' => 12,  // Site ID
            'P' => 18,  // Material Category
            'Q' => 32,  // Material Name
            'R' => 10,  // Quantity
            'S' => 6,   // Unit
            'T' => 32,  // Notes
            'U' => 16   // Created At
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function applyEnhancedConditionalFormatting(Worksheet $sheet, $highestRow)
    {
        // Enhanced conditional formatting for transaction types
        for ($row = 5; $row <= $highestRow; $row++) {
            $typeCell = $sheet->getCell('E' . $row)->getValue();

            // Transaction type formatting with cleaner colors
            if (strpos($typeCell, 'Penerimaan') !== false) {
                $this->applyCleanTransactionTypeStyle($sheet, $row, '10B981', '📥');
            } elseif (strpos($typeCell, 'Pengambilan') !== false) {
                $this->applyCleanTransactionTypeStyle($sheet, $row, 'EF4444', '📤');
            } elseif (strpos($typeCell, 'Pengembalian') !== false) {
                $this->applyCleanTransactionTypeStyle($sheet, $row, 'F97316', '🔄');
            } elseif (strpos($typeCell, 'Peminjaman') !== false) {
                $this->applyCleanTransactionTypeStyle($sheet, $row, '8B5CF6', '📋');
            } elseif (strpos($typeCell, 'Pemakaian Material') !== false) {
                $this->applyCleanTransactionTypeStyle($sheet, $row, '3B82F6', '⚡');
            }

            // Subtle zebra striping
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':U' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9FAFB');
            }

            // Highlight large quantities
            $quantity = $sheet->getCell('R' . $row)->getValue();
            if (is_numeric($quantity) && $quantity > 100) {
                $sheet->getStyle('R' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEF3C7');
            }
        }

        // Apply consistent alignments
        $this->applyConsistentAlignments($sheet, $highestRow);

        // Apply text wrapping for long content
        $this->applyTextWrapping($sheet, $highestRow);
    }

    private function applyCleanTransactionTypeStyle(Worksheet $sheet, $row, $color, $icon)
    {
        // Apply background color
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB($color);

        // Apply text color and icon
        $sheet->getStyle('E' . $row)->getFont()
            ->setColor(new Color(Color::COLOR_WHITE))
            ->setBold(true);

        // Set cell value with icon
        $sheet->setCellValue('E' . $row, $icon . ' ' . $sheet->getCell('E' . $row)->getValue());

        // Center align
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function applyConsistentAlignments(Worksheet $sheet, $highestRow)
    {
        // Center align specific columns
        $centerColumns = ['A', 'D', 'R', 'S']; // No, Time, Quantity, Unit
        foreach ($centerColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Right align quantity column
        $sheet->getStyle('R5:R' . $highestRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Left align text columns
        $leftColumns = ['B', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'T', 'U'];
        foreach ($leftColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
    }

    private function applyTextWrapping(Worksheet $sheet, $highestRow)
    {
        // Apply text wrapping to columns that may have long content
        $wrapColumns = ['G', 'H', 'K', 'Q', 'T']; // Project, Sub Project, Vendor, Material Name, Notes
        foreach ($wrapColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)->getAlignment()->setWrapText(true);
        }
    }

    private function setupNavigationFeatures(Worksheet $sheet)
    {
        // Add freeze panes
        $sheet->freezePane('E5');

        // Add auto-filter
        $sheet->setAutoFilter('A4:U4');

        // Set zoom level for better viewing
        $sheet->getSheetView()->setZoomScale(90);
    }
}
