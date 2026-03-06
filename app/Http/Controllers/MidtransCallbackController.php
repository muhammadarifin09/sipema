<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->transaction_status;

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if ($status == 'settlement' || $status == 'capture') {

            $pembayaran->status = 'berhasil';
            $pembayaran->tanggal_bayar = now();
            $pembayaran->save();

            $tagihan = Tagihan::find($pembayaran->tagihan_id);
            $tagihan->status = 'lunas';
            $tagihan->tanggal_bayar = now();
            $tagihan->metode_pembayaran = 'midtrans';
            $tagihan->save();

        } elseif ($status == 'pending') {

            $pembayaran->status = 'pending';
            $pembayaran->save();

        } elseif ($status == 'expire' || $status == 'cancel' || $status == 'deny') {

            $pembayaran->status = 'gagal';
            $pembayaran->save();

        }

        return response()->json(['message' => 'Callback berhasil diproses']);
    }
}