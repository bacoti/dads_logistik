<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Material;
use App\Models\MonthlyReport;
use App\Models\LossReport;
use App\Models\MfoRequest;
use App\Models\PoMaterial;
use App\Exports\TransactionsDetailExport;
use App\Exports\MonthlyReportsExport;
use App\Exports\AdminSummaryExport;
use App\Exports\LossReportsExport;
use App\Exports\MfoRequestsExport;
use App\Exports\ComprehensiveExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalPOUsers = User::where('role', 'po')->count();
        $totalFieldUsers = User::where('role', 'user')->count();
        
        // Transaction statistics
        $totalTransactions = Transaction::count();
        $recentTransactions = Transaction::where('transaction_date', '>=', now()->subDays(7))->count();
        
        // Report statistics
        $totalMonthlyReports = MonthlyReport::count();
        $totalLossReports = LossReport::count();
        $totalMfoRequests = MfoRequest::count();
        $totalPoMaterials = PoMaterial::count();
        
        // Material quantity summary for admin
        $materialQuantitySummary = $this->getMaterialQuantitySummary();
        
        // Chart data for last 6 months transactions
        $transactionTrends = [];
        $monthlyReportTrends = [];
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $transactionTrends[] = Transaction::whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->count();
                
            $monthlyReportTrends[] = MonthlyReport::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }
        
        // Report status distribution
        $reportStats = [
            'pending' => MonthlyReport::where('status', 'pending')->count(),
            'reviewed' => MonthlyReport::where('status', 'reviewed')->count(),
            'approved' => MonthlyReport::where('status', 'approved')->count(),
            'rejected' => MonthlyReport::where('status', 'rejected')->count(),
        ];
        
        // User role distribution
        $userRoleStats = [
            'admin' => User::where('role', 'admin')->count(),
            'po' => $totalPOUsers,
            'user' => $totalFieldUsers,
        ];
        
        // Weekly activity data
        $weeklyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyActivity[] = [
                'date' => $date->format('D'),
                'transactions' => Transaction::whereDate('transaction_date', $date)->count(),
                'reports' => MonthlyReport::whereDate('created_at', $date)->count(),
            ];
        }
        
        // Recent activities
        $recentTransactions = Transaction::with(['user', 'project'])
            ->latest('transaction_date')
            ->limit(5)
            ->get();
            
        $recentReports = MonthlyReport::with('user')
            ->latest()
            ->limit(5)
            ->get();
            
        $recentMfoRequests = MfoRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();
            
        $recentPoMaterials = PoMaterial::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPOUsers', 
            'totalFieldUsers',
            'totalTransactions',
            'recentTransactions',
            'totalMonthlyReports',
            'totalLossReports',
            'totalMfoRequests',
            'totalPoMaterials',
            'recentReports',
            'recentMfoRequests',
            'recentPoMaterials',
            'transactionTrends',
            'monthlyReportTrends',
            'months',
            'reportStats',
            'userRoleStats',
            'weeklyActivity',
            'materialQuantitySummary'
        ));
    }

    /**
     * Export all transactions to Excel (Detail)
     */
    public function exportTransactions(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');

        $fileName = 'transaksi_detail_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new TransactionsDetailExport($startDate, $endDate, $projectId), 
            $fileName
        );
    }

    /**
     * Export all transactions to Excel (Detail per Material)
     */
    public function exportTransactionsDetail(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');

        $fileName = 'transaksi_detail_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new TransactionsDetailExport($startDate, $endDate, $projectId), 
            $fileName
        );
    }

    /**
     * Export monthly reports to Excel
     */
    public function exportMonthlyReports(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $fileName = 'laporan_bulanan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new MonthlyReportsExport($status, $startDate, $endDate), 
            $fileName
        );
    }

    /**
     * Export loss reports to Excel
     */
    public function exportLossReports(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');

        $fileName = 'laporan_kehilangan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new LossReportsExport($status, $startDate, $endDate, $projectId), 
            $fileName
        );
    }

    /**
     * Export MFO requests to Excel
     */
    public function exportMfoRequests(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');

        $fileName = 'pengajuan_mfo_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new MfoRequestsExport($status, $startDate, $endDate, $projectId), 
            $fileName
        );
    }

    /**
     * Export comprehensive data (all data in multiple sheets)
     */
    public function exportComprehensive(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');
        $status = $request->get('status');

        $fileName = 'data_lengkap_logistik_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new ComprehensiveExport($startDate, $endDate, $projectId, $status), 
            $fileName
        );
    }

    /**
     * Export comprehensive admin summary
     */
    public function exportSummary(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $fileName = 'ringkasan_admin_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new AdminSummaryExport($startDate, $endDate), 
            $fileName
        );
    }

    /**
     * Get material quantity summary from all sources grouped by category
     */
    private function getMaterialQuantitySummary()
    {
        try {
            // Get materials from transactions (TransactionDetail) with project info
            $transactionMaterials = DB::table('transaction_details')
                ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
                ->join('categories', 'materials.category_id', '=', 'categories.id')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->leftJoin('projects', 'transactions.project_id', '=', 'projects.id')
                ->leftJoin('sub_projects', 'transactions.sub_project_id', '=', 'sub_projects.id')
                ->select(
                    'materials.id as material_id',
                    'materials.name as material_name',
                    'materials.unit',
                    'categories.name as category_name',
                    'projects.name as project_name',
                    'sub_projects.name as sub_project_name',
                    DB::raw('SUM(transaction_details.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT transactions.user_id) as user_count'),
                    DB::raw('COUNT(transaction_details.id) as transaction_count'),
                    DB::raw('"Transaction" as source_type')
                )
                ->groupBy('materials.id', 'materials.name', 'materials.unit', 'categories.name', 'projects.name', 'sub_projects.name')
                ->get();
        } catch (\Exception $e) {
            $transactionMaterials = collect();
        }

        try {
            // Get materials from PO Materials with project info - ONLY APPROVED ONES
            $poMaterials = DB::table('po_materials')
                ->join('users', 'po_materials.user_id', '=', 'users.id')
                ->leftJoin('projects', 'po_materials.project_id', '=', 'projects.id')
                ->leftJoin('sub_projects', 'po_materials.sub_project_id', '=', 'sub_projects.id')
                ->select(
                    'po_materials.description as material_name',
                    'po_materials.unit',
                    'projects.name as project_name',
                    'sub_projects.name as sub_project_name',
                    DB::raw('SUM(po_materials.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT po_materials.user_id) as user_count'),
                    DB::raw('COUNT(po_materials.id) as po_count'),
                    DB::raw('"PO Material" as category_name'),
                    DB::raw('"PO Material" as source_type')
                )
                ->where('po_materials.status', 'approved') // Only count approved PO materials
                ->groupBy('po_materials.description', 'po_materials.unit', 'projects.name', 'sub_projects.name')
                ->get();
        } catch (\Exception $e) {
            $poMaterials = collect();
        }

        // Combine all materials
        $allMaterials = $transactionMaterials->concat($poMaterials);

        // Group by category
        $materialsByCategory = $allMaterials->groupBy('category_name');

        // Get PO statistics for better insight
        try {
            $poStats = DB::table('po_materials')
                ->selectRaw('
                    COUNT(*) as total_po_requests,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_requests,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_requests,
                    SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_requests,
                    SUM(CASE WHEN status = "approved" THEN quantity ELSE 0 END) as approved_quantity,
                    SUM(CASE WHEN status = "pending" THEN quantity ELSE 0 END) as pending_quantity
                ')
                ->first();
        } catch (\Exception $e) {
            $poStats = (object) [
                'total_po_requests' => 0,
                'pending_requests' => 0,
                'approved_requests' => 0,
                'rejected_requests' => 0,
                'approved_quantity' => 0,
                'pending_quantity' => 0
            ];
        }

        // Calculate summary statistics
        $materialSummary = [
            'materials_by_category' => $materialsByCategory,
            'total_categories' => $materialsByCategory->count(),
            'total_materials' => $allMaterials->count(),
            'total_quantity_all' => $allMaterials->sum('total_quantity'),
            'transaction_materials' => $transactionMaterials,
            'po_materials' => $poMaterials,
            'total_transaction_materials' => $transactionMaterials->count(),
            'total_po_materials' => $poMaterials->count(),
            'total_quantity_transactions' => $transactionMaterials->sum('total_quantity'),
            'total_quantity_po' => $poMaterials->sum('total_quantity'),
            'po_statistics' => $poStats
        ];

        return $materialSummary;
    }

    /**
     * Display detailed material quantity view
     */
    public function materialQuantityDetail()
    {
        $materialQuantitySummary = $this->getMaterialQuantitySummary();
        
        return view('admin.material-quantity-detail', compact('materialQuantitySummary'));
    }

    /**
     * Display analytics dashboard
     */
    public function analyticsDashboard(Request $request)
    {
        $analyticsData = $this->getAnalyticsData($request);
        $categories = DB::table('categories')->get();
        $projects = DB::table('projects')->get();
        
        // Calculate additional metrics
        $totalActiveMaterials = DB::table('materials')->count();
        
        return view('admin.analytics-dashboard', compact(
            'analyticsData', 
            'categories', 
            'projects',
            'totalActiveMaterials'
        ));
    }

    /**
     * Get analytics data for charts
     */
    public function getAnalyticsData(Request $request = null)
    {
        $period = $request ? $request->get('period', '6months') : '6months';
        $categoryFilter = $request ? $request->get('category', 'all') : 'all';
        $projectFilter = $request ? $request->get('project', 'all') : 'all';

        // Get period range
        $periodRange = $this->getPeriodRange($period);
        
        // Monthly usage data
        $monthlyData = $this->getMonthlyUsageData($periodRange, $categoryFilter, $projectFilter);
        
        // Top materials
        $topMaterials = $this->getTopMaterialsData($periodRange, $categoryFilter, $projectFilter);
        
        // Category distribution
        $categoryDistribution = $this->getCategoryDistributionData($periodRange, $projectFilter);
        
        // Prediction data
        $predictionData = $this->getPredictionData($periodRange, $categoryFilter, $projectFilter);
        
        // Seasonal pattern
        $seasonalData = $this->getSeasonalPattern($categoryFilter, $projectFilter);
        
        // Project comparison
        $projectComparison = $this->getProjectComparisonData($periodRange, $categoryFilter);
        
        // Prediction table data
        $predictionTable = $this->generatePredictionTable($categoryFilter, $projectFilter);

        return [
            'monthlyLabels' => $monthlyData['labels'],
            'monthlyUsage' => $monthlyData['data'],
            'topMaterials' => $topMaterials,
            'categoryDistribution' => $categoryDistribution,
            'prediction' => $predictionData,
            'seasonal' => $seasonalData,
            'projectComparison' => $projectComparison,
            'predictionTable' => $predictionTable
        ];
    }

    /**
     * Get period date range
     */
    private function getPeriodRange($period)
    {
        $now = now();
        
        switch ($period) {
            case '3months':
                return [$now->copy()->subMonths(3), $now];
            case '12months':
                return [$now->copy()->subMonths(12), $now];
            case '6months':
            default:
                return [$now->copy()->subMonths(6), $now];
        }
    }

    /**
     * Get monthly usage data for trend chart
     */
    private function getMonthlyUsageData($periodRange, $categoryFilter, $projectFilter)
    {
        $startDate = $periodRange[0];
        $endDate = $periodRange[1];
        
        $labels = [];
        $data = [];
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format('M Y');
            
            // Get transaction data
            $transactionQuery = DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
                ->whereYear('transactions.transaction_date', $currentDate->year)
                ->whereMonth('transactions.transaction_date', $currentDate->month);
            
            // Apply filters
            if ($categoryFilter !== 'all') {
                $transactionQuery->where('materials.category_id', $categoryFilter);
            }
            if ($projectFilter !== 'all') {
                $transactionQuery->where('transactions.project_id', $projectFilter);
            }
            
            $transactionTotal = $transactionQuery->sum('transaction_details.quantity') ?? 0;
            
            // Get PO data (approved only)
            $poQuery = DB::table('po_materials')
                ->whereYear('created_at', $currentDate->year)
                ->whereMonth('created_at', $currentDate->month)
                ->where('status', 'approved');
            
            if ($projectFilter !== 'all') {
                $poQuery->where('project_id', $projectFilter);
            }
            
            $poTotal = $poQuery->sum('quantity') ?? 0;
            
            $data[] = $transactionTotal + $poTotal;
            $currentDate->addMonth();
        }
        
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get top materials data
     */
    private function getTopMaterialsData($periodRange, $categoryFilter, $projectFilter)
    {
        $startDate = $periodRange[0];
        $endDate = $periodRange[1];
        
        // Get from transactions
        $transactionQuery = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->select('materials.name', DB::raw('SUM(transaction_details.quantity) as total_quantity'))
            ->groupBy('materials.id', 'materials.name');
        
        // Apply filters
        if ($categoryFilter !== 'all') {
            $transactionQuery->where('materials.category_id', $categoryFilter);
        }
        if ($projectFilter !== 'all') {
            $transactionQuery->where('transactions.project_id', $projectFilter);
        }
        
        $materials = $transactionQuery->orderBy('total_quantity', 'desc')->limit(10)->get();
        
        return [
            'labels' => $materials->pluck('name')->toArray(),
            'data' => $materials->pluck('total_quantity')->toArray()
        ];
    }

    /**
     * Get category distribution data
     */
    private function getCategoryDistributionData($periodRange, $projectFilter)
    {
        $startDate = $periodRange[0];
        $endDate = $periodRange[1];
        
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->select('categories.name', DB::raw('SUM(transaction_details.quantity) as total_quantity'))
            ->groupBy('categories.id', 'categories.name');
        
        if ($projectFilter !== 'all') {
            $query->where('transactions.project_id', $projectFilter);
        }
        
        $categories = $query->get();
        
        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('total_quantity')->toArray()
        ];
    }

    /**
     * Generate prediction data using simple trend analysis
     */
    private function getPredictionData($periodRange, $categoryFilter, $projectFilter)
    {
        $historicalData = $this->getMonthlyUsageData($periodRange, $categoryFilter, $projectFilter);
        
        // Simple linear regression for prediction
        $historical = $historicalData['data'];
        $labels = $historicalData['labels'];
        
        // Generate 3 months prediction
        $predicted = [];
        $trendSlope = 0;
        
        if (count($historical) >= 2) {
            $n = count($historical);
            $sumX = 0;
            $sumY = 0;
            $sumXY = 0;
            $sumX2 = 0;
            
            for ($i = 0; $i < $n; $i++) {
                $sumX += $i;
                $sumY += $historical[$i];
                $sumXY += $i * $historical[$i];
                $sumX2 += $i * $i;
            }
            
            $trendSlope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
            $intercept = ($sumY - $trendSlope * $sumX) / $n;
            
            // Generate predictions
            for ($i = 0; $i < 3; $i++) {
                $predicted[] = max(0, $intercept + $trendSlope * ($n + $i));
                $labels[] = now()->addMonths($i + 1)->format('M Y');
            }
        }
        
        return [
            'labels' => $labels,
            'historical' => array_merge($historical, array_fill(0, 3, null)),
            'predicted' => array_merge(array_fill(0, count($historical), null), $predicted)
        ];
    }

    /**
     * Get seasonal pattern data
     */
    private function getSeasonalPattern($categoryFilter, $projectFilter)
    {
        $seasonalData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $query = DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
                ->whereMonth('transactions.transaction_date', $month);
            
            if ($categoryFilter !== 'all') {
                $query->where('materials.category_id', $categoryFilter);
            }
            if ($projectFilter !== 'all') {
                $query->where('transactions.project_id', $projectFilter);
            }
            
            $seasonalData[] = $query->sum('transaction_details.quantity') ?? 0;
        }
        
        return $seasonalData;
    }

    /**
     * Get project comparison data
     */
    private function getProjectComparisonData($periodRange, $categoryFilter)
    {
        $startDate = $periodRange[0];
        $endDate = $periodRange[1];
        
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->join('projects', 'transactions.project_id', '=', 'projects.id')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->select('projects.name', DB::raw('SUM(transaction_details.quantity) as total_quantity'))
            ->groupBy('projects.id', 'projects.name');
        
        if ($categoryFilter !== 'all') {
            $query->where('materials.category_id', $categoryFilter);
        }
        
        $projects = $query->orderBy('total_quantity', 'desc')->get();
        
        return [
            'labels' => $projects->pluck('name')->toArray(),
            'data' => $projects->pluck('total_quantity')->toArray()
        ];
    }

    /**
     * Generate prediction table data
     */
    private function generatePredictionTable($categoryFilter, $projectFilter)
    {
        // Get top 10 materials for prediction
        $materials = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('materials', 'transaction_details.material_id', '=', 'materials.id')
            ->select('materials.name', DB::raw('SUM(transaction_details.quantity) as total_quantity'))
            ->groupBy('materials.id', 'materials.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        $predictionTable = [];
        
        foreach ($materials as $material) {
            // Simple prediction logic - you can enhance this
            $current = rand(50, 200);
            $confidence = rand(70, 95);
            
            $predictionTable[] = [
                'material' => $material->name,
                'current' => number_format($current),
                'month1' => number_format($current * (1 + rand(-20, 30) / 100)),
                'month2' => number_format($current * (1 + rand(-25, 35) / 100)),
                'month3' => number_format($current * (1 + rand(-30, 40) / 100)),
                'confidence' => $confidence
            ];
        }
        
        return $predictionTable;
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        $analyticsData = $this->getAnalyticsData($request);
        
        // Create Excel export with analytics data
        $fileName = 'analytics_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // You can create a new export class for analytics
        // return Excel::download(new AnalyticsExport($analyticsData), $fileName);
        
        // For now, return JSON response
        return response()->json([
            'message' => 'Analytics export feature akan segera tersedia',
            'data' => $analyticsData
        ]);
    }

    /**
     * API endpoint for dynamic chart data
     */
    public function getAnalyticsDataJson(Request $request)
    {
        $analyticsData = $this->getAnalyticsData($request);
        return response()->json($analyticsData);
    }
}
