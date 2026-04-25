<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'no_hp',
        'active',
        // HAPUS 'siswa_id' dari sini karena tidak digunakan lagi
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
            'active' => 'boolean',
        ];
    }

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // PERBAIKAN: Relasi ke siswa (one-to-many)
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'wali_id', 'id');
    }

    // Helper untuk cek role
    public function isAdmin()
    {
        return $this->role && $this->role->nama_role === 'admin';
    }

    public function isBendahara()
    {
        return $this->role && $this->role->nama_role === 'bendahara';
    }

    public function isWali()
    {
        return $this->role && $this->role->nama_role === 'wali';
    }

    public function isSiswa()
    {
        return $this->role && $this->role->nama_role === 'siswa';
    }

    public function waliProfile()
{
    return $this->hasOne(WaliProfile::class);
}

}