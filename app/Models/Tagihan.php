<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'nominal',
        'tanggal_jatuh_tempo',
        'status',
        'metode_pembayaran',
        'tanggal_bayar',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}