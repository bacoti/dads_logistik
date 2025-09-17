<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MfoRequest;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MfoRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mfoRequests = MfoRequest::with(['project', 'subProject'])
            ->where('user_id', Auth::id())
            ->orderBy('request_date', 'desc')
            ->paginate(10);

        return view('user.mfo-requests.index', compact('mfoRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        // For now, we'll use projects as warehouses
        $warehouses = $projects;
        return view('user.mfo-requests.create', compact('warehouses', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'nullable|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'cluster' => 'nullable|string|max:255',
            'request_date' => 'required|date',
            'description' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Handle file upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('mfo-requests', 'public');
            $validated['document_path'] = $path;
        }

        MfoRequest::create($validated);

        return redirect()->route('user.mfo-requests.index')
            ->with('success', 'Pengajuan MFO berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MfoRequest $mfoRequest)
    {
        // Ensure user can only view their own requests
        if ($mfoRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.mfo-requests.show', compact('mfoRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MfoRequest $mfoRequest)
    {
        // Allow editing for pending and rejected requests
        if ($mfoRequest->user_id !== Auth::id() || !in_array($mfoRequest->status, ['pending', 'rejected'])) {
            abort(403, 'Anda tidak dapat mengedit pengajuan ini.');
        }

        $projects = Project::all();
        $warehouses = $projects; // Using projects as warehouses
        return view('user.mfo-requests.edit', compact('mfoRequest', 'warehouses', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MfoRequest $mfoRequest)
    {
        // Allow updating for pending and rejected requests
        if ($mfoRequest->user_id !== Auth::id() || !in_array($mfoRequest->status, ['pending', 'rejected'])) {
            abort(403, 'Anda tidak dapat mengupdate pengajuan ini.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'nullable|exists:sub_projects,id',
            'project_location' => 'required|string|max:255',
            'cluster' => 'nullable|string|max:255',
            'request_date' => 'required|date',
            'description' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Check if this is a resubmit action (for rejected requests)
        $action = $request->input('action', 'update');

        // Handle file upload
        if ($request->hasFile('document')) {
            // Delete old file
            if ($mfoRequest->document_path) {
                Storage::disk('public')->delete($mfoRequest->document_path);
            }

            $path = $request->file('document')->store('mfo-requests', 'public');
            $validated['document_path'] = $path;
        }

        // If this is a resubmit action (from rejected status), reset status to pending
        if ($action === 'resubmit' && $mfoRequest->status === 'rejected') {
            $validated['status'] = 'pending';
            $validated['admin_notes'] = null;
            $validated['reviewed_at'] = null;
            $validated['reviewed_by'] = null;
        }

        $mfoRequest->update($validated);

        $message = $action === 'resubmit'
            ? 'Pengajuan MFO berhasil diresubmit dan akan ditinjau kembali!'
            : 'Pengajuan MFO berhasil diperbarui!';

        return redirect()->route('user.mfo-requests.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MfoRequest $mfoRequest)
    {
        // Ensure user can only delete their own pending requests
        if ($mfoRequest->user_id !== Auth::id() || $mfoRequest->status !== 'pending') {
            abort(403);
        }

        // Delete file if exists
        if ($mfoRequest->document_path) {
            Storage::disk('public')->delete($mfoRequest->document_path);
        }

        $mfoRequest->delete();

        return redirect()->route('user.mfo-requests.index')
            ->with('success', 'Pengajuan MFO berhasil dihapus!');
    }

    public function download(MfoRequest $mfoRequest)
    {
        // Ensure user can only download their own files
        if ($mfoRequest->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$mfoRequest->document_path || !Storage::disk('public')->exists($mfoRequest->document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($mfoRequest->document_path);
    }

    /**
     * Resubmit a rejected MFO request with new document
     */
    public function resubmit(Request $request, MfoRequest $mfoRequest)
    {
        // Ensure user can only resubmit their own rejected requests
        if ($mfoRequest->user_id !== Auth::id() || $mfoRequest->status !== 'rejected') {
            abort(403, 'Anda tidak dapat mengajukan ulang pengajuan ini.');
        }

        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Delete old document if exists
        if ($mfoRequest->document_path) {
            Storage::disk('public')->delete($mfoRequest->document_path);
        }

        // Store new document
        $path = $request->file('document')->store('mfo-requests', 'public');

        // Update the MFO request with new document and reset status to pending
        $mfoRequest->update([
            'document_path' => $path,
            'status' => 'pending',
            'admin_notes' => null, // Clear previous admin notes
            'reviewed_at' => null, // Clear previous review time
            'reviewed_by' => null, // Clear previous reviewer
        ]);

        return redirect()->route('user.mfo-requests.index')
            ->with('success', 'Dokumen berhasil diupload ulang. Pengajuan MFO Anda akan ditinjau kembali oleh admin.');
    }

    public function getSubProjects(Request $request)
    {
        $subProjects = SubProject::where('project_id', $request->project_id)->get();
        return response()->json($subProjects);
    }
}
