<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Services\FonnteService;

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

            $tagihan = Tagihan::with('siswa.wali')->find($pembayaran->tagihan_id);

            $tagihan->status = 'lunas';
            $tagihan->tanggal_bayar = now();
            $tagihan->metode_pembayaran = 'midtrans';
            $tagihan->save();

            // kirim whatsapp
            $nomor = optional($tagihan->siswa->wali)->no_hp;

            if ($nomor) {

                $pesan =
                "📢 *Konfirmasi Pembayaran SPP*\n\n".
                "Pembayaran berhasil dilakukan.\n\n".
                "👤 Siswa : ".$tagihan->siswa->nama_lengkap."\n".
                "📅 Bulan : ".$tagihan->nama_bulan."\n".
                "💰 Nominal : Rp ".number_format($tagihan->nominal,0,',','.')."\n".
                "🕒 Tanggal : ".now()->format('d-m-Y H:i')."\n\n".
                "Terima kasih telah melakukan pembayaran.";

                FonnteService::send($nomor, $pesan);
            }

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