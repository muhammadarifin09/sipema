@extends('layouts.admin')

@section('title', 'Data Siswa - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Siswa</h1>
            <p class="text-white/80 mt-1">Kelola data siswa dan informasi terkait</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah Siswa
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-slide-in delay-1">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
                <h3 class="text-3xl font-bold text-[#0b4f8c]">{{ $data->total() }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Siswa Aktif</p>
                <h3 class="text-3xl font-bold text-green-600">{{ App\Models\Siswa::where('status', 'aktif')->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-check text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Laki-laki</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ App\Models\Siswa::where('jenis_kelamin', 'L')->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-male text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Perempuan</p>
                <h3 class="text-3xl font-bold text-pink-600">{{ App\Models\Siswa::where('jenis_kelamin', 'P')->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-female text-2xl text-pink-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" placeholder="Cari NIS, nama, atau email..." 
                       class="search-input" value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <select name="kelas_id" class="form-select w-40">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                </option>
                @endforeach
            </select>
            
            <select name="status" class="form-select w-36">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }} style="color: #059669;">Aktif</option>
                <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }} style="color: #2563eb;">Alumni</option>
                <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }} style="color: #b45309;">Keluar</option>
            </select>
            
            <select name="jenis_kelamin" class="form-select w-36">
                <option value="">Semua Gender</option>
                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            
            <button type="submit" class="btn-primary text-sm py-3">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="mb-4 flex items-center justify-between animate-slide-in delay-2">
    <div class="flex items-center space-x-2">
        <button onclick="selectAll()" class="text-sm text-gray-600 hover:text-gray-800">
            <i class="far fa-check-square mr-1"></i> Pilih Semua
        </button>
        <button onclick="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">
            <i class="far fa-square mr-1"></i> Batalkan
        </button>
        <button onclick="bulkDelete()" class="text-sm text-red-600 hover:text-red-700">
            <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih
        </button>
    </div>
    <a href="" class="text-sm text-green-600 hover:text-green-700">
        <i class="fas fa-download mr-1"></i> Export Excel
    </a>
</div>

<!-- Siswa Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="siswaTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl w-12">
                        <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-[#0b4f8c] focus:ring-[#0b4f8c]">
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $siswa)
                <tr class="table-row" data-id="{{ $siswa->id }}">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="row-checkbox rounded border-gray-300 text-[#0b4f8c] focus:ring-[#0b4f8c]" value="{{ $siswa->id }}">
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $data->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $siswa->nis }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</div>
                        @if($siswa->email)
                            <div class="text-xs text-gray-500">{{ $siswa->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($siswa->jenis_kelamin == 'L')
                            <span class="badge-info">Laki-laki</span>
                        @else
                            <span class="badge-warning">Perempuan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($siswa->status == 'aktif')
                            <span class="badge-success">Aktif</span>
                        @elseif($siswa->status == 'alumni')
                            <span class="badge-info">Alumni</span>
                        @else
                            <span class="badge-danger">Keluar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.siswa.show', $siswa->id) }}" 
                               class="text-blue-600 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-xl transition-all" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.siswa.edit', $siswa->id) }}" 
                               class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $siswa->id }}, '{{ $siswa->nama_lengkap }}')" 
                                    class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" 
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-2">Belum ada data siswa</p>
                            <p class="text-gray-400 text-sm">Klik tombol "Tambah Siswa" untuk menambahkan data</p>
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

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus siswa ini?</p>
            
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

<!-- BULK DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="bulkDeleteModal" style="display: none;">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus Massal</h3>
            <p class="text-gray-600 mb-6" id="bulkDeleteMessage">Apakah Anda yakin ingin menghapus <span id="selectedCount">0</span> data siswa yang dipilih?</p>
            
            <form action="" method="POST" id="bulkDeleteForm">
                @csrf
                <input type="hidden" name="ids" id="selectedIds">
                
                <div class="flex items-center justify-center space-x-4">
                    <button type="button" onclick="closeBulkDeleteModal()" class="btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash mr-2"></i>
                        Ya, Hapus Semua
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
    // ==================== DELETE MODAL ====================
    function confirmDelete(id, namaSiswa) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus siswa <strong>${namaSiswa}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/siswa/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // ==================== BULK DELETE ====================
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    function selectAll() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = true;
        }
    }
    
    function deselectAll() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
        }
    }
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (selectAllCheckbox) {
                const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
        });
    });
    
    function bulkDelete() {
        const selected = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('Pilih minimal satu data siswa untuk dihapus');
            return;
        }
        
        document.getElementById('selectedCount').textContent = selected.length;
        document.getElementById('selectedIds').value = JSON.stringify(selected);
        document.getElementById('bulkDeleteModal').style.display = 'flex';
    }
    
    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').style.display = 'none';
    }
    
    // ==================== CLOSE MODALS WHEN CLICKING OUTSIDE ====================
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        const bulkDeleteModal = document.getElementById('bulkDeleteModal');
        
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
        if (e.target === bulkDeleteModal) {
            closeBulkDeleteModal();
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