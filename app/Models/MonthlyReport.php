<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReport extends Model
{
    protected $fillable = [
        'user_id',
        'report_date',
        'report_period',
        'project_id',
        'sub_project_id',
        'project_location',
        'notes',
        'excel_file_path',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'report_date' => 'date',
        'reviewed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function subProject(): BelongsTo
    {
        return $this->belongsTo(SubProject::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedPeriodAttribute(): string
    {
        try {
            // Handle null or empty report_period
            if (empty($this->report_period)) {
                return 'Unknown Period';
            }

            // Try different date formats
            $formats = ['Y-m', 'Y-m-d', 'Y-m-d H:i:s', 'd/m/Y', 'm/Y'];

            foreach ($formats as $format) {
                try {
                    $date = \Carbon\Carbon::createFromFormat($format, $this->report_period);
                    return $date->format('F Y');
                } catch (\Exception $e) {
                    continue;
                }
            }

            // If all formats fail, try Carbon parse
            $date = \Carbon\Carbon::parse($this->report_period);
            return $date->format('F Y');

        } catch (\Exception $e) {
            // Return raw value if all else fails
            return $this->report_period ?? 'Invalid Period';
        }
    }
}
