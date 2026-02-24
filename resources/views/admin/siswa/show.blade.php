@extends('layouts.admin')

@section('title', 'Detail Siswa - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Detail Siswa</h1>
            <p class="text-white/80 mt-1">Informasi lengkap data siswa</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Profile Card -->
<div class="glass-card rounded-2xl p-8 animate-slide-in delay-1">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8 pb-8 border-b border-gray-200">
        <!-- Foto Profile -->
        <div class="relative">
            <div class="profile-avatar w-32 h-32 bg-gradient-to-br from-[#0b4f8c] to-[#1e6f9f]">
                @if($siswa->foto)
                    <img src="{{ Storage::url($siswa->foto) }}" alt="{{ $siswa->nama_lengkap }}" 
                         class="w-full h-full object-cover rounded-2xl">
                @else
                    <span class="text-4xl text-white font-bold">
                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                    </span>
                @endif
            </div>
            <div class="absolute -bottom-2 -right-2">
                @if($siswa->status == 'aktif')
                    <span class="badge-success text-sm px-3 py-1.5">Aktif</span>
                @elseif($siswa->status == 'alumni')
                    <span class="badge-info text-sm px-3 py-1.5">Alumni</span>
                @else
                    <span class="badge-danger text-sm px-3 py-1.5">Keluar</span>
                @endif
            </div>
        </div>
        
        <!-- Info Singkat -->
        <div class="flex-1">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $siswa->nama_lengkap }}</h2>
            <div class="flex flex-wrap items-center gap-4 text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-id-card w-5 text-[#0b4f8c]"></i>
                    <span class="ml-2">{{ $siswa->nis }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap w-5 text-[#0b4f8c]"></i>
                    <span class="ml-2">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-venus-mars w-5 text-[#0b4f8c]"></i>
                    <span class="ml-2">{{ $siswa->jenis_kelamin_label }}</span>
                </div>
                @if($siswa->umur != '-')
                <div class="flex items-center">
                    <i class="fas fa-birthday-cake w-5 text-[#0b4f8c]"></i>
                    <span class="ml-2">{{ $siswa->umur }} tahun</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Informasi Lengkap -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Informasi Pribadi -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-user text-[#0b4f8c] mr-2"></i>
                Informasi Pribadi
            </h3>
            
            <div class="space-y-3">
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">NIS</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->nis }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Nama Lengkap</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->nama_lengkap }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Jenis Kelamin</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->jenis_kelamin_label }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Tempat, Tgl Lahir</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">
                        : {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                    </span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Email</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->email ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">No. Telepon</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->no_telp ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Informasi Akademik -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-graduation-cap text-[#0b4f8c] mr-2"></i>
                Informasi Akademik
            </h3>
            
            <div class="space-y-3">
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Kelas</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Tingkat</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">: {{ $siswa->kelas->tingkat ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm text-gray-600">Status</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">
                        : {!! $siswa->status_label !!}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Alamat -->
        <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-map-marker-alt text-[#0b4f8c] mr-2"></i>
                Alamat
            </h3>
            
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-700">{{ $siswa->alamat ?? 'Tidak ada alamat' }}</p>
            </div>
        </div>
        
        <!-- Informasi Orang Tua -->
        <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-users text-[#0b4f8c] mr-2"></i>
                Informasi Orang Tua
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Nama Ayah</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->nama_ayah ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Nama Ibu</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->nama_ibu ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">No. Telepon Orang Tua</p>
                    <p class="text-sm font-medium text-gray-800">{{ $siswa->no_telp_orangtua ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Informasi Akun Login -->
        @if($siswa->user)
        <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-lock text-[#0b4f8c] mr-2"></i>
                Informasi Akun Login
            </h3>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-800 mb-1">Email: <span class="font-mono">{{ $siswa->user->email }}</span></p>
                        <p class="text-xs text-blue-600">Akun ini digunakan siswa untuk login ke sistem</p>
                    </div>
                    <span class="badge-success">Active</span>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Tombol Aksi -->
    <div class="flex items-center justify-end space-x-2 mt-8 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn-primary">
            <i class="fas fa-edit mr-2"></i>
            Edit Data
        </a>
        <button onclick="confirmDelete({{ $siswa->id }}, '{{ $siswa->nama_lengkap }}')" class="btn-danger">
            <i class="fas fa-trash mr-2"></i>
            Hapus Data
        </button>
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
    
    // ==================== CLOSE MODALS WHEN CLICKING OUTSIDE ====================
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection