<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPembayaran;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = RiwayatPembayaran::with(['siswa', 'siswa.kelas', 'tagihan'])
            ->orderBy('tanggal_bayar', 'desc');
        
        // Filter berdasarkan pencarian (NIS, nama siswa)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $query->whereMonth('tanggal_bayar', $bulan);
        }
        
        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $tahun = $request->tahun;
            $query->whereYear('tanggal_bayar', $tahun);
        }
        
        // Clone query untuk statistik (tanpa pagination)
        $statsQuery = clone $query;
        $allData = $statsQuery->get();
        
        // Hitung statistik
        $totalNominal = $allData->sum('nominal');
        $bulanIni = $allData->filter(function($item) {
            return \Carbon\Carbon::parse($item->tanggal_bayar)->month == now()->month && 
                   \Carbon\Carbon::parse($item->tanggal_bayar)->year == now()->year;
        })->count();
        $siswaPembayar = $allData->unique('siswa_id')->count();
        
        $data = $query->paginate(10);
        $data->appends($request->all());

        if(request()->is('admin/*')){
            return view('admin.riwayat.index', compact('data', 'totalNominal', 'bulanIni', 'siswaPembayar'));
        }

        return view('bendahara.riwayat.index', compact('data', 'totalNominal', 'bulanIni', 'siswaPembayar'));
    }
}