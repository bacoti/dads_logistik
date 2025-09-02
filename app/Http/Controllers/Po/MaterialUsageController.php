<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\MaterialStock;
use App\Models\MaterialTransaction;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialUsageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil history penggunaan material
        $usageTransactions = MaterialTransaction::with(['poMaterialItem.materialStock', 'project', 'user'])
            ->where('transaction_type', 'usage')
            ->latest('transaction_date')
            ->paginate(15);

        // Stats untuk dashboard
        $stats = [
            'total_usage_transactions' => MaterialTransaction::where('transaction_type', 'usage')->count(),
            'today_usage_transactions' => MaterialTransaction::where('transaction_type', 'usage')
                ->whereDate('transaction_date', today())->count(),
            'this_month_usage' => MaterialTransaction::where('transaction_type', 'usage')
                ->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year)
                ->sum('quantity'),
            'active_projects_with_usage' => MaterialTransaction::where('transaction_type', 'usage')
                ->whereMonth('transaction_date', now()->month)
                ->distinct('project_id')
                ->count('project_id')
        ];

        // Recent usage activities
        $todayUsages = MaterialTransaction::with(['poMaterialItem.materialStock', 'project', 'user'])
            ->where('transaction_type', 'usage')
            ->whereDate('transaction_date', today())
            ->latest('created_at')
            ->get();

        return view('po.material-usage.index', compact('usageTransactions', 'stats', 'todayUsages'));
    }

    public function create()
    {
        // Ambil material stocks yang tersedia
        $materialStocks = MaterialStock::with('poMaterialItem')
            ->where('current_stock', '>', 0)
            ->get();

        // Ambil projects untuk dropdown
        $projects = Project::with('subProjects')->get();

        return view('po.material-usage.create', compact('materialStocks', 'projects'));
    }

    public function store(Request $request)
    {
        // Debug: Log received data
        \Log::info('Material Usage Store Request:', $request->all());

        $request->validate([
            'usage_date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'activity_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string|max:1000',
            'materials' => 'required|array|min:1',
            'materials.*.material_stock_id' => 'required|exists:material_stocks,id',
            'materials.*.quantity_used' => 'required|numeric|min:0.01',
            'materials.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->materials as $materialData) {
                    $materialStock = MaterialStock::findOrFail($materialData['material_stock_id']);
                    
                    // Validasi stock tersedia
                    if ($materialData['quantity_used'] > $materialStock->current_stock) {
                        throw new \Exception("Quantity untuk {$materialStock->material_name} melebihi stock tersedia ({$materialStock->current_stock} {$materialStock->unit})");
                    }

                    // Buat transaction usage
                    MaterialTransaction::create([
                        'po_material_id' => $materialStock->poMaterialItem->po_material_id,
                        'po_material_item_id' => $materialStock->po_material_item_id,
                        'transaction_type' => 'usage',
                        'quantity' => $materialData['quantity_used'],
                        'unit' => $materialStock->unit,
                        'transaction_date' => $request->usage_date,
                        'user_id' => Auth::id(),
                        'project_id' => $request->project_id,
                        'activity_name' => $request->activity_name,
                        'pic_name' => $request->pic_name,
                        'location' => $request->location,
                        'notes' => $materialData['notes'] ?? $request->general_notes,
                        'condition' => 'good'
                    ]);

                    // Update stock
                    $materialStock->updateStock();
                }
            });

            return redirect()->route('po.material-usage.index')
                ->with('success', 'Penggunaan material berhasil dicatat!');

        } catch (\Exception $e) {
            \Log::error('Material Usage Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(MaterialTransaction $transaction)
    {
        $transaction->load(['poMaterialItem.materialStock', 'project', 'user']);
        
        return view('po.material-usage.show', compact('transaction'));
    }
}
