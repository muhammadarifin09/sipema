<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\SppSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Notifikasi;
use App\Helpers\FonnteHelper;  
use App\Models\User; 
use App\Helpers\LogHelper; 
use Illuminate\Support\Facades\Log;

class TagihanController extends Controller
{
    /**
     * Tampilkan daftar tagihan
     */
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $data = Tagihan::with(['siswa', 'tahunAjaran'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bendahara.tagihan.index', compact('data', 'bulan', 'tahun'));
    }

    /**
     * Generate tagihan bulan tertentu
     */
    public function generate(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAjaran) {
            return back()->with('error', 'Tidak ada Tahun Ajaran aktif.');
        }

        $spp = SppSetting::where('tahun_ajaran_id', $tahunAjaran->id)->first();

        if (!$spp) {
            return back()->with('error', 'SPP Setting belum dibuat untuk tahun ajaran aktif.');
        }

        $siswas = Siswa::all();

        $jumlahGenerate = 0;
        $detailTagihan = []; // untuk catatan log detail

        // Kelompokkan tagihan baru per wali untuk keperluan WA
        $tagihanPerWali = [];

        foreach ($siswas as $siswa) {
            $tagihan = Tagihan::firstOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'nominal' => $spp->nominal,
                    'tanggal_jatuh_tempo' => $spp->tanggal_jatuh_tempo,
                    'status' => 'belum_bayar',
                ]
            );

