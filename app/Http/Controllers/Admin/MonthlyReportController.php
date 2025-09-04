<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Models\Project;
use App\Models\User;
use App\Exports\MonthlyReportsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $query = MonthlyReport::with(['user', 'project', 'subProject', 'reviewer']);

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
                ->orWhere('report_period', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period')) {
            $query->where('report_period', $request->period);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('date_range')) {
            $range = $request->date_range;
            switch ($range) {
                case 'this_month':
                    $query->whereMonth('report_date', now()->month)
                          ->whereYear('report_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('report_date', now()->subMonth()->month)
                          ->whereYear('report_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('report_date', now()->year);
                    break;
            }
        }

        $reports = $query->orderBy('report_date', 'desc')->paginate(15);
        $users = User::where('role', 'user')->get();
        $projects = Project::all();

        // Statistics
        $stats = [
            'total' => MonthlyReport::count(),
            'pending' => MonthlyReport::where('status', 'pending')->count(),
            'approved' => MonthlyReport::where('status', 'approved')->count(),
            'rejected' => MonthlyReport::where('status', 'rejected')->count(),
        ];

        return view('admin.monthly-reports.index', compact('reports', 'users', 'projects', 'stats'));
    }

    public function show(MonthlyReport $monthlyReport)
    {
        return view('admin.monthly-reports.show', compact('monthlyReport'));
    }

    public function edit(MonthlyReport $monthlyReport)
    {
        $projects = Project::with('subProjects')->get();
        $users = User::where('role', 'user')->get();

        return view('admin.monthly-reports.edit', compact('monthlyReport', 'projects', 'users'));
    }

    public function update(Request $request, MonthlyReport $monthlyReport)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'report_period' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('excel_file')) {
            // Delete old file if exists
            if ($monthlyReport->excel_file_path) {
                \Storage::disk('public')->delete($monthlyReport->excel_file_path);
            }

            $file = $request->file('excel_file');
            $filename = 'monthly_report_' . $monthlyReport->user_id . '_' . $validated['report_period'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('monthly-reports', $filename, 'public');
            $validated['excel_file_path'] = $path;
        }

        $monthlyReport->update($validated);

        return redirect()->route('admin.monthly-reports.index')
            ->with('success', 'Laporan bulanan berhasil diperbarui.');
    }

    public function destroy(MonthlyReport $monthlyReport)
    {
        // Delete file if exists
        if ($monthlyReport->excel_file_path) {
            \Storage::disk('public')->delete($monthlyReport->excel_file_path);
        }

        $monthlyReport->delete();

        return redirect()->route('admin.monthly-reports.index')
            ->with('success', 'Laporan bulanan berhasil dihapus.');
    }

    public function updateStatus(Request $request, MonthlyReport $monthlyReport)
    {
        $validated = $request->validate([
            'status' => 'required|in:reviewed,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $monthlyReport->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        $statusMessages = [
            'reviewed' => 'Laporan telah ditandai sebagai reviewed.',
            'approved' => 'Laporan telah disetujui.',
            'rejected' => 'Laporan telah ditolak.'
        ];

        return redirect()->back()
            ->with('success', $statusMessages[$validated['status']]);
    }

    public function getChartData(Request $request)
    {
        try {
            \Log::info('Chart API called with params: ' . json_encode($request->all()));
            
            // Apply same filtering logic as index method
            $query = MonthlyReport::with(['user', 'project', 'subProject']);
            
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
                    ->orWhere('report_period', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('date_range')) {
                $range = $request->date_range;
                switch ($range) {
                    case 'this_month':
                        $query->whereMonth('report_date', now()->month)
                              ->whereYear('report_date', now()->year);
                        break;
                    case 'last_month':
                        $query->whereMonth('report_date', now()->subMonth()->month)
                              ->whereYear('report_date', now()->subMonth()->year);
                        break;
                    case 'this_year':
                        $query->whereYear('report_date', now()->year);
                        break;
                }
            }

            $reports = $query->get();

            // Project distribution data (top 10 projects)
            $projectReports = $reports->groupBy(function($r) {
                return optional($r->project)->name ?? 'Unknown Project';
            })->map->count()->sortDesc()->take(10);

            // Location distribution data (top 10 locations)
            $locationReports = $reports->groupBy(function($r) {
                return $r->project_location ?? 'Unknown Location';
            })->map->count()->sortDesc()->take(10);

            return response()->json([
                'projectData' => $projectReports->map(function ($count, $name) {
                    return ['name' => $name, 'count' => $count];
                })->values(),
                'locationData' => $locationReports->map(function ($count, $name) {
                    return ['name' => $name, 'count' => $count];
                })->values(),
                'summary' => [
                    'totalReports' => $reports->count(),
                    'totalUsers' => $reports->pluck('user_id')->unique()->count(),
                    'totalProjects' => $reports->pluck('project_id')->filter()->unique()->count(),
                    'filtersApplied' => collect($request->except(['period']))->filter()->count() > 0
                ]
            ]);

            \Log::info('Chart API response: ' . json_encode($response));
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Monthly Report Chart API Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat memuat data chart',
                'projectData' => [],
                'locationData' => [],
                'summary' => [
                    'totalReports' => 0,
                    'totalUsers' => 0,
                    'totalProjects' => 0,
                    'filtersApplied' => false
                ]
            ], 500);
        }
    }

    public function download(MonthlyReport $monthlyReport)
    {
        if (!$monthlyReport->excel_file_path) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        $filePath = storage_path('app/public/' . $monthlyReport->excel_file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    public function export(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('date_from');
        $endDate = $request->get('date_to');

        $fileName = 'laporan_bulanan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new MonthlyReportsExport($status, $startDate, $endDate), 
            $fileName
        );
    }
}
