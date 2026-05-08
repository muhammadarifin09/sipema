<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pembayarans';

    protected $fillable = [
        'siswa_id', 'tagihan_id', 'pembayaran_id', 'bulan', 'tahun',
        'nominal', 'metode_pembayaran', 'tanggal_bayar'
    ];

    protected $dates = ['tanggal_bayar'];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke tagihan (optional)
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}