            if ($tagihan->wasRecentlyCreated) {
                $jumlahGenerate++;
                $detailTagihan[] = [
                    'siswa_id' => $siswa->id,
                    'nama_siswa' => $siswa->nama_lengkap,
                    'nominal' => $spp->nominal,
                ];

                // Notifikasi ke wali (di database)
                if ($siswa->wali_id) {
                    Notifikasi::create([
                        'user_id' => $siswa->wali_id,
                        'judul' => 'Tagihan SPP Baru',
                        'pesan' => 'Tagihan SPP bulan ' . $bulan . ' telah dibuat.',
                        'status' => 'unread'
                    ]);

                    // Kelompokkan untuk keperluan WA
                    $tagihanPerWali[$siswa->wali_id][] = [
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nominal' => $tagihan->nominal,
                    ];
                }
            }
        }

        // ========== KIRIM WHATSAPP VIA FONNTE ==========
        $sentNumbers = [];

        foreach ($tagihanPerWali as $wali_id => $listSiswa) {
            $wali = User::find($wali_id);

            if (!$wali || !$wali->no_hp) {
                Log::warning("Wali ID {$wali_id} tidak punya nomor HP");
                continue;
            }

            // Normalisasi nomor HP
            $no_hp = $wali->no_hp;
            if (substr($no_hp, 0, 1) == '0') {
                $no_hp = '62' . substr($no_hp, 1);
            }
            $no_hp = str_replace('+', '', $no_hp);

            // Hindari duplikasi nomor
            if (in_array($no_hp, $sentNumbers)) {
                continue;
            }
            $sentNumbers[] = $no_hp;

            // Susun pesan WA
            $pesan = "Yth. Bapak/Ibu Wali Murid,\n\n"
                . "Kami informasikan bahwa tagihan SPP untuk bulan {$bulan}/{$tahun} telah tersedia.\n\n"
                . "Berikut rincian tagihan:\n\n";

            foreach ($listSiswa as $s) {
                $pesan .= "- {$s['nama_lengkap']} : Rp " . number_format($s['nominal'], 0, ',', '.') . "\n";
            }

            $pesan .= "\nPembayaran dapat dilakukan sesuai dengan ketentuan yang berlaku.\n"
                . "Mohon untuk melakukan pembayaran sebelum tanggal jatuh tempo.\n\n"
                . "Atas perhatian dan kerja samanya, kami ucapkan terima kasih.\n\n"
                . "Hormat kami,\n"
                . "Bendahara Sekolah";

            // Kirim via Fonnte
            $response = FonnteHelper::send($no_hp, $pesan);

            // Catat log (opsional)
            Log::info('Fonnte Response (Bendahara)', [
                'no_hp' => $no_hp,
                'response' => $response
            ]);
        }
        // ========== END KIRIM WA ==========

        // Catat log aktivitas generate tagihan (tetap pakai LogHelper)
        if ($jumlahGenerate > 0) {
            LogHelper::add(
                'create',
                'tagihan',
                'Generate tagihan SPP untuk bulan ' . $bulan . ' tahun ' . $tahun . '. Jumlah tagihan: ' . $jumlahGenerate,
                [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tahun_ajaran' => $tahunAjaran->nama,
                    'nominal_per_siswa' => $spp->nominal,
                    'detail' => $detailTagihan
                ]
            );
        } else {
            LogHelper::add(
                'create',
                'tagihan',
                'Generate tagihan SPP untuk bulan ' . $bulan . ' tahun ' . $tahun . ', tetapi tidak ada tagihan baru (sudah ada sebelumnya).',
                [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tahun_ajaran' => $tahunAjaran->nama,
                ]
            );
        }

        return back()->with('success', "Berhasil generate {$jumlahGenerate} tagihan untuk bulan ini. WA notifikasi telah dikirim ke wali.");
    }

    /**
     * Hapus tagihan
     */
    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak bisa dihapus.');
        }

        // Simpan data tagihan sebelum dihapus untuk keperluan log
        $dataTagihan = [
            'id' => $tagihan->id,
            'siswa_id' => $tagihan->siswa_id,
            'nama_siswa' => $tagihan->siswa->nama_lengkap ?? 'Tidak diketahui',
            'bulan' => $tagihan->bulan,
            'tahun' => $tagihan->tahun,
            'nominal' => $tagihan->nominal,
            'status' => $tagihan->status,
        ];

        $tagihan->delete();

        // Catat log aktivitas: hapus tagihan
        LogHelper::add(
            'delete',
            'tagihan',
            'Menghapus tagihan SPP untuk siswa ' . $dataTagihan['nama_siswa'] . ' bulan ' . $dataTagihan['bulan'] . ' tahun ' . $dataTagihan['tahun'],
            ['tagihan' => $dataTagihan]
        );

        return back()->with('success', 'Tagihan berhasil dihapus.');
    }


    public function unpaidTracking(Request $request)
{
    $siswas = Siswa::with(['tagihan' => function ($query) {
        $query->orderBy('tahun')->orderBy('bulan');
    }, 'kelas'])->get();

    $data = [];
    foreach ($siswas as $siswa) {
        $monthsData = [];
        $totalUnpaid = 0;
        $allLunas = true; // asumsi awalnya lunas
        foreach ($siswa->tagihan as $tagihan) {
            $bulanNama = Carbon::create()->month((int)$tagihan->bulan)->translatedFormat('F');
            $monthsData[] = [
                'id'          => $tagihan->id,
                'bulan_nama'  => $bulanNama,
                'bulan'       => $tagihan->bulan,
                'tahun'       => $tagihan->tahun,
                'nominal'     => $tagihan->nominal,
                'status'      => $tagihan->status,
                'jatuh_tempo' => $tagihan->tanggal_jatuh_tempo,
            ];
            if ($tagihan->status == 'belum_bayar') {
                $totalUnpaid += $tagihan->nominal;
                $allLunas = false;
            }
        }
        // Jika tidak ada tagihan sama sekali, anggap belum lunas? Bisa juga anggap status "Belum Ada Tagihan"
        if ($siswa->tagihan->isEmpty()) {
            $allLunas = false; // atau status "Tidak Ada Tagihan"
        }

        $data[] = [
            'siswa'        => $siswa,
            'nis'          => $siswa->nis, // asumsi ada field nis
            'status_agregat' => $allLunas ? 'Lunas' : 'Belum Lunas',
            'total_unpaid' => $totalUnpaid,
            'months'       => $monthsData,
        ];
    }

    // Urutkan berdasarkan status belum lunas dulu, lalu nama
    usort($data, function ($a, $b) {
        if ($a['status_agregat'] == $b['status_agregat']) {
            return $a['siswa']->nama_lengkap <=> $b['siswa']->nama_lengkap;
        }
        return ($a['status_agregat'] == 'Belum Lunas') ? -1 : 1;
    });

    return view('bendahara.tagihan.unpaid-tracking', compact('data'));
}

}