<?php

namespace App\Helpers;

use App\Models\LogsAktivitas;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function add($action, $module, $aktivitas, $data = null)
    {
        $userId = Auth::id();
        if (!$userId) {
            // Jika tidak ada user login, coba ambil user pertama (ID 1) atau skip
            $userId = 1; // Ganti dengan ID user yang valid, misal admin
        }

        return LogsAktivitas::create([
            'user_id'    => $userId,
            'action'     => $action,
            'module'     => $module,
            'aktivitas'  => $aktivitas,
            'ip_address' => request()->ip(),
            'data'       => $data,
        ]);
    }
}