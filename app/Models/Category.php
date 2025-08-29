<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sub_project_id'
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function subProject(): BelongsTo
    {
        return $this->belongsTo(SubProject::class);
    }
}
