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

    // Ambil semua riwayat pembayaran siswa
    $riwayatRaw = RiwayatPembayaran::where('siswa_id', $selectedSiswa->id)
                    ->orderBy('tahun', 'asc')
                    ->orderBy('bulan', 'asc')
                    ->get();

    // Tentukan tahun ajaran terbaru (ambil tahun maksimal dari data)
    $maxTahun = $riwayatRaw->max('tahun') ?? date('Y');
    // Kita buat kartu SPP untuk tahun ajaran yang mengandung bulan Juli tahun $maxTahun sampai Juni tahun $maxTahun+1
    $tahunAjaranMulai = $maxTahun; 
    $tahunAjaranSelesai = $maxTahun + 1;

    // Urutan bulan dalam tahun ajaran Indonesia: Juli (7) s/d Juni (6)
    $bulanUrut = [
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'
    ];

    // Buat map pembayaran berdasarkan bulan dan tahun
    $paymentMap = [];
    foreach ($riwayatRaw as $payment) {
        $paymentMap[$payment->tahun][$payment->bulan] = $payment;
    }

    // Siapkan data tabel untuk 12 bulan
    $dataTable = [];
    foreach ($bulanUrut as $bulanNum => $bulanNama) {
        $tahun = ($bulanNum >= 7) ? $tahunAjaranMulai : $tahunAjaranSelesai;
        $payment = $paymentMap[$tahun][$bulanNum] ?? null;
        
        $dataTable[] = [
            'bulan_nama' => $bulanNama,
            'tahun' => $tahun,
            'tanggal_bayar' => $payment ? \Carbon\Carbon::parse($payment->tanggal_bayar)->translatedFormat('d/m/Y') : null,
            'nominal' => $payment ? $payment->nominal : 0,
            'metode' => $payment ? $payment->metode_pembayaran : '-',
            'status' => $payment ? 'Lunas' : 'Belum Bayar'
        ];
    }

    $data = [
        'siswa'           => $selectedSiswa,
        'dataTable'       => $dataTable,
        'tahun_ajaran'    => $tahunAjaranMulai . '/' . $tahunAjaranSelesai,
        'tanggal_cetak'   => now()->translatedFormat('d F Y H:i'),
        'total_nominal'   => $riwayatRaw->sum('nominal'),
    ];

    $pdf = Pdf::loadView('wali.riwayat_pdf', $data);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->download('Kartu_SPP_' . $selectedSiswa->nama_lengkap . '_' . date('Ymd_His') . '.pdf');
}

}