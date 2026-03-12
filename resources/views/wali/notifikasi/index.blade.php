@extends('layouts.app')

@section('title', 'Notifikasi - Wali Murid')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(145deg, #0B2A4A 0%, #123456 100%);">
    <!-- Header dengan background solid dan gaya modern -->
    <div class="bg-[#0B2A4A] border-b border-[#1E3A5F] px-4 sm:px-6 lg:px-8 py-4 sticky top-0 z-10 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('wali.dashboard') }}" class="w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center hover:bg-[#2B4C7C] transition">
                    <i class="fas fa-arrow-left text-white text-lg lg:text-xl"></i>
                </a>
                <div>
                    <h1 class="text-lg lg:text-xl font-semibold text-white">Notifikasi</h1>
                    <p class="text-xs lg:text-sm text-white/70">Pemberitahuan dan informasi terbaru</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 lg:space-x-4">
                <a href="{{ route('wali.notifikasi.index') }}"
                   class="relative w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center hover:bg-[#2B4C7C] transition">
                    <i class="fas fa-bell text-white/90 text-lg"></i>
                    @if(isset($jumlahNotif) && $jumlahNotif > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">
                            {{ $jumlahNotif }}
                        </span>
                    @endif
                </a>
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
        <!-- Welcome Card Notifikasi -->
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2B4C7C] rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8 shadow-xl animate-slide-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/70 text-xs sm:text-sm lg:text-base mb-1">
                        <i class="fas fa-bell mr-2"></i>
                        Total Notifikasi
                    </p>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">{{ $notifikasis->count() }} Notifikasi</h2>
                    <p class="text-white/60 text-xs lg:text-sm mt-2 flex items-center">
                        <i class="fas fa-clock mr-1 lg:mr-2"></i>
                        {{ now()->format('l, d F Y') }}
                    </p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-[#1E3A5F] rounded-2xl lg:rounded-3xl flex items-center justify-center">
                    <i class="fas fa-bell text-2xl sm:text-3xl lg:text-4xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Statistik Notifikasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Total Notifikasi -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Total Notifikasi</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $notifikasis->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Belum Dibaca -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Belum Dibaca</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $notifikasis->where('status', 'unread')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Sudah Dibaca -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Sudah Dibaca</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-green-600">{{ $notifikasis->where('status', 'read')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Notifikasi -->
        <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-xl overflow-hidden animate-slide-in delay-2">
            <!-- Header dengan filter -->
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-list-ul"></i>
                    <span class="font-medium">Daftar Notifikasi</span>
                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <select id="filterStatus" class="text-sm bg-white/20 border border-white/30 rounded-lg px-3 py-1.5 text-white w-full sm:w-auto">
                        <option value="all" class="text-gray-800">Semua Notifikasi</option>
                        <option value="unread" class="text-gray-800">Belum Dibaca</option>
                        <option value="read" class="text-gray-800">Sudah Dibaca</option>
                    </select>
                </div>
            </div>

            <div class="divide-y divide-gray-100" id="notifikasiList">
                @forelse($notifikasis as $notif)
                <div class="p-4 sm:p-6 hover:bg-gray-50 transition notifikasi-item {{ $notif->status == 'unread' ? 'bg-blue-50/30' : '' }}"
                     data-status="{{ $notif->status }}">
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <!-- Icon Status -->
                        <div class="flex-shrink-0">
                            @if($notif->status == 'unread')
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bell text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-check-circle text-gray-400 text-lg sm:text-xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Konten Notifikasi -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1">
                                <h4 class="font-semibold text-gray-800 text-sm sm:text-base {{ $notif->status == 'unread' ? 'text-blue-800' : '' }}">
                                    {{ $notif->judul }}
                                    @if($notif->status == 'unread')
                                        <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-600 text-xs rounded-full">Baru</span>
                                    @endif
                                </h4>
                                <span class="text-xs text-gray-400 mt-1 sm:mt-0">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">
                                {{ $notif->pesan }}
                            </p>

                            <!-- Tombol Aksi -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    @if($notif->status == 'unread')
                                        <a href="{{ route('wali.notifikasi.read', $notif->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs transition">
                                            <i class="fas fa-check mr-1"></i>
                                            Tandai Dibaca
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Sudah Dibaca
                                        </span>
                                    @endif
                                </div>
                                
                                @if($notif->url)
                                    <a href="{{ $notif->url }}"
                                       class="text-xs text-blue-600 hover:text-blue-700 hover:underline">
                                        Lihat Detail
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 sm:p-12 text-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-3xl sm:text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Notifikasi</h3>
                    <p class="text-sm text-gray-500">Tidak ada notifikasi baru saat ini</p>
                </div>
                @endforelse
            </div>

            <!-- Footer -->
            @if($notifikasis->count() > 0)
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $notifikasis->count() }} notifikasi
                </p>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 bg-white border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-left mr-1"></i>
                        Sebelumnya
                    </button>
                    <span class="px-3 py-1 bg-[#0B2A4A] text-white rounded-lg text-sm">1</span>
                    <button class="px-3 py-1 bg-white border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        Selanjutnya
                        <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-6 lg:mt-8 text-center lg:text-left">
            <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Bottom Navigation - Hanya untuk mobile -->
       

        <!-- Spacer untuk bottom navigation -->
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

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
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

    /* Hover efek untuk item notifikasi */
    .notifikasi-item {
        transition: all 0.2s ease;
    }

    .notifikasi-item:hover {
        background-color: #f8fafc;
    }
</style>

<script>
    // Filter notifikasi berdasarkan status
    document.getElementById('filterStatus')?.addEventListener('change', function() {
        const status = this.value;
        const items = document.querySelectorAll('.notifikasi-item');
        
        items.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            
            if (status === 'all' || itemStatus === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    
</script>
@endsection