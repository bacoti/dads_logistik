<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\DB;

class ExecutiveSummarySheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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

    public function collection()
    {
        // Get transaction summary data
        $query = Transaction::with(['user', 'project', 'details.material']);

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

        $transactions = $query->get();

        // Calculate summary statistics
        $totalTransactions = $transactions->count();
        $totalMaterials = $transactions->sum(function($transaction) {
            return $transaction->details->sum('quantity');
        });

        // Transaction types breakdown (including new "pemakaian" type)
        $typeBreakdown = [
            'penerimaan' => $transactions->where('type', 'penerimaan')->count(),
            'pengambilan' => $transactions->where('type', 'pengambilan')->count(),
            'pengembalian' => $transactions->where('type', 'pengembalian')->count(),
            'peminjaman' => $transactions->where('type', 'peminjaman')->count(),
            'pemakaian' => $transactions->where('type', 'pemakaian')->count(), // New type
        ];

        // Top 5 materials
        $materialUsage = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $materialName = $detail->material ? $detail->material->name : 'Unknown Material';
                $unit = $detail->material ? $detail->material->unit : 'unit';

                // Check if material already exists in array
                $found = false;
                foreach ($materialUsage as &$material) {
                    if ($material['name'] === $materialName) {
                        $material['quantity'] += $detail->quantity;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $materialUsage[] = [
                        'name' => $materialName,
                        'quantity' => $detail->quantity,
                        'unit' => $unit
                    ];
                }
            }
        }

        $topMaterials = collect($materialUsage)->sortByDesc('quantity')->take(5);

        // Project breakdown
        $projectBreakdown = $transactions->groupBy(function($transaction) {
            return $transaction->project ? $transaction->project->name : 'No Project';
        })->map(function($group) {
            return [
                'project' => $group->first()->project ? $group->first()->project->name : 'No Project',
                'count' => $group->count(),
                'materials' => $group->sum(function($transaction) {
                    return $transaction->details->sum('quantity');
                })
            ];
        });

        // Prepare summary data
        $summaryData = collect([
            ['SUMMARY REPORT - TRANSACTION DATA'],
            ['Generated on:', now()->format('d/m/Y H:i:s')],
            ['Period:', ($this->startDate ? $this->startDate : 'All') . ' - ' . ($this->endDate ? $this->endDate : 'All')],
            [''],
            ['TOTAL STATISTICS'],
            ['Total Transactions:', $totalTransactions],
            ['Total Materials Quantity:', number_format($totalMaterials, 2)],
            [''],
            ['TRANSACTION TYPES BREAKDOWN'],
            ['Penerimaan (Masuk):', $typeBreakdown['penerimaan']],
            ['Pengambilan (Keluar):', $typeBreakdown['pengambilan']],
            ['Pengembalian:', $typeBreakdown['pengembalian']],
            ['Peminjaman:', $typeBreakdown['peminjaman']],
            ['Pemakaian Material:', $typeBreakdown['pemakaian']], // New type
            [''],
            ['TOP 5 MATERIALS'],
        ]);

        // Add top materials
        foreach ($topMaterials as $index => $material) {
            $summaryData->push([
                ($index + 1) . '. ' . $material['name'],
                number_format($material['quantity'], 2) . ' ' . $material['unit']
            ]);
        }

        $summaryData->push(['']);
        $summaryData->push(['PROJECT BREAKDOWN']);

        // Add project breakdown
        foreach ($projectBreakdown as $project) {
            $summaryData->push([
                $project['project'],
                $project['count'] . ' transactions',
                number_format($project['materials'], 2) . ' materials'
            ]);
        }

        return $summaryData;
    }

    public function headings(): array
    {
        return [
            'EXECUTIVE SUMMARY',
            'VALUE'
        ];
    }

    public function title(): string
    {
        return 'Executive Summary';
    }

    public function styles(Worksheet $sheet)
    {
        // Title styling
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '1F2937'] // Dark gray
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Merge title cells
        $sheet->mergeCells('A1:B1');

        // Header styling
        $sheet->getStyle('A2:B2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '3B82F6'] // Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Section headers styling
        $highestRow = $sheet->getHighestRow();
        for ($row = 3; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            if (in_array($cellValue, ['TOTAL STATISTICS', 'TRANSACTION TYPES BREAKDOWN', 'TOP 5 MATERIALS', 'PROJECT BREAKDOWN'])) {
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '059669'] // Green
                    ]
                ]);
            }
        }

        // Data styling
        $sheet->getStyle('A3:B' . $highestRow)->applyFromArray([
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

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);

        // Add some spacing
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);

        return [];
    }
}