<?php

namespace App\Models;

// Tambahkan ini di atas
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class, // Casting ke Enum
    ];

    // Relasi: Seorang user bisa punya banyak transaksi
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
