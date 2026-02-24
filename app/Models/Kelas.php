<?php
// app/Models/Kelas.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'tingkat',
    ];

    // Relasi dengan siswa (one to many)
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // Hitung jumlah siswa per kelas
    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->where('status', 'aktif')->count();
    }
}