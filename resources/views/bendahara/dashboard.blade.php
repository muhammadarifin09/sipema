@extends('layouts.bendahara')

@section('title', 'Dashboard Bendahara - SIPEMA')

@section('content')
<!-- Welcome Header -->
<div class="mb-8 animate-slide-in">
    <h1 class="text-3xl font-bold text-white drop-shadow-lg">Dashboard Bendahara</h1>
    <p class="text-white/80 mt-1">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>! Berikut ringkasan data user dan pembayaran.</p>
</div>

<!-- Stat Cards Sederhana -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="stat-card animate-slide-in delay-1">
        <div class="stat-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Total Users</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
        <p class="text-blue-600 text-sm mt-2">
            <i class="fas fa-user-check mr-1"></i>
            Seluruh user terdaftar
        </p>
    </div>

    <!-- Total Admin -->
    <div class="stat-card animate-slide-in delay-2">
        <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-user-tie"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Admin</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAdmin }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Administrator sistem
        </p>
    </div>

    <!-- Total Bendahara -->
    <div class="stat-card animate-slide-in delay-3">
        <div class="stat-icon" style="background: linear-gradient(135deg, #b45309, #d97706);">
            <i class="fas fa-wallet"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Bendahara</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBendahara }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Petugas keuangan
        </p>
    </div>

    <!-- Total Wali Murid -->
    <div class="stat-card animate-slide-in delay-4">
        <div class="stat-icon" style="background: linear-gradient(135deg, #6b21a5, #8b5cf6);">
            <i class="fas fa-user-friends"></i>
        </div>
        <p class="text-gray-500 text-sm font-medium">Wali Murid</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalWali }}</h3>
        <p class="text-gray-500 text-xs mt-2">
            Orang tua/wali siswa
        </p>
    </div>
</div>

<!-- GRAFIK SECTION (Line & Bar Chart) - Data Real dari Pembayaran -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Line Chart Card -->
    <div class="info-card animate-slide-in delay-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Tren Pembayaran (Line Chart)</h3>
            <i class="fas fa-chart-line text-[#0b4f8c] text-xl"></i>
        </div>
        <canvas id="paymentLineChart" width="400" height="250"></canvas>
        <p class="text-xs text-gray-400 mt-2 text-center">Data berdasarkan total nominal pembayaran per bulan</p>
    </div>

    <!-- Bar Chart Card -->
    <div class="info-card animate-slide-in delay-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Total per Bulan (Bar Chart)</h3>
            <i class="fas fa-chart-bar text-[#0b4f8c] text-xl"></i>
        </div>
        <canvas id="paymentBarChart" width="400" height="250"></canvas>
        <p class="text-xs text-gray-400 mt-2 text-center">6 bulan terakhir (termasuk bulan berjalan)</p>
    </div>
</div>




@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data dari controller
        const months = @json($months);
        const payments = @json($paymentsData);

        console.log('Months:', months);
        console.log('Payments:', payments);

        // Cek apakah data valid
        if (!months || !payments || months.length === 0) {
            console.error('Data grafik kosong atau tidak valid.');
            // Tampilkan pesan di dalam canvas
            const lineCanvas = document.getElementById('paymentLineChart');
            const barCanvas = document.getElementById('paymentBarChart');
            if (lineCanvas && lineCanvas.parentNode) {
                lineCanvas.parentNode.insertAdjacentHTML('beforeend', '<p class="text-red-500 text-sm mt-2">⚠️ Belum ada data pembayaran untuk grafik.</p>');
            }
            if (barCanvas && barCanvas.parentNode) {
                barCanvas.parentNode.insertAdjacentHTML('beforeend', '<p class="text-red-500 text-sm mt-2">⚠️ Belum ada data pembayaran untuk grafik.</p>');
            }
            return;
        }

        // Pastikan payments adalah array number (jika string, konversi)
        const paymentsNumber = payments.map(v => typeof v === 'number' ? v : parseFloat(v) || 0);

        // Line Chart
        const ctxLine = document.getElementById('paymentLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: paymentsNumber,
                    borderColor: '#0b4f8c',
                    backgroundColor: 'rgba(11, 79, 140, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#0b4f8c',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart
        const ctxBar = document.getElementById('paymentBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: paymentsNumber,
                    backgroundColor: 'rgba(11, 79, 140, 0.7)',
                    borderColor: '#0b4f8c',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush