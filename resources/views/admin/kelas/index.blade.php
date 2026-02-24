@extends('layouts.admin')

@section('title', 'Data Kelas - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Kelas</h1>
            <p class="text-white/80 mt-1">Kelola data kelas dan tingkatan</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kelas
        </button>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari kelas..." class="search-input" id="searchInput">
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="form-select w-40" id="tingkatFilter">
                <option value="">Semua Tingkat</option>
                <option value="10" style="color: #059669;">Kelas 10</option>
                <option value="11" style="color: #b45309;">Kelas 11</option>
                <option value="12" style="color: #6b21a5;">Kelas 12</option>
            </select>
            <button onclick="resetFilter()" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- Kelas Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="kelasTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tingkat</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Total Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $kelas)
                <tr class="table-row" 
                    data-nama="{{ strtolower($kelas->nama_kelas) }}" 
                    data-tingkat="{{ $kelas->tingkat }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: {{ 
                                $kelas->tingkat == '10' ? 'linear-gradient(135deg, #059669, #10b981)' : 
                                ($kelas->tingkat == '11' ? 'linear-gradient(135deg, #b45309, #d97706)' : 
                                'linear-gradient(135deg, #6b21a5, #8b5cf6)') 
                            }};">
                                <span>{{ $kelas->tingkat }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $kelas->nama_kelas }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($kelas->tingkat == '10')
                            <span class="badge-success">Kelas 10</span>
                        @elseif($kelas->tingkat == '11')
                            <span class="badge-warning">Kelas 11</span>
                        @else
                            <span class="badge-info">Kelas 12</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @if(isset($kelas->siswa_count))
                            {{ $kelas->siswa_count }} Siswa
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditModal({{ $kelas->id }})" class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $kelas->id }}, '{{ $kelas->nama_kelas }}')" class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Info -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Menampilkan {{ $data->count() }} data</p>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Kelas Baru</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.kelas.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="space-y-4 mb-4">
                <div>
                    <label class="form-label">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" class="form-input" placeholder="Contoh: X IPA 1 atau X IPA 1" required>
                    <p class="text-xs text-gray-500 mt-1">Masukkan nama kelas lengkap</p>
                </div>
                
                <div>
                    <label class="form-label">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" class="form-select" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="10" style="color: #059669; font-weight: 600;">Kelas 10</option>
                        <option value="11" style="color: #b45309; font-weight: 600;">Kelas 11</option>
                        <option value="12" style="color: #6b21a5; font-weight: 600;">Kelas 12</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-content max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Edit Kelas</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 mb-4">
                <div>
                    <label class="form-label">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" id="edit_tingkat" class="form-select" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="10" style="color: #059669; font-weight: 600;">Kelas 10</option>
                        <option value="11" style="color: #b45309; font-weight: 600;">Kelas 11</option>
                        <option value="12" style="color: #6b21a5; font-weight: 600;">Kelas 12</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Update
                </button>
            </div>
        </form>
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
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus kelas ini?</p>
            
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
    // Data kelas untuk edit (simpan dalam format JSON)
    const kelasData = @json($data);
    
    // ==================== CREATE MODAL ====================
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.getElementById('createForm').reset();
    }
    
    // ==================== EDIT MODAL ====================
    function openEditModal(id) {
        const kelas = kelasData.find(item => item.id === id);
        
        if (kelas) {
            document.getElementById('edit_nama_kelas').value = kelas.nama_kelas;
            document.getElementById('edit_tingkat').value = kelas.tingkat;
            
            // Set form action
            document.getElementById('editForm').action = `/admin/kelas/${id}`;
            
            document.getElementById('editModal').style.display = 'flex';
        }
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editForm').reset();
    }
    
    // ==================== DELETE MODAL ====================
    function confirmDelete(id, namaKelas) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus kelas <strong>${namaKelas}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/kelas/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // ==================== SEARCH & FILTER ====================
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('tingkatFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const tingkatFilter = document.getElementById('tingkatFilter').value;
        const rows = document.querySelectorAll('#kelasTable tbody tr');
        
        rows.forEach(row => {
            const nama = row.getAttribute('data-nama');
            const tingkat = row.getAttribute('data-tingkat');
            
            const matchesSearch = nama.includes(searchTerm);
            const matchesTingkat = tingkatFilter === '' || tingkat === tingkatFilter;
            
            row.style.display = matchesSearch && matchesTingkat ? '' : 'none';
        });
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('tingkatFilter').value = '';
        
        const rows = document.querySelectorAll('#kelasTable tbody tr');
        rows.forEach(row => row.style.display = '');
    }
    
    // ==================== CLOSE MODALS WHEN CLICKING OUTSIDE ====================
    window.addEventListener('click', function(e) {
        const createModal = document.getElementById('createModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        
        if (e.target === createModal) {
            closeCreateModal();
        }
        if (e.target === editModal) {
            closeEditModal();
        }
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
    
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush