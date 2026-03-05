<?php
// app/Http/Controllers/Wali/TagihanController.php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswaList = $user->siswa;
        
        if ($siswaList->isEmpty()) {
            return view('wali.tagihan', [
                'tagihans' => collect([]),
                'siswaList' => collect([]),
                'selectedSiswaId' => null,
                'message' => 'Belum ada siswa yang terhubung dengan akun Anda.'
            ]);
        }

        // Filter berdasarkan siswa jika ada parameter
        $selectedSiswaId = $request->get('siswa_id', $siswaList->first()->id);
        
        // Ambil tagihan untuk siswa yang dipilih
        $tagihans = Tagihan::where('siswa_id', $selectedSiswaId)
            ->with('siswa.kelas')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // Statistik per siswa yang dipilih
        $statistik = [
            'total_tagihan' => $tagihans->count(),
            'total_lunas' => $tagihans->where('status', 'lunas')->count(),
            'total_belum_bayar' => $tagihans->where('status', 'belum_bayar')->count(),
            'total_nominal' => $tagihans->sum('nominal'),
            'total_nominal_lunas' => $tagihans->where('status', 'lunas')->sum('nominal'),
            'total_nominal_belum_bayar' => $tagihans->where('status', 'belum_bayar')->sum('nominal')
        ];

        return view('wali.tagihan.index', compact(
            'tagihans',
            'siswaList',
            'selectedSiswaId',
            'statistik'
        ));
    }

    public function show($id)
    {
        $tagihan = Tagihan::with('siswa.kelas')->findOrFail($id);
        
        // Validasi apakah tagihan ini milik siswa yang terhubung dengan wali
        $user = Auth::user();
        $siswaIds = $user->siswa->pluck('id')->toArray();
        
        if (!in_array($tagihan->siswa_id, $siswaIds)) {
            abort(403, 'Unauthorized access');
        }

        return view('wali.tagihan-detail', compact('tagihan'));
    }
}