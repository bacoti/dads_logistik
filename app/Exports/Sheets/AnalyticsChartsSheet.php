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

class AnalyticsChartsSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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
        $data = collect();

        // Add chart data headers
        $data->push(['ANALYTICS & CHARTS DATA']);
        $data->push(['Generated:', now()->format('d/m/Y H:i:s')]);
        $data->push(['']);
        $data->push(['MONTHLY TREND DATA']);
        $data->push(['Month', 'Total Transactions', 'Total Materials', 'Penerimaan', 'Pengambilan', 'Pengembalian', 'Peminjaman', 'Pemakaian']);

        // Monthly trend data
        $monthlyData = $this->getMonthlyTrendData();
        foreach ($monthlyData as $monthData) {
            $data->push([
                $monthData->month,
                $monthData->total_transactions,
                $monthData->total_materials,
                $monthData->penerimaan,
                $monthData->pengambilan,
                $monthData->pengembalian,
                $monthData->peminjaman,
                $monthData->pemakaian // New type
            ]);
        }

        $data->push(['']);
        $data->push(['TOP MATERIALS DATA']);
        $data->push(['Material', 'Total Quantity', 'Transactions', 'Category']);

        // Top materials data
        $topMaterials = $this->getTopMaterialsData();
        foreach ($topMaterials as $material) {
            $data->push([
                $material->name,
                $material->quantity,
                $material->transactions,
                $material->category
            ]);
        }

        $data->push(['']);
        $data->push(['CATEGORY DISTRIBUTION']);
        $data->push(['Category', 'Total Quantity', 'Materials Count', 'Percentage']);

        // Category distribution
        $categoryData = $this->getCategoryDistribution();
        foreach ($categoryData as $category) {
            $data->push([
                $category['name'],
                $category['quantity'],
                $category['count'],
                $category['percentage'] . '%'
            ]);
        }

        $data->push(['']);
        $data->push(['PROJECT COMPARISON']);
        $data->push(['Project', 'Transactions', 'Materials', 'Avg per Transaction']);

        // Project comparison
        $projectData = $this->getProjectComparison();
        foreach ($projectData as $project) {
            $data->push([
                $project['name'],
                $project['transactions'],
                $project['materials'],
                number_format($project['avg_per_transaction'], 2)
            ]);
        }

        $data->push(['']);
        $data->push(['USAGE PATTERNS BY TYPE']);
        $data->push(['Transaction Type', 'Total Materials', 'Avg Quantity', 'Frequency']);

        // Usage patterns
        $usagePatterns = $this->getUsagePatterns();
        foreach ($usagePatterns as $pattern) {
            $data->push([
                $pattern['type'],
                $pattern['total_materials'],
                number_format($pattern['avg_quantity'], 2),
                $pattern['frequency']
            ]);
        }

        return $data;
    }

    private function getMonthlyTrendData()
    {
        $query = DB::table('transactions')
            ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(
                DB::raw('DATE_FORMAT(transactions.transaction_date, "%M %Y") as month'),
                DB::raw('COUNT(DISTINCT transactions.id) as total_transactions'),
                DB::raw('COALESCE(SUM(transaction_details.quantity), 0) as total_materials'),
                DB::raw('SUM(CASE WHEN transactions.type = "penerimaan" THEN COALESCE(transaction_details.quantity, 0) ELSE 0 END) as penerimaan'),
                DB::raw('SUM(CASE WHEN transactions.type = "pengambilan" THEN COALESCE(transaction_details.quantity, 0) ELSE 0 END) as pengambilan'),
                DB::raw('SUM(CASE WHEN transactions.type = "pengembalian" THEN COALESCE(transaction_details.quantity, 0) ELSE 0 END) as pengembalian'),
                DB::raw('SUM(CASE WHEN transactions.type = "peminjaman" THEN COALESCE(transaction_details.quantity, 0) ELSE 0 END) as peminjaman'),
                DB::raw('SUM(CASE WHEN transactions.type = "pemakaian" THEN COALESCE(transaction_details.quantity, 0) ELSE 0 END) as pemakaian') // New type
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

        return $query->groupBy('month')
            ->orderBy('transactions.transaction_date', 'desc')
            ->get()
            ->toArray();
    }

    private function getTopMaterialsData()
    {
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->leftJoin('categories', 'materials.category_id', '=', 'categories.id')
            ->select(
                'materials.name',
                DB::raw('SUM(transaction_details.quantity) as quantity'),
                DB::raw('COUNT(DISTINCT transactions.id) as transactions'),
                'categories.name as category'
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

        return $query->groupBy('materials.id', 'materials.name', 'categories.name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getCategoryDistribution()
    {
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->leftJoin('categories', 'materials.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(transaction_details.quantity) as quantity'),
                DB::raw('COUNT(DISTINCT materials.id) as count')
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

        $categories = $query->groupBy('categories.id', 'categories.name')
            ->orderBy('quantity', 'desc')
            ->get();

        $totalQuantity = $categories->sum('quantity');

        return $categories->map(function($category) use ($totalQuantity) {
            return [
                'name' => $category->name ?: 'Uncategorized',
                'quantity' => $category->quantity,
                'count' => $category->count,
                'percentage' => $totalQuantity > 0 ? round(($category->quantity / $totalQuantity) * 100, 1) : 0
            ];
        })->toArray();
    }

    private function getProjectComparison()
    {
        $query = DB::table('transactions')
            ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftJoin('projects', 'transactions.project_id', '=', 'projects.id')
            ->select(
                'projects.name',
                DB::raw('COUNT(DISTINCT transactions.id) as transactions'),
                DB::raw('COALESCE(SUM(transaction_details.quantity), 0) as materials')
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

        $projects = $query->groupBy('projects.id', 'projects.name')
            ->orderBy('materials', 'desc')
            ->get();

        return $projects->map(function($project) {
            return [
                'name' => $project->name ?: 'No Project',
                'transactions' => $project->transactions,
                'materials' => $project->materials,
                'avg_per_transaction' => $project->transactions > 0 ? $project->materials / $project->transactions : 0
            ];
        })->toArray();
    }

    private function getUsagePatterns()
    {
        $query = DB::table('transactions')
            ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(
                'transactions.type',
                DB::raw('COALESCE(SUM(transaction_details.quantity), 0) as total_materials'),
                DB::raw('COALESCE(AVG(transaction_details.quantity), 0) as avg_quantity'),
                DB::raw('COUNT(DISTINCT transactions.id) as frequency')
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

        return $query->groupBy('transactions.type')
            ->orderBy('total_materials', 'desc')
            ->get()
            ->map(function($pattern) {
                $typeLabels = [
                    'penerimaan' => '📥 Penerimaan',
                    'pengambilan' => '📤 Pengambilan',
                    'pengembalian' => '🔄 Pengembalian',
                    'peminjaman' => '📋 Peminjaman',
                    'pemakaian' => '⚡ Pemakaian Material' // New type
                ];

                return [
                    'type' => $typeLabels[$pattern->type] ?? ucfirst($pattern->type),
                    'total_materials' => $pattern->total_materials,
                    'avg_quantity' => $pattern->avg_quantity,
                    'frequency' => $pattern->frequency
                ];
            })
            ->toArray();
    }

    public function headings(): array
    {
        return [
            'ANALYTICS CHARTS DATA',
            'VALUE'
        ];
    }

    public function title(): string
    {
        return 'Analytics & Charts';
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
                'color' => ['rgb' => 'DC2626'] // Red
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
        $sectionHeaders = ['MONTHLY TREND DATA', 'TOP MATERIALS DATA', 'CATEGORY DISTRIBUTION', 'PROJECT COMPARISON', 'USAGE PATTERNS BY TYPE'];

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            if (in_array($cellValue, $sectionHeaders)) {
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'EA580C'] // Orange
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
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);

        // Add some spacing
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}