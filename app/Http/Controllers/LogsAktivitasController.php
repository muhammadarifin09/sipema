<?php

namespace App\Http\Controllers;

use App\Models\LogsAktivitas;
use Illuminate\Http\Request;

class LogsAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogsAktivitas::with('user')->latest();

        // Filter pencarian teks (aktivitas, modul, IP address)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan aksi (create, update, delete, login, logout)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter tanggal dari
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // Filter tanggal sampai
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(10);

        // Retain query string saat pagination
        $logs->appends($request->all());

        return view('admin.logs.index', compact('logs'));
    }
}