<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Material;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    /**
     * Menampilkan dashboard pilihan form untuk user.
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Menampilkan form untuk membuat transaksi baru.
     */
    public function create(string $type)
    {
        // Validasi tipe form
        if (!in_array($type, ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'])) {
            abort(404);
        }

        // Ambil semua data master untuk dropdown
        $projects = Project::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();

        // Tentukan judul halaman berdasarkan tipe
        $title = match ($type) {
            'penerimaan' => 'Form Penerimaan Material',
            'pengambilan' => 'Form Pengambilan Material',
            'pengembalian' => 'Form Pengembalian Material',
            'peminjaman' => 'Form Peminjaman Material',
        };

        return view('user.transactions.create', compact('type', 'title', 'projects', 'vendors', 'locations', 'materials'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data utama
        $validated = $request->validate([
            'type' => 'required|in:penerimaan,pengambilan,pengembalian,peminjaman',
            'transaction_date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'location_id' => 'required|exists:locations,id',
            'vendor_id' => 'required_if:type,penerimaan|nullable|exists:vendors,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.document' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat record transaksi utama
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'project_id' => $validated['project_id'],
                'location_id' => $validated['location_id'],
                'vendor_id' => $validated['vendor_id'] ?? null,
                'type' => $validated['type'],
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'],
                'status' => 'pending', // Semua transaksi baru statusnya pending
            ]);

            // 2. Loop dan simpan setiap item material
            foreach ($validated['items'] as $index => $itemData) {
                $documentPath = null;
                // Cek jika ada file yang di-upload
                if ($request->hasFile("items.{$index}.document")) {
                    $file = $request->file("items.{$index}.document");
                    // Simpan file ke storage/app/public/documents
                    $documentPath = $file->store('documents', 'public');
                }

                // Buat record untuk transaction item
                $transaction->items()->create([
                    'material_id' => $itemData['material_id'],
                    'quantity' => $itemData['quantity'],
                    'document_path' => $documentPath,
                ]);
            }

            DB::commit();

            return redirect()->route('user.dashboard')->with('success', 'Transaksi berhasil diajukan dan menunggu validasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Optional: Log the error
            // Log::error('Transaction store error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.')->withInput();
        }
    }

    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('project')
            ->latest()
            ->paginate(15);

        return view('user.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Pastikan user hanya bisa melihat transaksinya sendiri
        if (Auth::id() !== $transaction->user_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        $transaction->load(['project', 'location', 'vendor', 'items.material', 'approver']);

        return view('user.transactions.show', compact('transaction'));
    }
}
