<?php
// app/Models/Siswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'email',
        'kelas_id',
        'nama_ayah',
        'nama_ibu',
        'no_telp_orangtua',
        'foto',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke user (untuk login) - PERBAIKAN: tambahkan foreign key
    public function user()
    {
        return $this->hasOne(User::class, 'siswa_id', 'id');
    }

    // Accessor untuk menampilkan jenis kelamin lengkap
    public function getJenisKelaminLabelAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }

    // Accessor untuk menampilkan status dengan badge
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'aktif' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Aktif</span>',
            'alumni' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Alumni</span>',
            'keluar' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Keluar</span>',
            default => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    // Accessor untuk umur
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : '-';
    }

    // Scope untuk filter status
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeAlumni($query)
    {
        return $query->where('status', 'alumni');
    }

    public function scopeKeluar($query)
    {
        return $query->where('status', 'keluar');
    }

    public function tagihans()
{
    return $this->hasMany(Tagihan::class);
}
}