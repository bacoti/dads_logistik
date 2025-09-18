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
use Illuminate\Support\Facades\DB;

class MaterialAnalysisSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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
        // Get material usage analysis
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->leftJoin('categories', 'materials.category_id', '=', 'categories.id')
            ->leftJoin('projects', 'transactions.project_id', '=', 'projects.id')
            ->select(
                'materials.name as material_name',
                'materials.unit',
                'categories.name as category_name',
                'projects.name as project_name',
                DB::raw('SUM(transaction_details.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT transactions.id) as transaction_count'),
                DB::raw('COUNT(transaction_details.id) as detail_count'),
                DB::raw('AVG(transaction_details.quantity) as avg_quantity'),
                DB::raw('MIN(transaction_details.quantity) as min_quantity'),
                DB::raw('MAX(transaction_details.quantity) as max_quantity'),
                DB::raw('SUM(CASE WHEN transactions.type = "penerimaan" THEN transaction_details.quantity ELSE 0 END) as incoming_qty'),
                DB::raw('SUM(CASE WHEN transactions.type = "pengambilan" THEN transaction_details.quantity ELSE 0 END) as outgoing_qty'),
                DB::raw('SUM(CASE WHEN transactions.type = "pengembalian" THEN transaction_details.quantity ELSE 0 END) as return_qty'),
                DB::raw('SUM(CASE WHEN transactions.type = "peminjaman" THEN transaction_details.quantity ELSE 0 END) as loan_qty'),
                DB::raw('SUM(CASE WHEN transactions.type = "pemakaian" THEN transaction_details.quantity ELSE 0 END) as usage_qty') // New type
            );

        if ($this->startDate) {
            $query->whereDate('transactions.transaction_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('transactions.transaction_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $query->where('transactions.project_id', $this->projectId);
        }

        if ($this->location) {
            $query->where('transactions.location', 'like', '%' . $this->location . '%');
        }

        if ($this->cluster) {
            $query->where('transactions.cluster', 'like', '%' . $this->cluster . '%');
        }

        $materialAnalysis = $query->groupBy('materials.id', 'materials.name', 'materials.unit', 'categories.name', 'projects.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Prepare analysis data
        $analysisData = collect();

        // Add header info
        $analysisData->push(['MATERIAL USAGE ANALYSIS']);
        $analysisData->push(['Period:', ($this->startDate ?: 'All') . ' - ' . ($this->endDate ?: 'All')]);
        $analysisData->push(['Generated:', now()->format('d/m/Y H:i:s')]);
        $analysisData->push(['']);
        $analysisData->push(['SUMMARY STATISTICS']);
        $analysisData->push(['Total Materials:', $materialAnalysis->count()]);
        $analysisData->push(['Total Quantity:', number_format($materialAnalysis->sum('total_quantity'), 2)]);
        $analysisData->push(['Total Transactions:', $materialAnalysis->sum('transaction_count')]);
        $analysisData->push(['']);
        $analysisData->push(['DETAILED ANALYSIS']);

        // Add column headers for detailed analysis
        $analysisData->push([
            'Material Name',
            'Category',
            'Unit',
            'Project',
            'Total Qty',
            'Transactions',
            'Avg Qty',
            'Min Qty',
            'Max Qty',
            'Incoming',
            'Outgoing',
            'Returns',
            'Loans',
            'Usage' // New column for pemakaian
        ]);

        // Add material data
        foreach ($materialAnalysis as $material) {
            $analysisData->push([
                $material->material_name,
                $material->category_name ?: 'Uncategorized',
                $material->unit ?: 'unit',
                $material->project_name ?: 'All Projects',
                number_format($material->total_quantity, 2),
                $material->transaction_count,
                number_format($material->avg_quantity, 2),
                number_format($material->min_quantity, 2),
                number_format($material->max_quantity, 2),
                number_format($material->incoming_qty, 2),
                number_format($material->outgoing_qty, 2),
                number_format($material->return_qty, 2),
                number_format($material->loan_qty, 2),
                number_format($material->usage_qty, 2) // New usage data
            ]);
        }

        // Add category summary
        $analysisData->push(['']);
        $analysisData->push(['CATEGORY SUMMARY']);

        $categorySummary = $materialAnalysis->groupBy('category_name')->map(function($materials) {
            return [
                'category' => $materials->first()->category_name ?: 'Uncategorized',
                'materials_count' => $materials->count(),
                'total_quantity' => $materials->sum('total_quantity'),
                'avg_quantity' => $materials->avg('total_quantity')
            ];
        })->sortByDesc('total_quantity');

        $analysisData->push(['Category', 'Materials Count', 'Total Quantity', 'Avg per Material']);
        foreach ($categorySummary as $summary) {
            $analysisData->push([
                $summary['category'],
                $summary['materials_count'],
                number_format($summary['total_quantity'], 2),
                number_format($summary['avg_quantity'], 2)
            ]);
        }

        return $analysisData;
    }

    public function headings(): array
    {
        return [
            'MATERIAL ANALYSIS REPORT',
            'VALUE'
        ];
    }

    public function title(): string
    {
        return 'Material Analysis';
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
                'color' => ['rgb' => '059669'] // Green
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Merge title cells
        $sheet->mergeCells('A1:B1');

        // Section headers styling
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            if (in_array($cellValue, ['SUMMARY STATISTICS', 'DETAILED ANALYSIS', 'CATEGORY SUMMARY'])) {
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '7C3AED'] // Purple
                    ]
                ]);
            }
        }

        // Data styling
        $sheet->getStyle('A2:B' . $highestRow)->applyFromArray([
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
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(20);

        // Add some spacing
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}