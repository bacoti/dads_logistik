<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'po_number',
        'supplier',
        'release_date',
        'location',
        'project_id',
        'sub_project_id',
        'description',
        'quantity',
        'unit',
        'status',
        'notes',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'release_date' => 'date',
        'approved_at' => 'datetime',
        'quantity' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subProject()
    {
        return $this->belongsTo(SubProject::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>',
            'approved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
            'rejected' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>',
            'active' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>',
            'completed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Selesai</span>',
            'cancelled' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    // Method untuk kompatibilitas dengan pemanggilan getStatusBadge()
    public function getStatusBadge()
    {
        return $this->status_badge;
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }
}
