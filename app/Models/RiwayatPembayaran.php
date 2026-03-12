<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    protected $fillable = [
        'siswa_id',
        'tagihan_id',
        'pembayaran_id',
        'bulan',
        'tahun',
        'nominal',
        'metode_pembayaran',
        'tanggal_bayar'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}