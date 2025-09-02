<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_material_item_id',
        'material_name',
        'material_category',
        'current_stock',
        'unit',
        'minimum_stock',
        'maximum_stock',
        'reserved_stock',
        'available_stock',
        'storage_location',
        'average_unit_cost',
        'total_value',
        'last_transaction_date'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'reserved_stock' => 'decimal:2',
        'available_stock' => 'decimal:2',
        'average_unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'last_transaction_date' => 'date'
    ];

    // Relations
    public function poMaterialItem(): BelongsTo
    {
        return $this->belongsTo(PoMaterialItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MaterialTransaction::class, 'po_material_item_id', 'po_material_item_id');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(MaterialAlert::class);
    }

    // Accessors
    public function getFormattedCurrentStockAttribute()
    {
        return number_format($this->current_stock, 2) . ' ' . $this->unit;
    }

    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        } elseif ($this->maximum_stock && $this->current_stock >= $this->maximum_stock) {
            return 'overstocked';
        } else {
            return 'normal';
        }
    }

    public function getStockStatusTextAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'Habis',
            'low_stock' => 'Menipis',
            'overstocked' => 'Berlebihan',
            'normal' => 'Normal',
            default => 'Unknown'
        };
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    // Methods
    public function updateStock()
    {
        // Hitung total dari transactions
        $receipts = $this->transactions()->receipts()->sum('quantity');
        $usages = $this->transactions()->usages()->sum('quantity');
        $returns = $this->transactions()->where('transaction_type', 'return')->sum('quantity');
        $damages = $this->transactions()->where('transaction_type', 'damage')->sum('quantity');

        // Update current stock
        $this->current_stock = $receipts - $usages + $returns - $damages;
        $this->available_stock = $this->current_stock - $this->reserved_stock;

        $this->save();
    }
}
