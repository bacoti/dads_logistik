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
            'Materials & Quantity',
            'Total Items',
            'Keterangan',
            'Created At'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;
        
        // Ambil detail materials dengan quantity
        $materialsWithQty = $transaction->details->map(function($detail) {
            $materialName = $detail->material ? $detail->material->name : 'Unknown Material';
            return $materialName . ' (' . $detail->quantity . ' unit)';
        });
        
        $materialsString = $materialsWithQty->isNotEmpty() ? $materialsWithQty->join(', ') : '-';
        $totalItems = $transaction->details->sum('quantity');
        
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
        $sheet->getStyle('A1:N1')->applyFromArray([
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
            $sheet->getStyle('A2:N' . $highestRow)->applyFromArray([
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

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':N' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FAF5FF');
                }
            }
        }

        return [];
    }
}
