@extends('layouts.bendahara')

@section('title', 'Data Kelas - Bendahara - SIPEMA')

@push('styles')
<!-- style sama seperti sebelumnya, tidak perlu diubah -->
<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
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
    max-width: 550px;
    margin: 2rem auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
    overflow: hidden;
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

.modal-header {
    background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
    padding: 1.25rem 1.5rem;
    color: white;
}

.siswa-list {
    max-height: 350px;
    overflow-y: auto;
    padding: 0.5rem;
}

.siswa-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
    transition: background 0.2s;
}

.siswa-item:hover {
    background: #f8fafc;
}

.siswa-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #0b4f8c, #1e6f9f);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 12px;
    flex-shrink: 0;
}

.siswa-name {
    font-size: 0.95rem;
    font-weight: 500;
    color: #1e293b;
}

.empty-siswa {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
}
</style>
@endpush

@section('content')
<!-- Header, Search, Table - sama seperti sebelumnya, hanya tombol detail perlu dipastikan -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data Kelas</h1>
            <p class="text-white/80 mt-1">Kelola data kelas (Bendahara)</p>
        </div>
 
    </div>
</div>

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
                <tr class="table-row" data-nama="{{ strtolower($kelas->nama_kelas) }}" data-tingkat="{{ $kelas->tingkat }}" data-id="{{ $kelas->id }}">
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
                        <button onclick="showDetail({{ $kelas->id }})" class="text-[#0b4f8c] hover:text-[#1e6f9f] p-2 hover:bg-blue-50 rounded-xl transition-all" title="Detail">
                            <i class="fas fa-info-circle mr-1"></i> Detail
                        </button>
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

<!-- CREATE MODAL (sama) -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold">Tambah Kelas</h3>
                <button onclick="closeCreateModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('bendahara.kelas.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
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
            <div class="flex justify-end space-x-4 p-6 pt-0 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- DETAIL MODAL (sama) -->
<div class="modal-overlay" id="detailModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold"><i class="fas fa-school mr-2"></i> Detail Kelas</h3>
                <button onclick="closeDetailModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div><span class="font-semibold text-gray-700">Nama Kelas:</span></div>
                    <div><span id="detailNamaKelas" class="text-gray-800 font-medium"></span></div>
                    <div><span class="font-semibold text-gray-700">Tingkat:</span></div>
                    <div><span id="detailTingkat" class="text-gray-800 font-medium"></span></div>
                    <div><span class="font-semibold text-gray-700">Jumlah Siswa:</span></div>
                    <div><span id="detailJumlahSiswa" class="text-gray-800 font-medium"></span></div>
                </div>
            </div>
            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-users text-[#0b4f8c] mr-2"></i> Daftar Siswa
            </h4>
            <div class="siswa-list border rounded-xl bg-white">
                <div id="daftarSiswaContainer"></div>
            </div>
        </div>
        <div class="flex justify-end p-6 pt-0">
            <button onclick="closeDetailModal()" class="btn-secondary">Tutup</button>
        </div>
    </div>
</div>

@if(session('success')) ... @endif
@if(session('error')) ... @endif
@endsection

@push('scripts')
<?php
// Siapkan data untuk javascript dengan aman
$kelasDataArray = [];
foreach ($data as $kelas) {
    $siswaArray = [];
    // Pastikan relasi siswa ada dan merupakan collection
    if ($kelas->relationLoaded('siswa') && $kelas->siswa) {
        foreach ($kelas->siswa as $siswa) {
            $siswaArray[] = [
                'nama' => $siswa->nama_lengkap ?? 'Nama tidak tersedia'
            ];
        }
    }
    $kelasDataArray[] = [
        'id' => $kelas->id,
        'nama_kelas' => $kelas->nama_kelas,
        'tingkat' => $kelas->tingkat,
        'siswa_count' => $kelas->siswa_count,
        'siswa' => $siswaArray,
    ];
}
?>
<script>
    // Data kelas dari server
    const kelasData = @json($kelasDataArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    
    // Debug: cek data di console
    console.log('Kelas Data:', kelasData);

    // Fungsi tampilkan detail modal
    function showDetail(id) {
        console.log('Tombol Detail diklik untuk ID:', id);
        const kelas = kelasData.find(k => k.id == id);
        if (!kelas) {
            console.error('Kelas tidak ditemukan untuk ID:', id);
            alert('Data kelas tidak ditemukan!');
            return;
        }
        console.log('Data kelas ditemukan:', kelas);

        // Isi info kelas
        document.getElementById('detailNamaKelas').innerText = kelas.nama_kelas;
        document.getElementById('detailTingkat').innerText = 'Kelas ' + kelas.tingkat;
        document.getElementById('detailJumlahSiswa').innerHTML = kelas.siswa_count + ' orang';

        const siswaList = kelas.siswa;
        const container = document.getElementById('daftarSiswaContainer');
        
        if (!siswaList || siswaList.length === 0) {
            container.innerHTML = '<div class="empty-siswa"><i class="fas fa-user-graduate text-4xl mb-2 opacity-30"></i><p>Belum ada siswa di kelas ini</p></div>';
        } else {
            let html = '';
            siswaList.forEach((siswa, idx) => {
                const inisial = siswa.nama.charAt(0).toUpperCase();
                html += `
                    <div class="siswa-item">
                        <div class="siswa-avatar">${inisial}</div>
                        <div class="siswa-name">${escapeHtml(siswa.nama)}</div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        document.getElementById('detailModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Fungsi bantu untuk mengamankan output HTML
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Create modal functions
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.querySelector('#createModal form').reset();
        document.body.style.overflow = '';
    }

    // Filter table (sama)
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

    // Tutup modal saat klik overlay atau ESC
    window.addEventListener('click', e => {
        if (e.target === document.getElementById('createModal')) closeCreateModal();
        if (e.target === document.getElementById('detailModal')) closeDetailModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeDetailModal();
        }
    });

    // Auto hilangkan pesan flash
    setTimeout(() => {
        const success = document.getElementById('successMessage');
        const error = document.getElementById('errorMessage');
        if (success) success.remove();
        if (error) error.remove();
    }, 5000);
</script>
@endpush