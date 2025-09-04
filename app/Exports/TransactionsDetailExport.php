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

        $exportData = collect();
        $no = 1;

        foreach ($transactions as $transaction) {
            if ($transaction->details->count() > 0) {
                // Untuk setiap detail material, buat baris terpisah
                foreach ($transaction->details as $index => $detail) {
                    $isFirstDetail = $index === 0;
                    
                    // Menentukan vendor/tujuan berdasarkan tipe transaksi
                    $vendorDestination = '';
                    if ($transaction->type == 'pengembalian' && $transaction->return_destination) {
                        $vendorDestination = 'Tujuan: ' . $transaction->return_destination;
                    } elseif ($transaction->vendor) {
                        $vendorDestination = 'Vendor: ' . $transaction->vendor->name;
                    } elseif ($transaction->vendor_name) {
                        $vendorDestination = 'Vendor: ' . $transaction->vendor_name;
                    }
                    
                    $exportData->push([
                        'no' => $isFirstDetail ? $no : '', // Nomor hanya di baris pertama
                        'transaction_id' => $isFirstDetail ? $transaction->id : '',
                        'transaction_date' => $isFirstDetail ? ($transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '') : '',
                        'transaction_time' => $isFirstDetail ? ($transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '') : '',
                        'type' => $isFirstDetail ? ucfirst($transaction->type ?? '') : '',
                        'user_name' => $isFirstDetail ? ($transaction->user ? $transaction->user->name : '') : '',
                        'project_name' => $isFirstDetail ? ($transaction->project ? $transaction->project->name : '') : '',
                        'sub_project_name' => $isFirstDetail ? ($transaction->subProject ? $transaction->subProject->name : '') : '',
                        'location' => $isFirstDetail ? ($transaction->location ?? '') : '',
                        'cluster' => $isFirstDetail ? ($transaction->cluster ?? '') : '',
                        'vendor_destination' => $isFirstDetail ? $vendorDestination : '',
                        'delivery_order_no' => $isFirstDetail ? ($transaction->delivery_order_no ?? '') : '',
                        'delivery_note_no' => $isFirstDetail ? ($transaction->delivery_note_no ?? '') : '',
                        'delivery_return_no' => $isFirstDetail ? ($transaction->delivery_return_no ?? '') : '',
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
                $vendorDestination = '';
                if ($transaction->type == 'pengembalian' && $transaction->return_destination) {
                    $vendorDestination = 'Tujuan: ' . $transaction->return_destination;
                } elseif ($transaction->vendor) {
                    $vendorDestination = 'Vendor: ' . $transaction->vendor->name;
                } elseif ($transaction->vendor_name) {
                    $vendorDestination = 'Vendor: ' . $transaction->vendor_name;
                }
                
                $exportData->push([
                    'no' => $no,
                    'transaction_id' => $transaction->id,
                    'transaction_date' => $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
                    'transaction_time' => $transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : '',
                    'type' => ucfirst($transaction->type ?? ''),
                    'user_name' => $transaction->user ? $transaction->user->name : '',
                    'project_name' => $transaction->project ? $transaction->project->name : '',
                    'sub_project_name' => $transaction->subProject ? $transaction->subProject->name : '',
                    'location' => $transaction->location ?? '',
                    'cluster' => $transaction->cluster ?? '',
                    'vendor_destination' => $vendorDestination,
                    'delivery_order_no' => $transaction->delivery_order_no ?? '',
                    'delivery_note_no' => $transaction->delivery_note_no ?? '',
                    'delivery_return_no' => $transaction->delivery_return_no ?? '',
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

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:U1')->applyFromArray([
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
            $sheet->getStyle('A2:U' . $highestRow)->applyFromArray([
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
            $sheet->getColumnDimension('P')->setWidth(18); // Material Category
            $sheet->getColumnDimension('Q')->setWidth(30); // Material Name
            $sheet->getColumnDimension('R')->setWidth(12); // Quantity
            $sheet->getColumnDimension('S')->setWidth(10); // Unit
            $sheet->getColumnDimension('T')->setWidth(30); // Notes
            $sheet->getColumnDimension('U')->setWidth(18); // Created At

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':U' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0F9FF');
                }
            }

            // Center align quantity and unit columns
            $sheet->getStyle('R2:S' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
}
