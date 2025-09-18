<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\BOQActual;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Support\Collection;

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        $transactionsQuery = Transaction::with(['user', 'project', 'vendor', 'subProject', 'details.material']);

        if ($this->startDate) {
            $transactionsQuery->whereDate('transaction_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $transactionsQuery->whereDate('transaction_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $transactionsQuery->where('project_id', $this->projectId);
        }

        if ($this->location) {
            $transactionsQuery->where('location', 'like', '%' . $this->location . '%');
        }

        if ($this->cluster) {
            $transactionsQuery->where('cluster', 'like', '%' . $this->cluster . '%');
        }

        $transactions = $transactionsQuery->orderBy('transaction_date', 'desc')->get();

        // Get BOQ Actual data (pemakaian material)
        $boqQuery = BOQActual::with(['user', 'project', 'subProject', 'material']);

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
        return $allData->sortByDesc(function($item) {
            return $item->transaction_date ?? $item->created_at;
        })->values();
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
            'Detail Materials',
            'Total Quantity',
            'Jumlah Item',
            'Keterangan',
            'Created At'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;

        // Handle BOQ Actual data (pemakaian material)
        if (str_starts_with($transaction->id, 'BOQ-')) {
            $materialName = $transaction->details->first()->material ?
                sanitizeForSpreadsheet($transaction->details->first()->material->name) : 'Unknown Material';
            $unit = $transaction->details->first()->material && $transaction->details->first()->material->unit ?
                sanitizeForSpreadsheet($transaction->details->first()->material->unit) : 'unit';
            $quantity = $transaction->details->first()->quantity;

            $materialsString = "• " . $materialName . ": " . number_format($quantity, 2) . " " . $unit;
            $totalQuantity = $quantity;
            $totalItems = 1;

            $vendorDestination = 'BOQ Actual - ' . sanitizeForSpreadsheet($transaction->cluster ?? '');
        } else {
            // Handle regular transaction data
            $materialDetails = $transaction->details->map(function($detail) {
                $materialName = $detail->material ? sanitizeForSpreadsheet($detail->material->name) : 'Unknown Material';
                $unit = $detail->material && $detail->material->unit ? sanitizeForSpreadsheet($detail->material->unit) : 'unit';
                return "• " . $materialName . ": " . number_format($detail->quantity) . " " . $unit;
            });

            $materialsString = $materialDetails->isNotEmpty() ? $materialDetails->join("\n") : '-';
            $totalQuantity = $transaction->details->sum('quantity');
            $totalItems = $transaction->details->count();

            // Menentukan vendor/tujuan berdasarkan tipe transaksi
            $vendorDestination = '';
            if ($transaction->type == 'pengembalian' && $transaction->return_destination) {
                $vendorDestination = 'Tujuan: ' . sanitizeForSpreadsheet($transaction->return_destination);
            } elseif ($transaction->vendor) {
                $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor->name);
            } elseif ($transaction->vendor_name) {
                $vendorDestination = 'Vendor: ' . sanitizeForSpreadsheet($transaction->vendor_name);
            }
        }

        return [
            $no++,
            sanitizeForSpreadsheet($transaction->id),
            $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
            $transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '',
            $transaction->type == 'pemakaian' ? 'Pemakaian Material' : ucfirst(sanitizeForSpreadsheet($transaction->type ?? '')),
            $transaction->user ? sanitizeForSpreadsheet($transaction->user->name) : '',
            $transaction->project ? sanitizeForSpreadsheet($transaction->project->name) : '',
            $transaction->subProject ? sanitizeForSpreadsheet($transaction->subProject->name) : '',
            sanitizeForSpreadsheet($transaction->location ?? ''),
            sanitizeForSpreadsheet($transaction->cluster ?? ''),
            $vendorDestination,
            sanitizeForSpreadsheet($transaction->delivery_order_no ?? ''),
            sanitizeForSpreadsheet($transaction->delivery_note_no ?? ''),
            sanitizeForSpreadsheet($transaction->delivery_return_no ?? ''),
            sanitizeForSpreadsheet($transaction->site_id ?? ''),
            $materialsString,
            number_format($totalQuantity, 2),
            $totalItems,
            sanitizeForSpreadsheet($transaction->notes ?? ''),
            $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : ''
        ];
    }

    public function title(): string
    {
        return 'Data Transaksi';
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
        $sheet->setCellValue('A1', '📊 LAPORAN TRANSAKSI LOGISTIK');
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
        $sheet->mergeCells('A1:T1');
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
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Add summary statistics on the right
        $totalRows = $sheet->getHighestRow() - 4;
        $sheet->setCellValue('P2', '📈 Total Records:');
        $sheet->setCellValue('Q2', $totalRows);
        $sheet->setCellValue('P3', '🎯 Periode:');
        $sheet->setCellValue('Q3', ($this->startDate ?? 'All') . ' - ' . ($this->endDate ?? 'All'));

        $sheet->getStyle('P2:Q3')->applyFromArray([
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
        $sheet->mergeCells('Q2:R2');
        $sheet->mergeCells('Q3:R3');
    }

    private function applyHeaderStyling(Worksheet $sheet)
    {
        // Enhanced header styling with gradient
        $sheet->getStyle('A4:T4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => '8B5CF6'],
                'endColor' => ['rgb' => '7C3AED']
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

        // Set header row height
        $sheet->getRowDimension(4)->setRowHeight(35);
    }

    private function applyDataRowStyling(Worksheet $sheet, $highestRow)
    {
        // Apply consistent styling to all data rows
        $sheet->getStyle('A5:T' . $highestRow)->applyFromArray([
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
            'A' => 6,   // No
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
            'P' => 40,  // Detail Materials
            'Q' => 12,  // Total Quantity
            'R' => 10,  // Jumlah Item
            'S' => 28,  // Keterangan
            'T' => 16   // Created At
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function autoSizeColumns(Worksheet $sheet)
    {
        // Auto-size with reasonable limits
        $columnWidths = [
            'A' => 8,   // No
            'B' => 15,  // ID Transaksi
            'C' => 12,  // Tanggal
            'D' => 10,  // Waktu
            'E' => 18,  // Tipe (wider for icons)
            'F' => 20,  // User
            'G' => 25,  // Project
            'H' => 25,  // Sub Project
            'I' => 20,  // Location
            'J' => 15,  // Cluster
            'K' => 25,  // Vendor/Tujuan
            'L' => 18,  // DO No
            'M' => 18,  // DN No
            'N' => 18,  // DR No
            'O' => 15,  // Site ID
            'P' => 45,  // Detail Materials (wider)
            'Q' => 15,  // Total Quantity
            'R' => 12,  // Jumlah Item
            'S' => 30,  // Keterangan
            'T' => 18   // Created At
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
                $sheet->getStyle('A' . $row . ':T' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9FAFB');
            }

            // Highlight large quantities
            $quantity = $sheet->getCell('Q' . $row)->getValue();
            if (is_numeric($quantity) && $quantity > 100) {
                $sheet->getStyle('Q' . $row)->getFill()
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
        $centerColumns = ['A', 'D', 'Q', 'R']; // No, Time, Total Quantity, Jumlah Item
        foreach ($centerColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Right align quantity column
        $sheet->getStyle('Q5:Q' . $highestRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Left align text columns
        $leftColumns = ['B', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'S', 'T'];
        foreach ($leftColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
    }

    private function applyTextWrapping(Worksheet $sheet, $highestRow)
    {
        // Apply text wrapping to columns that may have long content
        $wrapColumns = ['G', 'H', 'K', 'P', 'S']; // Project, Sub Project, Vendor, Detail Materials, Keterangan
        foreach ($wrapColumns as $col) {
            $sheet->getStyle($col . '5:' . $col . $highestRow)->getAlignment()->setWrapText(true);
        }
    }

    private function setupNavigationFeatures(Worksheet $sheet)
    {
        // Add freeze panes
        $sheet->freezePane('E5');

        // Add auto-filter
        $sheet->setAutoFilter('A4:T4');

        // Set zoom level for better viewing
        $sheet->getSheetView()->setZoomScale(90);
    }

    private function applyTransactionTypeStyle(Worksheet $sheet, $row, $color, $iconText)
    {
        // Apply background color
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB($color);

        // Apply text color (white for better contrast)
        $sheet->getStyle('E' . $row)->getFont()
            ->setColor(new Color(Color::COLOR_WHITE));

        // Make it bold
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);

        // Center align
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
