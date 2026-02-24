@extends('layouts.admin')

@section('title', 'Edit Siswa - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Edit Siswa</h1>
            <p class="text-white/80 mt-1">Edit data siswa {{ $siswa->nama_lengkap }}</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="glass-card rounded-2xl p-8 animate-slide-in delay-1">
    <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Alert Error -->
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Terdapat kesalahan pengisian:</h4>
                    <ul class="text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Informasi Pribadi -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-user text-[#0b4f8c] mr-2"></i>
                Informasi Pribadi
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- NIS -->
                <div>
                    <label class="form-label">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" class="form-input @error('nis') border-red-500 @enderror" 
                           value="{{ old('nis', $siswa->nis) }}" placeholder="Masukkan NIS" required>
                    @error('nis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') border-red-500 @enderror" 
                           value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jenis Kelamin -->
                <div>
                    <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tempat Lahir -->
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-input" 
                           value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" placeholder="Contoh: Banjarmasin">
                </div>
                
                <!-- Tanggal Lahir -->
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-input" 
                           value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}">
                </div>
                
                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" 
                           value="{{ old('email', $siswa->email) }}" placeholder="contoh@email.com">
                </div>
                
                <!-- No. Telp -->
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_telp" class="form-input" 
                           value="{{ old('no_telp', $siswa->no_telp) }}" placeholder="081234567890">
                </div>
            </div>
        </div>
        
        <!-- Alamat -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-map-marker-alt text-[#0b4f8c] mr-2"></i>
                Alamat
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="form-input" placeholder="Masukkan alamat lengkap">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Informasi Akademik -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-graduation-cap text-[#0b4f8c] mr-2"></i>
                Informasi Akademik
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kelas -->
                <div>
                    <label class="form-label">Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas_id" class="form-select @error('kelas_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-select @error('status') border-red-500 @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="alumni" {{ old('status', $siswa->status) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="keluar" {{ old('status', $siswa->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Foto -->
                <div>
                    <label class="form-label">Foto</label>
                    <div class="flex items-center space-x-4">
                        <div class="profile-avatar w-16 h-16 bg-gray-200" id="preview-container" style="{{ $siswa->foto ? 'display: block;' : 'display: none;' }}">
                            <img id="foto-preview" src="{{ $siswa->foto ? Storage::url($siswa->foto) : '' }}" alt="Preview" class="w-full h-full object-cover rounded-xl">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" id="foto" class="form-input p-2" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Orang Tua -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-users text-[#0b4f8c] mr-2"></i>
                Informasi Orang Tua
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Ayah -->
                <div>
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-input" 
                           value="{{ old('nama_ayah', $siswa->nama_ayah) }}" placeholder="Masukkan nama ayah">
                </div>
                
                <!-- Nama Ibu -->
                <div>
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-input" 
                           value="{{ old('nama_ibu', $siswa->nama_ibu) }}" placeholder="Masukkan nama ibu">
                </div>
                
                <!-- No. Telp Orang Tua -->
                <div>
                    <label class="form-label">No. Telepon Orang Tua</label>
                    <input type="text" name="no_telp_orangtua" class="form-input" 
                           value="{{ old('no_telp_orangtua', $siswa->no_telp_orangtua) }}" placeholder="081234567890">
                </div>
            </div>
        </div>
        
        <!-- Informasi Akun -->
        @if($siswa->user)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-lock text-[#0b4f8c] mr-2"></i>
                Informasi Akun Login
            </h3>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800 mb-1">Siswa ini sudah memiliki akun login:</p>
                        <p class="text-sm text-blue-600 mb-1">Email: <span class="font-mono">{{ $siswa->user->email }}</span></p>
                        <p class="text-xs text-blue-500">Password dapat di-reset melalui fitur reset password di halaman manajemen user.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Tombol Submit -->
        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>
                Update Data Siswa
            </button>
        </div>
    </form>
</div>

<!-- Preview Foto Script -->
@push('scripts')
<script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('foto-preview').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection