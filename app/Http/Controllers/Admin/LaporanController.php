<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatPembayaran;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: join dengan siswa dan kelas
        $query = RiwayatPembayaran::query()
            ->join('siswas', 'riwayat_pembayarans.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select(
                'siswas.nis',
                'siswas.nama_lengkap as nama_siswa',
                'kelas.nama_kelas',
                DB::raw('COUNT(riwayat_pembayarans.id) as jumlah_bayar'),
                DB::raw('SUM(riwayat_pembayarans.nominal) as total_nominal')
            )
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.nama_lengkap', 'kelas.nama_kelas');

        // Filter tanggal (opsional)
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('riwayat_pembayarans.tanggal_bayar', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->whereDate('riwayat_pembayarans.tanggal_bayar', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->whereDate('riwayat_pembayarans.tanggal_bayar', '<=', $request->tanggal_akhir);
        }

        // Filter bulan & tahun (opsional)
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('riwayat_pembayarans.tanggal_bayar', $request->bulan)
                  ->whereYear('riwayat_pembayarans.tanggal_bayar', $request->tahun);
        } elseif ($request->filled('bulan')) {
            $query->whereMonth('riwayat_pembayarans.tanggal_bayar', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('riwayat_pembayarans.tanggal_bayar', $request->tahun);
        }

        $laporans = $query->orderBy('siswas.nama_lengkap')
                         ->paginate(15);

        return view('admin.laporan.index', compact('laporans'));
    }



public function exportPdf(Request $request)
{
    // Query sama seperti di index, tapi tanpa pagination (ambil semua)
    $query = RiwayatPembayaran::query()
        ->join('siswas', 'riwayat_pembayarans.siswa_id', '=', 'siswas.id')
        ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
        ->select(
            'siswas.nis',
            'siswas.nama_lengkap as nama_siswa',
            'kelas.nama_kelas',
            DB::raw('COUNT(riwayat_pembayarans.id) as jumlah_bayar'),
            DB::raw('SUM(riwayat_pembayarans.nominal) as total_nominal')
        )
        ->groupBy('siswas.id', 'siswas.nis', 'siswas.nama_lengkap', 'kelas.nama_kelas');

    // Filter tanggal
    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('riwayat_pembayarans.tanggal_bayar', [
            $request->tanggal_awal,
            $request->tanggal_akhir
        ]);
    } elseif ($request->filled('tanggal_awal')) {
        $query->whereDate('riwayat_pembayarans.tanggal_bayar', '>=', $request->tanggal_awal);
    } elseif ($request->filled('tanggal_akhir')) {
        $query->whereDate('riwayat_pembayarans.tanggal_bayar', '<=', $request->tanggal_akhir);
    }

    // Filter bulan & tahun
    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereMonth('riwayat_pembayarans.tanggal_bayar', $request->bulan)
              ->whereYear('riwayat_pembayarans.tanggal_bayar', $request->tahun);
    } elseif ($request->filled('bulan')) {
        $query->whereMonth('riwayat_pembayarans.tanggal_bayar', $request->bulan);
    } elseif ($request->filled('tahun')) {
        $query->whereYear('riwayat_pembayarans.tanggal_bayar', $request->tahun);
    }

    $laporans = $query->orderBy('siswas.nama_lengkap')->get();

    // Hitung total nominal dan total jumlah bayar
    $totalNominal = $laporans->sum('total_nominal');
    $totalJumlahBayar = $laporans->sum('jumlah_bayar');
    $totalSiswa = $laporans->count();

    // Data untuk ditampilkan di PDF
    $data = [
        'laporans' => $laporans,
        'totalNominal' => $totalNominal,
        'totalJumlahBayar' => $totalJumlahBayar,
        'totalSiswa' => $totalSiswa,
        'filter' => [
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]
    ];

    $pdf = Pdf::loadView('admin.laporan.export-pdf', $data);
    $pdf->setPaper('A4', 'landscape'); // Landscape agar tabel muat
    return $pdf->download('laporan_rekap_spp_' . date('Ymd_His') . '.pdf');
}

}