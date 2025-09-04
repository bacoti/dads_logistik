<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use App\Notifications\TransactionCreated;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['vendor', 'project', 'subProject', 'details.material'])
            ->latest()
            ->paginate(15);

        return view('user.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'penerimaan');

        // Validasi tipe transaksi
        if (!in_array($type, ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'])) {
            abort(404);
        }

        $projects = Project::orderBy('name')->get();

        return view('user.transactions.create', compact('type', 'projects'));
    }

    /**
     * Get sub projects by project ID (AJAX)
     */
    public function getSubProjects($projectId)
    {
        $subProjects = SubProject::where('project_id', $projectId)->orderBy('name')->get();
        return response()->json($subProjects);
    }

    /**
     * Get materials by project and sub project (AJAX)
     */
    public function getMaterialsByProject(Request $request, $projectId, $subProjectId = null)
    {
        // Jika sub project ID disediakan, ambil material berdasarkan sub project
        if ($subProjectId) {
            $materials = Material::with('category')
                ->where('sub_project_id', $subProjectId)
                ->orderBy('name')
                ->get()
                ->groupBy('category.name');
        } else {
            // Fallback: ambil semua material dari project tersebut
            $subProjectIds = SubProject::where('project_id', $projectId)->pluck('id');
            $materials = Material::with('category')
                ->whereIn('sub_project_id', $subProjectIds)
                ->orderBy('name')
                ->get()
                ->groupBy('category.name');
        }

        return response()->json($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'type' => 'required|in:penerimaan,pengambilan,pengembalian,peminjaman',
            'transaction_date' => 'required|date',
            'vendor_id' => 'nullable|exists:vendors,id',
            'vendor_name' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'location' => 'required|string|max:255',
            'cluster' => 'nullable|string|max:255',
            'site_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'proof_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'materials' => 'required|array|min:1',
            'return_destination' => 'nullable|string|max:255',
            'delivery_return_no' => 'nullable|string|max:255',
        ];

        // Tambahan validasi untuk transaksi penerimaan
        if ($request->type === 'penerimaan') {
            $validationRules['delivery_order_no'] = 'required|string|max:255';
            $validationRules['delivery_note_no'] = 'required|string|max:255';
        } else {
            $validationRules['delivery_order_no'] = 'nullable|string|max:255';
            $validationRules['delivery_note_no'] = 'nullable|string|max:255';
        }

        // Validasi tujuan pengembalian untuk transaksi pengembalian
        if ($request->type === 'pengembalian') {
            $validationRules['return_destination'] = 'required|string|max:255';
        }

        $request->validate($validationRules);

        // Wrap dalam database transaction untuk data integrity
        \DB::beginTransaction();
        try {
            // Upload file bukti jika ada
            $proofPath = null;
            if ($request->hasFile('proof_path')) {
                try {
                    $proofPath = $request->file('proof_path')->store('transaction-proofs', 'public');
                } catch (\Exception $e) {
                    throw new \Exception('Gagal mengupload file bukti: ' . $e->getMessage());
                }
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'type' => $request->type,
                'transaction_date' => $request->transaction_date,
                'vendor_id' => $request->vendor_id,
                'vendor_name' => $request->vendor_name,
                'project_id' => $request->project_id,
                'sub_project_id' => $request->sub_project_id,
                'location' => $request->location,
                'cluster' => $request->cluster,
                'site_id' => $request->site_id,
                'notes' => $request->notes,
                'proof_path' => $proofPath,
                'delivery_order_no' => $request->delivery_order_no,
                'delivery_note_no' => $request->delivery_note_no,
                'delivery_return_no' => $request->delivery_return_no,
                'return_destination' => $request->return_destination,
            ]);

            // Validasi dan simpan detail material
            $materialCount = 0;
            foreach ($request->materials as $materialData) {
                if (isset($materialData['material_id']) && isset($materialData['quantity']) && $materialData['quantity'] > 0) {
                    $transaction->details()->create([
                        'material_id' => $materialData['material_id'],
                        'quantity' => $materialData['quantity'],
                    ]);
                    $materialCount++;
                }
            }

            // Pastikan ada material yang disimpan
            if ($materialCount === 0) {
                throw new \Exception('Tidak ada material yang valid untuk disimpan');
            }

            // Kirim notifikasi menggunakan service
            try {
                $this->notificationService->notifyTransactionCreated($transaction);
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan transaksi
                \Log::warning('Gagal mengirim notifikasi transaksi: ' . $e->getMessage(), [
                    'transaction_id' => $transaction->id,
                    'user_id' => auth()->id()
                ]);
            }

            \DB::commit();
            return redirect()->route('user.dashboard')->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Pastikan user hanya bisa melihat transaksi miliknya
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->load(['vendor', 'project', 'subProject', 'details.material.category']);

        return view('user.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // Pastikan user hanya bisa edit transaksi miliknya
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $projects = Project::orderBy('name')->get();
        $categories = Category::with('materials')->orderBy('name')->get();
        $subProjects = SubProject::where('project_id', $transaction->project_id)->orderBy('name')->get();

        $transaction->load(['details']);

        return view('user.transactions.edit', compact('transaction', 'projects', 'categories', 'subProjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Pastikan user hanya bisa update transaksi miliknya
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $validationRules = [
            'type' => 'required|in:penerimaan,pengambilan,pengembalian,peminjaman',
            'transaction_date' => 'required|date',
            'vendor_id' => 'nullable|exists:vendors,id',
            'vendor_name' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'sub_project_id' => 'required|exists:sub_projects,id',
            'location' => 'required|string|max:255',
            'cluster' => 'nullable|string|max:255',
            'site_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'proof_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'return_destination' => 'nullable|string|max:255',
            'delivery_return_no' => 'nullable|string|max:255',
        ];

        // Tambahan validasi untuk transaksi penerimaan
        if ($request->type === 'penerimaan') {
            $validationRules['delivery_order_no'] = 'required|string|max:255';
            $validationRules['delivery_note_no'] = 'required|string|max:255';
        } else {
            $validationRules['delivery_order_no'] = 'nullable|string|max:255';
            $validationRules['delivery_note_no'] = 'nullable|string|max:255';
        }

        // Validasi tujuan pengembalian untuk transaksi pengembalian
        if ($request->type === 'pengembalian') {
            $validationRules['return_destination'] = 'required|string|max:255';
        }

        $request->validate($validationRules);

        $proofPath = $transaction->proof_path;
        if ($request->hasFile('proof_path')) {
            $proofPath = $request->file('proof_path')->store('transaction-proofs', 'public');
        }

        // Update transaksi
        $transaction->update([
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
            'vendor_id' => $request->vendor_id,
            'vendor_name' => $request->vendor_name,
            'project_id' => $request->project_id,
            'sub_project_id' => $request->sub_project_id,
            'location' => $request->location,
            'cluster' => $request->cluster,
            'site_id' => $request->site_id,
            'notes' => $request->notes,
            'proof_path' => $proofPath,
            'delivery_order_no' => $request->delivery_order_no,
            'delivery_note_no' => $request->delivery_note_no,
            'delivery_return_no' => $request->delivery_return_no,
            'return_destination' => $request->return_destination,
        ]);

        // Hapus detail lama dan buat yang baru
        $transaction->details()->delete();
        foreach ($request->materials as $material) {
            $transaction->details()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
            ]);
        }

        return redirect()->route('user.transactions.show', $transaction)->with('success', 'Transaksi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Pastikan user hanya bisa hapus transaksi miliknya
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('user.transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
