@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - SMA PGRI Pelaihari')

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
                    <h1 class="text-lg lg:text-xl font-semibold text-white">Riwayat Pembayaran</h1>
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
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Transaksi Pembayaran SPP
                    </p>
                    @if(isset($siswaList) && $siswaList->count() > 0)
                        @php
                            $selectedSiswa = $siswaList->firstWhere('id', $selectedSiswaId ?? request('siswa_id'));
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
                    <i class="fas fa-history text-2xl sm:text-3xl lg:text-4xl text-white"></i>
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
                    <a href="{{ route('wali.pembayaran.riwayat', ['siswa_id' => $siswa->id]) }}" 
                       class="px-4 py-2 rounded-lg text-sm transition-all {{ ($selectedSiswaId ?? request('siswa_id')) == $siswa->id ? 'bg-white text-[#0B2A4A] font-semibold shadow-lg' : 'bg-white/20 text-white hover:bg-white/30' }}">
                        {{ $siswa->nama_lengkap }}
                        <span class="text-xs ml-1 opacity-70">({{ $siswa->kelas->nama_kelas ?? '-' }})</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Statistik Pembayaran -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Total Transaksi -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Total Transaksi</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $statistik['total_transaksi'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Total Pembayaran</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-green-600">Rp {{ number_format($statistik['total_nominal'] ?? 0,0,',','.') }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Transaksi Berhasil -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Transaksi Berhasil</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-green-600">{{ $statistik['berhasil'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Transaksi Pending -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-lg animate-slide-in delay-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Transaksi Pending</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $statistik['pending'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 mb-6 flex flex-col sm:flex-row gap-3 justify-between items-center">
            <div class="text-white/80 text-sm">
                <i class="fas fa-filter mr-2"></i>
                Filter Status: 
                <select id="statusFilter" class="ml-2 bg-white/20 border border-white/30 rounded-lg px-3 py-1 text-white text-sm">
                    <option value="all" class="text-gray-800">Semua</option>
                    <option value="berhasil" class="text-gray-800">Berhasil</option>
                    <option value="pending" class="text-gray-800">Pending</option>
                    <option value="gagal" class="text-gray-800">Gagal</option>
                </select>
            </div>
            <div class="text-white/80 text-sm">
                <i class="fas fa-search mr-2"></i>
                <input type="text" id="searchInput" placeholder="Cari bulan..." class="bg-white/20 border border-white/30 rounded-lg px-3 py-1 text-white placeholder-white/50 text-sm">
            </div>
        </div>

        <!-- Tabel Riwayat Pembayaran dengan desain modern -->
        <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-xl overflow-hidden animate-slide-in delay-2">
            <!-- Mobile View (Card-based) - Tampil di mobile, hidden di tablet/desktop -->
            <div class="block sm:hidden divide-y divide-gray-100" id="mobileRiwayatList">
                @forelse($riwayat as $index => $item)
                <div class="p-4 hover:bg-gray-50 transition riwayat-item" data-status="{{ $item->status }}" data-bulan="{{ strtolower($item->tagihan->nama_bulan) }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">Bulan</span>
                        <span class="text-xs text-gray-400">#{{ $index + 1 }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $item->tagihan->nama_bulan }} {{ $item->tagihan->tahun }}</h3>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y H:i') }}
                            </p>
                        </div>
                        @if($item->status == 'berhasil')
                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>
                                Lunas
                            </span>
                        @elseif($item->status == 'pending')
                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                Pending
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i>
                                Gagal
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-xs text-gray-400">Nominal</p>
                            <p class="font-bold text-gray-800">Rp {{ number_format($item->jumlah_bayar,0,',','.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Metode</p>
                            <p class="text-sm font-medium text-gray-700">{{ $item->metode_bayar }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada riwayat pembayaran</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View (Table) - Hidden di mobile, tampil di tablet/desktop -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full" id="riwayatTable">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Bulan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tahun</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nominal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Metode</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Bayar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayat as $index => $item)
                        <tr class="hover:bg-gray-50 transition riwayat-row" data-status="{{ $item->status }}" data-bulan="{{ strtolower($item->tagihan->nama_bulan) }}">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ $item->tagihan->nama_bulan }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->tagihan->tahun }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800">Rp {{ number_format($item->jumlah_bayar,0,',','.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $item->metode_bayar }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status == 'berhasil')
                                    <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                @elseif($item->status == 'pending')
                                    <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Gagal
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <i class="fas fa-history text-5xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada riwayat pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Tabel -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $riwayat->count() }} data riwayat pembayaran
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
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">Tagihan</span>
                </a>
                <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-[#0B2A4A]/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-[#0B2A4A]"></i>
                    </div>
                    <span class="text-xs text-[#0B2A4A] mt-1 font-medium">Riwayat</span>
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
</style>

<script>
    // Filter berdasarkan status
    document.getElementById('statusFilter')?.addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        filterTable();
    });

    function filterTable() {
        const status = document.getElementById('statusFilter').value;
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter untuk mobile view (cards)
        const mobileItems = document.querySelectorAll('#mobileRiwayatList .riwayat-item');
        mobileItems.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            const itemBulan = item.getAttribute('data-bulan');
            
            const matchesStatus = status === 'all' || itemStatus === status;
            const matchesSearch = itemBulan.includes(searchTerm);
            
            item.style.display = matchesStatus && matchesSearch ? 'block' : 'none';
        });
        
        // Filter untuk desktop view (table rows)
        const rows = document.querySelectorAll('#riwayatTable tbody .riwayat-row');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowBulan = row.getAttribute('data-bulan');
            
            const matchesStatus = status === 'all' || rowStatus === status;
            const matchesSearch = rowBulan.includes(searchTerm);
            
            row.style.display = matchesStatus && matchesSearch ? '' : 'none';
        });
    }
</script>
@endsection