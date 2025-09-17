<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
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

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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

    public function query()
    {
        $query = Transaction::with(['user', 'project', 'vendor', 'subProject', 'details.material']);

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

        return $query->orderBy('transaction_date', 'desc');
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
        
        // Format materials dengan rapi - setiap material di baris baru
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
        
        return [
            $no++,
            sanitizeForSpreadsheet($transaction->id),
            $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
            $transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '',
            ucfirst(sanitizeForSpreadsheet($transaction->type ?? '')),
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
            number_format($totalQuantity),
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
        // Header styling
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '8B5CF6'] // Purple theme
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Data rows styling
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            $sheet->getStyle('A2:T' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(8);  // No
            $sheet->getColumnDimension('B')->setWidth(12); // ID Transaksi
            $sheet->getColumnDimension('C')->setWidth(15); // Tanggal
            $sheet->getColumnDimension('D')->setWidth(10); // Waktu
            $sheet->getColumnDimension('E')->setWidth(15); // Tipe
            $sheet->getColumnDimension('F')->setWidth(20); // User
            $sheet->getColumnDimension('G')->setWidth(25); // Project
            $sheet->getColumnDimension('H')->setWidth(25); // Sub Project
            $sheet->getColumnDimension('I')->setWidth(20); // Location
            $sheet->getColumnDimension('J')->setWidth(15); // Cluster
            $sheet->getColumnDimension('K')->setWidth(25); // Vendor/Tujuan
            $sheet->getColumnDimension('L')->setWidth(20); // DO No
            $sheet->getColumnDimension('M')->setWidth(20); // DN No
            $sheet->getColumnDimension('N')->setWidth(20); // DR No
            $sheet->getColumnDimension('O')->setWidth(15); // Site ID
            $sheet->getColumnDimension('P')->setWidth(45); // Detail Materials
            $sheet->getColumnDimension('Q')->setWidth(15); // Total Quantity
            $sheet->getColumnDimension('R')->setWidth(12); // Jumlah Item
            $sheet->getColumnDimension('S')->setWidth(30); // Keterangan
            $sheet->getColumnDimension('T')->setWidth(18); // Created At

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':T' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FAF5FF');
                }
                
                // Set row height for better readability of materials
                $sheet->getRowDimension($row)->setRowHeight(-1); // Auto height
            }
        }

        return [];
    }
}
