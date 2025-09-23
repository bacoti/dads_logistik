<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BOQActual;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Material;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Exports\BOQSummaryMatrixExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

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
                    'actual_usage' => 0, // Start with 0 usage
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
     * Compatibility wrapper for route expecting `storeBatch`.
     */
    public function storeBatch(Request $request)
    {
        return $this->batchStore($request);
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
                'actual_usage' => 0, // Start with 0 usage
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
                // Don't update actual_usage here - it should be updated separately
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
     * Summary page for material usage analysis - Optimized version
     */
    public function summary(Request $request)
    {
        // Increase memory limit for large data processing
        ini_set('memory_limit', '256M');

        // Optimized single query approach to prevent timeout
        $summaryQuery = DB::table('materials')
            ->leftJoin('categories', 'materials.category_id', '=', 'categories.id')
            ->leftJoin('sub_projects', 'materials.sub_project_id', '=', 'sub_projects.id')
            ->leftJoin('projects', 'sub_projects.project_id', '=', 'projects.id')
            ->leftJoin('transaction_details', 'materials.id', '=', 'transaction_details.material_id')
            ->leftJoin('transactions', function($join) {
                $join->on('transaction_details.transaction_id', '=', 'transactions.id')
                     ->where('transactions.type', '=', 'penerimaan');
            })
            ->leftJoin('boq_actuals', function($join) {
                $join->on('materials.id', '=', 'boq_actuals.material_id');
            })
            ->select(
                'materials.id as material_id',
                'materials.name as material_name',
                'materials.unit',
                'categories.name as category_name',
                'projects.name as project_name',
                'sub_projects.name as sub_project_name',
                DB::raw('COALESCE(transactions.cluster, boq_actuals.cluster) as cluster'),
                DB::raw('COALESCE(transactions.delivery_note_no, boq_actuals.dn_number) as dn_number'),
                DB::raw('SUM(COALESCE(transaction_details.quantity, 0)) as received_quantity'),
                DB::raw('SUM(COALESCE(boq_actuals.actual_usage, 0)) as actual_usage'),
                DB::raw('SUM(COALESCE(boq_actuals.actual_quantity, 0)) as boq_actual_quantity')
            );

        // Apply filters
        if ($request->filled('project_id')) {
            $summaryQuery->where('projects.id', $request->project_id);
        }

        if ($request->filled('sub_project_id')) {
            $summaryQuery->where('sub_projects.id', $request->sub_project_id);
        }

        if ($request->filled('cluster')) {
            $summaryQuery->where(function($query) use ($request) {
                $query->where('transactions.cluster', $request->cluster)
                      ->orWhere('boq_actuals.cluster', $request->cluster);
            });
        }

        // Group by all necessary fields
        $summaryQuery->groupBy(
            'materials.id',
            'materials.name',
            'materials.unit',
            'categories.name',
            'projects.name',
            'sub_projects.name',
            DB::raw('COALESCE(transactions.cluster, boq_actuals.cluster)'),
            DB::raw('COALESCE(transactions.delivery_note_no, boq_actuals.dn_number)')
        );

        // Execute query and get results
        $results = $summaryQuery->get();

        // Transform results to summary data format
        $summaryData = [];
        foreach ($results as $result) {
            $receivedQuantity = (float) $result->received_quantity;
            $actualUsage = (float) $result->actual_usage;
            $boqActualQuantity = (float) $result->boq_actual_quantity;
            $remainingStock = $receivedQuantity - $actualUsage;

            // Only include if there's meaningful data or hide_no_data is not set
            $hasData = $receivedQuantity > 0 || $actualUsage > 0 || $boqActualQuantity > 0;
            $shouldInclude = $hasData || !($request->filled('hide_no_data') && $request->hide_no_data == '1');

            if ($shouldInclude) {
                $summaryData[] = [
                    'material_name' => $result->material_name,
                    'category_name' => $result->category_name,
                    'unit' => $result->unit,
                    'project_name' => $result->project_name,
                    'sub_project_name' => $result->sub_project_name,
                    'cluster' => $result->cluster ?: 'No Cluster',
                    'dn_number' => $result->dn_number ?: 'No DN',
                    'received_quantity' => $receivedQuantity,
                    'actual_usage' => $actualUsage,
                    'boq_actual_quantity' => $boqActualQuantity,
                    'remaining_stock' => $remainingStock
                ];
            }
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

    /**
     * Export summary data to Excel (Matrix format) - Super Optimized version
     */
    public function exportSummary(Request $request)
    {
        $startTime = microtime(true);

        // Get optimized configuration
        $config = getExportDatabaseConfig();

        // Maximum performance settings
        ini_set('memory_limit', $config['memory_limit']);
        set_time_limit($config['max_execution_time']);

        // Skip database optimizations that cause errors
        // optimizeDatabaseConnections(); // Commented out to avoid MySQL errors
        preloadExportData();
        optimizeMemoryForExport();

        // Cache key for query results (include user filters)
        $cacheKey = 'summary_export_' . md5(serialize($request->all()) . auth()->id());

        // Try to get cached results first (cache for 5 minutes)
        $summaryData = cache()->remember($cacheKey, $config['cache_ttl'], function() use ($request) {
            return $this->getOptimizedSummaryData($request);
        });

        // Log performance metrics
        $queryTime = microtime(true) - $startTime;
        $metrics = getExportPerformanceMetrics($startTime, count($summaryData));
        logExportPerformance('Summary Export Query', array_merge($metrics, [
            'cache_hit' => cache()->has($cacheKey),
            'query_time_ms' => round($queryTime * 1000, 2)
        ]));

        // Generate filename
        $filename = 'BOQ_Summary_Matrix_' . date('Y-m-d_H-i-s');
        if ($request->filled('project_id')) {
            $project = Project::find($request->project_id);
            if ($project) {
                $filename .= '_' . str_replace(' ', '_', sanitizeForExcel($project->name));
            }
        }
        $filename .= '.xlsx';

        // Clean data before export
        $summaryData = $this->cleanDataForExport($summaryData);

        // Log final performance metrics
        $finalMetrics = getExportPerformanceMetrics($startTime, count($summaryData));
        logExportPerformance('Summary Export Complete', array_merge($finalMetrics, [
            'filename' => $filename,
            'data_processed' => count($summaryData)
        ]));

        return Excel::download(new BOQSummaryMatrixExport($summaryData), $filename);
    }

    /**
     * Get optimized summary data using raw SQL for maximum performance
     */
    private function getOptimizedSummaryData(Request $request)
    {
        // Build dynamic WHERE clauses
        $whereConditions = [];
        $bindings = [];

        if ($request->filled('project_id')) {
            $whereConditions[] = "p.id = ?";
            $bindings[] = $request->project_id;
        }

        if ($request->filled('sub_project_id')) {
            $whereConditions[] = "sp.id = ?";
            $bindings[] = $request->sub_project_id;
        }

        if ($request->filled('cluster')) {
            $whereConditions[] = "(t.cluster = ? OR boq.cluster = ? OR tpu.cluster = ?)";
            $bindings[] = $request->cluster;
            $bindings[] = $request->cluster;
            $bindings[] = $request->cluster;
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Super optimized raw SQL query with proper GROUP BY
        $sql = "
            SELECT
                m.id as material_id,
                m.name as material_name,
                m.unit,
                c.name as category_name,
                p.name as project_name,
                sp.name as sub_project_name,
                COALESCE(t.cluster, boq.cluster, tpu.cluster, 'No Cluster') as cluster,
                COALESCE(t.delivery_note_no, boq.dn_number, tpu.delivery_note_no, 'No DN') as dn_number,
                COALESCE(SUM(td.quantity), 0) as received_quantity,
                COALESCE(SUM(tp.quantity), 0) as actual_usage,
                COALESCE(SUM(boq.actual_quantity), 0) as boq_actual_quantity
            FROM materials m
            LEFT JOIN categories c ON m.category_id = c.id
            LEFT JOIN sub_projects sp ON m.sub_project_id = sp.id
            LEFT JOIN projects p ON sp.project_id = p.id
            LEFT JOIN transaction_details td ON m.id = td.material_id
            LEFT JOIN transactions t ON td.transaction_id = t.id AND t.type = 'penerimaan'
            LEFT JOIN transaction_details tp ON m.id = tp.material_id
            LEFT JOIN transactions tpu ON tp.transaction_id = tpu.id AND tpu.type = 'pemakaian'
            LEFT JOIN boq_actuals boq ON m.id = boq.material_id
            {$whereClause}
            GROUP BY
                m.id, m.name, m.unit, c.name, p.name, sp.name,
                t.cluster, t.delivery_note_no, boq.cluster, boq.dn_number, tpu.cluster, tpu.delivery_note_no
            HAVING (COALESCE(SUM(td.quantity), 0) + COALESCE(SUM(tp.quantity), 0) + COALESCE(SUM(boq.actual_quantity), 0)) > 0
            ORDER BY p.name, sp.name, m.name
        ";

        $results = DB::select($sql, $bindings);

        // Transform to array format for better performance
        $summaryData = [];
        foreach ($results as $result) {
            $receivedQuantity = (float) $result->received_quantity;
            $actualUsage = (float) $result->actual_usage;
            $boqActualQuantity = (float) $result->boq_actual_quantity;
            $remainingStock = $receivedQuantity - $actualUsage;

            $summaryData[] = [
                'material_name' => $result->material_name,
                'category_name' => $result->category_name,
                'unit' => $result->unit,
                'project_name' => $result->project_name,
                'sub_project_name' => $result->sub_project_name,
                'cluster' => $result->cluster,
                'dn_number' => $result->dn_number,
                'received_quantity' => $receivedQuantity,
                'actual_usage' => $actualUsage,
                'boq_actual_quantity' => $boqActualQuantity,
                'remaining_stock' => $remainingStock
            ];
        }

        return $summaryData;
    }

    /**
     * Clean and optimize data for export
     */
    private function cleanDataForExport(array $summaryData): array
    {
        // First sanitize the entire array to prevent UTF-8 issues
        $summaryData = sanitizeArrayForJson($summaryData);

        return array_map(function($item) {
            return [
                'material_name' => sanitizeForSpreadsheet($item['material_name'] ?? ''),
                'category_name' => sanitizeForSpreadsheet($item['category_name'] ?? ''),
                'unit' => sanitizeForSpreadsheet($item['unit'] ?? ''),
                'project_name' => sanitizeForSpreadsheet($item['project_name'] ?? ''),
                'sub_project_name' => sanitizeForSpreadsheet($item['sub_project_name'] ?? ''),
                'cluster' => sanitizeForSpreadsheet($item['cluster'] ?? ''),
                'dn_number' => sanitizeForSpreadsheet($item['dn_number'] ?? ''),
                'received_quantity' => is_numeric($item['received_quantity'] ?? 0) ? (float)$item['received_quantity'] : 0,
                'actual_usage' => is_numeric($item['actual_usage'] ?? 0) ? (float)$item['actual_usage'] : 0,
                'boq_actual_quantity' => is_numeric($item['boq_actual_quantity'] ?? 0) ? (float)$item['boq_actual_quantity'] : 0,
                'remaining_stock' => is_numeric($item['remaining_stock'] ?? 0) ? (float)$item['remaining_stock'] : 0
            ];
        }, array_filter($summaryData, function($item) {
            return !empty($item['material_name'] ?? '') &&
                   is_string($item['material_name'] ?? '') &&
                   mb_check_encoding($item['material_name'] ?? '', 'UTF-8');
        }));
    }

    /**
     * Update actual usage for a BOQ Actual record
     */
    public function updateUsage(Request $request, BOQActual $boqActual)
    {
        $validator = Validator::make($request->all(), [
            'actual_usage' => 'required|numeric|min:0',
            'usage_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $boqActual->update([
                'actual_usage' => $request->actual_usage,
                'notes' => $request->usage_notes ?: $boqActual->notes
            ]);

            return response()->json([
                'message' => 'Pemakaian material berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
