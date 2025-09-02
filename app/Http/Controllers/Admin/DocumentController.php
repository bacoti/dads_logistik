<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('user');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalDocuments = Document::count();
        $activeDocuments = Document::where('is_active', true)->count();
        $inactiveDocuments = Document::where('is_active', false)->count();
        $totalDownloads = Document::sum('download_count');

        return view('admin.documents.index', compact('documents', 'totalDocuments', 'activeDocuments', 'inactiveDocuments', 'totalDownloads'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:template,manual,form,document,other',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480', // 20MB max
            'is_active' => 'boolean'
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Store file in documents directory
            $filePath = $file->storeAs('documents', $fileName, 'public');

            Document::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'is_active' => $request->boolean('is_active', true),
                'uploaded_by' => auth()->id()
            ]);

            return redirect()->route('admin.documents.index')
                           ->with('success', 'Dokumen berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(Document $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:template,manual,form,document,other',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480',
            'is_active' => 'boolean'
        ]);

        try {
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'is_active' => $request->boolean('is_active', true),
            ];

            // If new file is uploaded
            if ($request->hasFile('file')) {
                // Delete old file
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

                $filePath = $file->storeAs('documents', $fileName, 'public');

                $updateData = array_merge($updateData, [
                    'file_name' => $fileName,
                    'original_name' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }

            $document->update($updateData);

            return redirect()->route('admin.documents.index')
                           ->with('success', 'Dokumen berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy(Document $document)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Increment download count
        $document->incrementDownload();

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    public function toggleStatus(Document $document)
    {
        try {
            $document->update(['is_active' => !$document->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Status dokumen berhasil diperbarui.',
                'is_active' => $document->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
