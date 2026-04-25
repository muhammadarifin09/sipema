@extends('layouts.app')

@section('title', 'Notifikasi - Wali Murid')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm px-5 pt-8 pb-3 md:pt-4 md:pb-4 shadow-sm sticky top-0 z-20">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('wali.dashboard') }}" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <p class="text-gray-600 text-sm">Notifikasi</p>
                    <h2 class="text-gray-800 text-lg font-bold">Pemberitahuan</h2>
                </div>
            </div>
            <!-- <div class="flex items-center space-x-3">
                <a href="{{ route('wali.notifikasi.index') }}" class="relative">
                    <i class="fas fa-bell text-gray-500 text-xl"></i>
                    @if(isset($jumlahNotif) && $jumlahNotif > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">{{ $jumlahNotif }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt text-gray-500 text-xl"></i>
                    </button>
                </form>
            </div> -->
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="px-4 py-5 md:px-8 lg:px-12">
        <div class="max-w-7xl mx-auto">
            {{-- Welcome Card --}}
            <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-300 rounded-2xl p-5 shadow-xl mb-6 border border-white/30 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/5 rounded-full"></div>
                <div class="relative z-10 md:flex md:items-center md:justify-between">
                    <div>
                        <p class="text-white/80 text-xs uppercase tracking-wider flex items-center">
                            <i class="fas fa-bell mr-2"></i>Total Notifikasi
                        </p>
                        <h2 class="text-white text-xl md:text-2xl font-bold mt-1">{{ $notifikasis->count() }} Notifikasi</h2>
                        <p class="text-white/70 text-sm mt-1 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 inline-flex items-center">
                            <i class="fas fa-bell text-white mr-2"></i>
                            <span class="text-white text-sm">Pemberitahuan</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Filter --}}
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200 flex justify-end">
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-filter mr-2"></i>
                    <select id="filterStatus" class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                        <option value="all">Semua</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>
            </div>

            {{-- Daftar Notifikasi --}}
            <div class="bg-white rounded-2xl shadow-md border border-blue-200 overflow-hidden mb-6">
                {{-- Header Daftar --}}
                <div class="px-6 py-4 bg-gradient-to-r from-[#305CDE] to-[#305CDE] text-white flex items-center">
                    <i class="fas fa-list-ul mr-2"></i>
                    <span class="font-medium">Daftar Notifikasi</span>
                </div>

                <div class="divide-y divide-blue-100" id="notifikasiList">
                    @forelse($notifikasis as $notif)
                    <div class="p-4 sm:p-6 notifikasi-item {{ $notif->status == 'unread' ? 'bg-blue-50/50' : '' }}"
                         data-status="{{ $notif->status }}">
                        <div class="flex items-start space-x-3 sm:space-x-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0">
                                @if($notif->status == 'unread')
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-bell text-blue-600"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-check-circle text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Konten --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1">
                                    <h4 class="font-semibold text-gray-800 {{ $notif->status == 'unread' ? 'text-blue-800' : '' }}">
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
                                <p class="text-sm text-gray-600 mb-3">{{ $notif->pesan }}</p>
                                <div class="flex items-center justify-between">
                                    <div>
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
                                        <a href="{{ $notif->url }}" class="text-xs text-blue-600 hover:text-blue-700 hover:underline">
                                            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-3xl mb-2"></i>
                        <p>Belum ada notifikasi.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                @if($notifikasis->count() > 0)
                <div class="px-6 py-4 bg-gray-50 border-t border-blue-200 text-sm text-gray-600 flex justify-between items-center">
                    <span>Menampilkan {{ $notifikasis->count() }} notifikasi</span>
                </div>
                @endif
            </div>

            {{-- Tombol Kembali --}}
            <div class="text-center md:text-left">
                <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
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
            <a href="#" class="flex flex-col items-center">
                <i class="fas fa-user text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Profil</span>
            </a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>

<script>
    // Filter notifikasi
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

<style>
    /* Styling tambahan */
    .bg-gradient-to-br {
        background-size: 100% 100%;
    }
    .notifikasi-item {
        transition: background-color 0.2s;
    }
</style>
@endsection