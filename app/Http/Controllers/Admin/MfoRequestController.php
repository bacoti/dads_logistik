<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MfoRequest;
use App\Exports\MfoRequestsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MfoRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MfoRequest::with(['user', 'project', 'subProject', 'reviewer']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('project', function($project) use ($search) {
                    $project->where('name', 'like', "%{$search}%");
                })
                ->orWhere('project_location', 'like', "%{$search}%")
                ->orWhere('cluster', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $range = $request->date_range;
            switch ($range) {
                case 'this_month':
                    $query->whereMonth('request_date', now()->month)
                          ->whereYear('request_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('request_date', now()->subMonth()->month)
                          ->whereYear('request_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('request_date', now()->year);
                    break;
            }
        }

        $mfoRequests = $query->orderBy('request_date', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => MfoRequest::count(),
            'pending' => MfoRequest::where('status', 'pending')->count(),
            'reviewed' => MfoRequest::where('status', 'reviewed')->count(),
            'approved' => MfoRequest::where('status', 'approved')->count(),
            'rejected' => MfoRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.mfo-requests.index', compact('mfoRequests', 'stats'));
    }

    public function show(MfoRequest $mfoRequest)
    {
        return view('admin.mfo-requests.show', compact('mfoRequest'));
    }

    public function updateStatus(Request $request, MfoRequest $mfoRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $mfoRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Status pengajuan MFO berhasil diperbarui.');
    }

    public function download(MfoRequest $mfoRequest)
    {
        if (!$mfoRequest->document_path || !Storage::disk('public')->exists($mfoRequest->document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($mfoRequest->document_path);
    }

    public function export(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('date_from');
        $endDate = $request->get('date_to');
        $projectId = $request->get('project_id');

        $fileName = 'pengajuan_mfo_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new MfoRequestsExport($status, $startDate, $endDate, $projectId), 
            $fileName
        );
    }

    public function getChartData(Request $request)
    {
        try {
            $groupBy = $request->get('group_by', 'month');
            $start = $request->get('start');
            $end = $request->get('end');
            
            // Query builder
            $query = MfoRequest::query();
            
            // Apply date filters if provided
            if ($start && $end) {
                $query->whereBetween('request_date', [$start, $end]);
            }
            
            // Group by period
            switch ($groupBy) {
                case 'day':
                    $dateFormat = '%Y-%m-%d';
                    $selectFormat = 'DATE(request_date)';
                    break;
                case 'week':
                    $dateFormat = '%Y-%u';
                    $selectFormat = 'YEARWEEK(request_date)';
                    break;
                case 'month':
                default:
                    $dateFormat = '%Y-%m-01';
                    $selectFormat = 'DATE_FORMAT(request_date, "%Y-%m-01")';
                    break;
            }
            
            $data = $query->selectRaw("$selectFormat as period, COUNT(*) as count")
                         ->groupBy('period')
                         ->orderBy('period')
                         ->get();
            
            // Format data for chart
            $chartData = $data->map(function ($item) use ($groupBy) {
                $period = $item->period;
                
                // Format period for display
                if ($groupBy === 'month') {
                    $period = date('Y-m-01', strtotime($item->period));
                } elseif ($groupBy === 'week') {
                    // Convert YEARWEEK to readable format
                    $year = substr($item->period, 0, 4);
                    $week = substr($item->period, 4);
                    $period = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
                }
                
                return [
                    'period' => $period,
                    'count' => (int) $item->count
                ];
            });
            
            return response()->json([
                'data' => $chartData,
                'group_by' => $groupBy,
                'start' => $start,
                'end' => $end
            ]);
            
        } catch (\Exception $e) {
            \Log::error('MFO Chart Data Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error loading chart data: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getChartDetails(Request $request)
    {
        try {
            $period = $request->get('period');
            $groupBy = $request->get('group_by', 'month');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            // Build query based on grouping
            $query = MfoRequest::with(['user', 'project', 'subProject']);
            
            // Filter by period
            if ($period) {
                switch ($groupBy) {
                    case 'day':
                        $query->whereDate('request_date', $period);
                        break;
                    case 'week':
                        if (strpos($period, 'W') !== false) {
                            // Parse YYYY-WXX format
                            list($year, $week) = explode('-W', $period);
                            $query->whereRaw('YEARWEEK(request_date) = ?', [$year . $week]);
                        }
                        break;
                    case 'month':
                    default:
                        $query->whereYear('request_date', date('Y', strtotime($period)))
                              ->whereMonth('request_date', date('m', strtotime($period)));
                        break;
                }
            }
            
            // Get paginated results
            $mfoRequests = $query->orderBy('request_date', 'desc')
                                ->paginate($perPage, ['*'], 'page', $page);
            
            return response()->json([
                'data' => $mfoRequests->items(),
                'pagination' => [
                    'current_page' => $mfoRequests->currentPage(),
                    'last_page' => $mfoRequests->lastPage(),
                    'per_page' => $mfoRequests->perPage(),
                    'total' => $mfoRequests->total()
                ],
                'period' => $period,
                'group_by' => $groupBy
            ]);
            
        } catch (\Exception $e) {
            \Log::error('MFO Chart Details Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error loading chart details: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
