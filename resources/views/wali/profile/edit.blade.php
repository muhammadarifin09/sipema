@extends('layouts.app')

@section('title', 'Edit Profil - SMA PGRI Pelaihari')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 overflow-x-hidden">
    
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm px-5 pt-8 pb-3 md:pt-4 md:pb-4 shadow-sm sticky top-0 z-20">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('wali.profile') }}" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <p class="text-gray-600 text-sm">Profil</p>
                    <h2 class="text-gray-800 text-lg font-bold">Edit Profil Wali Murid</h2>
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
            
            {{-- Card Edit --}}
            <div class="bg-white rounded-2xl shadow-md border border-blue-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg flex items-center">
                        <i class="fas fa-user-edit mr-2"></i>
                        Form Edit Profil
                    </h3>
                </div>

                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg text-sm flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('wali.profile.update') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-gray-50/50">
                            </div>

                            {{-- No HP --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">No HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}" 
                                    class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-gray-50/50">
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 inline-flex items-center justify-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                            <a href="{{ route('wali.profile') }}" 
                                class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl transition inline-flex items-center justify-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                        </div>
                    </form>
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