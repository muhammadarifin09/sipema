<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RiwayatPembayaran; // Ganti model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik user (sama seperti sebelumnya)
        $totalUsers = User::count();
        $totalAdmin = User::whereHas('role', function($q) {
            $q->where('nama_role', 'admin');
        })->count();
        $totalBendahara = User::whereHas('role', function($q) {
            $q->where('nama_role', 'bendahara');
        })->count();
        $totalWali = User::whereHas('role', function($q) {
            $q->where('nama_role', 'wali');
        })->count();
        
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // --- Data grafik dari RiwayatPembayaran (6 bulan terakhir) ---
        $months = collect();
        $paymentsData = collect();

        for ($i = 5; $i >= 0; $i--) {
            $start = now()->startOfMonth()->subMonths($i);
            $end = now()->startOfMonth()->subMonths($i - 1);
            $monthName = $start->translatedFormat('F Y'); // Contoh: "Januari 2025"

            // Gunakan kolom 'nominal' dan 'tanggal_bayar' dari riwayat_pembayarans
            $total = RiwayatPembayaran::whereBetween('tanggal_bayar', [$start, $end])
                ->sum('nominal'); // Perhatikan: kolom 'nominal', bukan 'jumlah_bayar'

            $months->push($monthName);
            $paymentsData->push($total);
        }

        // Optional: Debug untuk memastikan data tidak kosong
        // dd($months, $paymentsData); // Uncomment untuk cek sementara

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmin',
            'totalBendahara',
            'totalWali',
            'recentUsers',
            'months',
            'paymentsData'
        ));
    }
}