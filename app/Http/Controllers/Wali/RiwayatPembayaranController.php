<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswaList = $user->siswa; // Ambil semua siswa yang terhubung dengan wali
        
        if ($siswaList->isEmpty()) {
            return view('wali.riwayat.index', [
                'riwayat' => collect([]),
                'siswaList' => collect([]),
                'selectedSiswaId' => null,
                'statistik' => [
                    'total_transaksi' => 0,
                    'total_nominal' => 0,
                    'berhasil' => 0,
                    'pending' => 0,
                    'gagal' => 0
                ]
            ]);
        }

        // Filter berdasarkan siswa jika ada parameter
        $selectedSiswaId = $request->get('siswa_id', $siswaList->first()->id);
        
        // Ambil riwayat pembayaran untuk siswa yang dipilih
        $riwayat = Pembayaran::with('tagihan')
            ->where('siswa_id', $selectedSiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik per siswa yang dipilih
        $statistik = [
            'total_transaksi' => $riwayat->count(),
            'total_nominal' => $riwayat->sum('jumlah_bayar'),
            'berhasil' => $riwayat->where('status', 'berhasil')->count(),
            'pending' => $riwayat->where('status', 'pending')->count(),
            'gagal' => $riwayat->where('status', 'gagal')->count()
        ];

        return view('wali.riwayat.index', compact(
            'riwayat',
            'siswaList',
            'selectedSiswaId',
            'statistik'
        ));
    }
}