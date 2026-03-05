@extends('layouts.admin')

@section('title', 'Data Wali Murid - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Wali Murid</h1>
            <p class="text-white/80 mt-1">Kelola data orang tua/wali murid</p>
        </div>
        <a href="{{ route('admin.wali.create') }}" class="btn-primary" style="background: linear-gradient(135deg, #0b4f8c, #0b4f8c);">
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
    <!-- Total Wali -->
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

    <!-- Terhubung -->
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

    <!-- Belum Terhubung -->
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

    <!-- Total Siswa Terhubung -->
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

<!-- Wali Murid Table -->
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
                                @foreach($w->siswa as $siswa)
                                    @if($siswa && is_object($siswa))
                                    <div class="flex items-center space-x-2 bg-purple-50 rounded-lg px-2 py-1">
                                        <i class="fas fa-user-graduate text-purple-600 text-xs"></i>
                                        <span class="text-sm text-gray-700">{{ $siswa->nama_lengkap ?? 'Nama tidak tersedia' }}</span>
                                        <span class="text-xs px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded-full">
                                            {{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                        </span>
                                    </div>
                                    @endif
                                @endforeach
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
                            <a href="{{ route('admin.wali.edit', $w->id) }}" class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $w->id }}, '{{ $w->name }}')" class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users-slash text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 mb-2">Belum ada data wali murid</p>
                            <a href="{{ route('admin.wali.create') }}" class="text-[#6b21a5] hover:underline text-sm">
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

    <!-- Pagination Info -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Menampilkan {{ $wali->count() }} data wali murid</p>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus wali murid ini?</p>
            
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <div class="flex items-center justify-center space-x-4">
                    <button type="button" onclick="closeDeleteModal()" class="btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash mr-2"></i>
                        Ya, Hapus
                    </button>
                </div>
            </form>
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
@endsection

@push('scripts')
<script>
    // ==================== SEARCH & FILTER ====================
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('connectionFilter')?.addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const connectionFilter = document.getElementById('connectionFilter').value;
        const rows = document.querySelectorAll('#waliTable tbody tr');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const siswa = row.getAttribute('data-siswa');
            const connected = row.getAttribute('data-connected');
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm) || (siswa && siswa.includes(searchTerm));
            const matchesConnection = connectionFilter === '' || connected === connectionFilter;
            
            row.style.display = matchesSearch && matchesConnection ? '' : 'none';
        });
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('connectionFilter').value = '';
        
        const rows = document.querySelectorAll('#waliTable tbody tr');
        rows.forEach(row => row.style.display = '');
    }
    
    // ==================== DELETE MODAL ====================
    function confirmDelete(waliId, waliName) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus wali murid <strong>${waliName}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/wali/${waliId}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // ==================== CLOSE MODALS WHEN CLICKING OUTSIDE ====================
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
    
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        
        if (successMsg) {
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
        }
        if (errorMsg) {
            errorMsg.style.opacity = '0';
            setTimeout(() => errorMsg.remove(), 500);
        }
    }, 5000);
</script>
@endpush