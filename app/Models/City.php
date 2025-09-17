<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province',
        'type'
    ];

    public function getFullNameAttribute()
    {
        return $this->type . ' ' . $this->name . ', ' . $this->province;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('province', 'LIKE', "%{$search}%");
    }
}
