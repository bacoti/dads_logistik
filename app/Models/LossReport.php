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

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getSupportingDocumentUrlAttribute()
    {
        return $this->supporting_document_path ? asset('storage/' . $this->supporting_document_path) : null;
    }
}
