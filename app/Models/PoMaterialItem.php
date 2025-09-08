<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }
}
