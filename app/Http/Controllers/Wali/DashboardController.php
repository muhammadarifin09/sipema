<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return view('wali.dashboard')->with('error', 'Data siswa belum terhubung.');
        }

        $tagihan_bulan_ini = Tagihan::where('siswa_id', $siswa->id)
            ->where('bulan', now()->month)
            ->where('tahun', now()->year)
            ->sum('nominal');

        $total_tunggakan = Tagihan::where('siswa_id', $siswa->id)
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        return view('wali.dashboard', compact(
            'tagihan_bulan_ini',
            'total_tunggakan'
        ));
    }
}