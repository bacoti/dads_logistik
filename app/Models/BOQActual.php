<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BOQActual extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang benar (Laravel salah mengkonversi BOQActual menjadi b_o_q_actuals)
     */
    protected $table = 'boq_actuals';

    protected $fillable = [
        'user_id',
        'project_id',
        'sub_project_id',
        'material_id',
        'cluster',
        'dn_number',
        'actual_quantity',
        'usage_date',
        'notes'
    ];

    protected $casts = [
        'usage_date' => 'date',
        'actual_quantity' => 'decimal:2'
    ];

    /**
     * Relasi ke User (admin yang input)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi ke SubProject
     */
    public function subProject(): BelongsTo
    {
        return $this->belongsTo(SubProject::class);
    }

    /**
     * Relasi ke Material
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Accessor untuk format quantity dengan unit
     */
    public function getFormattedQuantityAttribute(): string
    {
        return number_format($this->actual_quantity, 2) . ' ' . $this->material->unit;
    }

    /**
     * Scope untuk filter berdasarkan project
     */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope untuk filter berdasarkan sub project
     */
    public function scopeBySubProject($query, $subProjectId)
    {
        return $query->where('sub_project_id', $subProjectId);
    }

    /**
     * Scope untuk filter berdasarkan cluster
     */
    public function scopeByCluster($query, $cluster)
    {
        return $query->where('cluster', $cluster);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('usage_date', [$startDate, $endDate]);
    }
}
