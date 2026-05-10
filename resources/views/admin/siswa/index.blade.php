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

<!-- Siswa Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="siswaTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
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
                @php
                    // Siapkan data untuk atribut data-*
                    $waliName = $siswa->wali ? $siswa->wali->name : '-';
                    $fotoUrl = $siswa->foto ? Storage::url($siswa->foto) : '';
                    $userEmail = $siswa->user ? $siswa->user->email : '';
                    $tanggalLahirFormatted = $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-';
                    $tempatLahir = $siswa->tempat_lahir ?? '-';
                @endphp
                <tr class="table-row" 
                    data-id="{{ $siswa->id }}"
                    data-nis="{{ $siswa->nis }}"
                    data-nama="{{ $siswa->nama_lengkap }}"
                    data-jk="{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}"
                    data-kelas="{{ $siswa->kelas->nama_kelas ?? '-' }}"
                    data-status="{{ $siswa->status }}"
                    data-wali="{{ $waliName }}"
                    data-tempat-lahir="{{ $tempatLahir }}"
                    data-tanggal-lahir="{{ $tanggalLahirFormatted }}"
                    data-alamat="{{ $siswa->alamat ?? '-' }}"
                    data-email="{{ $siswa->email ?? '-' }}"
                    data-no-telp="{{ $siswa->no_telp ?? '-' }}"
                    data-nama-ayah="{{ $siswa->nama_ayah ?? '-' }}"
                    data-nama-ibu="{{ $siswa->nama_ibu ?? '-' }}"
                    data-no-telp-ortu="{{ $siswa->no_telp_orangtua ?? '-' }}"
                    data-foto="{{ $fotoUrl }}"
                    data-user-email="{{ $userEmail }}"
                >
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $data->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $siswa->nis }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</div>
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
                            <button onclick="showDetailModal(this)" 
                                    data-id="{{ $siswa->id }}"
                                    class="text-blue-600 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-xl transition-all" 
                                    title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
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
                    <td colspan="7" class="px-6 py-12 text-center">
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

