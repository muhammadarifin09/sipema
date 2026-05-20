@extends('layouts.bendahara')

@section('title', 'Data Wali Murid - SIPEMA')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Wali Murid</h1>
            <p class="text-white/80 mt-1">Data orang tua/wali murid</p>
        </div>
        <a href="{{ route('bendahara.wali.create') }}" class="btn-primary" style="background: linear-gradient(135deg, #0b4f8c, #0b4f8c);">
            <i class="fas fa-user-plus mr-2"></i>
            Tambah Wali Murid
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari wali murid..." class="search-input" id="searchInput">
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="form-select w-48" id="connectionFilter">
                <option value="">Semua Status</option>
                <option value="connected">Terhubung dengan Siswa</option>
                <option value="disconnected">Belum Terhubung</option>
            </select>
            <button onclick="resetFilter()" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 animate-slide-in delay-1">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Wali Murid</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $wali->count() }}</h3>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #0b4f8c, #0b4f8c);">
                <i class="fas fa-users text-white"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Terhubung dg Siswa</p>
                <h3 class="text-2xl font-bold text-green-600">{{ $wali->filter(function($w) { return $w->siswa && $w->siswa->count() > 0; })->count() }}</h3>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
                <i class="fas fa-link text-white"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Belum Terhubung</p>
                <h3 class="text-2xl font-bold text-red-600">{{ $wali->filter(function($w) { return !$w->siswa || $w->siswa->count() == 0; })->count() }}</h3>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #b45309, #d97706);">
                <i class="fas fa-unlink text-white"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Siswa Terhubung</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ $wali->sum(function($w) { return $w->siswa ? $w->siswa->count() : 0; }) }}</h3>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
                <i class="fas fa-user-graduate text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Wali Murid -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="waliTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Wali</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wali as $index => $w)
                <tr class="table-row" 
                    data-id="{{ $w->id }}"
                    data-name="{{ strtolower($w->name) }}" 
                    data-email="{{ strtolower($w->email) }}"
                    data-siswa="{{ $w->siswa ? $w->siswa->pluck('nama_lengkap')->implode(' ') : '' }}"
                    data-connected="{{ $w->siswa && $w->siswa->count() > 0 ? 'connected' : 'disconnected' }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: linear-gradient(135deg, #0b4f8c, #0b4f8c);">
                                <span>{{ strtoupper(substr($w->name, 0, 2)) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $w->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $w->email }}</td>
                    <td class="px-6 py-4">
                        @if($w->siswa && $w->siswa->count() > 0)
                            <div class="flex flex-col space-y-1">
                                @foreach($w->siswa->take(2) as $siswa)
                                    @if($siswa && is_object($siswa))
                                    <div class="flex items-center space-x-2 bg-purple-50 rounded-lg px-2 py-1">
                                        <i class="fas fa-user-graduate text-purple-600 text-xs"></i>
                                        <span class="text-sm text-gray-700">{{ $siswa->nama_lengkap ?? 'Nama tidak tersedia' }}</span>
                                    </div>
                                    @endif
                                @endforeach
                                @if($w->siswa->count() > 2)
                                    <span class="text-xs text-purple-600 ml-2">+{{ $w->siswa->count() - 2 }} lainnya</span>
                                @endif
                            </div>
                        @else
                            <span class="badge-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Belum Terhubung
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($w->siswa && $w->siswa->count() > 0)
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-700 rounded-full font-semibold text-sm">
                                {{ $w->siswa->count() }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $w->id }})" class="text-blue-600 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-xl transition-all" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users-slash text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 mb-2">Belum ada data wali murid</p>
                            <a href="{{ route('bendahara.wali.create') }}" class="text-[#6b21a5] hover:underline text-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Wali Murid Baru
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Menampilkan {{ $wali->count() }} data wali murid</p>
    </div>
</div>

