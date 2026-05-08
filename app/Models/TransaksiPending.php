<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPending extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai
    protected $table = 'transaksi_pending';

    protected $fillable = [
        'order_id', 'user_id', 'tagihan_ids', 'total', 'status'
    ];

    protected $casts = [
        'tagihan_ids' => 'array',   // otomatis json decode
    ];
}