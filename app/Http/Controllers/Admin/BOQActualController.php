<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BOQActual;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Material;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BOQActualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BOQActual::with(['material.category', 'project', 'subProject', 'user']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('material', function($subQ) use ($request) {
                    $subQ->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('dn_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('sub_project_id')) {
            $query->where('sub_project_id', $request->sub_project_id);
        }

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('usage_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('usage_date', '<=', $request->date_to);
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        // Get all data without pagination for hierarchy view
        $boqActuals = $query->get();

        // Group data by project -> cluster -> dn_number -> materials
        $hierarchyData = [];
        foreach($boqActuals as $boq) {
            $projectKey = $boq->project->id . '-' . $boq->project->name;
            $clusterKey = $boq->cluster ?: 'Tidak Ada Cluster';
            $dnKey = $boq->dn_number ?: 'Tidak Ada DN';
            
            if (!isset($hierarchyData[$projectKey])) {
                $hierarchyData[$projectKey] = [
                    'project' => $boq->project,
                    'clusters' => [],
                    'totalMaterials' => 0,
                    'totalDNs' => 0
                ];
            }
            
            if (!isset($hierarchyData[$projectKey]['clusters'][$clusterKey])) {
                $hierarchyData[$projectKey]['clusters'][$clusterKey] = [
                    'cluster_name' => $clusterKey,
                    'dns' => [],
                    'totalMaterials' => 0
                ];
            }
            
            if (!isset($hierarchyData[$projectKey]['clusters'][$clusterKey]['dns'][$dnKey])) {
                $hierarchyData[$projectKey]['clusters'][$clusterKey]['dns'][$dnKey] = [
                    'dn_number' => $dnKey,
                    'materials' => [],
                    'usage_date' => $boq->usage_date,
                    'user' => $boq->user
                ];
            }
            
            $hierarchyData[$projectKey]['clusters'][$clusterKey]['dns'][$dnKey]['materials'][] = $boq;
            $hierarchyData[$projectKey]['clusters'][$clusterKey]['totalMaterials']++;
            $hierarchyData[$projectKey]['totalMaterials']++;
        }

        // Count unique DNs per project
        foreach($hierarchyData as $projectKey => $projectData) {
            $totalDNs = 0;
            foreach($projectData['clusters'] as $clusterKey => $clusterData) {
                $totalDNs += count($clusterData['dns']);
            }
            $hierarchyData[$projectKey]['totalDNs'] = $totalDNs;
        }

        // Get filter data
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $subProjects = SubProject::select('id', 'name', 'project_id')->orderBy('name')->get();
        $clusters = BOQActual::select('cluster')->distinct()->whereNotNull('cluster')->pluck('cluster')->sort()->values();

        return view('admin.boq-actuals.index', compact(
            'boqActuals',
            'hierarchyData',
            'projects',
            'subProjects',
            'clusters'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::with('subProjects')->orderBy('name')->get();
        return view('admin.boq-actuals.create-batch', compact('projects'));
    }

    /**
     * Store batch BOQ Actual data
     */
    public function batchStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'cluster' => 'required|string|max:255',
            'dn_number' => 'required|string|max:255',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.actual_quantity' => 'required|numeric|min:0.01'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->materials as $materialData) {
                BOQActual::create([
                    'user_id' => auth()->id(),
                    'project_id' => $request->project_id,
                    'sub_project_id' => $request->sub_project_id,
                    'material_id' => $materialData['material_id'],
                    'cluster' => $request->cluster,
                    'dn_number' => $request->dn_number,
                    'actual_quantity' => $materialData['actual_quantity'],
                    'usage_date' => $request->usage_date,
                    'notes' => $request->notes
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Data BOQ Actual berhasil disimpan! Total: ' . count($request->materials) . ' material.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get materials with received quantities for batch input
     */
    public function getMaterialsWithQuantities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'cluster' => 'required|string',
            'dn_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid parameters'], 422);
        }

        try {
            // Get all materials for the sub project
            $materials = Material::where('sub_project_id', $request->sub_project_id)
                                ->with('category')
                                ->orderBy('name')
                                ->get();

            $materialsWithQuantities = [];

            foreach ($materials as $material) {
                // Get received quantity from transactions
                $receivedQuantity = DB::table('transaction_details')
                    ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                    ->where('transaction_details.material_id', $material->id)
                    ->where('transactions.type', 'penerimaan')
                    ->where('transactions.project_id', $request->project_id)
                    ->where('transactions.sub_project_id', $request->sub_project_id)
                    ->where('transactions.cluster', $request->cluster)
                    ->where('transactions.delivery_note_no', $request->dn_number)
                    ->sum('transaction_details.quantity') ?? 0;

                // Get previous BOQ actual for this material/project/cluster
                $previousActual = BOQActual::where('material_id', $material->id)
                    ->where('project_id', $request->project_id)
                    ->where('sub_project_id', $request->sub_project_id)
                    ->where('cluster', $request->cluster)
                    ->sum('actual_quantity') ?? 0;

                // Only include materials that have received quantity
                if ($receivedQuantity > 0) {
                    $materialsWithQuantities[] = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'unit' => $material->unit,
                        'category' => $material->category->name ?? 'No Category',
                        'received_quantity' => $receivedQuantity,
                        'previous_actual' => $previousActual,
                        'remaining_stock' => $receivedQuantity - $previousActual
                    ];
                }
            }

            return response()->json($materialsWithQuantities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load materials'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'material_id' => 'required|exists:materials,id',
            'cluster' => 'required|string|max:255',
            'dn_number' => 'required|string|max:255',
            'actual_quantity' => 'required|numeric|min:0',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            BOQActual::create([
                'user_id' => auth()->id(),
                'project_id' => $request->project_id,
                'sub_project_id' => $request->sub_project_id,
                'material_id' => $request->material_id,
                'cluster' => $request->cluster,
                'dn_number' => $request->dn_number,
                'actual_quantity' => $request->actual_quantity,
                'usage_date' => $request->usage_date,
                'notes' => $request->notes
            ]);

            return redirect()->route('admin.boq-actuals.index')
                           ->with('success', 'Data BOQ Actual berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BOQActual $boqActual)
    {
        $boqActual->load(['user', 'project', 'subProject', 'material.category']);
        return view('admin.boq-actuals.show', compact('boqActual'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BOQActual $boqActual)
    {
        $projects = Project::with('subProjects')->orderBy('name')->get();
        $materials = Material::where('sub_project_id', $boqActual->sub_project_id)
                            ->with('category')
                            ->orderBy('name')
                            ->get();
                            
        return view('admin.boq-actuals.edit', compact('boqActual', 'projects', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BOQActual $boqActual)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'material_id' => 'required|exists:materials,id',
            'cluster' => 'required|string|max:255',
            'dn_number' => 'required|string|max:255',
            'actual_quantity' => 'required|numeric|min:0',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $boqActual->update([
                'project_id' => $request->project_id,
                'sub_project_id' => $request->sub_project_id,
                'material_id' => $request->material_id,
                'cluster' => $request->cluster,
                'dn_number' => $request->dn_number,
                'actual_quantity' => $request->actual_quantity,
                'usage_date' => $request->usage_date,
                'notes' => $request->notes
            ]);

            return redirect()->route('admin.boq-actuals.index')
                           ->with('success', 'Data BOQ Actual berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BOQActual $boqActual)
    {
        try {
            $boqActual->delete();
            return redirect()->route('admin.boq-actuals.index')
                           ->with('success', 'Data BOQ Actual berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get sub projects by project (AJAX)
     */
    public function getSubProjects($projectId)
    {
        $subProjects = SubProject::where('project_id', $projectId)
                                ->orderBy('name')
                                ->get(['id', 'name']);
        
        return response()->json($subProjects);
    }

    /**
     * Get materials by sub project (AJAX)
     */
    public function getMaterials($subProjectId)
    {
        $materials = Material::where('sub_project_id', $subProjectId)
                            ->with('category')
                            ->orderBy('name')
                            ->get()
                            ->map(function($material) {
                                return [
                                    'id' => $material->id,
                                    'name' => $material->name,
                                    'unit' => $material->unit,
                                    'category' => $material->category->name ?? 'No Category'
                                ];
                            });

        return response()->json($materials);
    }

    /**
     * Get distinct clusters by project and sub project (AJAX)
     */
    public function getClusters(Request $request)
    {
        $query = Transaction::whereNotNull('cluster')
                           ->where('cluster', '!=', '');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('sub_project_id')) {
            $query->where('sub_project_id', $request->sub_project_id);
        }

        $clusters = $query->distinct()
                         ->orderBy('cluster')
                         ->pluck('cluster');

        return response()->json($clusters);
    }

    /**
     * Get distinct DN numbers by project, sub project, and cluster (AJAX)
     */
    public function getDNNumbers(Request $request)
    {
        $query = Transaction::whereNotNull('delivery_note_no')
                           ->where('delivery_note_no', '!=', '')
                           ->where('type', 'penerimaan'); // Hanya dari transaksi penerimaan

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('sub_project_id')) {
            $query->where('sub_project_id', $request->sub_project_id);
        }

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        $dnNumbers = $query->distinct()
                          ->orderBy('delivery_note_no')
                          ->pluck('delivery_note_no');

        return response()->json($dnNumbers);
    }

    /**
     * Summary page for material usage analysis
     */
    public function summary(Request $request)
    {
        $query = DB::table('materials')
            ->leftJoin('categories', 'materials.category_id', '=', 'categories.id')
            ->leftJoin('sub_projects', 'materials.sub_project_id', '=', 'sub_projects.id')
            ->leftJoin('projects', 'sub_projects.project_id', '=', 'projects.id')
            ->select(
                'materials.id as material_id',
                'materials.name as material_name',
                'materials.unit',
                'categories.name as category_name',
                'projects.name as project_name',
                'sub_projects.name as sub_project_name'
            );

        // Apply filters
        if ($request->filled('project_id')) {
            $query->where('projects.id', $request->project_id);
        }

        if ($request->filled('sub_project_id')) {
            $query->where('sub_projects.id', $request->sub_project_id);
        }

        $materials = $query->get();

        // Get summary data for each material
        $summaryData = [];
        foreach ($materials as $material) {
            // Total material received (DO) from transactions
            $receivedQuantity = DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->where('transaction_details.material_id', $material->material_id)
                ->where('transactions.type', 'penerimaan')
                ->when($request->filled('project_id'), function($q) use ($request) {
                    return $q->where('transactions.project_id', $request->project_id);
                })
                ->when($request->filled('sub_project_id'), function($q) use ($request) {
                    return $q->where('transactions.sub_project_id', $request->sub_project_id);
                })
                ->when($request->filled('cluster'), function($q) use ($request) {
                    return $q->where('transactions.cluster', $request->cluster);
                })
                ->sum('transaction_details.quantity') ?? 0;

            // Total BOQ Actual usage
            $actualUsage = BOQActual::where('material_id', $material->material_id)
                ->when($request->filled('project_id'), function($q) use ($request) {
                    return $q->where('project_id', $request->project_id);
                })
                ->when($request->filled('sub_project_id'), function($q) use ($request) {
                    return $q->where('sub_project_id', $request->sub_project_id);
                })
                ->when($request->filled('cluster'), function($q) use ($request) {
                    return $q->where('cluster', $request->cluster);
                })
                ->sum('actual_quantity') ?? 0;

            // Remaining stock
            $remainingStock = $receivedQuantity - $actualUsage;

            $summaryData[] = [
                'material_name' => $material->material_name,
                'category_name' => $material->category_name,
                'unit' => $material->unit,
                'project_name' => $material->project_name,
                'sub_project_name' => $material->sub_project_name,
                'received_quantity' => $receivedQuantity,
                'actual_usage' => $actualUsage,
                'remaining_stock' => $remainingStock
            ];
        }

        // Filter out materials with no data if needed
        if ($request->filled('hide_no_data') && $request->hide_no_data == '1') {
            $summaryData = array_filter($summaryData, function($item) {
                return $item['received_quantity'] > 0 || $item['actual_usage'] > 0;
            });
        }

        // Get data for filters
        $projects = Project::orderBy('name')->get();
        $subProjects = SubProject::orderBy('name')->get();
        $clusters = Transaction::whereNotNull('cluster')
                              ->where('cluster', '!=', '')
                              ->distinct()
                              ->orderBy('cluster')
                              ->pluck('cluster');

        return view('admin.boq-actuals.summary', compact('summaryData', 'projects', 'subProjects', 'clusters'));
    }
}
