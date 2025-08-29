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
        return view('user.mfo-requests.create', compact('projects'));
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
        // Ensure user can only edit their own pending requests
        if ($mfoRequest->user_id !== Auth::id() || $mfoRequest->status !== 'pending') {
            abort(403);
        }

        $projects = Project::all();
        return view('user.mfo-requests.edit', compact('mfoRequest', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MfoRequest $mfoRequest)
    {
        // Ensure user can only edit their own pending requests
        if ($mfoRequest->user_id !== Auth::id() || $mfoRequest->status !== 'pending') {
            abort(403);
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

        // Handle file upload
        if ($request->hasFile('document')) {
            // Delete old file
            if ($mfoRequest->document_path) {
                Storage::disk('public')->delete($mfoRequest->document_path);
            }
            
            $path = $request->file('document')->store('mfo-requests', 'public');
            $validated['document_path'] = $path;
        }

        $mfoRequest->update($validated);

        return redirect()->route('user.mfo-requests.index')
            ->with('success', 'Pengajuan MFO berhasil diperbarui!');
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

    public function getSubProjects(Request $request)
    {
        $subProjects = SubProject::where('project_id', $request->project_id)->get();
        return response()->json($subProjects);
    }
}
