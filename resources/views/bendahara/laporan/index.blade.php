@extends('layouts.bendahara')

@section('title', 'Laporan Rekap Pembayaran SPP - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">📊 Laporan Rekap Pembayaran SPP</h1>
            <p class="text-white/80 mt-1">Rekapitulasi total pembayaran per siswa (agregat)</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Tombol Export PDF -->
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn-danger">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="glass-card rounded-2xl p-6 mb-6 animate-slide-in delay-1">
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label flex items-center">
                    <i class="fas fa-calendar-alt text-[#0b4f8c] mr-2"></i>
                    Tanggal Awal
                </label>
                <input type="date" name="tanggal_awal" class="form-input w-full" value="{{ request('tanggal_awal') }}">
            </div>
            <div>
                <label class="form-label flex items-center">
                    <i class="fas fa-calendar-check text-[#0b4f8c] mr-2"></i>
                    Tanggal Akhir
                </label>
                <input type="date" name="tanggal_akhir" class="form-input w-full" value="{{ request('tanggal_akhir') }}">
            </div>
            <div>
                <label class="form-label flex items-center">
                    <i class="fas fa-calendar-week text-[#0b4f8c] mr-2"></i>
                    Bulan
                </label>
                <select name="bulan" class="form-select w-full">
                    <option value="">-- Pilih Bulan --</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label flex items-center">
                    <i class="fas fa-calendar text-[#0b4f8c] mr-2"></i>
                    Tahun
                </label>
                <input type="number" name="tahun" class="form-input w-full" placeholder="Tahun" value="{{ request('tahun') }}">
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-filter mr-2"></i> Terapkan Filter
            </button>
            <a href="{{ route('admin.laporan.index') }}" class="btn-secondary ml-2">
                <i class="fas fa-redo-alt mr-2"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Tabel Rekap -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Jumlah Bayar</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold rounded-tr-xl">Total Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $item)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $item->nis ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: linear-gradient(135deg, #0b4f8c, #1e6f9f);">
                                <span>{{ strtoupper(substr($item->nama_siswa ?? '?', 0, 2)) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $item->nama_siswa }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            {{ $item->jumlah_bayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-800">
                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-chart-line text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-lg mb-2">Belum ada data pembayaran</p>
                            <p class="text-gray-400 text-sm">Ubah filter atau tunggu transaksi masuk</p>
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
            Menampilkan {{ $laporans->firstItem() ?? 0 }} - {{ $laporans->lastItem() ?? 0 }} dari {{ $laporans->total() }} data
        </p>
        <div class="pagination-links">
            {{ $laporans->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="successMessage">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="errorMessage">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@endsection

@push('scripts')
<script>
    setTimeout(function() {
        const success = document.getElementById('successMessage');
        const error = document.getElementById('errorMessage');
        if (success) success.remove();
        if (error) error.remove();
    }, 5000);
</script>
@endpush

<style>
    .pagination-links nav {
        display: inline-block;
    }
    .pagination-links .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
    }
    .pagination-links .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.75rem;
        background: white;
        color: #0b4f8c;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pagination-links .page-item.active .page-link {
        background: #0b4f8c;
        color: white;
        border-color: #0b4f8c;
    }
    .pagination-links .page-item .page-link:hover {
        background: #f0f9ff;
        border-color: #0b4f8c;
    }
</style>