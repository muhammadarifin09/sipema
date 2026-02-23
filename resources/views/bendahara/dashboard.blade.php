@extends('layouts.bendahara')

@section('title', 'Dashboard Bendahara - SMA PGRI Pelaihari')

@section('content')
<!-- Welcome Header -->
<div class="mb-8 animate-slide-in">
    <h1 class="text-3xl font-bold text-white drop-shadow-lg">Dashboard Bendahara</h1>
    <p class="text-white/80 mt-1">Selamat datang, <span class="font-semibold">{{ auth()->user()->name ?? 'Bendahara' }}</span>! Berikut ringkasan keuangan hari ini.</p>
</div>

<!-- Stat Cards Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Penerimaan Bulan Ini -->
    <div class="stat-card animate-slide-in delay-1">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Penerimaan Bulan Ini</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 68.450.000</h3>
        <p class="text-green-600 text-sm mt-2">
            <i class="fas fa-arrow-up mr-1"></i>
            +12.5% dari bulan lalu
        </p>
    </div>

    <!-- Total Siswa Lunas -->
    <div class="stat-card animate-slide-in delay-2">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Siswa Lunas</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">342</h3>
        <div class="progress-bar mt-3">
            <div class="progress-fill" style="width: 75%"></div>
        </div>
        <p class="text-gray-500 text-xs mt-2">
            <span class="text-green-600 font-medium">75%</span> dari total siswa
        </p>
    </div>

    <!-- Total Tunggakan -->
    <div class="stat-card animate-slide-in delay-3">
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Total Tunggakan</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 12.540.000</h3>
        <p class="text-red-600 text-sm mt-2">
            <i class="fas fa-arrow-down mr-1"></i>
            114 siswa menunggak
        </p>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="stat-card animate-slide-in delay-4">
        <div class="stat-icon">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Transaksi Hari Ini</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">47</h3>
        <p class="text-blue-600 text-sm mt-2">
            <i class="fas fa-clock mr-1"></i>
            Total: Rp 7.050.000
        </p>
    </div>
</div>

<!-- Statistik Per Kelas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grafik Sederhana Per Kelas -->
    <div class="info-card animate-slide-in delay-2 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Statistik Pembayaran Per Kelas</h3>
            <i class="fas fa-chart-bar text-[#b45309] text-xl"></i>
        </div>
        
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Kelas X</span>
                    <span class="font-semibold text-gray-800">128 Lunas / 156 Siswa</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 82%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Tunggakan: Rp 4.200.000</p>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Kelas XI</span>
                    <span class="font-semibold text-gray-800">112 Lunas / 148 Siswa</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 76%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Tunggakan: Rp 5.400.000</p>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Kelas XII</span>
                    <span class="font-semibold text-gray-800">102 Lunas / 152 Siswa</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 67%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Tunggakan: Rp 7.500.000</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-100">
            <div class="text-center p-2 bg-orange-50 rounded-xl">
                <p class="text-xs text-gray-600">Total Penerimaan</p>
                <p class="font-bold text-gray-800">Rp 68.4 Jt</p>
            </div>
            <div class="text-center p-2 bg-orange-50 rounded-xl">
                <p class="text-xs text-gray-600">Target</p>
                <p class="font-bold text-gray-800">Rp 91.2 Jt</p>
            </div>
            <div class="text-center p-2 bg-orange-50 rounded-xl">
                <p class="text-xs text-gray-600">Kekurangan</p>
                <p class="font-bold text-gray-800">Rp 22.8 Jt</p>
            </div>
        </div>
    </div>

    <!-- Info Penting -->
    <div class="info-card animate-slide-in delay-3">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Info Penting</h3>
            <i class="fas fa-bullhorn text-[#b45309] text-xl"></i>
        </div>

        <div class="space-y-3">
            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-xl">
                <i class="fas fa-calendar-alt text-blue-600 mt-1"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800">Jatuh Tempo SPP</p>
                    <p class="text-xs text-gray-600">Batas pembayaran: 10 April 2024</p>
                    <p class="text-xs text-red-600 mt-1">Sisa 5 hari lagi</p>
                </div>
            </div>

            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-xl">
                <i class="fas fa-chart-line text-green-600 mt-1"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800">Target Bulan Ini</p>
                    <p class="text-xs text-gray-600">Realisasi: Rp 68.4 Jt dari Rp 91.2 Jt</p>
                    <div class="progress-bar mt-2">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-xl">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800">Perhatian!</p>
                    <p class="text-xs text-gray-600">114 siswa belum membayar SPP bulan ini</p>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2">
            <div class="text-center p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500">Hari Efektif</p>
                <p class="text-lg font-bold text-gray-800">22</p>
                <p class="text-[10px] text-gray-400">dari 30 hari</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500">Rata-rata Harian</p>
                <p class="text-lg font-bold text-gray-800">Rp 3.1 Jt</p>
                <p class="text-[10px] text-green-600">+8%</p>
            </div>
        </div>
    </div>
