<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Helpers\LogHelper; // Import LogHelper

class KelasController extends Controller
{
    public function index()
    {
        $data = Kelas::withCount('siswa')->latest()->get();
        return view('bendahara.kelas.index', compact('data'));
    }

    public function create()
    {
        return view('bendahara.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat' => 'required|in:10,11,12',
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi',
            'nama_kelas.unique' => 'Nama kelas sudah ada',
            'tingkat.required' => 'Tingkat harus dipilih',
            'tingkat.in' => 'Tingkat tidak valid',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
        ]);

        // Catat log aktivitas: tambah kelas
        LogHelper::add(
            'create',
            'kelas',
            'Menambah kelas baru: ' . $kelas->nama_kelas . ' (Tingkat ' . $kelas->tingkat . ')',
            ['id' => $kelas->id, 'nama_kelas' => $kelas->nama_kelas, 'tingkat' => $kelas->tingkat]
        );

        return redirect()->route('bendahara.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        return response()->json($kelas);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $id,
            'tingkat' => 'required|in:10,11,12',
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi',
            'nama_kelas.unique' => 'Nama kelas sudah ada',
            'tingkat.required' => 'Tingkat harus dipilih',
            'tingkat.in' => 'Tingkat tidak valid',
        ]);

        $kelas = Kelas::findOrFail($id);
        $oldData = $kelas->toArray(); // data sebelum update

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
        ]);

        // Catat log aktivitas: update kelas
        LogHelper::add(
            'update',
            'kelas',
            'Mengupdate kelas: ' . $kelas->nama_kelas . ' (Tingkat ' . $kelas->tingkat . ')',
            [
                'old' => $oldData,
                'new' => $kelas->fresh()->toArray()
            ]
        );

        return redirect()->route('bendahara.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('bendahara.kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa!');
        }

        $kelasData = $kelas->toArray(); // data sebelum hapus
        $kelas->delete();

        // Catat log aktivitas: hapus kelas
        LogHelper::add(
            'delete',
            'kelas',
            'Menghapus kelas: ' . $kelasData['nama_kelas'] . ' (Tingkat ' . $kelasData['tingkat'] . ')',
            ['kelas' => $kelasData]
        );

        return redirect()->route('bendahara.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus');
    }
}