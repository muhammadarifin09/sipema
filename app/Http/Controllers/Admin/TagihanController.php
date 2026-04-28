<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.tagihan.index', compact('data', 'bulan', 'tahun'));
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

        $siswas = Siswa::all(); // kalau ada status aktif, tambahkan where('status','aktif')

        $jumlahGenerate = 0;

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

                if ($siswa->wali_id) {

                    // simpan ke grouping per wali
                    $tagihanPerWali[$siswa->wali_id][] = [
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nominal' => $tagihan->nominal,
                    ];

                    // Notifikasi tetap jalan
                    Notifikasi::create([
                        'user_id' => $siswa->wali_id,
                        'judul' => 'Tagihan SPP Baru',
                        'pesan' => 'Tagihan SPP bulan '.$bulan.' telah dibuat.',
                        'status' => 'unread'
                    ]);
                }
            }

        }

        $sentNumbers = [];

foreach ($tagihanPerWali as $wali_id => $listSiswa) {

    $wali = User::find($wali_id);

    if (!$wali || !$wali->no_hp) {
        \Log::warning("Wali ID {$wali_id} tidak punya nomor HP");
        continue;
    }

    // normalisasi nomor
    $no_hp = $wali->no_hp;

    if (substr($no_hp, 0, 1) == '0') {
        $no_hp = '62' . substr($no_hp, 1);
    }

    $no_hp = str_replace('+', '', $no_hp);

    // hindari nomor duplikat
    if (in_array($no_hp, $sentNumbers)) {
        continue;
    }

    $sentNumbers[] = $no_hp;

    // susun pesan
   $pesan = "Yth. Bapak/Ibu Wali Murid,\n\n"
    ."Kami informasikan bahwa tagihan SPP untuk bulan {$bulan}/{$tahun} telah tersedia.\n\n"
    ."Berikut rincian tagihan:\n\n";

    foreach ($listSiswa as $s) {
        $pesan .= "- {$s['nama_lengkap']} : Rp ".number_format($s['nominal'],0,',','.')."\n";
    }

    $pesan .= "\nPembayaran dapat dilakukan sesuai dengan ketentuan yang berlaku.\n"
    ."Mohon untuk melakukan pembayaran sebelum tanggal jatuh tempo.\n\n"
    ."Atas perhatian dan kerja samanya, kami ucapkan terima kasih.\n\n"
    ."Hormat kami,\n"
    ."Admin Sekolah";

    $response = FonnteHelper::send($no_hp, $pesan);

\Log::info('Fonnte Response', [
    'no_hp' => $no_hp,
    'response' => $response
]);

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

        $tagihan->delete();

        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}