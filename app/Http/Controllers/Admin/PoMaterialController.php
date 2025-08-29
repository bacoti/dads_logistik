<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoMaterial;
use App\Models\User;
use App\Models\Project;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PoMaterialController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $query = PoMaterial::with(['user', 'project', 'subProject', 'approvedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                ->orWhere('supplier', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('project', function($project) use ($search) {
                    $project->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $range = $request->date_range;
            switch ($range) {
                case 'this_month':
                    $query->whereMonth('release_date', now()->month)
                          ->whereYear('release_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('release_date', now()->subMonth()->month)
                          ->whereYear('release_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('release_date', now()->year);
                    break;
            }
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $poMaterials = $query->orderBy('release_date', 'desc')->paginate(15);

        // Get users and projects for filters
        $users = User::where('role', 'po')->get();
        $projects = Project::all();

        // Statistics
        $statistics = [
            'total' => PoMaterial::count(),
            'pending' => PoMaterial::where('status', 'pending')->count(),
            'approved' => PoMaterial::where('status', 'approved')->count(),
            'rejected' => PoMaterial::where('status', 'rejected')->count(),
            'active' => PoMaterial::where('status', 'active')->count(),
            'completed' => PoMaterial::where('status', 'completed')->count(),
            'cancelled' => PoMaterial::where('status', 'cancelled')->count(),
            'total_quantity' => PoMaterial::sum('quantity'),
        ];

        return view('admin.po-materials.index', compact('poMaterials', 'statistics', 'users', 'projects'));
    }

    public function show(PoMaterial $poMaterial)
    {
        return view('admin.po-materials.show', compact('poMaterial'));
    }

    public function updateStatus(Request $request, PoMaterial $poMaterial)
    {
        // Log untuk debugging
        \Log::info('PO Material update status called', [
            'po_material_id' => $poMaterial->id,
            'po_number' => $poMaterial->po_number,
            'current_status' => $poMaterial->status,
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'timestamp' => now(),
        ]);

        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,active,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
            'confirm_action' => 'nullable|string',
        ]);

        // Cek apakah PO Material bisa diupdate
        if ($poMaterial->status !== 'pending') {
            \Log::warning('Attempt to update non-pending PO Material', [
                'po_material_id' => $poMaterial->id,
                'po_number' => $poMaterial->po_number,
                'current_status' => $poMaterial->status,
                'requested_status' => $validated['status'],
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error',
                "PO Material {$poMaterial->po_number} tidak dapat diubah statusnya karena sudah {$poMaterial->status}."
            );
        }

        $oldStatus = $poMaterial->status;
        $newStatus = $validated['status'];

        // Siapkan data untuk update - hanya kolom yang pasti ada
        $updateData = [
            'status' => $newStatus,
        ];

        // Tambahkan catatan admin jika ada dan kolom admin_notes tersedia
        if (!empty($validated['admin_notes'])) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        // Set approved_by dan approved_at untuk status approved/rejected
        if (in_array($newStatus, ['approved', 'rejected'])) {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
        }

        // Update PO Material
        $updated = $poMaterial->update($updateData);

        // Kirim notifikasi berdasarkan status
        if ($updated) {
            if ($newStatus === 'approved') {
                $this->notificationService->notifyPoMaterialApproved($poMaterial);
            } elseif ($newStatus === 'rejected') {
                $rejectionReason = $validated['admin_notes'] ?? 'Tidak ada keterangan spesifik';
                $this->notificationService->notifyPoMaterialRejected($poMaterial, $rejectionReason);
            }
        }

        // Log hasil update
        \Log::info('PO Material status updated successfully', [
            'po_material_id' => $poMaterial->id,
            'po_number' => $poMaterial->po_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_by' => auth()->id(),
            'approved_by_name' => auth()->user()->name,
            'updated_success' => $updated,
            'timestamp' => now(),
        ]);

        // Mapping status untuk pesan
        $statusTexts = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        // Buat pesan sukses yang dinamis
        $actionText = $newStatus === 'approved' ? 'menyetujui' : 'menolak';
        $statusText = $statusTexts[$newStatus] ?? $newStatus;

        $message = sprintf(
            '✅ Berhasil %s PO Material "%s". Status berubah dari "%s" menjadi "%s".',
            $actionText,
            $poMaterial->po_number,
            $statusTexts[$oldStatus] ?? $oldStatus,
            $statusText
        );

        // Tambahkan informasi tambahan ke pesan
        if (!empty($validated['admin_notes'])) {
            $message .= " Catatan: " . $validated['admin_notes'];
        }

        return redirect()->back()->with('success', $message);
    }
}
