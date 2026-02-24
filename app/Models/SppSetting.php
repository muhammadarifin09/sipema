<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SppSetting extends Model
{
    protected $fillable = [
        'tahun_ajaran_id',
        'nominal',
        'tanggal_jatuh_tempo',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}