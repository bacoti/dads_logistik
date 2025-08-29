<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubProject extends Model
{
    protected $fillable = [
        'project_id',
        'name'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
