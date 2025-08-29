<?php

namespace App\Http\Controllers\User;

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
    public function index()
    {
        $poTransports = PoTransport::with(['user', 'reviewer'])
            ->where('user_id', Auth::id())
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('user.po-transports.index', compact('poTransports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.po-transports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_transports,po_number',
            'document' => 'required|file|mimes:xlsx,xls|max:10240', // Only Excel files, max 10MB
            'description' => 'nullable|string|max:1000',
        ], [
            'document.required' => 'Dokumen Excel wajib diupload.',
            'document.mimes' => 'Dokumen harus berformat Excel (.xlsx atau .xls).',
            'document.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
            'po_number.required' => 'Nomor PO wajib diisi.',
            'po_number.unique' => 'Nomor PO sudah digunakan, silakan gunakan nomor lain.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['submitted_at'] = now();

        // Handle file upload
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            
            // Generate unique filename
            $filename = time() . '_' . $originalName;
            $path = $file->storeAs('po-transports', $filename, 'public');
            
            $validated['document_name'] = $originalName;
            $validated['document_path'] = $path;
        }

        PoTransport::create($validated);

        return redirect()->route('user.po-transports.index')
            ->with('success', 'PO Transport berhasil diajukan! Menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PoTransport $poTransport)
    {
        // Ensure user can only view their own requests
        if ($poTransport->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return view('user.po-transports.show', compact('poTransport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoTransport $poTransport)
    {
        // Ensure user can only edit their own pending requests
        if ($poTransport->user_id !== Auth::id() || $poTransport->status !== 'pending') {
            abort(403, 'Anda tidak dapat mengedit PO Transport ini.');
        }

        return view('user.po-transports.edit', compact('poTransport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PoTransport $poTransport)
    {
        // Ensure user can only edit their own pending requests
        if ($poTransport->user_id !== Auth::id() || $poTransport->status !== 'pending') {
            abort(403, 'Anda tidak dapat mengedit PO Transport ini.');
        }

        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_transports,po_number,' . $poTransport->id,
            'document' => 'nullable|file|mimes:xlsx,xls|max:10240',
            'description' => 'nullable|string|max:1000',
        ], [
            'document.mimes' => 'Dokumen harus berformat Excel (.xlsx atau .xls).',
            'document.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
            'po_number.required' => 'Nomor PO wajib diisi.',
            'po_number.unique' => 'Nomor PO sudah digunakan, silakan gunakan nomor lain.',
        ]);

        // Handle file upload
        if ($request->hasFile('document')) {
            // Delete old file
            if ($poTransport->document_path) {
                Storage::disk('public')->delete($poTransport->document_path);
            }
            
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            
            // Generate unique filename
            $filename = time() . '_' . $originalName;
            $path = $file->storeAs('po-transports', $filename, 'public');
            
            $validated['document_name'] = $originalName;
            $validated['document_path'] = $path;
        }

        $poTransport->update($validated);

        return redirect()->route('user.po-transports.index')
            ->with('success', 'PO Transport berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PoTransport $poTransport)
    {
        // Ensure user can only delete their own pending requests
        if ($poTransport->user_id !== Auth::id() || $poTransport->status !== 'pending') {
            abort(403, 'Anda tidak dapat menghapus PO Transport ini.');
        }

        // Delete file if exists
        if ($poTransport->document_path) {
            Storage::disk('public')->delete($poTransport->document_path);
        }

        $poTransport->delete();

        return redirect()->route('user.po-transports.index')
            ->with('success', 'PO Transport berhasil dihapus!');
    }

    /**
     * Download the document
     */
    public function download(PoTransport $poTransport)
    {
        // Ensure user can only download their own files
        if ($poTransport->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        if (!$poTransport->document_path || !Storage::disk('public')->exists($poTransport->document_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($poTransport->document_path, $poTransport->document_name);
    }
}
