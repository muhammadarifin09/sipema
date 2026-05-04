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
use App\Helpers\LogHelper; // Import LogHelper

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
        $detailTagihan = []; // untuk mencatat detail log

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

                // Notifikasi ke wali
                if ($siswa->wali_id) {
                    Notifikasi::create([
                        'user_id' => $siswa->wali_id,
                        'judul' => 'Tagihan SPP Baru',
                        'pesan' => 'Tagihan SPP bulan ' . $bulan . ' telah dibuat.',
                        'status' => 'unread'
                    ]);
                }
            }
        }

        // Catat log aktivitas: generate tagihan
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

        return back()->with('success', "Berhasil generate {$jumlahGenerate} tagihan untuk bulan ini.");
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
}