<?php
// app/Http/Controllers/Admin/SiswaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'LIKE', "%{$search}%")
                  ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        $kelasList = Kelas::all();
        
        return view('admin.siswa.index', compact('data', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasList = Kelas::all();
        return view('admin.siswa.create', compact('kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255|unique:siswas,email',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telp_orangtua' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,alumni,keluar',
            'create_user' => 'boolean', // Opsi untuk membuat akun login
        ], [
            'nis.required' => 'NIS harus diisi',
            'nis.unique' => 'NIS sudah digunakan',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'kelas_id.required' => 'Kelas harus dipilih',
            'email.unique' => 'Email sudah digunakan',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
        }

        // Buat data siswa
        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'no_telp_orangtua' => $request->no_telp_orangtua,
            'foto' => $fotoPath,
            'status' => $request->status,
        ]);

        // Buat akun user untuk login jika dicentang
        if ($request->has('create_user') && $request->create_user) {
            User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email ?? $request->nis . '@siswa.sch.id',
                'password' => Hash::make($request->nis), // Password default = NIS
                'role_id' => 4, // Role ID untuk siswa (sesuaikan dengan roles di DB)
                'siswa_id' => $siswa->id,
            ]);
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with('kelas', 'user')->findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'required|string|max:20|unique:siswas,nis,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255|unique:siswas,email,' . $id,
            'kelas_id' => 'required|exists:kelas,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telp_orangtua' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,alumni,keluar',
        ]);

        // Data yang akan diupdate
        $data = [
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'no_telp_orangtua' => $request->no_telp_orangtua,
            'status' => $request->status,
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $data['foto'] = $request->file('foto')->store('siswa/foto', 'public');
        }

        $siswa->update($data);

        // Update data user jika ada
        if ($siswa->user) {
            $siswa->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email ?? $siswa->user->email,
            ]);
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        // Cek apakah siswa memiliki relasi dengan data lain
        // Misalnya: pembayaran, rapor, dll

        // Hapus foto
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        // Hapus user terkait jika ada
        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Bulk delete selected items.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:siswas,id'
        ]);

        $siswas = Siswa::whereIn('id', $request->ids)->get();

        foreach ($siswas as $siswa) {
            // Hapus foto
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            
            // Hapus user terkait
            if ($siswa->user) {
                $siswa->user->delete();
            }
            
            $siswa->delete();
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', count($request->ids) . ' data siswa berhasil dihapus');
    }

    /**
     * Export data to Excel/PDF (opsional)
     */
    public function export(Request $request)
    {
        // Implementasi export data
        // Bisa menggunakan Laravel Excel atau dompdf
    }

    /**
     * Import data from Excel (opsional)
     */
    public function import(Request $request)
    {
        // Implementasi import data
    }
}