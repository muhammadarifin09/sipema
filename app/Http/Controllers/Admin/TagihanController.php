<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\SppSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            }
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