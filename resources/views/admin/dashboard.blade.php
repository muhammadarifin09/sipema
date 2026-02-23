@extends('layouts.admin')

@section('title', 'Dashboard Admin - SMA PGRI Pelaihari')

@section('content')
<!-- Welcome Header -->
<div class="mb-8 animate-slide-in">
    <h1 class="text-3xl font-bold text-white drop-shadow-lg">Dashboard Admin</h1>
    <p class="text-white/80 mt-1">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>! Berikut ringkasan data user.</p>
</div>

<!-- Stat Cards Sederhana -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="stat-card animate-slide-in delay-1">
        <div class="stat-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Total Users</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
        <p class="text-blue-600 text-sm mt-2">
            <i class="fas fa-user-check mr-1"></i>
            Seluruh user terdaftar
        </p>
    </div>

    <!-- Total Admin -->
    <div class="stat-card animate-slide-in delay-2">
        <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-user-tie"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Admin</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAdmin }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Administrator sistem
        </p>
    </div>

    <!-- Total Bendahara -->
    <div class="stat-card animate-slide-in delay-3">
        <div class="stat-icon" style="background: linear-gradient(135deg, #b45309, #d97706);">
            <i class="fas fa-wallet"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Bendahara</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBendahara }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Petugas keuangan
        </p>
    </div>

    <!-- Total Wali Murid -->
    <div class="stat-card animate-slide-in delay-4">
        <div class="stat-icon" style="background: linear-gradient(135deg, #6b21a5, #8b5cf6);">
            <i class="fas fa-user-friends"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Wali Murid</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalWali }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Orang tua/wali siswa
        </p>
    </div>
</div>

<!-- User Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- 5 User Terbaru -->
    <div class="info-card animate-slide-in delay-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">5 User Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-[#0b4f8c] hover:underline">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentUsers as $index => $user)
            <div class="mini-stat-card">
                <div class="mini-stat-icon" style="background: {{ 
                    $user->role->nama_role == 'admin' ? 'linear-gradient(135deg, #059669, #10b981)' : 
                    ($user->role->nama_role == 'bendahara' ? 'linear-gradient(135deg, #b45309, #d97706)' : 
                    'linear-gradient(135deg, #6b21a5, #8b5cf6)') 
                }};">
                    <i class="fas fa-{{ 
                        $user->role->nama_role == 'admin' ? 'user-tie' : 
                        ($user->role->nama_role == 'bendahara' ? 'wallet' : 'user-friends') 
                    }}"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">
                        {{ ucfirst($user->role->nama_role) }} • {{ $user->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Belum ada user terdaftar</p>
            @endforelse
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('admin.users.create') }}" class="text-sm text-[#0b4f8c] hover:underline">
                <i class="fas fa-plus-circle mr-1"></i> Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Info Ringkas -->
    <div class="info-card animate-slide-in delay-3">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Info Ringkas</h3>
            <i class="fas fa-info-circle text-[#0b4f8c] text-xl"></i>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-600">
                    <i class="fas fa-users text-blue-600 mr-2"></i>Total Users
                </span>
                <span class="font-bold text-gray-800">{{ $totalUsers }}</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-600">
                    <i class="fas fa-user-tie text-emerald-600 mr-2"></i>Admin
                </span>
                <span class="font-bold text-gray-800">{{ $totalAdmin }}</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-600">
                    <i class="fas fa-wallet text-orange-600 mr-2"></i>Bendahara
                </span>
                <span class="font-bold text-gray-800">{{ $totalBendahara }}</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-600">
                    <i class="fas fa-user-friends text-purple-600 mr-2"></i>Wali Murid
                </span>
                <span class="font-bold text-gray-800">{{ $totalWali }}</span>
            </div>
        </div>

        <div class="mt-4 p-3 bg-blue-50 rounded-xl">
            <p class="text-sm text-gray-700">
                <i class="fas fa-clock text-blue-600 mr-2"></i>
                Terakhir login: {{ auth()->user()->updated_at->format('d M Y H:i') }}
            </p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
    <a href="{{ route('admin.users.create') }}" class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 text-white hover:bg-white/20 transition-all text-center">
        <i class="fas fa-user-plus text-2xl mb-2"></i>
        <p class="font-medium">Tambah User Baru</p>
    </a>
    <a href="{{ route('admin.users.index') }}" class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 text-white hover:bg-white/20 transition-all text-center">
        <i class="fas fa-list text-2xl mb-2"></i>
        <p class="font-medium">Kelola User</p>
    </a>
</div>
@endsection