</div>

<!-- 5 Pembayaran Terbaru -->
<div class="table-container animate-slide-in delay-4">
  <div class="flex items-center justify-between mb-6">
    <h3 class="font-semibold text-gray-800">5 Pembayaran Terbaru</h3>
    <a href="" class="text-sm text-[#0b4f8c] hover:text-[#1e6f9f] hover:underline">
        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
    </a>
</div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#0b4f8c] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Bulan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-gray-700">1</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024001</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Budi Santoso</td>
                    <td class="px-6 py-4 text-sm text-gray-700">XII IPA 1</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-[#b45309] hover:text-[#d97706] mr-3" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="#" class="text-green-600 hover:text-green-700" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-gray-700">2</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024045</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Siti Aisyah</td>
                    <td class="px-6 py-4 text-sm text-gray-700">XI IPS 2</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-[#b45309] hover:text-[#d97706] mr-3" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="#" class="text-green-600 hover:text-green-700" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-gray-700">3</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024089</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Ahmad Hidayat</td>
                    <td class="px-6 py-4 text-sm text-gray-700">X IPA 3</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                    <td class="px-6 py-4"><span class="badge-warning">Menunggu</span></td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-[#b45309] hover:text-[#d97706] mr-3" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="#" class="text-green-600 hover:text-green-700" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-gray-700">4</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024012</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Dewi Lestari</td>
                    <td class="px-6 py-4 text-sm text-gray-700">XII IPS 1</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-[#b45309] hover:text-[#d97706] mr-3" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="#" class="text-green-600 hover:text-green-700" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-gray-700">5</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024078</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Rizki Pratama</td>
                    <td class="px-6 py-4 text-sm text-gray-700">XI IPA 1</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-[#b45309] hover:text-[#d97706] mr-3" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="#" class="text-green-600 hover:text-green-700" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Tombol Cepat -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <a href="{{ route('bendahara.dashboard') }}" class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 text-white hover:bg-white/20 transition-all text-center">
        <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
        <p class="font-medium">Input Pembayaran</p>
        <p class="text-xs text-white/70 mt-1">Catat pembayaran SPP baru</p>
    </a>
    <a href="{{ route('bendahara.dashboard') }}" class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 text-white hover:bg-white/20 transition-all text-center">
        <i class="fas fa-download text-2xl mb-2"></i>
        <p class="font-medium">Ekspor Laporan</p>
        <p class="text-xs text-white/70 mt-1">Download laporan keuangan</p>
    </a>
    <a href="{{ route('bendahara.dashboard') }}" class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 text-white hover:bg-white/20 transition-all text-center">
        <i class="fas fa-search text-2xl mb-2"></i>
        <p class="font-medium">Cek Tunggakan</p>
        <p class="text-xs text-white/70 mt-1">Lihat siswa yang menunggak</p>
    </a>
</div>
@endsection