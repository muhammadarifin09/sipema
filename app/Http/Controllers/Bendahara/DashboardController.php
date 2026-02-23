<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data statistik sederhana (nanti bisa diganti dengan data real dari database)
        $data = [
            'total_penerimaan' => 68450000,
            'siswa_lunas' => 342,
            'total_tunggakan' => 12540000,
            'siswa_tunggakan' => 114,
            'transaksi_hari_ini' => 47,
            'nominal_hari_ini' => 7050000,
        ];
        
        return view('bendahara.dashboard', compact('data'));
    }
}