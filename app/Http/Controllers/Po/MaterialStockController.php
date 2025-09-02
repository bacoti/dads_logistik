<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\MaterialStock;
use App\Models\MaterialTransaction;
use Illuminate\Http\Request;

class MaterialStockController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialStock::with(['poMaterialItem', 'poMaterialItem.transactions']);

        // Filter berdasarkan status stock
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'normal':
                    $query->whereRaw('current_stock > minimum_stock');
                    break;
            }
        }

        // Filter berdasarkan nama material
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('material_name', 'like', "%{$search}%");
        }

        $materialStocks = $query->get()->map(function ($stock) {
            // Total yang diterima (receipt)
            $totalReceived = $stock->poMaterialItem->transactions()
                ->where('transaction_type', 'receipt')
                ->sum('quantity');
            
            // Total yang digunakan (usage)
            $totalUsed = $stock->poMaterialItem->transactions()
                ->where('transaction_type', 'usage')
                ->sum('quantity');
            
            // Sisa stock
            $remainingStock = $totalReceived - $totalUsed;
            
            // Persentase penggunaan
            $usagePercentage = $totalReceived > 0 ? ($totalUsed / $totalReceived) * 100 : 0;
            
            // Transaksi terakhir
            $lastTransaction = $stock->poMaterialItem->transactions()
                ->latest('transaction_date')
                ->first();
            
            $stock->total_received = $totalReceived;
            $stock->total_used = $totalUsed;
            $stock->remaining_stock = $remainingStock;
            $stock->usage_percentage = round($usagePercentage, 1);
            $stock->last_transaction_date = $lastTransaction ? $lastTransaction->transaction_date : null;
            $stock->last_transaction_type = $lastTransaction ? $lastTransaction->transaction_type : null;
            
            return $stock;
        });

        // Apply pagination manually
        $currentPage = $request->get('page', 1);
        $perPage = 15;
        $offset = ($currentPage - 1) * $perPage;
        $materialStocksCollection = collect($materialStocks);
        $paginatedStocks = $materialStocksCollection->slice($offset, $perPage);
        $materialStocks = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedStocks,
            $materialStocksCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Summary statistics
        $stats = [
            'total_materials' => $materialStocksCollection->count(),
            'total_received' => $materialStocksCollection->sum('total_received'),
            'total_used' => $materialStocksCollection->sum('total_used'),
            'total_remaining' => $materialStocksCollection->sum('remaining_stock'),
            'low_stock' => MaterialStock::lowStock()->count(),
            'out_of_stock' => MaterialStock::outOfStock()->count(),
            'total_value' => MaterialStock::sum('total_value')
        ];

        return view('po.material-stock.index', compact('materialStocks', 'stats'));
    }

    public function show(MaterialStock $materialStock)
    {
        $materialStock->load(['poMaterialItem.poMaterial', 'transactions.user', 'transactions.project']);

        // Get recent transactions
        $recentTransactions = $materialStock->transactions()
            ->with(['user', 'project'])
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        // Get usage by project
        $usageByProject = $materialStock->transactions()
            ->where('transaction_type', 'usage')
            ->with('project')
            ->get()
            ->groupBy('project.name')
            ->map(function ($transactions) {
                return $transactions->sum('quantity');
            });

        return view('po.material-stock.show', compact('materialStock', 'recentTransactions', 'usageByProject'));
    }

    public function transactions(MaterialStock $materialStock, Request $request)
    {
        $query = $materialStock->transactions()->with(['user', 'project']);

        // Filter berdasarkan type
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->paginate(20);

        return view('po.material-stock.transactions', compact('materialStock', 'transactions'));
    }
}
