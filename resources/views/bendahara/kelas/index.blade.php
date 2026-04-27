@extends('layouts.bendahara')

@section('title', 'Data Kelas - Bendahara SMA PGRI Pelaihari')

@push('styles')
<style>
/* Modal Styles (sama seperti sebelumnya) */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    overflow-y: auto;
}

.modal-content {
    background: white;
    border-radius: 1.5rem;
    width: 90%;
    max-width: 500px;
    margin: 2rem auto;
    padding: 1.5rem;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Kelas</h1>
            <p class="text-white/80 mt-1">Kelola data kelas (Bendahara)</p>
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
                <input type="text" placeholder="Cari nama kelas..." class="search-input" id="searchInput">
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="form-select w-40" id="tingkatFilter">
                <option value="">Semua Tingkat</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $kelas)
                <tr class="table-row" data-nama="{{ strtolower($kelas->nama_kelas) }}" data-tingkat="{{ $kelas->tingkat }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $kelas->nama_kelas }}</td>
                    <td class="px-6 py-4">
                        <span class="badge-info">Kelas {{ $kelas->tingkat }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge-{{ $kelas->siswa_count > 0 ? 'success' : 'warning' }}">
                            {{ $kelas->siswa_count }} Siswa
                        </span>
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
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Menampilkan {{ $data->count() }} data</p>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Kelas</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form action="{{ route('bendahara.kelas.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" class="form-input" placeholder="Contoh: XII IPA 1" required>
                </div>
                <div>
                    <label class="form-label">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" class="form-select" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Edit Kelas</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form action="" method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" id="edit_tingkat" class="form-select" required>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
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
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="closeDeleteModal()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Messages -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50" id="successMessage">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button onclick="this.parentElement.remove()" class="ml-4">&times;</button>
</div>
@endif
@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50" id="errorMessage">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    <button onclick="this.parentElement.remove()" class="ml-4">&times;</button>
</div>
@endif
@endsection

@push('scripts')
<script>
    const kelasData = @json($data);

    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.querySelector('#createModal form').reset();
        document.body.style.overflow = '';
    }

    function openEditModal(id) {
        const item = kelasData.find(k => k.id == id);
        if (item) {
            document.getElementById('edit_nama_kelas').value = item.nama_kelas;
            document.getElementById('edit_tingkat').value = item.tingkat;
            document.getElementById('editForm').action = `/bendahara/kelas/${id}`;
            document.getElementById('editModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function confirmDelete(id, nama) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus kelas <strong>${nama}</strong>?`;
        document.getElementById('deleteForm').action = `/bendahara/kelas/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Filter
    const searchInput = document.getElementById('searchInput');
    const tingkatFilter = document.getElementById('tingkatFilter');
    const rows = () => document.querySelectorAll('#kelasTable tbody tr');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const tingkat = tingkatFilter.value;
        rows().forEach(row => {
            const nama = row.getAttribute('data-nama');
            const rowTingkat = row.getAttribute('data-tingkat');
            let show = true;
            if (search && !nama.includes(search)) show = false;
            if (tingkat && rowTingkat !== tingkat) show = false;
            row.style.display = show ? '' : 'none';
        });
    }
    searchInput.addEventListener('keyup', filterTable);
    tingkatFilter.addEventListener('change', filterTable);
    function resetFilter() {
        searchInput.value = '';
        tingkatFilter.value = '';
        filterTable();
    }

    // Close modals on outside click
    window.addEventListener('click', e => {
        if (e.target === document.getElementById('createModal')) closeCreateModal();
        if (e.target === document.getElementById('editModal')) closeEditModal();
        if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });

    setTimeout(() => {
        const success = document.getElementById('successMessage');
        const error = document.getElementById('errorMessage');
        if (success) success.remove();
        if (error) error.remove();
    }, 5000);
</script>
@endpush