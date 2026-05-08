<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatPembayaran; // gunakan model RiwayatPembayaran
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswaList = $user->siswa;

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

        $selectedSiswaId = $request->get('siswa_id', $siswaList->first()->id);

        // Ambil riwayat dari tabel riwayat_pembayaran
        $riwayat = RiwayatPembayaran::where('siswa_id', $selectedSiswaId)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        // Statistik
        $statistik = [
            'total_transaksi' => $riwayat->count(),
            'total_nominal' => $riwayat->sum('nominal'),
            'berhasil' => $riwayat->count(), // karena semua riwayat adalah sukses
            'pending' => 0,
            'gagal' => 0
        ];

        return view('wali.riwayat.index', compact(
            'riwayat',
            'siswaList',
            'selectedSiswaId',
            'statistik'
        ));
    }
}