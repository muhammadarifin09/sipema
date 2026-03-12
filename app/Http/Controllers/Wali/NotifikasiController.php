<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wali.notifikasi.index', compact('notifikasis'));
    }

    public function read($id)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update([
            'status' => 'read'
        ]);

        return back();
    }
}