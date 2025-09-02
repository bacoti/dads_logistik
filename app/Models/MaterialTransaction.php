<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_material_id',
        'po_material_item_id',
        'transaction_type',
        'quantity',
        'unit',
        'transaction_date',
        'user_id',
        'project_id',
        'activity_name',
        'pic_name',
        'notes',
        'attachments',
        'condition',
        'location'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'attachments' => 'array',
        'quantity' => 'decimal:2'
    ];

    // Relations
    public function poMaterial(): BelongsTo
    {
        return $this->belongsTo(PoMaterial::class);
    }

    public function poMaterialItem(): BelongsTo
    {
        return $this->belongsTo(PoMaterialItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Accessors
    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }

    public function getTransactionTypeTextAttribute()
    {
        return match($this->transaction_type) {
            'receipt' => 'Material Masuk',
            'usage' => 'Penggunaan Material',
            'return' => 'Pengembalian Material',
            'damage' => 'Material Rusak/Hilang',
            'adjustment' => 'Koreksi Stock',
            default => 'Unknown'
        };
    }

    public function getTransactionTypeIconAttribute()
    {
        return match($this->transaction_type) {
            'receipt' => '📦',
            'usage' => '🔧',
            'return' => '↩️',
            'damage' => '❌',
            'adjustment' => '⚙️',
            default => '❓'
        };
    }

    public function getTransactionTypeColorAttribute()
    {
        return match($this->transaction_type) {
            'receipt' => 'green',
            'usage' => 'blue',
            'return' => 'yellow',
            'damage' => 'red',
            'adjustment' => 'gray',
            default => 'gray'
        };
    }

    // Scopes
    public function scopeReceipts($query)
    {
        return $query->where('transaction_type', 'receipt');
    }

    public function scopeUsages($query)
    {
        return $query->where('transaction_type', 'usage');
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeForMaterial($query, $poMaterialItemId)
    {
        return $query->where('po_material_item_id', $poMaterialItemId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
