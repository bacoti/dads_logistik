<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoMaterialItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_material_id',
        'description',
        'quantity',
        'unit',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function poMaterial(): BelongsTo
    {
        return $this->belongsTo(PoMaterial::class);
    }

    public function materialStock(): HasOne
    {
        return $this->hasOne(MaterialStock::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MaterialTransaction::class);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }

    // Method untuk membuat stock entry saat PO approved
    public function createStock()
    {
        if (!$this->materialStock) {
            MaterialStock::create([
                'po_material_item_id' => $this->id,
                'material_name' => $this->description,
                'material_category' => 'General', // Bisa dikustomisasi
                'current_stock' => 0,
                'unit' => $this->unit,
                'minimum_stock' => 0,
                'maximum_stock' => null,
                'reserved_stock' => 0,
                'available_stock' => 0,
                'storage_location' => null,
                'average_unit_cost' => 0,
                'total_value' => 0,
                'last_transaction_date' => null
            ]);
        }
    }

    // Method untuk mendapatkan total received (material yang sudah diterima)
    public function getTotalReceivedAttribute()
    {
        return $this->transactions()->where('transaction_type', 'receipt')->sum('quantity') ?? 0;
    }

    // Method untuk mendapatkan total used (material yang sudah digunakan)
    public function getTotalUsedAttribute()
    {
        return $this->transactions()->where('transaction_type', 'usage')->sum('quantity') ?? 0;
    }

    // Method untuk mendapatkan current stock
    public function getCurrentStockAttribute()
    {
        return $this->materialStock ? $this->materialStock->current_stock : 0;
    }

    // Method untuk mendapatkan status receipt
    public function getReceiptStatusAttribute()
    {
        $totalReceived = $this->total_received;
        $poQuantity = $this->quantity;

        if ($totalReceived >= $poQuantity) {
            return 'complete';
        } elseif ($totalReceived > 0) {
            return 'partial';
        } else {
            return 'pending';
        }
    }

    public function getReceiptStatusTextAttribute()
    {
        return match($this->receipt_status) {
            'complete' => 'Diterima Lengkap',
            'partial' => 'Diterima Sebagian',
            'pending' => 'Belum Diterima',
            default => 'Unknown'
        };
    }

    public function getReceiptPercentageAttribute()
    {
        if ($this->quantity <= 0) return 0;
        return round(($this->total_received / $this->quantity) * 100, 2);
    }
}
