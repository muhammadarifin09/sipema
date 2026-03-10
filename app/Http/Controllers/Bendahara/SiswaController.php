<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('nis','LIKE',"%{$search}%")
                  ->orWhere('nama_lengkap','LIKE',"%{$search}%")
                  ->orWhere('email','LIKE',"%{$search}%");
            });
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        $kelasList = Kelas::all();

        return view('bendahara.siswa.index', compact('data','kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        return view('bendahara.siswa.create', compact('kelasList'));
    }

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
            'create_user' => 'boolean'
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa/foto','public');
        }

        $siswa = Siswa::create([
            'nis'=>$request->nis,
            'nama_lengkap'=>$request->nama_lengkap,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'tempat_lahir'=>$request->tempat_lahir,
            'tanggal_lahir'=>$request->tanggal_lahir,
            'alamat'=>$request->alamat,
            'no_telp'=>$request->no_telp,
            'email'=>$request->email,
            'kelas_id'=>$request->kelas_id,
            'nama_ayah'=>$request->nama_ayah,
            'nama_ibu'=>$request->nama_ibu,
            'no_telp_orangtua'=>$request->no_telp_orangtua,
            'foto'=>$fotoPath,
            'status'=>$request->status
        ]);

        if ($request->has('create_user') && $request->create_user) {

            User::create([
                'name'=>$request->nama_lengkap,
                'email'=>$request->email ?? $request->nis.'@siswa.sch.id',
                'password'=>Hash::make($request->nis),
                'role_id'=>4,
                'siswa_id'=>$siswa->id
            ]);

        }

        return redirect()->route('bendahara.siswa.index')
        ->with('success','Data siswa berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $siswa = Siswa::with('kelas','user')->findOrFail($id);
        return view('bendahara.siswa.show', compact('siswa'));
    }

    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::all();

        return view('bendahara.siswa.edit', compact('siswa','kelasList'));
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'required|string|max:20|unique:siswas,nis,'.$id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255|unique:siswas,email,'.$id,
            'kelas_id' => 'required|exists:kelas,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telp_orangtua' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,alumni,keluar'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {

            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $data['foto'] = $request->file('foto')->store('siswa/foto','public');
        }

        $siswa->update($data);

        if ($siswa->user) {

            $siswa->user->update([
                'name'=>$request->nama_lengkap,
                'email'=>$request->email ?? $siswa->user->email
            ]);

        }

        return redirect()->route('bendahara.siswa.index')
        ->with('success','Data siswa berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        return redirect()->route('bendahara.siswa.index')
        ->with('success','Data siswa berhasil dihapus');
    }
}