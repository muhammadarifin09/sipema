<?php
// app/Http/Controllers/WaliMuridController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WaliMuridController extends Controller
{
    // tampil data wali
    public function index()
    {
        $wali = User::with('siswa')
                ->where('role_id', 3)
                ->get();

        return view('admin.wali.index', compact('wali'));
    }

    // Tampilkan form tambah wali murid
    public function create()
    {
        // Ambil semua siswa yang belum memiliki wali (wali_id = null)
        $siswa = Siswa::whereNull('wali_id')->get();
        
        return view('admin.wali.create', compact('siswa'));
    }

    // Simpan data wali murid baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswas,id' // Sesuaikan dengan nama tabel 'siswas'
        ]);

        // Buat user baru dengan role wali (role_id = 3)
        $wali = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'active' => $request->has('active') ? true : false
        ]);

        // Hubungkan dengan siswa jika ada
        if ($request->has('siswa_id') && !empty($request->siswa_id)) {
            Siswa::whereIn('id', $request->siswa_id)->update([
                'wali_id' => $wali->id
            ]);
        }

        return redirect()->route('admin.wali.index')
            ->with('success', 'Data wali murid berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wali = User::with('siswa')->findOrFail($id);

        // Ambil siswa yang belum memiliki wali ATAU sudah terhubung dengan wali ini
        $siswa = Siswa::whereNull('wali_id')
            ->orWhere('wali_id', $id)
            ->get();

        return view('admin.wali.edit', compact('wali', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $wali = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswas,id' // Sesuaikan dengan nama tabel 'siswas'
        ]);

        // Update data wali
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->has('active') ? true : false
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $wali->update($data);

        // Reset semua siswa yang sebelumnya terhubung
        Siswa::where('wali_id', $wali->id)->update([
            'wali_id' => null
        ]);

        // Hubungkan siswa baru jika ada
        if ($request->has('siswa_id') && !empty($request->siswa_id)) {
            Siswa::whereIn('id', $request->siswa_id)->update([
                'wali_id' => $wali->id
            ]);
        }

        return redirect()->route('admin.wali.index')
            ->with('success', 'Data wali murid berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $wali = User::findOrFail($id);
        
        // Reset siswa yang terhubung dengan wali ini
        Siswa::where('wali_id', $wali->id)->update([
            'wali_id' => null
        ]);
        
        $wali->delete();

        return redirect()->route('admin.wali.index')
            ->with('success', 'Data berhasil dihapus');
    }
}