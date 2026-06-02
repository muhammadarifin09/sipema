<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatPembayaran;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // ✅ Perbaikan 1: gunakan input() bukan get() (deprecated)
        $selectedSiswaId = $request->input('siswa_id', $siswaList->first()->id);

        $riwayat = RiwayatPembayaran::where('siswa_id', $selectedSiswaId)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $statistik = [
            'total_transaksi' => $riwayat->count(),
            'total_nominal' => $riwayat->sum('nominal'),
            'berhasil' => $riwayat->count(),
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

    public function exportPdf(Request $request)
{
    $wali = Auth::user();
    $siswaId = $request->input('siswa_id');

    $siswaList = $wali->siswa;
    if ($siswaList->isEmpty()) {
        return back()->with('error', 'Tidak ada siswa terdaftar.');
    }

    $selectedSiswa = $siswaList->first();
    if ($siswaId && $siswaList->contains('id', $siswaId)) {
        $selectedSiswa = $siswaList->find($siswaId);
    }

    // Ambil semua riwayat pembayaran siswa yang sukses (sudah pasti sukses karena model RiwayatPembayaran)
    $riwayat = RiwayatPembayaran::where('siswa_id', $selectedSiswa->id)
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();

    // Hitung total nominal
    $totalNominal = $riwayat->sum('nominal');

    // Tentukan tahun ajaran (ambil dari tahun pertama dan terakhir jika ada, atau default)
    if ($riwayat->isNotEmpty()) {
        $minYear = $riwayat->min('tahun');
        $maxYear = $riwayat->max('tahun');
        // Tampilkan rentang tahun ajaran sederhana (misal 2024/2025)
        $tahunAjaran = $minYear . '/' . ($maxYear + 1);
    } else {
        $tahunAjaran = date('Y') . '/' . (date('Y') + 1);
    }

    $data = [
        'siswa'         => $selectedSiswa,
        'riwayat'       => $riwayat,   // kirim collection
        'total_nominal' => $totalNominal,
        'tahun_ajaran'  => $tahunAjaran,
        'tanggal_cetak' => now()->translatedFormat('d F Y H:i'),
    ];

    $pdf = Pdf::loadView('wali.riwayat_pdf', $data);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->download('Kartu_SPP_' . $selectedSiswa->nama_lengkap . '_' . date('Ymd_His') . '.pdf');
}

}