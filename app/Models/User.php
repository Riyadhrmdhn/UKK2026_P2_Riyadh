<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shift',   // 🔥 FIX: tambahkan shift
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'shift' => 'string',   // 🔥 tambahkan cast
            'status' => 'string',
        ];
    }

    // 🔹 Relasi kendaraan
    public function kendaraan()
    {
        return $this->hasMany(\App\Models\Kendaraan::class, 'id_user');
    }

    // 🔹 Relasi transaksi
    public function transaksi()
    {
        return $this->hasMany(\App\Models\Transaksi::class, 'id_user');
    }

    // 🔹 Relasi log aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(\App\Models\LogAktivitas::class, 'id_user');
    }

    // 🔥 Helper role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }
}