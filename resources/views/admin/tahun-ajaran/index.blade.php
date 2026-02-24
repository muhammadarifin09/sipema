@extends('layouts.admin')

@section('title', 'Data Tahun Ajaran - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Tahun Ajaran</h1>
            <p class="text-white/80 mt-1">Kelola data tahun ajaran dan semester</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah Tahun Ajaran
        </button>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari tahun ajaran..." class="search-input" id="searchInput">
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="form-select w-40" id="semesterFilter">
                <option value="">Semua Semester</option>
                <option value="ganjil" style="color: #059669;">Ganjil</option>
                <option value="genap" style="color: #b45309;">Genap</option>
            </select>
            <select class="form-select w-40" id="statusFilter">
                <option value="">Semua Status</option>
                <option value="aktif" style="color: #059669;">Aktif</option>
                <option value="nonaktif" style="color: #b45309;">Nonaktif</option>
            </select>
            <button onclick="resetFilter()" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- Tahun Ajaran Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="tahunAjaranTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Semester</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tahunAjaran as $index => $ta)
                <tr class="table-row" 
                    data-tahun="{{ strtolower($ta->tahun) }}" 
                    data-semester="{{ strtolower($ta->semester) }}"
                    data-status="{{ strtolower($ta->status) }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: linear-gradient(135deg, #0b4f8c, #1e6f9f);">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <span class="font-medium text-gray-800">{{ $ta->tahun }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($ta->semester == 'ganjil')
                            <span class="badge-success">Ganjil</span>
                        @else
                            <span class="badge-warning">Genap</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($ta->status == 'aktif')
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditModal({{ $ta->id }})" class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $ta->id }}, '{{ $ta->tahun }} - {{ ucfirst($ta->semester) }}')" class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
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
        <p class="text-sm text-gray-500">Menampilkan {{ $tahunAjaran->count() }} data</p>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Tahun Ajaran</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="space-y-4 mb-4">
                <div>
                    <label class="form-label">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun" class="form-input" placeholder="Contoh: 2025/2026" required>
                    <p class="text-xs text-gray-500 mt-1">Format: YYYY/YYYY (contoh: 2025/2026)</p>
                </div>
                
                <div>
                    <label class="form-label">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" class="form-select" required>
                        <option value="">Pilih Semester</option>
                        <option value="ganjil" style="color: #059669; font-weight: 600;">Ganjil</option>
                        <option value="genap" style="color: #b45309; font-weight: 600;">Genap</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" style="color: #059669; font-weight: 600;">Aktif</option>
                        <option value="nonaktif" style="color: #b45309; font-weight: 600;">Nonaktif</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hanya satu tahun ajaran yang dapat berstatus aktif</p>
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
            <h3 class="text-xl font-bold text-gray-800">Edit Tahun Ajaran</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 mb-4">
                <div>
                    <label class="form-label">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun" id="edit_tahun" class="form-input" placeholder="Contoh: 2025/2026" required>
                    <p class="text-xs text-gray-500 mt-1">Format: YYYY/YYYY (contoh: 2025/2026)</p>
                </div>
                
                <div>
                    <label class="form-label">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" id="edit_semester" class="form-select" required>
                        <option value="">Pilih Semester</option>
                        <option value="ganjil" style="color: #059669; font-weight: 600;">Ganjil</option>
                        <option value="genap" style="color: #b45309; font-weight: 600;">Genap</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="edit_status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" style="color: #059669; font-weight: 600;">Aktif</option>
                        <option value="nonaktif" style="color: #b45309; font-weight: 600;">Nonaktif</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hanya satu tahun ajaran yang dapat berstatus aktif</p>
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
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus tahun ajaran ini?</p>
            
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
    // Data tahun ajaran untuk edit (simpan dalam format JSON)
    const tahunAjaranData = @json($tahunAjaran);
    
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
        const ta = tahunAjaranData.find(item => item.id === id);
        
        if (ta) {
            document.getElementById('edit_tahun').value = ta.tahun;
            document.getElementById('edit_semester').value = ta.semester;
            document.getElementById('edit_status').value = ta.status;
            
            // Set form action
            document.getElementById('editForm').action = `/admin/tahun-ajaran/${id}`;
            
            document.getElementById('editModal').style.display = 'flex';
        }
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editForm').reset();
    }
    
    // ==================== DELETE MODAL ====================
    function confirmDelete(id, tahunAjaran) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus tahun ajaran <strong>${tahunAjaran}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/tahun-ajaran/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // ==================== SEARCH & FILTER ====================
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('semesterFilter').addEventListener('change', function() {
        filterTable();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const semesterFilter = document.getElementById('semesterFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#tahunAjaranTable tbody tr');
        
        rows.forEach(row => {
            const tahun = row.getAttribute('data-tahun');
            const semester = row.getAttribute('data-semester').toLowerCase();
            const status = row.getAttribute('data-status').toLowerCase();
            
            const matchesSearch = tahun.includes(searchTerm);
            const matchesSemester = semesterFilter === '' || semester === semesterFilter;
            const matchesStatus = statusFilter === '' || status === statusFilter;
            
            row.style.display = matchesSearch && matchesSemester && matchesStatus ? '' : 'none';
        });
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('semesterFilter').value = '';
        document.getElementById('statusFilter').value = '';
        
        const rows = document.querySelectorAll('#tahunAjaranTable tbody tr');
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