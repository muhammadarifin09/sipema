@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - SMA PGRI Pelaihari')

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
                    <p class="text-gray-600 text-sm">Riwayat Pembayaran</p>
                    <h2 class="text-gray-800 text-lg font-bold">SMA PGRI Pelaihari</h2>
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
                            <i class="fas fa-history mr-2"></i>Riwayat Transaksi Pembayaran SPP
                        </p>
                        @if(isset($siswaList) && $siswaList->count() > 0)
                            @php
                                $selectedSiswa = $siswaList->firstWhere('id', $selectedSiswaId ?? request('siswa_id'));
                            @endphp
                            <h2 class="text-white text-xl md:text-2xl font-bold mt-1">{{ $selectedSiswa->nama_lengkap ?? 'Pilih Siswa' }}</h2>
                            <p class="text-white/70 text-sm mt-1 flex items-center">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Kelas {{ $selectedSiswa->kelas->nama_kelas ?? '-' }} • NIS: {{ $selectedSiswa->nis ?? '-' }}
                            </p>
                        @else
                            <h2 class="text-white text-xl md:text-2xl font-bold">Belum Ada Siswa</h2>
                            <p class="text-white/70 text-sm mt-1">Hubungi admin untuk menghubungkan siswa</p>
                        @endif
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 inline-flex items-center">
                            <i class="fas fa-history text-white mr-2"></i>
                            <span class="text-white text-sm">Riwayat</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Siswa (jika lebih dari 1) --}}
            @if(isset($siswaList) && $siswaList->count() > 1)
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-600 text-sm"><i class="fas fa-users mr-1"></i>Pilih Siswa:</span>
                    @foreach($siswaList as $siswa)
                    <a href="{{ route('wali.riwayat.index', ['siswa_id' => $siswa->id]) }}" 
                       class="px-4 py-2 rounded-lg text-sm transition {{ ($selectedSiswaId ?? request('siswa_id')) == $siswa->id ? 'bg-[#0B2A4A] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $siswa->nama_lengkap }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Statistik Kartu (Mobile: 2 kolom, Desktop: 4 kolom) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Transaksi -->
                <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-xl p-4 text-white shadow-md">
                    <p class="text-white/80 text-xs uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold mt-1">{{ $statistik['total_transaksi'] ?? 0 }}</p>
                    <i class="fas fa-exchange-alt text-white/50 text-2xl absolute bottom-2 right-2"></i>
                </div>
                <!-- Total Pembayaran -->
                <div class="bg-gradient-to-br from-green-600 to-green-400 rounded-xl p-4 text-white shadow-md">
                    <p class="text-white/80 text-xs uppercase tracking-wider">Total Pembayaran</p>
                    <p class="text-lg font-bold mt-1 truncate">Rp {{ number_format($statistik['total_nominal'] ?? 0,0,',','.') }}</p>
                </div>
            </div>

            {{-- Filter & Pencarian --}}
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-search mr-2"></i>
                    <input type="text" id="searchInput" placeholder="Cari bulan..." class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                </div>
            </div>

            {{-- Tabel Riwayat dengan Border Biru --}}
            <div class="bg-white rounded-2xl shadow-md border border-blue-600 overflow-hidden mb-6">
                {{-- Mobile View (Card) dengan border biru setiap item --}}
                <div class="block sm:hidden divide-y divide-blue-100" id="mobileRiwayatList">
                    @forelse($riwayat as $index => $item)
                    <div class="p-4 riwayat-item border border-blue-200 rounded-lg mb-3 mx-2 first:mt-2" data-status="{{ $item->status }}" data-bulan="{{ strtolower($item->tagihan->nama_bulan ?? '') }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $item->tagihan->nama_bulan ?? '-' }} {{ $item->tagihan->tahun ?? '' }}</h3>
                                <p class="text-xs text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y H:i') }}
                                </p>
                            </div>
                            @if($item->status == 'berhasil')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lunas</span>
                            @elseif($item->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Gagal</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Rp {{ number_format($item->jumlah_bayar,0,',','.') }}</span>
                            <span class="text-sm text-gray-600">{{ $item->metode_bayar }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-history text-3xl mb-2"></i>
                        <p>Belum ada riwayat pembayaran.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Desktop View (Table) dengan border biru pada sel --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full border-collapse" id="riwayatTable">
                        <thead class="bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">Bulan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">Tahun</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">Metode</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold border-r border-blue-300/30">Tanggal Bayar</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-100">
                            @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-gray-50 riwayat-row" data-status="{{ $item->status }}" data-bulan="{{ strtolower($item->tagihan->nama_bulan ?? '') }}">
                                <td class="px-6 py-4 text-sm text-gray-600 border-r border-blue-100">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800 border-r border-blue-100">{{ $item->tagihan->nama_bulan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 border-r border-blue-100">{{ $item->tagihan->tahun ?? '' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800 border-r border-blue-100">Rp {{ number_format($item->jumlah_bayar,0,',','.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 border-r border-blue-100">{{ $item->metode_bayar }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 border-r border-blue-100">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($item->status == 'berhasil')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lunas</span>
                                    @elseif($item->status == 'pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full">Gagal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-500">
                                    <i class="fas fa-history text-3xl mb-2"></i>
                                    <p>Belum ada riwayat pembayaran.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Tabel --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-blue-200 text-sm text-gray-600 flex justify-between items-center">
                    <span>Menampilkan {{ $riwayat->count() }} data</span>
                </div>
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
                <i class="fas fa-history text-[#0B2A4A] text-xl"></i>
                <span class="text-[10px] text-[#0B2A4A] mt-1">Riwayat</span>
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
    // Filter & Search
    const searchInput = document.getElementById('searchInput');

    function filterTable() {
        const term = searchInput.value.toLowerCase();
        
        document.querySelectorAll('.riwayat-item, .riwayat-row').forEach(el => {
            const elBulan = el.dataset.bulan || '';
            const matchSearch = elBulan.includes(term);
            el.style.display = matchSearch ? '' : 'none';
        });
    }

    searchInput?.addEventListener('keyup', filterTable);
</script>

<style>
    /* Tambahan styling opsional */
    .bg-gradient-to-br {
        background-size: 100% 100%;
    }
    /* Border biru tambahan untuk card mobile */
    .riwayat-item {
        background-color: white;
    }
</style>
@endsection