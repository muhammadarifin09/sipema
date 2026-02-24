<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppSetting;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $data = SppSetting::with('tahunAjaran')->get();
    $tahunAjaran = TahunAjaran::all(); // Pastikan ini dikirim
    return view('admin.spp-setting.index', compact('data', 'tahunAjaran'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAjaran = TahunAjaran::all();
        return view('admin.spp-setting.create', compact('tahunAjaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:31',
        ]);

        // Cek duplikasi
        $exists = SppSetting::where('tahun_ajaran_id', $request->tahun_ajaran_id)->exists();
        if ($exists) {
            return redirect()->back()
                ->with('error', 'Setting SPP untuk tahun ajaran ini sudah ada!')
                ->withInput();
        }

        SppSetting::create($request->all());

        return redirect()->route('admin.spp-setting.index')
            ->with('success', 'SPP Setting berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $tahunAjaran = TahunAjaran::all();
        return view('admin.spp-setting.edit', compact('sppSetting', 'tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:31',
        ]);

        $sppSetting = SppSetting::findOrFail($id);
        
        // Cek duplikasi kecuali data sendiri
        $exists = SppSetting::where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->with('error', 'Setting SPP untuk tahun ajaran ini sudah ada!')
                ->withInput();
        }

        $sppSetting->update($request->all());

        return redirect()->route('admin.spp-setting.index')
            ->with('success', 'SPP Setting berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $sppSetting->delete();

        return redirect()->route('admin.spp-setting.index')
            ->with('success', 'SPP Setting berhasil dihapus');
    }
}