<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LossReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LossReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LossReport::with(['project', 'subProject'])
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('project', function($project) use ($search) {
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

        return view('user.loss-reports.index', compact('reports'));
    }

    public function create()
    {
        $projects = Project::with('subProjects')->get();

        return view('user.loss-reports.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'cluster' => 'required|string|max:255',
            'loss_date' => 'required|date',
            'material_type' => 'required|string',
            'loss_chronology' => 'required|string',
            'additional_notes' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ]);

        $validated['user_id'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('supporting_document')) {
            $path = $request->file('supporting_document')->store('loss-reports', 'public');
            $validated['supporting_document_path'] = $path;
        }

        LossReport::create($validated);

        return redirect()->route('user.loss-reports.index')
            ->with('success', 'Laporan kehilangan berhasil dibuat dan akan segera ditinjau oleh admin.');
    }

    public function show(LossReport $lossReport)
    {
        // Ensure user can only view their own reports
        if ($lossReport->user_id !== auth()->id()) {
            abort(403);
        }

        // Load relationships
        $lossReport->load(['project', 'subProject', 'user', 'reviewer']);

        return view('user.loss-reports.show', compact('lossReport'));
    }

    public function edit(LossReport $lossReport)
    {
        // Ensure user can only edit their own reports and only if pending
        if ($lossReport->user_id !== auth()->id() || $lossReport->status !== 'pending') {
            abort(403);
        }

        $projects = Project::with('subProjects')->get();

        return view('user.loss-reports.edit', compact('lossReport', 'projects'));
    }

    public function update(Request $request, LossReport $lossReport)
    {
        // Ensure user can only update their own reports and only if pending
        if ($lossReport->user_id !== auth()->id() || $lossReport->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'cluster' => 'required|string|max:255',
            'loss_date' => 'required|date',
            'material_type' => 'required|string',
            'loss_chronology' => 'required|string',
            'additional_notes' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('supporting_document')) {
            // Delete old file if exists
            if ($lossReport->supporting_document_path) {
                Storage::disk('public')->delete($lossReport->supporting_document_path);
            }

            $path = $request->file('supporting_document')->store('loss-reports', 'public');
            $validated['supporting_document_path'] = $path;
        }

        $lossReport->update($validated);

        return redirect()->route('user.loss-reports.index')
            ->with('success', 'Laporan kehilangan berhasil diperbarui.');
    }

    public function destroy(LossReport $lossReport)
    {
        // Ensure user can only delete their own reports and only if pending
        if ($lossReport->user_id !== auth()->id() || $lossReport->status !== 'pending') {
            abort(403);
        }

        // Delete associated file
        if ($lossReport->supporting_document_path) {
            Storage::disk('public')->delete($lossReport->supporting_document_path);
        }

        $lossReport->delete();

        return redirect()->route('user.loss-reports.index')
            ->with('success', 'Laporan kehilangan berhasil dihapus.');
    }

    public function download(LossReport $lossReport)
    {
        // Ensure user can only download their own reports
        if ($lossReport->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$lossReport->supporting_document_path || !Storage::disk('public')->exists($lossReport->supporting_document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($lossReport->supporting_document_path);
    }
}
