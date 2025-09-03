<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsDetailExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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

    public function collection()
    {
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

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $exportData = collect();
        $no = 1;

        foreach ($transactions as $transaction) {
            if ($transaction->details->count() > 0) {
                // Untuk setiap detail material, buat baris terpisah
                foreach ($transaction->details as $index => $detail) {
                    $isFirstDetail = $index === 0;
                    
                    $exportData->push([
                        'no' => $isFirstDetail ? $no : '', // Nomor hanya di baris pertama
                        'transaction_date' => $isFirstDetail ? ($transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '') : '',
                        'user_name' => $isFirstDetail ? ($transaction->user ? $transaction->user->name : '') : '',
                        'type' => $isFirstDetail ? ucfirst($transaction->type ?? '') : '',
                        'project_name' => $isFirstDetail ? ($transaction->project ? $transaction->project->name : '') : '',
                        'sub_project_name' => $isFirstDetail ? ($transaction->subProject ? $transaction->subProject->name : '') : '',
                        'vendor_name' => $isFirstDetail ? ($transaction->vendor ? $transaction->vendor->name : '') : '',
                        'location' => $isFirstDetail ? ($transaction->location ?? '') : '',
                        'cluster' => $isFirstDetail ? ($transaction->cluster ?? '') : '',
                        'site_id' => $isFirstDetail ? ($transaction->site_id ?? '') : '',
                        'material_category' => $detail->material && $detail->material->category ? $detail->material->category->name : '-',
                        'material_name' => $detail->material ? $detail->material->name : 'Unknown Material',
                        'quantity' => number_format($detail->quantity),
                        'unit' => $detail->material && $detail->material->unit ? $detail->material->unit : 'unit',
                        'notes' => $isFirstDetail ? ($transaction->notes ?? '') : '',
                        'created_at' => $isFirstDetail ? ($transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : '') : ''
                    ]);
                }
            } else {
                // Jika tidak ada detail material
                $exportData->push([
                    'no' => $no,
                    'transaction_date' => $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
                    'user_name' => $transaction->user ? $transaction->user->name : '',
                    'type' => ucfirst($transaction->type ?? ''),
                    'project_name' => $transaction->project ? $transaction->project->name : '',
                    'sub_project_name' => $transaction->subProject ? $transaction->subProject->name : '',
                    'vendor_name' => $transaction->vendor ? $transaction->vendor->name : '',
                    'location' => $transaction->location ?? '',
                    'cluster' => $transaction->cluster ?? '',
                    'site_id' => $transaction->site_id ?? '',
                    'material_category' => '-',
                    'material_name' => 'No Materials',
                    'quantity' => '-',
                    'unit' => '-',
                    'notes' => $transaction->notes ?? '',
                    'created_at' => $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : ''
                ]);
            }
            $no++;
        }

        return $exportData;
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

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '6366F1'] // Indigo theme untuk detail
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
            $sheet->getStyle('A2:P' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Set column widths
            $sheet->getColumnDimension('L')->setWidth(25); // Material Name
            $sheet->getColumnDimension('K')->setWidth(18); // Material Category
            $sheet->getColumnDimension('M')->setWidth(12); // Quantity
            $sheet->getColumnDimension('N')->setWidth(10); // Unit
            $sheet->getColumnDimension('O')->setWidth(30); // Notes

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':P' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0F9FF');
                }
            }

            // Center align quantity and unit columns
            $sheet->getStyle('M2:N' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
}