<!-- MODAL DETAIL SISWA -->
<div id="siswaDetailModal" class="modal-overlay" style="display: none;">
    <div class="modal-content max-w-4xl">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold text-gray-800">Detail Siswa</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="modalDetailContent" class="max-h-[80vh] overflow-y-auto">
            <!-- Akan diisi dengan JS -->
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
    // ==================== DETAIL MODAL ====================
    function showDetailModal(button) {
        // Dapatkan baris tr terdekat
        const row = button.closest('tr');
        if (!row) return;
        
        // Ambil data dari atribut data-* di tr
        const data = {
            id: row.getAttribute('data-id'),
            nis: row.getAttribute('data-nis'),
            nama: row.getAttribute('data-nama'),
            jk: row.getAttribute('data-jk'),
            kelas: row.getAttribute('data-kelas'),
            status: row.getAttribute('data-status'),
            wali: row.getAttribute('data-wali'),
            tempat_lahir: row.getAttribute('data-tempat-lahir'),
            tanggal_lahir: row.getAttribute('data-tanggal-lahir'),
            alamat: row.getAttribute('data-alamat'),
            email: row.getAttribute('data-email'),
            no_telp: row.getAttribute('data-no-telp'),
            nama_ayah: row.getAttribute('data-nama-ayah'),
            nama_ibu: row.getAttribute('data-nama-ibu'),
            no_telp_ortu: row.getAttribute('data-no-telp-ortu'),
            foto: row.getAttribute('data-foto'),
            user_email: row.getAttribute('data-user-email')
        };
        
        // Bangun HTML modal detail (mirip dengan halaman show)
        let fotoHtml = '';
        if (data.foto) {
            fotoHtml = `<img src="${data.foto}" alt="${data.nama}" class="w-full h-full object-cover rounded-2xl">`;
        } else {
            fotoHtml = `<span class="text-4xl text-white font-bold">${data.nama.substring(0, 2).toUpperCase()}</span>`;
        }
        
        let statusBadge = '';
        if (data.status === 'aktif') statusBadge = '<span class="badge-success text-sm px-3 py-1.5">Aktif</span>';
        else if (data.status === 'alumni') statusBadge = '<span class="badge-info text-sm px-3 py-1.5">Alumni</span>';
        else statusBadge = '<span class="badge-danger text-sm px-3 py-1.5">Keluar</span>';
        
        let userHtml = '';
        if (data.user_email) {
            userHtml = `
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-lock text-[#0b4f8c] mr-2"></i> Informasi Akun Login
                    </h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-800 mb-1">Email: <span class="font-mono">${data.user_email}</span></p>
                                <p class="text-xs text-blue-600">Akun ini digunakan siswa untuk login ke sistem</p>
                            </div>
                            <span class="badge-success">Active</span>
                        </div>
                    </div>
                </div>
            `;
        }
        
        const modalContent = `
            <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8 pb-8 border-b border-gray-200">
                <div class="relative">
                    <div class="profile-avatar w-32 h-32 bg-gradient-to-br from-[#0b4f8c] to-[#1e6f9f]">
                        ${fotoHtml}
                    </div>
                    <div class="absolute -bottom-2 -right-2">
                        ${statusBadge}
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">${data.nama}</h2>
                    <div class="flex flex-wrap items-center gap-4 text-gray-600">
                        <div class="flex items-center"><i class="fas fa-id-card w-5 text-[#0b4f8c]"></i><span class="ml-2">${data.nis}</span></div>
                        <div class="flex items-center"><i class="fas fa-graduation-cap w-5 text-[#0b4f8c]"></i><span class="ml-2">${data.kelas}</span></div>
                        <div class="flex items-center"><i class="fas fa-venus-mars w-5 text-[#0b4f8c]"></i><span class="ml-2">${data.jk}</span></div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user text-[#0b4f8c] mr-2"></i> Informasi Pribadi
                    </h3>
                    <div class="space-y-3">
                        <div><span class="w-32 inline-block text-sm text-gray-600">NIS</span>: ${data.nis}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Nama Lengkap</span>: ${data.nama}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Jenis Kelamin</span>: ${data.jk}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Tempat, Tgl Lahir</span>: ${data.tempat_lahir !== '-' ? data.tempat_lahir + ', ' : ''}${data.tanggal_lahir}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Email</span>: ${data.email}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">No. Telepon</span>: ${data.no_telp}</div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-graduation-cap text-[#0b4f8c] mr-2"></i> Informasi Akademik
                    </h3>
                    <div class="space-y-3">
                        <div><span class="w-32 inline-block text-sm text-gray-600">Kelas</span>: ${data.kelas}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Status</span>: ${statusBadge}</div>
                        <div><span class="w-32 inline-block text-sm text-gray-600">Wali Murid</span>: ${data.wali}</div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-map-marker-alt text-[#0b4f8c] mr-2"></i> Alamat
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4"><p class="text-sm text-gray-700">${data.alamat}</p></div>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-users text-[#0b4f8c] mr-2"></i> Informasi Orang Tua
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-xl p-4"><p class="text-xs text-gray-500 mb-1">Nama Ayah</p><p class="text-sm font-medium text-gray-800">${data.nama_ayah}</p></div>
                        <div class="bg-gray-50 rounded-xl p-4"><p class="text-xs text-gray-500 mb-1">Nama Ibu</p><p class="text-sm font-medium text-gray-800">${data.nama_ibu}</p></div>
                        <div class="bg-gray-50 rounded-xl p-4"><p class="text-xs text-gray-500 mb-1">No. Telepon Orang Tua</p><p class="text-sm font-medium text-gray-800">${data.no_telp_ortu}</p></div>
                    </div>
                </div>
                ${userHtml}
            </div>
            
            <div class="flex items-center justify-end space-x-2 mt-8 pt-4 border-t border-gray-200">
                <a href="/admin/siswa/${data.id}/edit" class="btn-primary">
                    <i class="fas fa-edit mr-2"></i> Edit Data
                </a>
                <button onclick="confirmDeleteFromModal(${data.id}, '${data.nama}')" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i> Hapus Data
                </button>
            </div>
        `;
        
        document.getElementById('modalDetailContent').innerHTML = modalContent;
        document.getElementById('siswaDetailModal').style.display = 'flex';
    }
    
    function closeDetailModal() {
        document.getElementById('siswaDetailModal').style.display = 'none';
    }
    
    // Override confirmDelete untuk dipanggil dari modal
    function confirmDeleteFromModal(id, nama) {
        closeDetailModal();
        confirmDelete(id, nama);
    }
    
    // ==================== DELETE MODAL (sama seperti sebelumnya) ====================
    function confirmDelete(id, namaSiswa) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus siswa <strong>${namaSiswa}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/siswa/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // Bulk delete (dari template asli)
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    function selectAll() {
        rowCheckboxes.forEach(checkbox => checkbox.checked = true);
        if (selectAllCheckbox) selectAllCheckbox.checked = true;
    }
    
    function deselectAll() {
        rowCheckboxes.forEach(checkbox => checkbox.checked = false);
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
    }
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = Array.from(rowCheckboxes).every(cb => cb.checked);
            }
        });
    });
    
    function bulkDelete() {
        const selected = Array.from(rowCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
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
    
    // Tutup modal saat klik di luar
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        const bulkDeleteModal = document.getElementById('bulkDeleteModal');
        const detailModal = document.getElementById('siswaDetailModal');
        if (e.target === deleteModal) closeDeleteModal();
        if (e.target === bulkDeleteModal) closeBulkDeleteModal();
        if (e.target === detailModal) closeDetailModal();
    });
    
    // Auto hide success/error
    setTimeout(() => {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush