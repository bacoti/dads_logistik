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

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $projectId;

    public function __construct($startDate = null, $endDate = null, $projectId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
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

        return $query->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'User',
            'Tipe Transaksi',
            'Project',
            'Sub Project',
            'Vendor',
            'Location',
            'Cluster', 
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
            $materialName = $detail->material ? $detail->material->name : 'Unknown Material';
            $unit = $detail->material && $detail->material->unit ? $detail->material->unit : 'unit';
            return "• " . $materialName . ": " . number_format($detail->quantity) . " " . $unit;
        });
        
        $materialsString = $materialDetails->isNotEmpty() ? $materialDetails->join("\n") : '-';
        $totalQuantity = $transaction->details->sum('quantity');
        $totalItems = $transaction->details->count();
        
        return [
            $no++,
            $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
            $transaction->user ? $transaction->user->name : '',
            ucfirst($transaction->type ?? ''),
            $transaction->project ? $transaction->project->name : '',
            $transaction->subProject ? $transaction->subProject->name : '',
            $transaction->vendor ? $transaction->vendor->name : '',
            $transaction->location ?? '',
            $transaction->cluster ?? '',
            $transaction->site_id ?? '',
            $materialsString,
            number_format($totalQuantity),
            $totalItems,
            $transaction->notes ?? '',
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
        $sheet->getStyle('A1:O1')->applyFromArray([
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
            $sheet->getStyle('A2:O' . $highestRow)->applyFromArray([
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
            $sheet->getColumnDimension('K')->setWidth(40); // Detail Materials column
            $sheet->getColumnDimension('L')->setWidth(15); // Total Quantity
            $sheet->getColumnDimension('M')->setWidth(12); // Jumlah Item
            $sheet->getColumnDimension('N')->setWidth(30); // Keterangan

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':O' . $row)->getFill()
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
