<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\RiwayatPembayaran;
use Carbon\Carbon;



class PembayaranController extends Controller
{
    public function bayar($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'SPP-'.$tagihan->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $tagihan->nominal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'siswa_id' => $tagihan->siswa_id,
            'order_id' => $orderId,
            'jumlah_bayar' => $tagihan->nominal,
            'metode_bayar' => 'midtrans',
            'status' => 'pending'
        ]);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }

    public function storeManual(Request $request)
{
    $request->validate([
        'tagihan_id' => 'required',
        'jumlah_bayar' => 'required|numeric',
        'tanggal_bayar' => 'required|date',
    ]);

    $tagihan = Tagihan::findOrFail($request->tagihan_id);

    // ❌ Cegah bayar 2x
    if ($tagihan->status == 'lunas') {
        return back()->with('error', 'Tagihan sudah lunas!');
    }

    // ❌ Validasi jumlah harus sama
    if ($request->jumlah_bayar != $tagihan->nominal) {
        return back()->with('error', 'Jumlah bayar harus sesuai nominal!');
    }

    // ✅ Simpan ke riwayat pembayaran
    RiwayatPembayaran::create([
        'tagihan_id' => $tagihan->id,
        'siswa_id' => $tagihan->siswa_id,
        'pembayaran_id' => null, // karena ini manual
        'bulan' => $tagihan->bulan,
        'tahun' => $tagihan->tahun,
        'nominal' => $request->jumlah_bayar,
        'metode_pembayaran' => 'manual',
        'tanggal_bayar' => $request->tanggal_bayar,
    ]);

    // ✅ Update status tagihan
    $tagihan->update([
        'status' => 'lunas'
    ]);

    return back()->with('success', 'Pembayaran manual berhasil dicatat!');
}
}