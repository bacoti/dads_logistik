<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LossReport;
use App\Exports\LossReportsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LossReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LossReport::with(['user', 'project', 'subProject', 'reviewer']);

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
                ->orWhere('material_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $range = $request->date_range;
            switch ($range) {
                case 'this_month':
                    $query->whereMonth('loss_date', now()->month)
                          ->whereYear('loss_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('loss_date', now()->subMonth()->month)
                          ->whereYear('loss_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('loss_date', now()->year);
                    break;
            }
        }

        $reports = $query->orderBy('loss_date', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => LossReport::count(),
            'pending' => LossReport::where('status', 'pending')->count(),
            'reviewed' => LossReport::where('status', 'reviewed')->count(),
            'completed' => LossReport::where('status', 'completed')->count(),
        ];

        return view('admin.loss-reports.index', compact('reports', 'stats'));
    }

    public function show(LossReport $lossReport)
    {
        return view('admin.loss-reports.show', compact('lossReport'));
    }

    public function updateStatus(Request $request, LossReport $lossReport)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $lossReport->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Status laporan kehilangan berhasil diperbarui.');
    }

    public function download(LossReport $lossReport)
    {
        if (!$lossReport->supporting_document_path || !Storage::disk('public')->exists($lossReport->supporting_document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($lossReport->supporting_document_path);
    }

    public function export(Request $request)
    {
        $status = $request->get('status');
        $startDate = $request->get('date_from');
        $endDate = $request->get('date_to');
        $projectId = $request->get('project_id');

        $fileName = 'laporan_kehilangan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new LossReportsExport($status, $startDate, $endDate, $projectId), 
            $fileName
        );
    }
}
