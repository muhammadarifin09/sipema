<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;

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
}