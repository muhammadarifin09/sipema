<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliProfileController extends Controller
{
    // Tampilkan halaman profil (read-only)
    public function index()
    {
        $user = Auth::user();

        return view('wali.profile', compact('user'));
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();

        return view('wali.profile.edit', compact('user'));
    }

    // Proses update profil
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'hubungan' => 'nullable|in:ayah,ibu,wali',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // upload foto (kalau ada)
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto'), $filename);

            $user->foto = $filename;
        }

        // update data user
        $user->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'hubungan' => $request->hubungan,
        ]);

        return redirect()->route('wali.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}