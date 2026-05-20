<?php
// app/Http/Controllers/WaliMuridController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WaliMuridController extends Controller
{
    /**
     * Mendapatkan prefix view berdasarkan role user yang login
     * - Admin (role_id = 1) → 'admin'
     * - Bendahara (role_id = 2) → 'bendahara'
     * - default → 'admin'
     */
    private function getViewPrefix()
    {
        $user = auth()->user();
        if ($user && $user->role_id == 2) {
            return 'bendahara';
        }
        return 'admin'; // role_id 1 atau lainnya
    }

    // tampil data wali
    public function index()
    {
        $wali = User::with('siswa')
                ->where('role_id', 3)
                ->get();

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.wali.index", compact('wali'));
    }

    // Tampilkan form tambah wali murid
    public function create()
    {
        $siswa = Siswa::whereNull('wali_id')->get();
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.wali.create", compact('siswa'));
    }

    // Simpan data wali murid baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswas,id'
        ]);

        $wali = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
            'role_id'  => 3,
            'active'   => $request->has('active') ? true : false
        ]);

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
        $siswa = Siswa::whereNull('wali_id')
            ->orWhere('wali_id', $id)
            ->get();

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.wali.edit", compact('wali', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $wali = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswas,id'
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
            'active' => $request->has('active') ? true : false
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $wali->update($data);

        Siswa::where('wali_id', $wali->id)->update(['wali_id' => null]);

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
        Siswa::where('wali_id', $wali->id)->update(['wali_id' => null]);
        $wali->delete();

        return redirect()->route('admin.wali.index')
            ->with('success', 'Data berhasil dihapus');
    }
}