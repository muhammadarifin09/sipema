<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\SppSetting;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SppSettingController extends Controller
{
    /**
     * Menampilkan daftar setting SPP
     */
    public function index()
    {
        $data = SppSetting::with('tahunAjaran')->get();
        $tahunAjaran = TahunAjaran::all();
        return view('bendahara.spp-setting.index', compact('data', 'tahunAjaran'));
    }

    /**
     * Form tambah setting SPP
     */
    public function create()
    {
        $tahunAjaran = TahunAjaran::all();
        return view('bendahara.spp-setting.create', compact('tahunAjaran'));
    }

    /**
     * Simpan setting SPP baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:31',
        ]);

        $exists = SppSetting::where('tahun_ajaran_id', $request->tahun_ajaran_id)->exists();
        if ($exists) {
            return redirect()->back()
                ->with('error', 'Setting SPP untuk tahun ajaran ini sudah ada!')
                ->withInput();
        }

        SppSetting::create($request->all());

        return redirect()->route('bendahara.spp-setting.index')
            ->with('success', 'Setting SPP berhasil ditambahkan');
    }

    /**
     * Form edit setting SPP
     */
    public function edit(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $tahunAjaran = TahunAjaran::all();
        return view('bendahara.spp-setting.edit', compact('sppSetting', 'tahunAjaran'));
    }

    /**
     * Update setting SPP
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:31',
        ]);

        $sppSetting = SppSetting::findOrFail($id);

        $exists = SppSetting::where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Setting SPP untuk tahun ajaran ini sudah ada!')
                ->withInput();
        }

        $sppSetting->update($request->all());

        return redirect()->route('bendahara.spp-setting.index')
            ->with('success', 'Setting SPP berhasil diperbarui');
    }

    /**
     * Hapus setting SPP
     */
    public function destroy(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $sppSetting->delete();

        return redirect()->route('bendahara.spp-setting.index')
            ->with('success', 'Setting SPP berhasil dihapus');
    }
}