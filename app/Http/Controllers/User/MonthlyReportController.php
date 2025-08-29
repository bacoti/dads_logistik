<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $query = MonthlyReport::with(['project', 'subProject'])
            ->where('user_id', Auth::id());

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('project', function($project) use ($search) {
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

        return view('user.monthly-reports.index', compact('reports'));
    }

    public function create()
    {
        $projects = Project::with('subProjects')->get();

        return view('user.monthly-reports.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'report_period' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        $validated['user_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            $filename = 'monthly_report_' . Auth::id() . '_' . $validated['report_period'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('monthly-reports', $filename, 'public');
            $validated['excel_file_path'] = $path;
        }

        MonthlyReport::create($validated);

        return redirect()->route('user.monthly-reports.index')
            ->with('success', 'Laporan bulanan berhasil dibuat dan menunggu review admin.');
    }

    public function show(MonthlyReport $monthlyReport)
    {
        // Ensure user can only view their own reports
        if ($monthlyReport->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.monthly-reports.show', compact('monthlyReport'));
    }

    public function edit(MonthlyReport $monthlyReport)
    {
        // Ensure user can only edit their own reports and only if pending
        if ($monthlyReport->user_id !== Auth::id() || $monthlyReport->status !== 'pending') {
            abort(403, 'Tidak dapat mengedit laporan yang sudah direview.');
        }

        $projects = Project::with('subProjects')->get();

        return view('user.monthly-reports.edit', compact('monthlyReport', 'projects'));
    }

    public function update(Request $request, MonthlyReport $monthlyReport)
    {
        // Ensure user can only update their own reports and only if pending
        if ($monthlyReport->user_id !== Auth::id() || $monthlyReport->status !== 'pending') {
            abort(403, 'Tidak dapat mengedit laporan yang sudah direview.');
        }

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
                Storage::disk('public')->delete($monthlyReport->excel_file_path);
            }

            $file = $request->file('excel_file');
            $filename = 'monthly_report_' . Auth::id() . '_' . $validated['report_period'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('monthly-reports', $filename, 'public');
            $validated['excel_file_path'] = $path;
        }

        $monthlyReport->update($validated);

        return redirect()->route('user.monthly-reports.index')
            ->with('success', 'Laporan bulanan berhasil diperbarui.');
    }

    public function destroy(MonthlyReport $monthlyReport)
    {
        // Ensure user can only delete their own reports and only if pending
        if ($monthlyReport->user_id !== Auth::id() || $monthlyReport->status !== 'pending') {
            abort(403, 'Tidak dapat menghapus laporan yang sudah direview.');
        }

        // Delete file if exists
        if ($monthlyReport->excel_file_path) {
            Storage::disk('public')->delete($monthlyReport->excel_file_path);
        }

        $monthlyReport->delete();

        return redirect()->route('user.monthly-reports.index')
            ->with('success', 'Laporan bulanan berhasil dihapus.');
    }

    public function getSubProjects(Project $project)
    {
        return response()->json($project->subProjects);
    }
}
