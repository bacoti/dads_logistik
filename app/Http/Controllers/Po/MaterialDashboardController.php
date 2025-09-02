<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\MaterialStock;
use App\Models\MaterialTransaction;
use App\Models\MaterialAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialDashboardController extends Controller
{
    public function index()
    {
        // Summary Statistics
        $stats = [
            'total_materials' => MaterialStock::count(),
            'active_materials' => MaterialStock::where('current_stock', '>', 0)->count(),
            'low_stock_materials' => MaterialStock::lowStock()->count(),
            'out_of_stock_materials' => MaterialStock::outOfStock()->count(),
            'total_stock_value' => MaterialStock::sum('total_value'),
            'total_transactions_today' => MaterialTransaction::whereDate('transaction_date', today())->count(),
            'active_alerts' => MaterialAlert::active()->count(),
            'critical_alerts' => MaterialAlert::critical()->active()->count()
        ];

        // Material Summary dengan detail Stock Awal, Digunakan, Tersisa
        $materialSummary = MaterialStock::with(['poMaterialItem', 'poMaterialItem.transactions'])
            ->get()
            ->map(function ($stock) {
                // Total yang diterima (receipt)
                $totalReceived = $stock->poMaterialItem->transactions()
                    ->where('transaction_type', 'receipt')
                    ->sum('quantity');
                
                // Total yang digunakan (usage)
                $totalUsed = $stock->poMaterialItem->transactions()
                    ->where('transaction_type', 'usage')
                    ->sum('quantity');
                
                // Sisa stock (seharusnya sama dengan current_stock)
                $remainingStock = $totalReceived - $totalUsed;
                
                // Persentase penggunaan
                $usagePercentage = $totalReceived > 0 ? ($totalUsed / $totalReceived) * 100 : 0;
                
                return [
                    'id' => $stock->id,
                    'material_name' => $stock->material_name,
                    'material_category' => $stock->material_category,
                    'unit' => $stock->unit,
                    'total_received' => $totalReceived,
                    'total_used' => $totalUsed,
                    'remaining_stock' => $remainingStock,
                    'current_stock' => $stock->current_stock,
                    'usage_percentage' => round($usagePercentage, 1),
                    'status' => $this->getStockStatus($stock, $remainingStock),
                    'last_transaction' => $stock->last_transaction_date,
                ];
            })
            ->sortByDesc('total_received');

        // Recent Transactions (Last 10)
        $recentTransactions = MaterialTransaction::with(['poMaterialItem.materialStock', 'user', 'project'])
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        // Low Stock Materials
        $lowStockMaterials = MaterialStock::with('poMaterialItem')
            ->lowStock()
            ->limit(8)
            ->get();

        // Materials Usage This Month
        $monthlyUsage = MaterialTransaction::where('transaction_type', 'usage')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->with(['poMaterialItem.materialStock'])
            ->get()
            ->groupBy('po_material_item_id')
            ->map(function ($transactions) {
                $first = $transactions->first();
                return [
                    'material_name' => $first->poMaterialItem->materialStock->material_name ?? 'Unknown',
                    'total_used' => $transactions->sum('quantity'),
                    'unit' => $first->unit,
                    'transactions_count' => $transactions->count()
                ];
            })
            ->sortByDesc('total_used')
            ->take(10);

        // Material Receipt vs Usage Chart Data (Last 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $receipts = MaterialTransaction::where('transaction_type', 'receipt')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');
            $usage = MaterialTransaction::where('transaction_type', 'usage')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');
            
            $chartData[] = [
                'date' => $date->format('M d'),
                'receipts' => $receipts,
                'usage' => $usage
            ];
        }

        // Top Projects by Material Usage
        $topProjects = MaterialTransaction::where('transaction_type', 'usage')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->with('project')
            ->get()
            ->groupBy('project_id')
            ->map(function ($transactions) {
                $first = $transactions->first();
                return [
                    'project_name' => $first->project->name ?? 'Unknown Project',
                    'total_transactions' => $transactions->count(),
                    'materials_used' => $transactions->sum('quantity'),
                    'different_materials' => $transactions->unique('po_material_item_id')->count()
                ];
            })
            ->sortByDesc('materials_used')
            ->take(5);

        // Active Alerts
        $activeAlerts = MaterialAlert::with('materialStock')
            ->active()
            ->latest('triggered_at')
            ->limit(5)
            ->get();

        return view('po.material-dashboard.index', compact(
            'stats',
            'materialSummary',
            'recentTransactions', 
            'lowStockMaterials',
            'monthlyUsage',
            'chartData',
            'topProjects',
            'activeAlerts'
        ));
    }

    private function getStockStatus($stock, $remainingStock)
    {
        if ($remainingStock <= 0) {
            return ['label' => 'Habis', 'color' => 'red'];
        } elseif ($remainingStock <= $stock->minimum_stock) {
            return ['label' => 'Stock Rendah', 'color' => 'yellow'];
        } elseif ($remainingStock <= ($stock->minimum_stock * 2)) {
            return ['label' => 'Perhatian', 'color' => 'orange'];
        } else {
            return ['label' => 'Normal', 'color' => 'green'];
        }
    }
}
