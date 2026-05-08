<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagihanController extends Controller
{
    private $bulanOrder = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

   public function index(Request $request)
{
    $user = Auth::user();
    $siswaList = $user->siswa;

    if ($siswaList->isEmpty()) {
        return view('wali.tagihan.index', [
            'tagihans' => collect([]),
            'siswaList' => collect([]),
            'selectedSiswaId' => null,
            'statistik' => null,
        ]);
    }

    $selectedSiswaId = $request->get('siswa_id', $siswaList->first()->id);

    // Hanya ambil tagihan yang BELUM LUNAS
    $tagihans = Tagihan::where('siswa_id', $selectedSiswaId)
        ->where('status', 'belum_bayar')
        ->with('siswa.kelas')
        ->get();

    // Urutkan manual berdasarkan tahun & urutan bulan
    $bulanMap = array_flip($this->bulanOrder);
    $tagihans = $tagihans->sortBy(function ($item) use ($bulanMap) {
        $bulanIndex = $bulanMap[$item->bulan] ?? 12;
        return $item->tahun . '-' . str_pad($bulanIndex + 1, 2, '0', STR_PAD_LEFT);
    })->values();

    // Tentukan apakah ada tagihan sebelumnya yang belum lunas (untuk alert)
    $tagihans = $tagihans->map(function ($tagihan, $index) use ($tagihans) {
        $hasPreviousUnpaid = false;
        for ($i = 0; $i < $index; $i++) {
            if ($tagihans[$i]->status == 'belum_bayar') {
                $hasPreviousUnpaid = true;
                break;
            }
        }
        $tagihan->has_previous_unpaid = $hasPreviousUnpaid;
        return $tagihan;
    });

    $statistik = [
        'total_tagihan' => $tagihans->count(),
        'total_lunas' => 0, // karena hanya belum bayar
        'total_belum_bayar' => $tagihans->count(),
        'total_nominal' => $tagihans->sum('nominal'),
        'total_nominal_lunas' => 0,
        'total_nominal_belum_bayar' => $tagihans->sum('nominal'),
    ];

    return view('wali.tagihan.index', compact('tagihans', 'siswaList', 'selectedSiswaId', 'statistik'));
}

    public function bayar(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $user = Auth::user();
        $siswaIds = $user->siswa->pluck('id')->toArray();

        if (!in_array($tagihan->siswa_id, $siswaIds)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Ambil semua tagihan siswa (tanpa filter status)
        $semuaTagihan = Tagihan::where('siswa_id', $tagihan->siswa_id)->get();

        // Urutkan berdasarkan tahun & bulan
        $bulanMap = array_flip($this->bulanOrder);
        $semuaTagihan = $semuaTagihan->sortBy(function ($item) use ($bulanMap) {
            $bulanIndex = $bulanMap[$item->bulan] ?? 12;
            return $item->tahun . '-' . str_pad($bulanIndex + 1, 2, '0', STR_PAD_LEFT);
        })->values();

        $tagihanYangHarusDibayar = [];
        $totalNominal = 0;
        $targetTercapai = false;

        foreach ($semuaTagihan as $t) {
            // Jika sudah melewati tagihan target, berhenti
            if ($targetTercapai) break;

            // Jika tagihan ini statusnya belum bayar, masukkan ke daftar
            if ($t->status == 'belum_bayar') {
                $tagihanYangHarusDibayar[] = $t;
                $totalNominal += $t->nominal;
            }

            // Jika tagihan ini adalah yang dipilih, tandai target tercapai
            if ($t->id == $tagihan->id) {
                $targetTercapai = true;
            }
        }

        // Log untuk debugging (bisa dihapus setelah sukses)
        Log::info('Akumulasi tagihan', [
            'tagihan_id_dipilih' => $id,
            'jumlah_tagihan_dibayar' => count($tagihanYangHarusDibayar),
            'total_nominal' => $totalNominal,
            'ids' => collect($tagihanYangHarusDibayar)->pluck('id')->toArray()
        ]);

        if (empty($tagihanYangHarusDibayar)) {
            return response()->json(['error' => 'Tidak ada tagihan yang perlu dibayar'], 400);
        }

        $snapToken = $this->createMidtransTransaction($totalNominal, $tagihanYangHarusDibayar, $user);
        return response()->json(['snap_token' => $snapToken]);
    }

    private function createMidtransTransaction($total, $tagihanList, $user)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'SPP-' . time() . '-' . rand(100, 999);

        $itemDetails = array_map(function($tagihan) {
            return [
                'id'       => $tagihan->id,
                'price'    => (int) $tagihan->nominal,
                'quantity' => 1,
                'name'     => 'SPP ' . $tagihan->bulan . ' ' . $tagihan->tahun,
            ];
        }, $tagihanList);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => $itemDetails,
        ];

        // Simpan ke tabel transaksi_pending
        DB::table('transaksi_pending')->insert([
            'order_id'     => $orderId,
            'user_id'      => $user->id,
            'tagihan_ids'  => json_encode(array_map(function($t) { return $t->id; }, $tagihanList)),
            'total'        => $total,
            'status'       => 'pending',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return \Midtrans\Snap::getSnapToken($params);
    }
}