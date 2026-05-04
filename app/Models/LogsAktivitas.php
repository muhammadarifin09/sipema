<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsAktivitas extends Model
{
    protected $table = 'logs_aktivitas';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'aktivitas',
        'ip_address',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}