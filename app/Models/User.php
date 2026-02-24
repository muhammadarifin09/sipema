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
        'siswa_id',  // Pastikan kolom ini ada di tabel users
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
        ];
    }

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
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
}