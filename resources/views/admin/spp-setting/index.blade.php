@extends('layouts.admin')

@section('title', 'Data SPP Setting - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data SPP Setting</h1>
            <p class="text-white/80 mt-1">Kelola pengaturan SPP per tahun ajaran</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah Setting SPP
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
            <select class="form-select w-48" id="tahunAjaranFilter">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($tahunAjaran as $ta)
                    <option value="{{ $ta->tahun_ajaran }}">{{ $ta->tahun_ajaran }} @if($ta->status == 'aktif') (Aktif) @endif</option>
                @endforeach
            </select>
            <button onclick="resetFilter()" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- SPP Settings Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="sppTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nominal SPP</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr class="table-row" 
                    data-tahun="{{ strtolower($item->tahunAjaran->tahun ?? '') }}" 
                    data-nominal="{{ $item->nominal }}"
                    data-tanggal="{{ $item->tanggal_jatuh_tempo }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: linear-gradient(135deg, #0b4f8c, #1e6f9f);">
                                <span>{{ substr($item->tahunAjaran->tahun ?? 'TA', 2, 2) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $item->tahunAjaran->tahun ?? 'Tidak Ada' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="badge-info">Tanggal {{ $item->tanggal_jatuh_tempo }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->tahunAjaran && $item->tahunAjaran->status == 'aktif')
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-warning">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditModal({{ $item->id }})" class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $item->id }}, '{{ $item->tahunAjaran->tahun ?? 'Tahun Ajaran' }}')" class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-money-bill-wave text-5xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">Belum ada data SPP Setting</p>
                            <p class="text-sm">Klik tombol "Tambah Setting SPP" untuk menambahkan data</p>
                        </div>
                    </td>
                </tr>
                @endforelse
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
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Setting SPP Baru</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.spp-setting.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="grid grid-cols-1 gap-4 mb-4">
                <!-- Tahun Ajaran -->
                <div>
                    <label class="form-label">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="tahun_ajaran_id" class="form-select" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($tahunAjaran as $ta)
                            <option value="{{ $ta->id }}" 
                                {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}
                                @if($ta->status == 'aktif') style="color: #059669; font-weight: 600; background-color: #f0fdf4;" @endif
                            >
                                {{ $ta->tahun }} 
                                @if($ta->status == 'aktif') 
                                    (Aktif) 👑
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih tahun ajaran yang akan diatur SPP-nya</p>
                    @error('tahun_ajaran_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nominal SPP -->
                    <div>
                        <label class="form-label">Nominal SPP <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" 
                                   name="nominal" 
                                   class="form-input pl-12 pr-4" 
                                   placeholder="0" 
                                   required 
                                   min="0" 
                                   step="1000"
                                   value="{{ old('nominal') }}">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan nominal dalam Rupiah</p>
                        @error('nominal')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tanggal Jatuh Tempo -->
                    <div>
                        <label class="form-label">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                        <select name="tanggal_jatuh_tempo" class="form-select" required>
                            <option value="">-- Pilih Tanggal --</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ old('tanggal_jatuh_tempo') == $i ? 'selected' : '' }}>
                                    Tanggal {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tanggal pembayaran SPP setiap bulannya</p>
                        @error('tanggal_jatuh_tempo')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary px-6 py-3">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button type="submit" class="btn-primary px-6 py-3">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Setting
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Edit Setting SPP</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-4 mb-4">
                <!-- Tahun Ajaran -->
                <div>
                    <label class="form-label">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="tahun_ajaran_id" id="edit_tahun_ajaran_id" class="form-select" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($tahunAjaran as $ta)
                            <option value="{{ $ta->id }}"
                                @if($ta->status == 'aktif') style="color: #059669; font-weight: 600; background-color: #f0fdf4;" @endif
                            >
                                {{ $ta->tahun }} 
                                @if($ta->status == 'aktif') 
                                    (Aktif) 👑
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih tahun ajaran yang akan diatur SPP-nya</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nominal SPP -->
                    <div>
                        <label class="form-label">Nominal SPP <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" 
                                   name="nominal" 
                                   id="edit_nominal" 
                                   class="form-input pl-12 pr-4" 
                                   required min="0" 
                                   step="1000"
                                   oninput="formatNominal(this)">
                        </div>
                    </div>
                    
                    <!-- Tanggal Jatuh Tempo - REDESIGNED -->
                    <div>
                        <label class="form-label">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="tanggal_jatuh_tempo" id="edit_tanggal_jatuh_tempo" class="form-select appearance-none" required>
                                <option value="">-- Pilih Tanggal --</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih tanggal jatuh tempo pembayaran SPP setiap bulannya
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl mb-4 border border-blue-100">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800">Informasi Setting SPP</h4>
                        <p class="text-xs text-blue-600 mt-1">
                            Setting SPP akan berlaku untuk tahun ajaran yang dipilih. 
                            Tanggal jatuh tempo akan digunakan untuk menentukan batas pembayaran SPP setiap bulannya.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="btn-secondary px-6 py-3">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button type="submit" class="btn-primary px-6 py-3">
                    <i class="fas fa-save mr-2"></i>
                    Update Setting
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
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus setting SPP ini?</p>
            
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <div class="flex items-center justify-center space-x-4">
                    <button type="button" onclick="closeDeleteModal()" class="btn-secondary px-6 py-3">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn-danger px-6 py-3">
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

<!-- Validation Errors -->
@if($errors->any())
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="validationErrors">
    <i class="fas fa-exclamation-circle"></i>
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<style>
/* Custom styles untuk select dropdown */
.modal-content select option {
    padding: 8px 12px;
}

.modal-content select option:hover {
    background-color: #f0f9ff;
}

/* Style untuk dropdown yang lebih rapi */
.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* Hover effect untuk options */
.form-select option:checked {
    background: linear-gradient(135deg, #0b4f8c, #1e6f9f);
    color: white;
}
</style>
@endsection

@push('scripts')
<script>
    // Data spp settings untuk edit (simpan dalam format JSON)
    const sppData = @json($data);
    
    // ==================== CREATE MODAL ====================
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.getElementById('createForm').reset();
        document.body.style.overflow = '';
    }
    
    // ==================== EDIT MODAL ====================
    function openEditModal(id) {
        const item = sppData.find(s => s.id === id);
        
        if (item) {
            document.getElementById('edit_tahun_ajaran_id').value = item.tahun_ajaran_id;
            document.getElementById('edit_nominal').value = item.nominal;
            document.getElementById('edit_tanggal_jatuh_tempo').value = item.tanggal_jatuh_tempo;
            
            // Set form action
            document.getElementById('editForm').action = `/admin/spp-setting/${id}`;
            
            document.getElementById('editModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editForm').reset();
        document.body.style.overflow = '';
    }
    
    // ==================== DELETE MODAL ====================
    function confirmDelete(id, tahunAjaran) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus setting SPP untuk <strong>${tahunAjaran}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/spp-setting/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // ==================== FORMAT NOMINAL ====================
    function formatNominal(input) {
        // Hanya untuk display, value yang dikirim tetap angka
        const value = input.value;
        const preview = document.getElementById('previewNominalEdit');
        if (preview && value) {
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
            preview.innerText = formatted;
        }
    }
    
    // ==================== SEARCH & FILTER ====================
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('tahunAjaranFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const tahunFilter = document.getElementById('tahunAjaranFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#sppTable tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            
            const tahun = row.getAttribute('data-tahun');
            
            const matchesSearch = tahun.includes(searchTerm);
            const matchesTahun = tahunFilter === '' || tahun === tahunFilter;
            
            row.style.display = matchesSearch && matchesTahun ? '' : 'none';
        });
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('tahunAjaranFilter').value = '';
        
        const rows = document.querySelectorAll('#sppTable tbody tr');
        rows.forEach(row => {
            if (!row.querySelector('td[colspan]')) {
                row.style.display = '';
            }
        });
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
    
    // ==================== CLOSE WITH ESC KEY ====================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
    
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        const validationErrors = document.getElementById('validationErrors');
        
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
        if (validationErrors) validationErrors.remove();
    }, 5000);
</script>
@endpush