<!-- MODAL DETAIL (tanpa tombol edit) -->
<div class="modal-overlay" id="detailModal" style="display: none;">
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-3">
                <div class="profile-avatar w-12 h-12 text-sm" id="detailAvatar" style="background: linear-gradient(135deg, #0b4f8c, #0b4f8c);">
                    <span id="avatarText">WM</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800" id="detailName">Nama Wali</h3>
                    <p class="text-sm text-gray-500" id="detailStatus">Status Akun</p>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-user-circle text-[#0b4f8c] mr-2"></i>
                    Informasi Akun
                </h4>
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div>
                        <label class="text-xs text-gray-400">Email</label>
                        <p class="text-sm text-gray-800 font-medium" id="detailEmail">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Nomor HP</label>
                        <p class="text-sm text-gray-800 font-medium" id="detailNoHp">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Alamat</label>
                        <p class="text-sm text-gray-800 font-medium" id="detailAlamat">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Status</label>
                        <div id="detailActive"></div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Terdaftar Pada</label>
                        <p class="text-sm text-gray-800 font-medium" id="detailCreatedAt">-</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-chart-pie text-[#0b4f8c] mr-2"></i>
                    Statistik
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-blue-600" id="detailTotalSiswa">0</p>
                        <p class="text-xs text-gray-500">Total Siswa</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-purple-600" id="detailTotalKelas">0</p>
                        <p class="text-xs text-gray-500">Total Kelas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-user-graduate text-[#0b4f8c] mr-2"></i>
                Daftar Siswa Bimbingan
            </h4>
            <div class="border border-gray-200 rounded-xl overflow-hidden max-h-60 overflow-y-auto" id="siswaList"></div>
        </div>

        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
            <button onclick="closeDetailModal()" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Tutup
            </button>
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
@php
    $waliJson = $wali->map(function($w) {
        return [
            'id' => $w->id,
            'name' => $w->name,
            'email' => $w->email,
            'no_hp' => $w->no_hp ?? '-',
            'alamat' => $w->alamat ?? '-',
            'active' => $w->active,
            'created_at' => $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-',
            'total_siswa' => $w->siswa ? $w->siswa->count() : 0,
            'total_kelas' => $w->siswa ? $w->siswa->pluck('kelas.nama_kelas')->unique()->count() : 0,
            'siswa' => $w->siswa ? $w->siswa->map(function($siswa) {
                return [
                    'nama_lengkap' => $siswa->nama_lengkap ?? 'Nama tidak tersedia',
                    'nis' => $siswa->nis ?? '-',
                    'kelas' => $siswa->kelas->nama_kelas ?? 'Tanpa Kelas'
                ];
            }) : []
        ];
    })->values();
@endphp
<script>
    const waliData = @json($waliJson);

    function showDetail(id) {
        const wali = waliData.find(w => w.id === id);
        if (!wali) return;

        document.getElementById('avatarText').textContent = wali.name.substring(0, 2).toUpperCase();
        document.getElementById('detailName').textContent = wali.name;
        document.getElementById('detailEmail').textContent = wali.email;
        document.getElementById('detailNoHp').textContent = wali.no_hp;
        document.getElementById('detailAlamat').textContent = wali.alamat;
        document.getElementById('detailCreatedAt').textContent = wali.created_at;
        document.getElementById('detailTotalSiswa').textContent = wali.total_siswa;
        document.getElementById('detailTotalKelas').textContent = wali.total_kelas;

        const statusEl = document.getElementById('detailActive');
        if (wali.active) {
            statusEl.innerHTML = '<span class="badge-success"><i class="fas fa-check-circle mr-1"></i>Aktif</span>';
        } else {
            statusEl.innerHTML = '<span class="badge-danger"><i class="fas fa-times-circle mr-1"></i>Nonaktif</span>';
        }
        document.getElementById('detailStatus').textContent = wali.active ? 'Akun Aktif' : 'Akun Nonaktif';

        const siswaList = document.getElementById('siswaList');
        if (wali.siswa.length > 0) {
            let html = '';
            wali.siswa.forEach(s => {
                html += `
                    <div class="flex items-center justify-between p-3 hover:bg-purple-50 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-graduate text-purple-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">${s.nama_lengkap}</p>
                                <p class="text-xs text-gray-400">NIS: ${s.nis}</p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">${s.kelas}</span>
                    </div>
                `;
            });
            siswaList.innerHTML = html;
        } else {
            siswaList.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-user-graduate text-4xl mb-2 opacity-50"></i>
                    <p class="text-sm">Belum ada siswa yang terhubung</p>
                </div>
            `;
        }

        document.getElementById('detailModal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    // Filter
    document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
    document.getElementById('connectionFilter')?.addEventListener('change', filterTable);

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const connFilter = document.getElementById('connectionFilter').value;
        const rows = document.querySelectorAll('#waliTable tbody tr');
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const siswa = row.getAttribute('data-siswa');
            const connected = row.getAttribute('data-connected');
            const matchSearch = name.includes(search) || email.includes(search) || (siswa && siswa.includes(search));
            const matchConn = connFilter === '' || connected === connFilter;
            row.style.display = matchSearch && matchConn ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('connectionFilter').value = '';
        filterTable();
    }

    // Tutup modal saat klik di luar
    window.addEventListener('click', e => {
        const modal = document.getElementById('detailModal');
        if (e.target === modal) closeDetailModal();
    });

    setTimeout(() => {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush