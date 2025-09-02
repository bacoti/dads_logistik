<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\PoMaterial;
use App\Models\MaterialTransaction;
use App\Models\MaterialStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialReceiptController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil PO Materials yang sudah approved dan belum fully received
        $poMaterials = PoMaterial::with(['items.materialStock', 'items.transactions', 'project'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('items', function($query) {
                // Filter items yang belum fully received
                $query->whereRaw('
                    (SELECT COALESCE(SUM(quantity), 0) 
                     FROM material_transactions 
                     WHERE po_material_item_id = po_material_items.id 
                     AND transaction_type = "receipt") < po_material_items.quantity
                ');
            })
            ->latest()
            ->paginate(10);

        return view('po.material-receipt.index', compact('poMaterials'));
    }

    public function show(PoMaterial $poMaterial)
    {
        // Pastikan user hanya bisa akses PO sendiri
        if ($poMaterial->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $poMaterial->load(['items.materialStock', 'items.transactions', 'project', 'subProject']);

        return view('po.material-receipt.show', compact('poMaterial'));
    }

    public function create(PoMaterial $poMaterial)
    {
        // Pastikan user hanya bisa akses PO sendiri
        if ($poMaterial->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Pastikan PO sudah approved
        if ($poMaterial->status !== 'approved') {
            return redirect()->route('po.material-receipt.index')
                ->with('error', 'Hanya PO yang sudah disetujui yang bisa diinput penerimaan materialnya.');
        }

        $poMaterial->load(['items.materialStock', 'items.transactions']);

        return view('po.material-receipt.create', compact('poMaterial'));
    }

    public function store(Request $request, PoMaterial $poMaterial)
    {
        // Pastikan user hanya bisa akses PO sendiri
        if ($poMaterial->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'receipts' => 'required|array',
            'receipts.*.po_material_item_id' => 'required|exists:po_material_items,id',
            'receipts.*.quantity_received' => 'required|numeric|min:0',
            'receipts.*.condition' => 'required|in:good,damaged',
            'receipts.*.notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'receipt_location' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $poMaterial) {
            foreach ($request->receipts as $receiptData) {
                if ($receiptData['quantity_received'] > 0) {
                    $poMaterialItem = $poMaterial->items()->findOrFail($receiptData['po_material_item_id']);
                    
                    // Buat stock entry jika belum ada
                    $poMaterialItem->createStock();
                    
                    // Refresh relationship setelah createStock
                    $poMaterialItem->load('materialStock');
                    
                    // Buat transaction receipt
                    MaterialTransaction::create([
                        'po_material_id' => $poMaterial->id,
                        'po_material_item_id' => $poMaterialItem->id,
                        'transaction_type' => 'receipt',
                        'quantity' => $receiptData['quantity_received'],
                        'unit' => $poMaterialItem->unit,
                        'transaction_date' => $request->transaction_date,
                        'user_id' => Auth::id(),
                        'notes' => $receiptData['notes'] ?? $request->general_notes,
                        'condition' => $receiptData['condition'],
                        'location' => $request->receipt_location
                    ]);

                    // Update stock - refresh relationship again setelah transaction dibuat
                    $poMaterialItem->refresh();
                    $poMaterialItem->materialStock->updateStock();
                }
            }
        });

        return redirect()->route('po.material-receipt.show', $poMaterial)
            ->with('success', 'Penerimaan material berhasil dicatat!');
    }
}
