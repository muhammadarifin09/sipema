<?php
// app/Http/Controllers/Wali/DashboardController.php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua siswa yang terhubung dengan wali ini
        $siswaList = $user->siswa; // Relasi hasMany
        
        if ($siswaList->isEmpty()) {
            return view('wali.dashboard', [
                'siswaList' => collect([]),
                'tagihan_bulan_ini' => 0,
                'total_tunggakan' => 0,
                'jumlah_siswa' => 0,
                'siswa_terhubung' => [],
                'bulan_dibayar' => 0,
                'bulan_belum_dibayar' => 0,
                'total_bulan_tagihan' => 0,
                'tahun_ajaran' => $this->getTahunAjaranAktif(),
                'message' => 'Belum ada siswa yang terhubung dengan akun Anda.'
            ]);
        }

        // Inisialisasi variabel
        $totalTagihanBulanIni = 0;
        $totalTunggakan = 0;
        $totalBulanDibayar = 0;
        $totalBulanBelumDibayar = 0;
        $totalBulanTagihan = 0;
        $siswaTerhubung = [];

        foreach ($siswaList as $siswa) {
            // Kumpulkan data siswa
            $siswaTerhubung[] = [
                'id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'tagihan_bulan_ini' => $this->getTagihanBulanIni($siswa->id),
                'total_tunggakan' => $this->getTotalTunggakan($siswa->id),
                'status_pembayaran' => $this->getStatusPembayaran($siswa->id)
            ];

            // Hitung total tagihan bulan ini untuk semua siswa
            $totalTagihanBulanIni += $this->getTagihanBulanIni($siswa->id);
            
            // Hitung total tunggakan untuk semua siswa
            $totalTunggakan += $this->getTotalTunggakan($siswa->id);
            
            // Hitung statistik pembayaran
            $statistik = $this->getStatistikPembayaran($siswa->id);
            $totalBulanDibayar += $statistik['bulan_dibayar'];
            $totalBulanBelumDibayar += $statistik['bulan_belum_dibayar'];
            $totalBulanTagihan += $statistik['total_bulan'];
        }

        // Hitung progress keseluruhan
        $totalSemuaBulan = $totalBulanDibayar + $totalBulanBelumDibayar;
        $progressKeseluruhan = $totalSemuaBulan > 0 
            ? round(($totalBulanDibayar / $totalSemuaBulan) * 100) 
            : 0;

        return view('wali.dashboard', [
            'siswaList' => $siswaList,
            'siswaTerhubung' => $siswaTerhubung,
            'tagihan_bulan_ini' => $totalTagihanBulanIni,
            'total_tunggakan' => $totalTunggakan,
            'jumlah_siswa' => $siswaList->count(),
            'bulan_dibayar' => $totalBulanDibayar,
            'bulan_belum_dibayar' => $totalBulanBelumDibayar,
            'total_bulan_tagihan' => $totalBulanTagihan,
            'progress_keseluruhan' => $progressKeseluruhan,
            'tahun_ajaran' => $this->getTahunAjaranAktif(),
            'jatuh_tempo' => '10' // Bisa diambil dari setting
        ]);
    }

    // Helper function untuk mendapatkan tagihan bulan ini per siswa
      private function getTagihanBulanIni($siswaId)
{
    return Tagihan::where('siswa_id', $siswaId)
        ->where('bulan', now()->month)
        ->where('tahun', now()->year)
        ->where('status','belum_bayar')
        ->sum('nominal');
}

    // Helper function untuk mendapatkan total tunggakan per siswa
    private function getTotalTunggakan($siswaId)
    {
        return Tagihan::where('siswa_id', $siswaId)
            ->where('status', 'belum_bayar')
            ->sum('nominal');
    }

    // Helper function untuk mendapatkan status pembayaran per siswa
    private function getStatusPembayaran($siswaId)
    {
        $totalTagihan = Tagihan::where('siswa_id', $siswaId)->count();
        $tagihanLunas = Tagihan::where('siswa_id', $siswaId)
            ->where('status', 'lunas')
            ->count();

        if ($totalTagihan == 0) return 'no_tagihan';
        if ($tagihanLunas == $totalTagihan) return 'lunas';
        return 'belum_lunas';
    }

    // Helper function untuk mendapatkan statistik pembayaran per siswa
    private function getStatistikPembayaran($siswaId)
    {
        $bulanDibayar = Tagihan::where('siswa_id', $siswaId)
            ->where('status', 'lunas')
            ->count();
            
        $bulanBelumDibayar = Tagihan::where('siswa_id', $siswaId)
            ->where('status', 'belum_bayar')
            ->count();
            
        $totalBulan = $bulanDibayar + $bulanBelumDibayar;

        return [
            'bulan_dibayar' => $bulanDibayar,
            'bulan_belum_dibayar' => $bulanBelumDibayar,
            'total_bulan' => $totalBulan
        ];
    }

    // Helper function untuk mendapatkan tahun ajaran aktif
    private function getTahunAjaranAktif()
    {
        // Sesuaikan dengan logika tahun ajaran di aplikasi Anda
        $tahun = now()->year;
        $bulan = now()->month;
        
        if ($bulan >= 7) {
            return "$tahun/" . ($tahun + 1);
        } else {
            return ($tahun - 1) . "/$tahun";
        }
    }
}