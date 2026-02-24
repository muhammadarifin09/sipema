<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        return view('admin.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun.required' => 'Tahun ajaran harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'semester.in' => 'Semester tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        // Cek duplikat data (tahun dan semester yang sama)
        $existingData = TahunAjaran::where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->first();

        if ($existingData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tahun ajaran ' . $request->tahun . ' semester ' . $request->semester . ' sudah ada!');
        }

        // Jika status yang dipilih adalah aktif, nonaktifkan semua data yang aktif
        if ($request->status == 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        TahunAjaran::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
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
        $tahunAjaran = TahunAjaran::findOrFail($id);
        return response()->json($tahunAjaran);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tahun' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun.required' => 'Tahun ajaran harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'semester.in' => 'Semester tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Cek duplikat data (tahun dan semester yang sama) kecuali data yang sedang diedit
        $existingData = TahunAjaran::where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->where('id', '!=', $id)
            ->first();

        if ($existingData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tahun ajaran ' . $request->tahun . ' semester ' . $request->semester . ' sudah ada!');
        }

        // Jika status diubah menjadi aktif, nonaktifkan semua data yang aktif (kecuali data yang sedang diupdate)
        if ($request->status == 'aktif' && $tahunAjaran->status != 'aktif') {
            TahunAjaran::where('status', 'aktif')
                ->where('id', '!=', $id)
                ->update(['status' => 'nonaktif']);
        }

        $tahunAjaran->update([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        
        // Cek apakah data yang akan dihapus berstatus aktif
        if ($tahunAjaran->status == 'aktif') {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus! Nonaktifkan terlebih dahulu.');
        }

        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus');
    }
}