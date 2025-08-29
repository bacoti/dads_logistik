<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::where('is_active', true)->with('uploader');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('file_type')) {
            $query->where('file_type', 'like', '%' . $request->file_type . '%');
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(12);

        // Statistics
        $stats = [
            'total' => Document::where('is_active', true)->count(),
            'templates' => Document::where('is_active', true)->where('category', 'template')->count(),
            'manuals' => Document::where('is_active', true)->where('category', 'manual')->count(),
            'forms' => Document::where('is_active', true)->where('category', 'form')->count(),
        ];

        return view('user.documents.index', compact('documents', 'stats'));
    }

    public function download(Document $document)
    {
        // Check if document is active
        if (!$document->is_active) {
            return back()->with('error', 'Dokumen tidak tersedia untuk didownload.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Increment download count
        $document->incrementDownload();

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }
}
