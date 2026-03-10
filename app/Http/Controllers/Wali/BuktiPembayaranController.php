<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class BuktiPembayaranController extends Controller
{
    public function download()
    {
        // ambil pembayaran berhasil milik wali yang login
        $pembayarans = Pembayaran::with('tagihan.siswa')
            ->where('status','berhasil')
            ->whereHas('tagihan.siswa', function($query){
                $query->where('wali_id', Auth::id());
            })
            ->orderBy('tanggal_bayar','desc')
            ->get();

        // total pembayaran
        $total = $pembayarans->sum('jumlah_bayar');

        $pdf = Pdf::loadView('wali.bukti_pembayaran',[
            'pembayarans' => $pembayarans,
            'total' => $total
        ]);

        return $pdf->download('bukti-pembayaran-spp.pdf');
    }

    
}