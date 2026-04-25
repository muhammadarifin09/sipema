@extends('layouts.app')

@section('title', 'Profil - SMA PGRI Pelaihari')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 overflow-x-hidden">
    
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm px-5 pt-8 pb-3 md:pt-4 md:pb-4 shadow-sm sticky top-0 z-20">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('wali.dashboard') }}" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <p class="text-gray-600 text-sm">Profil</p>
                    <h2 class="text-gray-800 text-lg font-bold">Profil Wali Murid</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"><i class="fas fa-sign-out-alt text-gray-500 text-xl"></i></button>
            </form>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="px-4 py-5 md:px-8 lg:px-12">
        <div class="max-w-3xl mx-auto">
            
            {{-- Card Profil --}}
            <div class="bg-white rounded-2xl shadow-md border border-blue-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg flex items-center">
                        <i class="fas fa-id-card mr-2"></i>
                        Informasi Profil
                    </h3>
                </div>

                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto mb-3 bg-gradient-to-br from-blue-700 to-blue-400 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $user->name }}</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-xl">
                            <i class="fas fa-phone text-blue-600 mt-1"></i>
                            <div>
                                <p class="text-xs text-gray-500">No HP</p>
                                <p class="font-medium text-gray-800">{{ $user->no_hp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Edit --}}
                    <div class="mt-6">
                        <a href="{{ route('wali.profile.edit') }}" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 inline-flex items-center justify-center space-x-2">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation Mobile --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm border-t border-gray-100 px-5 py-2 shadow-lg md:hidden z-20">
        <div class="flex justify-around items-center">
            <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center">
                <i class="fas fa-home text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Beranda</span>
            </a>
            <a href="{{ route('wali.tagihan.index') }}" class="flex flex-col items-center">
                <i class="fas fa-file-invoice text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Tagihan</span>
            </a>
            <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center">
                <i class="fas fa-history text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Riwayat</span>
            </a>
            <a href="{{ route('wali.profile') }}" class="flex flex-col items-center">
                <i class="fas fa-user text-[#0B2A4A] text-xl"></i>
                <span class="text-[10px] text-[#0B2A4A] mt-1">Profil</span>
            </a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>
@endsection