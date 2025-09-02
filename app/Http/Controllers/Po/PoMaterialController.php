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
        $query = PoMaterial::with(['project', 'subProject', 'items'])
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
            'notes' => 'nullable|string',
            // Validation untuk multiple materials
            'materials' => 'required|array|min:1',
            'materials.*.description' => 'required|string',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit' => 'required|string|max:50',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Buat PO Material utama
        $poMaterial = PoMaterial::create([
            'user_id' => $validated['user_id'],
            'po_number' => $validated['po_number'],
            'supplier' => $validated['supplier'],
            'release_date' => $validated['release_date'],
            'location' => $validated['location'],
            'project_id' => $validated['project_id'],
            'sub_project_id' => $validated['sub_project_id'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            // Set description dan quantity dari material pertama untuk kompatibilitas
            'description' => $validated['materials'][0]['description'],
            'quantity' => $validated['materials'][0]['quantity'],
            'unit' => $validated['materials'][0]['unit'],
        ]);

        // Simpan semua material items
        foreach ($validated['materials'] as $materialData) {
            $poMaterial->items()->create([
                'description' => $materialData['description'],
                'quantity' => $materialData['quantity'],
                'unit' => $materialData['unit'],
            ]);
        }

        // Kirim notifikasi ke admin
        $this->notificationService->notifyPoMaterialSubmitted($poMaterial);

        return redirect()->route('po.po-materials.index')
            ->with('success', 'PO Material dengan ' . count($validated['materials']) . ' material berhasil dibuat!');
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

        // Load items relationship
        $poMaterial->load('items');

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

    /**
     * Update status of PO Material by the PO user themselves
     */
    public function updateStatus(Request $request, PoMaterial $poMaterial)
    {
        // Ensure user can only update their own PO Materials
        if ($poMaterial->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Validate input
        $validated = $request->validate([
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Additional business logic validations
        if ($poMaterial->status !== 'pending') {
            return back()->with('error', 'Hanya PO Material dengan status "Menunggu" yang dapat diubah statusnya.');
        }

        // Update PO Material status
        $oldStatus = $poMaterial->status;

        $poMaterial->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $poMaterial->notes,
        ]);

        // Log the status change
        \Log::info('PO Material status updated by user', [
            'po_material_id' => $poMaterial->id,
            'po_number' => $poMaterial->po_number,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'timestamp' => now(),
        ]);

        // Success message based on status
        $statusMessages = [
            'approved' => 'PO Material berhasil disetujui!',
            'cancelled' => 'PO Material berhasil dibatalkan.',
        ];

        return back()->with('success', $statusMessages[$validated['status']]);
    }
}
