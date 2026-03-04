<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siswa = $user->siswa;

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $tagihans = Tagihan::where('siswa_id', $siswa->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('wali.tagihan.index', compact('tagihans'));
    }
}