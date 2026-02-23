<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik sederhana
        $totalUsers = User::count();
        
        // Hitung per role
        $totalAdmin = User::whereHas('role', function($q) {
            $q->where('nama_role', 'admin');
        })->count();
        
        $totalBendahara = User::whereHas('role', function($q) {
            $q->where('nama_role', 'bendahara');
        })->count();
        
        $totalWali = User::whereHas('role', function($q) {
            $q->where('nama_role', 'wali');
        })->count();
        
        // 5 user terbaru
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmin',
            'totalBendahara',
            'totalWali',
            'recentUsers'
        ));
    }
}