@extends('layouts.bendahara')

@section('title', 'Riwayat Pembayaran - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Riwayat Pembayaran</h1>
            <p class="text-white/80 mt-1">Daftar riwayat pembayaran SPP siswa</p>
        </div>

    </div>
</div>

<!-- Statistics Cards -->
@php
    // Hitung statistik dari collection
    $allData = $data->getCollection(); // Ambil data dari paginator
    
    $totalNominal = $allData->sum('nominal');
    $bulanIni = $allData->filter(function($item) {
        return \Carbon\Carbon::parse($item->tanggal_bayar)->month == now()->month && 
               \Carbon\Carbon::parse($item->tanggal_bayar)->year == now()->year;
    })->count();
    $siswaPembayar = $allData->unique('siswa_id')->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-slide-in delay-1">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                <h3 class="text-3xl font-bold text-[#0b4f8c]">{{ $data->total() }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-history text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Nominal</p>
                <h3 class="text-3xl font-bold text-green-600">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Bulan Ini</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ $bulanIni }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Siswa Pembayar</p>
                <h3 class="text-3xl font-bold text-orange-600">{{ $siswaPembayar }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <form method="GET" action="{{ route('bendahara.riwayat.index') }}" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" placeholder="Cari NIS, nama siswa, atau bulan..." 
                       class="search-input" value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="flex items-center space-x-2 flex-wrap gap-2">
            <select name="bulan" class="form-select w-36">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            
            <input type="number" name="tahun" placeholder="Tahun" 
                   class="form-input w-24" value="{{ request('tahun') }}" 
                   min="2020" max="{{ date('Y') }}">
            
            <button type="submit" class="btn-primary text-sm py-3">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            
            <a href="{{ route('bendahara.riwayat.index') }}" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Riwayat Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="riwayatTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Periode</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nominal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Metode</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr class="table-row" data-id="{{ $item->id }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $data->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $item->siswa->nis ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $item->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</div>
                        @if($item->siswa && $item->siswa->email)
                            <div class="text-xs text-gray-500">{{ $item->siswa->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800">
                                @php
                                    $bulanList = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    
                                    // Prioritaskan dari tagihan, lalu dari field langsung, lalu dari tanggal_bayar
                                    if (isset($item->tagihan) && $item->tagihan) {
                                        $bulan = $item->tagihan->bulan;
                                        $tahun = $item->tagihan->tahun;
                                    } elseif (isset($item->bulan) && $item->bulan) {
                                        $bulan = $item->bulan;
                                        $tahun = $item->tahun;
                                    } else {
                                        $bulan = \Carbon\Carbon::parse($item->tanggal_bayar)->month;
                                        $tahun = \Carbon\Carbon::parse($item->tanggal_bayar)->year;
                                    }
                                @endphp
                                {{ $bulanList[$bulan] ?? 'Unknown' }} {{ $tahun }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-green-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $metode = strtolower($item->metode_pembayaran ?? '');
                            $badgeClass = match(true) {
                                str_contains($metode, 'transfer') || str_contains($metode, 'bank') || str_contains($metode, 'midtrans') => 'badge-info',
                                str_contains($metode, 'qris') => 'badge-purple',
                                str_contains($metode, 'virtual') || str_contains($metode, 'va') => 'badge-warning',
                                str_contains($metode, 'cash') || str_contains($metode, 'tunai') => 'badge-success',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="{{ $badgeClass ?? 'badge-secondary' }}">
                            {{ $item->metode_pembayaran ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</span>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('H:i') }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-history text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-2">Belum ada riwayat pembayaran</p>
                            <p class="text-gray-400 text-sm">Riwayat pembayaran akan muncul setelah siswa melakukan pembayaran</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} 
            dari {{ $data->total() }} data
        </p>
        <div class="flex items-center space-x-2">
            {{ $data->links() }}
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="successMessage">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="errorMessage">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<!-- Tambahkan CSS untuk badge purple -->
<style>
    .badge-purple {
        @apply px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium;
    }
    .badge-secondary {
        @apply px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium;
    }
</style>
@endsection

@push('scripts')
<script>
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush