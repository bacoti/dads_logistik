<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LossReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'sub_project_id',
        'project_location',
        'cluster',
        'loss_date',
        'material_type',
        'loss_chronology',
        'additional_notes',
        'supporting_document_path',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'loss_date' => 'date',
        'reviewed_at' => 'datetime',
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

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Review</span>',
            'reviewed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sedang Ditinjau</span>',
            'completed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getSupportingDocumentUrlAttribute()
    {
        return $this->supporting_document_path ? asset('storage/' . $this->supporting_document_path) : null;
    }
}