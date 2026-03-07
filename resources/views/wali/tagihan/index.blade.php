@extends('layouts.app')

@section('title', 'Tagihan SPP - SMA PGRI Pelaihari')

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
                    <h1 class="text-lg lg:text-xl font-semibold text-white">Tagihan SPP</h1>
                    <p class="text-xs lg:text-sm text-white/70">SMA PGRI Pelaihari</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 lg:space-x-4">
                <button class="w-10 h-10 lg:w-12 lg:h-12 bg-[#1E3A5F] rounded-2xl flex items-center justify-center hover:bg-[#2B4C7C] transition">
                    <i class="fas fa-bell text-white/90 text-lg"></i>
                </button>
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
        <!-- Welcome Card dengan Filter Siswa -->
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2B4C7C] rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8 shadow-xl animate-slide-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-white/70 text-xs sm:text-sm lg:text-base mb-1">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Daftar Tagihan SPP
                    </p>
                    @if(isset($siswaList) && $siswaList->count() > 0)
                        @php
                            $selectedSiswa = $siswaList->firstWhere('id', $selectedSiswaId);
                        @endphp
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">{{ $selectedSiswa->nama_lengkap ?? 'Pilih Siswa' }}</h2>
                        <p class="text-white/60 text-xs lg:text-sm mt-2 flex items-center">
                            <i class="fas fa-graduation-cap mr-1 lg:mr-2"></i>
                            Kelas {{ $selectedSiswa->kelas->nama_kelas ?? '-' }} • NIS: {{ $selectedSiswa->nis ?? '-' }}
                        </p>
                    @else
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Belum Ada Siswa</h2>
                        <p class="text-white/60 text-xs lg:text-sm mt-2">Hubungi admin untuk menghubungkan siswa</p>
                    @endif
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-[#1E3A5F] rounded-2xl lg:rounded-3xl flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-2xl sm:text-3xl lg:text-4xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Filter Siswa (Jika memiliki lebih dari 1 siswa) -->
        @if(isset($siswaList) && $siswaList->count() > 1)
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 mb-6 animate-slide-in delay-1">
            <div class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="text-white/80 text-sm flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Pilih Siswa:
                </div>
                <div class="flex-1 flex flex-wrap gap-2">
                    @foreach($siswaList as $siswa)
                    <a href="{{ route('wali.tagihan.index', ['siswa_id' => $siswa->id]) }}" 
                       class="px-4 py-2 rounded-lg text-sm transition-all {{ $selectedSiswaId == $siswa->id ? 'bg-white text-[#0B2A4A] font-semibold shadow-lg' : 'bg-white/20 text-white hover:bg-white/30' }}">
                        {{ $siswa->nama_lengkap }}
                        <span class="text-xs ml-1 opacity-70">({{ $siswa->kelas->nama_kelas ?? '-' }})</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Statistik Tagihan -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Total Tagihan -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Total Tagihan</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $statistik['total_tagihan'] ?? 0 }} Bulan</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Sudah Dibayar -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Sudah Dibayar</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-green-600">{{ $statistik['total_lunas'] ?? 0 }} Bulan</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Belum Dibayar -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Belum Dibayar</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-red-600">{{ $statistik['total_belum_bayar'] ?? 0 }} Bulan</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Nominal -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Total Nominal</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-purple-600">Rp {{ number_format($statistik['total_nominal'] ?? 0,0,',','.') }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Tambahan Nominal -->
        @if(isset($statistik) && ($statistik['total_nominal_lunas'] > 0 || $statistik['total_nominal_belum_bayar'] > 0))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-green-50 rounded-xl p-3 flex items-center justify-between">
                <span class="text-sm text-green-700 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Total Lunas:
                </span>
                <span class="font-bold text-green-700">Rp {{ number_format($statistik['total_nominal_lunas'] ?? 0,0,',','.') }}</span>
            </div>
            <div class="bg-red-50 rounded-xl p-3 flex items-center justify-between">
                <span class="text-sm text-red-700 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Total Belum Dibayar:
                </span>
                <span class="font-bold text-red-700">Rp {{ number_format($statistik['total_nominal_belum_bayar'] ?? 0,0,',','.') }}</span>
            </div>
        </div>
        @endif

        <!-- Filter Status -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 mb-6 flex flex-col sm:flex-row gap-3 justify-between items-center">
            <div class="text-white/80 text-sm">
                <i class="fas fa-filter mr-2"></i>
                Filter Status: 
                <select id="statusFilter" class="ml-2 bg-white/20 border border-white/30 rounded-lg px-3 py-1 text-white text-sm">
                    <option value="all" class="text-gray-800">Semua</option>
                    <option value="belum_bayar" class="text-gray-800">Belum Bayar</option>
                    <option value="lunas" class="text-gray-800">Lunas</option>
                </select>
            </div>
            <div class="text-white/80 text-sm">
                <i class="fas fa-search mr-2"></i>
                <input type="text" id="searchInput" placeholder="Cari bulan..." class="bg-white/20 border border-white/30 rounded-lg px-3 py-1 text-white placeholder-white/50 text-sm">
            </div>
        </div>

        <!-- Tabel Tagihan dengan desain modern -->
        <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-xl overflow-hidden animate-slide-in delay-2">
            <!-- Mobile View (Card-based) - Tampil di mobile, hidden di tablet/desktop -->
            <div class="block sm:hidden divide-y divide-gray-100" id="mobileTagihanList">
                @forelse($tagihans as $index => $tagihan)
                <div class="p-4 hover:bg-gray-50 transition tagihan-item" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($tagihan->nama_bulan) }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">Bulan</span>
                        <span class="text-xs text-gray-400">#{{ $index + 1 }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $tagihan->nama_bulan }} {{ $tagihan->tahun }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo }}</p>
                        </div>
                        @if($tagihan->status == 'belum_bayar')
                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                Belum Bayar
                            </span>
                        @elseif($tagihan->status == 'lunas')
                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                Lunas
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                Menunggu
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400">Nominal</p>
                            <p class="font-bold text-gray-800">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                        </div>
                        @if($tagihan->status == 'belum_bayar')
                        <button 
                        onclick="bayarTagihan({{ $tagihan->id }})"
                        class="px-4 py-2 bg-[#0B2A4A] text-white rounded-xl text-xs hover:bg-[#1E3A5F] transition">
                        <i class="fas fa-credit-card mr-1"></i>
                        Bayar Sekarang
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada tagihan SPP untuk siswa ini.</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View (Table) - Hidden di mobile, tampil di tablet/desktop -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full" id="tagihanTable">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Bulan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tahun</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nominal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tagihans as $index => $tagihan)
                        <tr class="hover:bg-gray-50 transition tagihan-row" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($tagihan->nama_bulan) }}">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ $tagihan->nama_bulan }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $tagihan->tahun }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                {{ $tagihan->tanggal_jatuh_tempo }}
                            </td>
                            <td class="px-6 py-4">
                                @if($tagihan->status == 'belum_bayar')
                                    <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum Bayar
                                    </span>
                                @elseif($tagihan->status == 'lunas')
                                    <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                        <i class="fas fa-spinner mr-1"></i>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tagihan->status == 'belum_bayar')
                               <button
                                onclick="bayarTagihan({{ $tagihan->id }})"
                                class="px-4 py-2 bg-[#0B2A4A] text-white rounded-lg text-xs hover:bg-[#1E3A5F] transition inline-flex items-center">
                                <i class="fas fa-credit-card mr-1"></i>
                                Bayar
                                </button>
                                @else
                                <button disabled class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs cursor-not-allowed">
                                    <i class="fas fa-check mr-1"></i>
                                    Selesai
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <i class="fas fa-file-invoice text-5xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada tagihan SPP untuk siswa ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Tabel -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $tagihans->count() }} data tagihan
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
        </div>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-6 lg:mt-8 text-center lg:text-left">
            <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Bottom Navigation - Hanya untuk mobile -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl px-6 py-3 z-20">
            <div class="flex items-center justify-between max-w-md mx-auto">
                <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">Home</span>
                </a>
                <a href="{{ route('wali.tagihan.index') }}" class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-[#0B2A4A]/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-[#0B2A4A]"></i>
                    </div>
                    <span class="text-xs text-[#0B2A4A] mt-1 font-medium">Tagihan</span>
                </a>
                <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">History</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center">
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

    /* Hover efek untuk baris tabel */
    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Responsive table */
    @media (max-width: 640px) {
        .table-container {
            padding: 1rem;
        }
    }
</style>

<script>
    // Filter berdasarkan status
   

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        filterTable();
    });

    function filterTable() {
        const status = document.getElementById('statusFilter').value;
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter untuk mobile view (cards)
        const mobileItems = document.querySelectorAll('#mobileTagihanList .tagihan-item');
        mobileItems.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            const itemBulan = item.getAttribute('data-bulan');
            
            const matchesStatus = status === 'all' || itemStatus === status;
            const matchesSearch = itemBulan.includes(searchTerm);
            
            item.style.display = matchesStatus && matchesSearch ? 'block' : 'none';
        });
        
        // Filter untuk desktop view (table rows)
        const rows = document.querySelectorAll('#tagihanTable tbody .tagihan-row');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowBulan = row.getAttribute('data-bulan');
            
            const matchesStatus = status === 'all' || rowStatus === status;
            const matchesSearch = rowBulan.includes(searchTerm);
            
            row.style.display = matchesStatus && matchesSearch ? '' : 'none';
        });
    }

    


