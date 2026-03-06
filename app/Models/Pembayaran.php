<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'siswa_id',
        'order_id',
        'jumlah_bayar',
        'metode_bayar',
        'status',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // relasi ke tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    // relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

}