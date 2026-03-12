@extends('layouts.app')

@section('title', 'Dashboard Wali Murid - SMA PGRI Pelaihari')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(145deg, #0B2A4A 0%, #123456 100%);">
    <!-- Header dengan background solid dan gaya modern -->
    <div class="bg-[#0B2A4A] border-b border-[#1E3A5F] px-4 sm:px-6 lg:px-8 py-4 sticky top-0 z-10 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-white text-lg lg:text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg lg:text-xl font-semibold text-white">Dashboard Wali Murid</h1>
                    <p class="text-xs lg:text-sm text-white/70">SMA PGRI Pelaihari</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 lg:space-x-4">
                <a href="{{ route('wali.notifikasi.index') }}"
                class="relative w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center hover:bg-[#2B4C7C] transition">

                    <i class="fas fa-bell text-white/90 text-lg"></i>

                    @if($jumlahNotif > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">
                            {{ $jumlahNotif }}
                        </span>
                    @endif

                </a>
                <!-- <button class="w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center hover:bg-[#2B4C7C] transition lg:hidden">
                    <i class="fas fa-ellipsis-v text-white/90"></i>
                </button> -->
                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-3">
                    <span class="text-white/90 text-sm font-medium">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-[#1E3A5F] hover:bg-[#2B4C7C] text-white rounded-xl text-sm transition flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Welcome Card dengan desain responsive -->
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2B4C7C] rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8 shadow-xl animate-slide-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/70 text-xs sm:text-sm lg:text-base mb-1">Selamat datang kembali 👋</p>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-white/60 text-xs lg:text-sm mt-2 flex items-center">
                        <i class="fas fa-clock mr-1 lg:mr-2"></i>
                        {{ now()->format('l, d F Y') }}
                    </p>
                    @if(isset($siswaList) && $siswaList->count() > 0)
                    <p class="text-white/60 text-xs mt-2 flex items-center">
                        <i class="fas fa-graduation-cap mr-1 lg:mr-2"></i>
                        {{ $siswaList->count() }} siswa terhubung
                    </p>
                    @endif
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-[#1E3A5F] rounded-2xl lg:rounded-3xl flex items-center justify-center">
                    <i class="fas fa-smile text-2xl sm:text-3xl lg:text-4xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Stat Cards - Grid responsive: 1 kolom mobile, 2 kolom tablet, 3 kolom desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Tagihan Bulan Ini -->
            <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-5 lg:p-6 shadow-lg animate-slide-in delay-1 hover:shadow-xl transition">
                <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <span class="text-gray-400 text-xs sm:text-sm">Tagihan Bulan Ini</span>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-lg lg:rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Rp {{ number_format($tagihan_bulan_ini ?? 0,0,',','.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Periode {{ date('F Y') }}</p>
                    </div>
                    <span class="mt-2 sm:mt-0 px-2 sm:px-3 py-1 bg-blue-50 text-blue-600 text-xs rounded-full w-fit">Active</span>
                </div>
            </div>

            <!-- Total Tunggakan -->
            <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-5 lg:p-6 shadow-lg animate-slide-in delay-2 hover:shadow-xl transition">
                <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <span class="text-gray-400 text-xs sm:text-sm">Total Tunggakan</span>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-50 rounded-lg lg:rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-600">Rp {{ number_format($total_tunggakan ?? 0,0,',','.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $bulan_belum_dibayar ?? 0 }} bulan tertunggak</p>
                    </div>
                    @if(($total_tunggakan ?? 0) > 0)
                    <span class="mt-2 sm:mt-0 px-2 sm:px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full w-fit">Overdue</span>
                    @endif
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-5 lg:p-6 shadow-lg animate-slide-in delay-3 hover:shadow-xl transition sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <span class="text-gray-400 text-xs sm:text-sm">Status Pembayaran</span>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-50 rounded-lg lg:rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        @if(($total_tunggakan ?? 0) > 0)
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">Belum Lunas</h3>
                        @else
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">Lunas</h3>
                        @endif
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 {{ ($total_tunggakan ?? 0) > 0 ? 'bg-red-600' : 'bg-green-600' }} rounded-full"></div>
                        <span class="text-xs text-gray-500">{{ ($total_tunggakan ?? 0) > 0 ? 'Aktif' : 'Clear' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jika ada banyak siswa, tampilkan daftar siswa -->
        @if(isset($siswaTerhubung) && count($siswaTerhubung) > 0)
        <!-- Daftar Siswa Cards -->
        <div class="mb-6 animate-slide-in delay-2">
            <h3 class="text-white font-semibold mb-3 flex items-center">
                <i class="fas fa-users mr-2"></i>
                Daftar Siswa ({{ count($siswaTerhubung) }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($siswaTerhubung as $siswa)
                <div class="bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-full flex items-center justify-center">
                                <i class="fas fa-user-graduate text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $siswa['nama'] }}</h4>
                                <p class="text-xs text-gray-400">{{ $siswa['kelas'] }}</p>
                            </div>
                        </div>
                        @if($siswa['status_pembayaran'] == 'lunas')
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lunas</span>
                        @elseif($siswa['status_pembayaran'] == 'belum_lunas')
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Lunas</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-xs text-gray-400">Tagihan Bulan Ini</p>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($siswa['tagihan_bulan_ini'],0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Tunggakan</p>
                            <p class="font-semibold text-red-600">Rp {{ number_format($siswa['total_tunggakan'],0,',','.') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('wali.tagihan.index') }}?siswa_id={{ $siswa['id'] }}" class="mt-3 block text-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg transition">
                        Lihat Tagihan
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Grid 2 kolom untuk tablet/desktop: Informasi Siswa dan Ringkasan Pembayaran -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Informasi Siswa (untuk multiple siswa) -->
            <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-5 lg:p-6 shadow-lg animate-slide-in delay-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base lg:text-lg flex items-center">
                        <i class="fas fa-graduation-cap text-[#0B2A4A] mr-2"></i>
                        Data Siswa Terhubung
                    </h3>
                    <span class="px-2 sm:px-3 py-1 bg-[#0B2A4A]/10 text-[#0B2A4A] text-xs rounded-full">
                        {{ isset($siswaList) ? $siswaList->count() : 0 }} siswa
                    </span>
                </div>

                @if(isset($siswaList) && $siswaList->count() > 0)
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                        @foreach($siswaList as $index => $siswa)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-full flex items-center justify-center text-white text-xs">
                                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $siswa->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400">{{ $siswa->nis }} • {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('wali.tagihan.index') }}?siswa_id={{ $siswa->id }}" class="text-xs text-[#0B2A4A] hover:underline">
                                Detail
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-user-slash text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500 text-sm">Belum ada siswa terhubung</p>
                    </div>
                @endif
            </div>

            <!-- Ringkasan Pembayaran dengan progress bar -->
            <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-5 lg:p-6 shadow-lg animate-slide-in delay-3">
                @php
                    $totalBulan = ($bulan_dibayar ?? 0) + ($bulan_belum_dibayar ?? 0);
                    $progress = $totalBulan > 0 ? round(($bulan_dibayar ?? 0) / $totalBulan * 100) : 0;
                @endphp

                <h3 class="font-semibold text-gray-800 text-sm sm:text-base lg:text-lg flex items-center mb-4">
                    <i class="fas fa-chart-pie text-[#0B2A4A] mr-2"></i>
                    Ringkasan Pembayaran (Semua Siswa)
                </h3>

                <!-- Progress bar -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">Progress Pembayaran</span>
                        <span class="text-xs font-semibold text-[#0B2A4A]">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-xs sm:text-sm text-gray-600">Sudah Dibayar</span>
                        </div>
                        <span class="font-semibold text-gray-800 text-sm sm:text-base">{{ $bulan_dibayar ?? 0 }} bulan</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                            <span class="text-xs sm:text-sm text-gray-600">Belum Dibayar</span>
                        </div>
                        <span class="font-semibold text-gray-800 text-sm sm:text-base">{{ $bulan_belum_dibayar ?? 0 }} bulan</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-xs sm:text-sm text-gray-600">Total Tagihan</span>
                        </div>
                        <span class="font-semibold text-gray-800 text-sm sm:text-base">{{ $total_bulan_tagihan ?? 0 }} bulan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - Grid responsive: 2 kolom mobile, 4 kolom tablet/desktop -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
            <a href="{{ route('wali.tagihan.index') }}" class="bg-white rounded-xl lg:rounded-2xl p-3 sm:p-4 shadow-lg text-center hover:shadow-xl transition-all transform hover:scale-105">
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-lg lg:rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-file-invoice-dollar text-white text-sm sm:text-base lg:text-lg"></i>
                </div>
                <p class="font-medium text-gray-800 text-xs sm:text-sm">Tagihan SPP</p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Lihat detail</p>
            </a>

            <a href="{{ route('wali.riwayat.index') }}" class="bg-white rounded-xl lg:rounded-2xl p-3 sm:p-4 shadow-lg text-center hover:shadow-xl transition-all transform hover:scale-105">
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-lg lg:rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-history text-white text-sm sm:text-base lg:text-lg"></i>
                </div>
                <p class="font-medium text-gray-800 text-xs sm:text-sm">Riwayat</p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Histori bayar</p>
            </a>


           <a href="{{ route('wali.bukti.pembayaran') }}" 
            target="_blank"
            class="bg-white rounded-xl lg:rounded-2xl p-3 sm:p-4 shadow-lg text-center hover:shadow-xl transition-all transform hover:scale-105">

                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-lg lg:rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-download text-white text-sm sm:text-base lg:text-lg"></i>
                </div>

                <p class="font-medium text-gray-800 text-xs sm:text-sm">Unduh</p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Bukti bayar</p>

            </a>


            <a href="#" class="bg-white rounded-xl lg:rounded-2xl p-3 sm:p-4 shadow-lg text-center hover:shadow-xl transition-all transform hover:scale-105">
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-[#0B2A4A] to-[#1E3A5F] rounded-lg lg:rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-headset text-white text-sm sm:text-base lg:text-lg"></i>
                </div>
                <p class="font-medium text-gray-800 text-xs sm:text-sm">Bantuan</p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Hubungi kami</p>
            </a>
        </div>

        <!-- Bottom Navigation dengan perbaikan z-index dan pointer-events -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl px-6 py-3 z-20">
            <div class="flex items-center justify-between max-w-md mx-auto relative" style="z-index: 30;">
                <!-- Home Link -->
                <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center" style="pointer-events: auto; position: relative; z-index: 40;">
                    <div class="w-10 h-10 bg-[#0B2A4A]/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-[#0B2A4A]"></i>
                    </div>
                    <span class="text-xs text-[#0B2A4A] mt-1 font-medium">Home</span>
                </a>

                <!-- Tagihan Link -->
                <a href="{{ route('wali.tagihan.index') }}" class="flex flex-col items-center" style="pointer-events: auto; position: relative; z-index: 40;">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">Tagihan</span>
                </a>

                <!-- History Link -->
                <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center" style="pointer-events: auto; position: relative; z-index: 40;">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">History</span>
                </a>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center" style="pointer-events: auto; position: relative; z-index: 40;">
                    @csrf
                    <button type="submit" class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sign-out-alt text-gray-400"></i>
                        </div>
                        <span class="text-xs text-gray-400 mt-1">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Logout Button (hidden di mobile) -->
        <div class="hidden lg:flex justify-end mt-8">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-6 py-3 bg-[#1E3A5F] hover:bg-[#2B4C7C] text-white rounded-xl transition-all duration-200 flex items-center shadow-lg">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Keluar
                </button>
            </form>
        </div>

        <!-- Spacer untuk bottom navigation di mobile -->
        <div class="lg:hidden h-20"></div>
    </div>
</div>

<style>
    /* Animasi slide in */
    .animate-slide-in {
        animation: slideIn 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .delay-1 {
        animation-delay: 0.1s;
    }

    .delay-2 {
        animation-delay: 0.2s;
    }

    .delay-3 {
        animation-delay: 0.3s;
    }

    .delay-4 {
        animation-delay: 0.4s;
    }

    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover effects untuk cards */
    .bg-white {
        transition: all 0.3s ease;
    }

    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(11, 42, 74, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Safe area untuk notched phones */
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        .fixed.bottom-0 {
            padding-bottom: env(safe-area-inset-bottom);
        }
    }

    /* Desktop hover effects */
    @media (min-width: 1024px) {
        .bg-white {
            transition: all 0.3s ease;
        }
        
        .bg-white:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 30px -10px rgba(11, 42, 74, 0.15);
        }
    }

    /* Tablet adjustments */
    @media (min-width: 640px) and (max-width: 1023px) {
        .container {
            max-width: 100%;
        }
    }
</style>
@endsection