</script>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}"></script>

<style>
/* Custom styles untuk Midtrans popup di mobile */
@media (max-width: 640px) {
    /* Styling untuk popup Midtrans */
    .snap-container iframe,
    iframe[src*="midtrans"],
    .popup-container {
        max-width: 90% !important;
        max-height: 80vh !important;
        margin: 10% auto !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 999999 !important;
    }
    
    /* Styling untuk overlay Midtrans */
    .snap-overlay,
    .popup-overlay {
        background-color: rgba(0, 0, 0, 0.6) !important;
        backdrop-filter: blur(4px) !important;
    }
    
    /* Memastikan popup tidak fullscreen */
    .snap-drawer,
    .snap-modal {
        max-width: 90% !important;
        max-height: 80vh !important;
        margin: 10% auto !important;
        border-radius: 20px !important;
    }
}
</style>

<script>
document.getElementById('statusFilter')?.addEventListener('change', function() {
    filterTable();
});

document.getElementById('searchInput')?.addEventListener('keyup', function() {
    filterTable();
});

function filterTable() {
    const status = document.getElementById('statusFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();

    const mobileItems = document.querySelectorAll('#mobileTagihanList .tagihan-item');
    mobileItems.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        const itemBulan = item.getAttribute('data-bulan');

        const matchesStatus = status === 'all' || itemStatus === status;
        const matchesSearch = itemBulan.includes(searchTerm);

        item.style.display = matchesStatus && matchesSearch ? 'block' : 'none';
    });

    const rows = document.querySelectorAll('#tagihanTable tbody .tagihan-row');
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowBulan = row.getAttribute('data-bulan');

        const matchesStatus = status === 'all' || rowStatus === status;
        const matchesSearch = rowBulan.includes(searchTerm);

        row.style.display = matchesStatus && matchesSearch ? '' : 'none';
    });
}

