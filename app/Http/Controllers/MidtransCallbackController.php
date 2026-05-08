<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\TransaksiPending;
use App\Models\RiwayatPembayaran;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Log semua request yang masuk (untuk debug)
        Log::info('Midtrans callback raw request', $request->all());

        $orderId = $request->order_id;
        $status = $request->transaction_status;

        Log::info('Callback received', ['order_id' => $orderId, 'status' => $status]);

        try {
            // 1. Cek di tabel transaksi_pending
            $transaksiPending = TransaksiPending::where('order_id', $orderId)->first();

            if ($transaksiPending) {
                return $this->handleWaliTransaction($transaksiPending, $status);
            }

            // 2. Cek di tabel pembayaran
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();
            if ($pembayaran) {
                return $this->handleBendaharaTransaction($pembayaran, $status);
            }

            Log::warning('Order ID tidak ditemukan di kedua tabel', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        } catch (\Exception $e) {
            Log::error('Callback global error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    private function handleWaliTransaction($transaksiPending, $status)
    {
        try {
            Log::info('Processing wali transaction', [
                'order_id' => $transaksiPending->order_id,
                'status' => $status,
                'data' => $transaksiPending->toArray()
            ]);

            if (in_array($status, ['settlement', 'capture'])) {
                // Update status transaksi
                $transaksiPending->status = 'success';
                $transaksiPending->save();

                // Ambil tagihan_ids
                $tagihanIds = $transaksiPending->tagihan_ids;
                if (is_string($tagihanIds)) {
                    $tagihanIds = json_decode($tagihanIds, true);
                }

                Log::info('Tagihan IDs to be updated', $tagihanIds);

                $tagihans = Tagihan::whereIn('id', $tagihanIds)->get();

                foreach ($tagihans as $tagihan) {
                    $tagihan->update([
                        'status' => 'lunas',
                        'tanggal_bayar' => now(),
                        'metode_pembayaran' => 'midtrans'
                    ]);

                    // Simpan riwayat pembayaran
                    RiwayatPembayaran::create([
                        'siswa_id' => $tagihan->siswa_id,
                        'tagihan_id' => $tagihan->id,
                        'pembayaran_id' => null,
                        'bulan' => $tagihan->bulan,
                        'tahun' => $tagihan->tahun,
                        'nominal' => $tagihan->nominal,
                        'metode_pembayaran' => 'midtrans',
                        'tanggal_bayar' => now(),
                    ]);
                }

                // Kirim notifikasi WhatsApp
                $user = \App\Models\User::find($transaksiPending->user_id);
                if ($user && !empty($user->no_hp)) {
                    $bulanList = $tagihans->pluck('bulan')->implode(', ');
                    $total = $transaksiPending->total;
                    $pesan = "📢 *Pembayaran SPP Berhasil*\n\n" .
                             "Anda telah membayar SPP untuk bulan: $bulanList\n" .
                             "Total: Rp " . number_format($total, 0, ',', '.') . "\n" .
                             "Tanggal: " . now()->format('d-m-Y H:i') . "\n\nTerima kasih.";
                    FonnteService::send($user->no_hp, $pesan);
                }

                Log::info("Transaksi wali sukses: order_id {$transaksiPending->order_id}");
            } 
            elseif ($status == 'pending') {
                $transaksiPending->status = 'pending';
                $transaksiPending->save();
            } 
            else {
                $transaksiPending->status = 'failed';
                $transaksiPending->save();
            }

            return response()->json(['message' => 'Callback wali processed']);
        } catch (\Exception $e) {
            Log::error('Error handleWaliTransaction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'order_id' => $transaksiPending->order_id
            ]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    private function handleBendaharaTransaction($pembayaran, $status)
    {
        try {
            Log::info('Processing bendahara transaction', [
                'order_id' => $pembayaran->order_id,
                'status' => $status
            ]);

            if (in_array($status, ['settlement', 'capture'])) {
                $pembayaran->update([
                    'status' => 'berhasil',
                    'tanggal_bayar' => now(),
                ]);

                $tagihan = Tagihan::find($pembayaran->tagihan_id);
                if ($tagihan) {
                    $tagihan->update([
                        'status' => 'lunas',
                        'tanggal_bayar' => now(),
                        'metode_pembayaran' => 'midtrans',
                    ]);

                    if (!RiwayatPembayaran::where('pembayaran_id', $pembayaran->id)->exists()) {
                        RiwayatPembayaran::create([
                            'siswa_id' => $tagihan->siswa_id,
                            'tagihan_id' => $tagihan->id,
                            'pembayaran_id' => $pembayaran->id,
                            'bulan' => $tagihan->bulan,
                            'tahun' => $tagihan->tahun,
                            'nominal' => $pembayaran->jumlah_bayar,
                            'metode_pembayaran' => 'midtrans',
                            'tanggal_bayar' => now(),
                        ]);
                    }

                    $nomor = optional($tagihan->siswa->wali)->no_hp;
                    if ($nomor) {
                        $pesan = "📢 *Konfirmasi Pembayaran SPP*\n\n" .
                                 "Pembayaran berhasil dilakukan.\n\n" .
                                 "👤 Siswa : " . $tagihan->siswa->nama_lengkap . "\n" .
                                 "📅 Bulan : " . $tagihan->bulan . "\n" .
                                 "💰 Nominal : Rp " . number_format($tagihan->nominal, 0, ',', '.') . "\n" .
                                 "🕒 Tanggal : " . now()->format('d-m-Y H:i') . "\n\n" .
                                 "Terima kasih.";
                        FonnteService::send($nomor, $pesan);
                    }
                }
            } 
            elseif ($status == 'pending') {
                $pembayaran->update(['status' => 'pending']);
            } 
            else {
                $pembayaran->update(['status' => 'gagal']);
            }

            return response()->json(['message' => 'Callback bendahara processed']);
        } catch (\Exception $e) {
            Log::error('Error handleBendaharaTransaction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'order_id' => $pembayaran->order_id
            ]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}