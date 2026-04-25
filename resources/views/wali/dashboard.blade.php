@extends('layouts.app')

@section('title', 'Dashboard Wali Murid - SMA PGRI Pelaihari')

@section('content')
<div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 overflow-x-hidden">
    
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm px-5 pt-8 pb-3 md:pt-4 md:pb-4 shadow-sm sticky top-0 z-20">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:block text-gray-600 hover:text-[#0B2A4A] focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <p class="text-gray-600 text-sm">Selamat datang,</p>
                    <h2 class="text-gray-800 text-xl font-bold">{{ auth()->user()->name }}</h2>
                </div>
            </div>

            <div class="flex items-center justify-between md:justify-end md:space-x-4">
                {{-- Tanggal & Waktu --}}
                <div class="flex items-center space-x-2 text-gray-700 bg-gray-100 px-3 py-1.5 rounded-xl">
                    <i class="fas fa-calendar-alt text-[#0B2A4A]"></i>
                    <span id="realTimeDate" class="text-sm font-medium"></span>
                    <span class="text-gray-400">|</span>
                    <span id="realTimeClock" class="text-sm font-mono font-semibold"></span>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('wali.notifikasi.index') }}" class="relative">
                        <i class="fas fa-bell text-gray-500 text-xl"></i>
                        @if($jumlahNotif > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">{{ $jumlahNotif }}</span>
                        @endif
                    </a>
                    <!-- <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt text-gray-500 text-xl"></i></button>
                    </form> -->
                </div>
            </div>
        </div>
    </div>

    {{-- Wrapper Flex Sidebar + Konten --}}
    <div class="flex overflow-x-hidden">
        {{-- SIDEBAR DESKTOP --}}
        <aside 
            class="hidden md:block bg-white/90 backdrop-blur-sm border-r border-gray-200 h-[calc(100vh-73px)] sticky top-[73px] transition-all duration-300 overflow-hidden"
            :class="sidebarOpen ? 'w-64' : 'w-0'"
        >
            <div class="p-4 space-y-2">
                <a href="{{ route('wali.dashboard') }}" class="flex items-center space-x-3 p-2 rounded-lg bg-blue-50 text-[#0B2A4A]">
                    <i class="fas fa-home w-5"></i>
                    <span x-show="sidebarOpen">Beranda</span>
                </a>
                <a href="{{ route('wali.tagihan.index') }}" class="flex items-center space-x-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-file-invoice w-5"></i>
                    <span x-show="sidebarOpen">Tagihan</span>
                </a>
                <a href="{{ route('wali.riwayat.index') }}" class="flex items-center space-x-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-history w-5"></i>
                    <span x-show="sidebarOpen">Riwayat</span>
                </a>
                <a href="{{ route('wali.profile') }}" class="flex items-center space-x-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-user w-5"></i>
                    <span x-show="sidebarOpen">Profil</span>
                </a>
            </div>
        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 px-4 py-5 md:px-6 lg:px-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto">
                
                {{-- Kartu Saldo --}}
                <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-300 rounded-2xl p-5 shadow-xl mb-6 border border-white/30 relative overflow-hidden">
                    {{-- Decorative circles - perkecil di mobile agar tidak overflow --}}
                    <div class="absolute -right-10 -top-10 w-32 h-32 md:w-40 md:h-40 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-5 -bottom-5 w-24 h-24 md:w-32 md:h-32 bg-white/5 rounded-full"></div>
                    <div class="relative z-10 md:flex md:items-center md:justify-between">
                        <div>
                            <p class="text-white/80 text-xs uppercase tracking-wider">Total Tagihan Bulan Ini</p>
                            <p class="text-white text-3xl lg:text-4xl font-extrabold mt-1 tracking-tight">
                                Rp {{ number_format($tagihan_bulan_ini ?? 0,0,',','.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Daftar Siswa --}}
                @if(isset($siswaTerhubung) && count($siswaTerhubung) > 0)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-users text-[#0B2A4A] mr-2"></i> Daftar Siswa
                    </h3>
                    <div class="space-y-3 md:grid md:grid-cols-2 md:gap-4 md:space-y-0">
                        @foreach($siswaTerhubung as $siswa)
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:border-[#0B2A4A]/30 transition-all duration-200 flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-800">{{ $siswa['nama'] }}</p>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $siswa['kelas'] }}</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">NIS: {{ $siswa['nis'] }}</p>
                            </div>
                            <div class="text-right ml-3">
                                <p class="text-xs text-gray-500">Tunggakan</p>
                                <p class="font-semibold text-red-600">Rp {{ number_format($siswa['total_tunggakan'] ?? 0,0,',','.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl p-4 text-center text-gray-500 mb-6 border border-gray-200">
                    <i class="fas fa-user-slash text-2xl mb-1"></i>
                    <p>Belum ada siswa terhubung</p>
                </div>
                @endif

                {{-- Dua Card --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-300 rounded-xl p-4 md:p-6 shadow-md border border-white/30 relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-white/5 rounded-full"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <p class="text-white/80 text-xs uppercase tracking-wider">Total Pembayaran Bulan Ini</p>
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-1.5">
                                    <i class="fas fa-arrow-down text-white text-sm"></i>
                                </div>
                            </div>
                            <p class="text-white text-2xl md:text-3xl font-bold mt-2">
                                Rp {{ number_format($total_pembayaran_bulan_ini ?? 0,0,',','.') }}
                            </p>
                            <p class="text-white/60 text-xs mt-1">Periode {{ date('F Y') }}</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-300 rounded-xl p-4 md:p-6 shadow-md border border-white/30 relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-white/5 rounded-full"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <p class="text-white/80 text-xs uppercase tracking-wider">Total Tunggakan</p>
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-1.5">
                                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                </div>
                            </div>
                            <p class="text-white text-2xl md:text-3xl font-bold mt-2">
                                Rp {{ number_format($total_tunggakan ?? 0,0,',','.') }}
                            </p>
                            <p class="text-white/60 text-xs mt-1">{{ $bulan_belum_dibayar ?? 0 }} bulan tertunggak</p>
                        </div>
                    </div>
                </div>

                {{-- Grafik Batang --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-gray-800">Riwayat Pembayaran Per Bulan</h3>
                        <span class="text-xs text-gray-500">6 bulan terakhir</span>
                    </div>
                    @php
                        if(isset($pembayaranPerBulan) && is_array($pembayaranPerBulan)) {
                            $chartData = $pembayaranPerBulan;
                        } else {
                            $chartData = [
                                'Nov' => 0,
                                'Des' => 0,
                                'Jan' => 100000,
                                'Feb' => 120000,
                                'Mar' => 920000,
                                'Apr' => 200000,
                            ];
                        }
                        $maxValue = max($chartData);
                        $totalBayar = array_sum($chartData);
                        $targetPerBulan = 1000000;
                        $totalTarget = $targetPerBulan * count($chartData);
                        $persentase = $totalTarget > 0 ? round(($totalBayar / $totalTarget) * 100) : 0;
                    @endphp

                    <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-200 overflow-x-auto">
                        {{-- Container batang --}}
                        <div class="flex items-end justify-center space-x-2 md:space-x-4 h-48 md:h-64 mb-4 min-w-max md:min-w-0">
                            @foreach($chartData as $bulan => $nominal)
                                @php
                                    // Tinggi maksimal 100px di mobile, 120px di desktop (sesuaikan)
                                    $maxHeight = $maxValue > 0 ? ($nominal / $maxValue) * 130 : 0;
                                    $maxHeight = max($maxHeight, 4); // minimal 4px
                                @endphp
                                <div class="flex flex-col items-center w-11 md:w-14">
                                    {{-- Batang --}}
                                    <div class="w-full bg-blue-200 rounded-t-lg transition-all duration-300" style="height: {{ $maxHeight }}px;"></div>
                                    <div class="text-center mt-2">
                                        <span class="text-[10px] md:text-xs font-semibold text-gray-700">Rp {{ number_format($nominal,0,',','.') }}</span>
                                        <p class="text-[10px] md:text-xs text-gray-400 mt-1">{{ $bulan }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-4 pt-2 border-t border-gray-100">
                            <span class="text-2xl font-bold text-[#0B2A4A]">{{ $persentase }}%</span>
                            <span class="text-gray-500 text-sm"> dari target pembayaran</span>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- Bottom Navigation Mobile --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm border-t border-gray-100 px-5 py-2 shadow-lg md:hidden z-20">
        <div class="flex justify-around items-center">
            <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center">
                <i class="fas fa-home text-[#0B2A4A] text-xl"></i>
                <span class="text-[10px] text-[#0B2A4A] mt-1">Beranda</span>
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
                <i class="fas fa-user text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Profil</span>
            </a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>

<script>
    (function() {
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', dateOptions);
            
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);
            
            const dateEl = document.getElementById('realTimeDate');
            const clockEl = document.getElementById('realTimeClock');
            if (dateEl) dateEl.textContent = dateString;
            if (clockEl) clockEl.textContent = timeString;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    })();
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection