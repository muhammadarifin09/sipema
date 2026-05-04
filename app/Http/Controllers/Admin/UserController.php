<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogHelper;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::whereIn('nama_role', ['admin', 'bendahara', 'wali'])->get();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::whereIn('nama_role', ['admin', 'bendahara', 'wali'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Catat log aktivitas: tambah user
        LogHelper::add(
            'create',
            'user',
            'Menambah user baru: ' . $user->name . ' (' . $user->email . ')',
            ['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('nama_role', ['admin', 'bendahara', 'wali'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id'
        ]);

        $oldData = $user->toArray(); // data sebelum update (untuk log)

        $data = [
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Catat log aktivitas: update user
        LogHelper::add(
            'update',
            'user',
            'Mengupdate user: ' . $user->name . ' (' . $user->email . ')',
            [
                'old' => $oldData,
                'new' => $user->fresh()->toArray()
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $userData = $user->toArray(); // simpan data sebelum hapus

        $user->delete();

        // Catat log aktivitas: hapus user
        LogHelper::add(
            'delete',
            'user',
            'Menghapus user: ' . $userData['name'] . ' (' . $userData['email'] . ')',
            ['user' => $userData]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}