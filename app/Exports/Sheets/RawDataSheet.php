<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RawDataSheet implements FromQuery, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
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

        return $query->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'transaction_id',
            'transaction_date',
            'transaction_time',
            'type',
            'user_id',
            'user_name',
            'project_id',
            'project_name',
            'sub_project_id',
            'sub_project_name',
            'location',
            'cluster',
            'vendor_id',
            'vendor_name',
            'vendor_name_manual',
            'site_id',
            'delivery_order_no',
            'delivery_note_no',
            'delivery_return_no',
            'return_destination',
            'notes',
            'created_at',
            'updated_at',
            'material_id',
            'material_name',
            'material_category_id',
            'material_category_name',
            'quantity',
            'unit'
        ];
    }

    public function map($transaction): array
    {
        $baseData = [
            $transaction->id,
            $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d') : null,
            $transaction->transaction_date ? $transaction->transaction_date->format('H:i:s') : null,
            $transaction->type,
            $transaction->user_id,
            $transaction->user ? $transaction->user->name : null,
            $transaction->project_id,
            $transaction->project ? $transaction->project->name : null,
            $transaction->sub_project_id,
            $transaction->subProject ? $transaction->subProject->name : null,
            $transaction->location,
            $transaction->cluster,
            $transaction->vendor_id,
            $transaction->vendor ? $transaction->vendor->name : null,
            $transaction->vendor_name,
            $transaction->site_id,
            $transaction->delivery_order_no,
            $transaction->delivery_note_no,
            $transaction->delivery_return_no,
            $transaction->return_destination,
            $transaction->notes,
            $transaction->created_at ? $transaction->created_at->format('Y-m-d H:i:s') : null,
            $transaction->updated_at ? $transaction->updated_at->format('Y-m-d H:i:s') : null,
        ];

        // If transaction has details, create one row per material
        if ($transaction->details->count() > 0) {
            $rows = [];
            foreach ($transaction->details as $detail) {
                $materialData = [
                    $detail->material_id,
                    $detail->material ? $detail->material->name : 'Unknown Material',
                    $detail->material && $detail->material->category ? $detail->material->category->id : null,
                    $detail->material && $detail->material->category ? $detail->material->category->name : null,
                    $detail->quantity,
                    $detail->material ? $detail->material->unit : 'unit'
                ];

                $rows[] = array_merge($baseData, $materialData);
            }
            return $rows;
        } else {
            // No materials, return single row with null material data
            $materialData = [
                null, // material_id
                null, // material_name
                null, // material_category_id
                null, // material_category_name
                null, // quantity
                null  // unit
            ];

            return [array_merge($baseData, $materialData)];
        }
    }

    public function title(): string
    {
        return 'Raw Data';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:AC1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '374151'] // Dark gray
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
            $sheet->getStyle('A2:AC' . $highestRow)->applyFromArray([
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
            $columnWidths = [
                'A' => 12, // transaction_id
                'B' => 12, // transaction_date
                'C' => 10, // transaction_time
                'D' => 12, // type
                'E' => 8,  // user_id
                'F' => 20, // user_name
                'G' => 8,  // project_id
                'H' => 25, // project_name
                'I' => 8,  // sub_project_id
                'J' => 25, // sub_project_name
                'K' => 20, // location
                'L' => 15, // cluster
                'M' => 8,  // vendor_id
                'N' => 25, // vendor_name
                'O' => 25, // vendor_name_manual
                'P' => 15, // site_id
                'Q' => 20, // delivery_order_no
                'R' => 20, // delivery_note_no
                'S' => 20, // delivery_return_no
                'T' => 25, // return_destination
                'U' => 30, // notes
                'V' => 18, // created_at
                'W' => 18, // updated_at
                'X' => 8,  // material_id
                'Y' => 30, // material_name
                'Z' => 8,  // material_category_id
                'AA' => 20, // material_category_name
                'AB' => 12, // quantity
                'AC' => 10  // unit
            ];

            foreach ($columnWidths as $column => $width) {
                $sheet->getColumnDimension($column)->setWidth($width);
            }

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':AC' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F9FAFB');
                }
            }

            // Highlight transaction type column with conditional formatting
            for ($row = 2; $row <= $highestRow; $row++) {
                $typeCell = $sheet->getCell('D' . $row)->getValue();

                if ($typeCell === 'penerimaan') {
                    $sheet->getStyle('D' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('DCFCE7'); // Light green
                } elseif ($typeCell === 'pengambilan') {
                    $sheet->getStyle('D' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FEE2E2'); // Light red
                } elseif ($typeCell === 'pengembalian') {
                    $sheet->getStyle('D' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FED7AA'); // Light orange
                } elseif ($typeCell === 'peminjaman') {
                    $sheet->getStyle('D' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('DDD6FE'); // Light purple
                } elseif ($typeCell === 'pemakaian') {
                    $sheet->getStyle('D' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('DBEAFE'); // Light blue for new type
                }
            }
        }

        // Add note about raw data format
        $noteRow = $highestRow + 2;
        $sheet->setCellValue('A' . $noteRow, 'NOTE: This sheet contains raw data in machine-readable format for import/analysis purposes');
        $sheet->getStyle('A' . $noteRow)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 9,
                'color' => ['rgb' => '6B7280']
            ]
        ]);

        return [];
    }
}