function bayarTagihan(tagihanId) {
    // Tampilkan loading
    const loadingAlert = alert("Memproses...");
    
    fetch(`/wali/tagihan/${tagihanId}/bayar`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.snap_token) {
            // Konfigurasi Snap Midtrans
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil");
                    location.reload();
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran");
                    location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal");
                    console.error(result);
                },
                onClose: function() {
                    console.log('Popup ditutup');
                }
            });
            
            // Setelah popup muncul, terapkan styling untuk mobile
            setTimeout(function() {
                // Cari elemen popup Midtrans
                const popupElements = document.querySelectorAll('[class*="snap"], [class*="popup"], iframe');
                popupElements.forEach(function(element) {
                    // Cek apakah ini elemen popup Midtrans
                    if (element.src && element.src.includes('midtrans')) {
                        element.style.maxWidth = '90%';
                        element.style.maxHeight = '80vh';
                        element.style.margin = '10% auto';
                        element.style.borderRadius = '20px';
                        element.style.position = 'fixed';
                        element.style.top = '0';
                        element.style.left = '0';
                        element.style.right = '0';
                        element.style.bottom = '0';
                        element.style.zIndex = '999999';
                    }
                });
                
                // Styling untuk overlay
                const overlays = document.querySelectorAll('[class*="overlay"]');
                overlays.forEach(function(overlay) {
                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                    overlay.style.backdropFilter = 'blur(4px)';
                });
            }, 1000);
        } else {
            throw new Error('Snap token tidak ditemukan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan: " + error.message);
    });
}

// Fungsi untuk mendeteksi dan styling popup secara real-time
function styleMidtransPopup() {
    // Interval untuk terus memeriksa keberadaan popup
    const checkInterval = setInterval(function() {
        // Cari elemen yang mungkin merupakan popup Midtrans
        const potentialPopups = document.querySelectorAll(
            'iframe[src*="midtrans"], ' +
            '[class*="snap-modal"], ' +
            '[class*="snap-drawer"], ' +
            '[class*="popup-container"], ' +
            '[role="dialog"]'
        );
        
        if (potentialPopups.length > 0) {
            // Jika di mobile, terapkan styling
            if (window.innerWidth <= 640) {
                potentialPopups.forEach(function(popup) {
                    popup.style.maxWidth = '90%';
                    popup.style.maxHeight = '80vh';
                    popup.style.margin = '10% auto';
                    popup.style.borderRadius = '20px';
                    
                    // Jika ini iframe
                    if (popup.tagName === 'IFRAME') {
                        popup.style.position = 'fixed';
                        popup.style.top = '0';
                        popup.style.left = '0';
                        popup.style.right = '0';
                        popup.style.bottom = '0';
                        popup.style.zIndex = '999999';
                    }
                });
                
                // Styling overlay
                const overlays = document.querySelectorAll('[class*="overlay"]');
                overlays.forEach(function(overlay) {
                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                    overlay.style.backdropFilter = 'blur(4px)';
                });
            }
            
            // Hentikan interval setelah popup ditemukan
            clearInterval(checkInterval);
        }
    }, 500);
    
    // Hentikan interval setelah 10 detik untuk menghindari infinite loop
    setTimeout(function() {
        clearInterval(checkInterval);
    }, 10000);
}

// Panggil fungsi styling saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    styleMidtransPopup();
});

// Tambahkan event listener untuk resize window
window.addEventListener('resize', function() {
    if (window.innerWidth <= 640) {
        styleMidtransPopup();
    }
});
</script>


@endsection