<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'project'])
            ->latest()
            ->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail satu transaksi.
     */
    public function show(Transaction $transaction)
    {
        // Eager load relasi untuk efisiensi query
        $transaction->load(['user', 'project', 'location', 'vendor', 'items.material']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Menyetujui transaksi dan memperbarui stok.
     */
    public function approve(Transaction $transaction)
    {
        // Pastikan hanya transaksi pending yang bisa diproses
        if ($transaction->status !== 'pending') {
            return redirect()->route('admin.transactions.show', $transaction)->with('error', 'Transaksi ini sudah diproses.');
        }

        try {
            DB::transaction(function () use ($transaction) {
                foreach ($transaction->items as $item) {
                    $material = Material::findOrFail($item->material_id);

                    // Logika pembaruan stok
                    if ($transaction->type == 'penerimaan' || $transaction->type == 'pengembalian') {
                        // Jika material masuk, stok bertambah
                        $material->stock += $item->quantity;
                    } elseif ($transaction->type == 'pengambilan' || $transaction->type == 'peminjaman') {
                        // Jika material keluar, stok berkurang
                        if ($material->stock < $item->quantity) {
                            // Jika stok tidak mencukupi, batalkan transaksi
                            throw new \Exception("Stok untuk material '{$material->name}' tidak mencukupi.");
                        }
                        $material->stock -= $item->quantity;
                    }
                    $material->save();
                }

                // Update status transaksi
                $transaction->status = 'approved';
                $transaction->approved_by = Auth::id();
                $transaction->approved_at = now();
                $transaction->save();
            });

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil disetujui.');

        } catch (\Exception $e) {
            return redirect()->route('admin.transactions.show', $transaction)->with('error', 'Gagal menyetujui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menolak transaksi.
     */
    public function reject(Request $request, Transaction $transaction)
    {
        // Pastikan hanya transaksi pending yang bisa diproses
        if ($transaction->status !== 'pending') {
            return redirect()->route('admin.transactions.show', $transaction)->with('error', 'Transaksi ini sudah diproses.');
        }

        // Nanti bisa ditambahkan validasi untuk alasan penolakan
        // $request->validate(['rejection_reason' => 'required|string']);

        $transaction->status = 'rejected';
        $transaction->approved_by = Auth::id(); // Admin yang menolak
        // $transaction->rejection_reason = $request->rejection_reason;
        $transaction->save();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil ditolak.');
    }
}
