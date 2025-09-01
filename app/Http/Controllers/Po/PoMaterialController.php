<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\PoMaterial;
use App\Models\Project;
use App\Models\SubProject;
use App\Services\NotificationService;
use App\Notifications\MfoRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoMaterialController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PoMaterial::with(['project', 'subProject'])
            ->where('user_id', Auth::id());

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                ->orWhere('supplier', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $poMaterials = $query->orderBy('release_date', 'desc')->paginate(10);

        // Get projects for filter dropdown
        $projects = Project::all();

        return view('po.po-materials.index', compact('poMaterials', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::with('subProjects')->get();
        return view('po.po-materials.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_materials,po_number',
            'supplier' => 'required|string|max:255',
            'release_date' => 'required|date',
            'location' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'nullable|exists:sub_projects,id',
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending'; // Default status for new PO Materials

        $poMaterial = PoMaterial::create($validated);

        // Kirim notifikasi ke admin
        $this->notificationService->notifyPoMaterialSubmitted($poMaterial);

        return redirect()->route('po.po-materials.index')
            ->with('success', 'PO Material berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PoMaterial $poMaterial)
    {
        // Ensure user can only view their own PO Materials
        if ($poMaterial->user_id !== Auth::id()) {
            abort(403);
        }

        return view('po.po-materials.show', compact('poMaterial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoMaterial $poMaterial)
    {
        // Ensure user can only edit their own pending PO Materials
        if ($poMaterial->user_id !== Auth::id() || $poMaterial->status !== 'pending') {
            abort(403);
        }

        $projects = Project::with('subProjects')->get();
        return view('po.po-materials.edit', compact('poMaterial', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PoMaterial $poMaterial)
    {
        // Ensure user can only edit their own pending PO Materials
        if ($poMaterial->user_id !== Auth::id() || $poMaterial->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_materials,po_number,' . $poMaterial->id,
            'supplier' => 'required|string|max:255',
            'release_date' => 'required|date',
            'location' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'nullable|exists:sub_projects,id',
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $poMaterial->update($validated);

        return redirect()->route('po.po-materials.index')
            ->with('success', 'PO Material berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PoMaterial $poMaterial)
    {
        // Ensure user can only delete their own pending PO Materials
        if ($poMaterial->user_id !== Auth::id() || $poMaterial->status !== 'pending') {
            abort(403);
        }

        $poMaterial->delete();

        return redirect()->route('po.po-materials.index')
            ->with('success', 'PO Material berhasil dihapus!');
    }

    public function getSubProjects(Request $request)
    {
        $subProjects = SubProject::where('project_id', $request->project_id)->get();
        return response()->json($subProjects);
    }
}
