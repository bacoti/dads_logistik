<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PoTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PoTransport::with(['user', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'LIKE', "%{$search}%")
                  ->orWhere('document_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $poTransports = $query->orderBy('submitted_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => PoTransport::count(),
            'pending' => PoTransport::where('status', 'pending')->count(),
            'approved' => PoTransport::where('status', 'approved')->count(),
            'rejected' => PoTransport::where('status', 'rejected')->count(),
        ];

        return view('admin.po-transports.index', compact('poTransports', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PoTransport $poTransport)
    {
        $poTransport->load(['user', 'reviewer']);
        return view('admin.po-transports.show', compact('poTransport'));
    }

    /**
     * Update status of the specified resource.
     */
    public function updateStatus(Request $request, PoTransport $poTransport)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,active,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ], [
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $validated['reviewed_at'] = now();
        $validated['reviewed_by'] = Auth::id();

        $poTransport->update($validated);

        $statusText = [
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'active' => 'diaktifkan',
            'completed' => 'diselesaikan',
            'cancelled' => 'dibatalkan',
        ];

        return redirect()->route('admin.po-transports.show', $poTransport)
            ->with('success', "PO Transport berhasil {$statusText[$validated['status']]}!");
    }

    /**
     * Download the document
     */
    public function download(PoTransport $poTransport)
    {
        if (!$poTransport->document_path || !Storage::disk('public')->exists($poTransport->document_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($poTransport->document_path, $poTransport->document_name);
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        // This can be implemented later with Excel export functionality
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
