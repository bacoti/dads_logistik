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
}
