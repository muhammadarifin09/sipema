@extends('layouts.admin')

@section('title', 'Edit Wali Murid - SMA PGRI Pelaihari')

@section('content')
<!-- Header dengan tombol back -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.wali.index') }}" class="text-white/80 hover:text-white transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <h1 class="text-3xl font-bold text-white drop-shadow-lg">Edit Wali Murid</h1>
            </div>
            <p class="text-white/80 mt-1">Edit data orang tua/wali murid</p>
        </div>
        <div class="profile-avatar w-12 h-12 text-sm" style="background: linear-gradient(135deg, #6b21a5, #8b5cf6);">
            <span>{{ strtoupper(substr($wali->name, 0, 2)) }}</span>
        </div>
    </div>
</div>

<!-- Form Edit -->
<div class="glass-card rounded-2xl p-6 animate-slide-in delay-1">
    <form action="{{ route('admin.wali.update', $wali->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <!-- Nama Wali -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-user text-[#6b21a5] mr-2"></i>
                        Nama Lengkap Wali <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-input @error('name') border-red-500 @enderror" 
                           value="{{ old('name', $wali->name) }}"
                           placeholder="Masukkan nama lengkap wali"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-envelope text-[#6b21a5] mr-2"></i>
                        Email <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-input @error('email') border-red-500 @enderror" 
                           value="{{ old('email', $wali->email) }}"
                           placeholder="contoh@email.com"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-phone text-[#6b21a5] mr-2"></i>
                        Nomor HP
                        <span class="text-gray-400 text-xs ml-2">(Opsional)</span>
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           class="form-input @error('no_hp') border-red-500 @enderror" 
                           value="{{ old('no_hp', $wali->no_hp) }}"
                           placeholder="Contoh: 081234567890">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Masukkan nomor HP aktif untuk komunikasi
                    </p>
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Opsional) -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-lock text-[#6b21a5] mr-2"></i>
                        Password <span class="text-gray-400 text-xs ml-2">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-input @error('password') border-red-500 @enderror" 
                           placeholder="Minimal 6 karakter">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Kosongkan jika tidak ingin mengubah password
                    </p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4">
                <!-- Pilih Siswa (Multiple) -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-user-graduate text-[#6b21a5] mr-2"></i>
                        Hubungkan dengan Siswa <span class="text-gray-400 text-xs ml-2">(Bisa pilih lebih dari satu)</span>
                    </label>
                    
                    <!-- Search box untuk siswa -->
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" 
                                   id="searchSiswa" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6b21a5]/20 focus:border-[#6b21a5]" 
                                   placeholder="Cari siswa...">
                        </div>
                    </div>

                    <!-- Select All / Deselect All Buttons -->
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-500" id="selectedCountInfo">0 siswa dipilih</span>
                        <div class="space-x-2">
                            <button type="button" id="selectAllBtn" class="text-xs text-[#6b21a5] hover:text-[#8b5cf6] transition">
                                <i class="fas fa-check-double mr-1"></i>Pilih Semua
                            </button>
                            <button type="button" id="deselectAllBtn" class="text-xs text-gray-500 hover:text-gray-700 transition">
                                <i class="fas fa-times mr-1"></i>Hapus Semua
                            </button>
                        </div>
                    </div>

                    <!-- Multiple Select dengan checkbox -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden max-h-60 overflow-y-auto">
                        @forelse($siswa as $s)
                        <div class="flex items-center p-3 hover:bg-purple-50 border-b border-gray-100 last:border-b-0 siswa-item">
                            <input type="checkbox" 
                                   name="siswa_id[]" 
                                   value="{{ $s->id }}" 
                                   id="siswa_{{ $s->id }}"
                                   class="w-5 h-5 text-[#6b21a5] rounded focus:ring-[#6b21a5] siswa-checkbox"
                                   {{ $wali->siswa && in_array($s->id, old('siswa_id', $wali->siswa->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label for="siswa_{{ $s->id }}" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-800 text-sm">{{ $s->nama_lengkap }}</span>
                                        <span class="text-xs text-gray-400 ml-2">({{ $s->nis }})</span>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                                        {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                    </span>
                                </div>
                            </label>
                        </div>
                        @empty
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-user-graduate text-3xl mb-2 opacity-50"></i>
                            <p class="text-sm">Tidak ada data siswa tersedia</p>
                            <a href="{{ route('admin.siswa.create') }}" class="text-xs text-[#6b21a5] hover:underline mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Tambah Siswa Baru
                            </a>
                        </div>
                        @endforelse
                    </div>

                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Centang siswa yang ingin dihubungkan dengan wali murid ini. Satu wali dapat membimbing lebih dari satu siswa.
                    </p>

                    @error('siswa_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('siswa_id.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Siswa Saat Ini -->
                @if($wali->siswa && $wali->siswa->count() > 0)
                <div class="mt-3 p-4 bg-purple-50 rounded-xl">
                    <p class="text-sm font-medium text-purple-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Siswa Terhubung Saat Ini ({{ $wali->siswa->count() }})
                    </p>
                    <div class="space-y-2 max-h-32 overflow-y-auto">
                        @foreach($wali->siswa as $siswaTerhubung)
                            @if($siswaTerhubung && is_object($siswaTerhubung))
                            <div class="flex items-center justify-between bg-white/50 p-2 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate text-purple-600 mr-2 text-xs"></i>
                                    <span class="text-sm text-gray-700">{{ $siswaTerhubung->nama_lengkap ?? 'Nama tidak tersedia' }}</span>
                                    <span class="text-xs text-gray-400 ml-2">({{ $siswaTerhubung->nis ?? '-' }})</span>
                                </div>
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">
                                    {{ $siswaTerhubung->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Status Akun -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="active" value="1" {{ $wali->active ? 'checked' : '' }} class="w-5 h-5 text-[#6b21a5] rounded focus:ring-[#6b21a5]">
                        <span class="text-gray-700 text-sm">Akun Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2 ml-8">
                        Nonaktifkan untuk mencegah wali murid login ke sistem.
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl">
            <div class="flex items-start space-x-3">
                <i class="fas fa-shield-alt text-blue-600 mt-1"></i>
                <div>
                    <p class="text-sm font-medium text-blue-800">Informasi Keamanan</p>
                    <p class="text-xs text-blue-600 mt-1">
                        • Email digunakan untuk login ke sistem<br>
                        • Password minimal 6 karakter (kosongkan jika tidak diubah)<br>
                        • Nomor HP digunakan untuk komunikasi dan notifikasi<br>
                        • Wali murid dapat melihat data semua siswa yang terhubung
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.wali.index') }}" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
            <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #6b21a5, #8b5cf6);">
                <i class="fas fa-save mr-2"></i>
                Update Wali Murid
            </button>
        </div>
    </form>
</div>

<!-- Preview Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Info Wali -->
    <div class="glass-card rounded-2xl p-5 animate-slide-in delay-2 lg:col-span-2">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-[#6b21a5] mr-2"></i>
            Ringkasan Data
        </h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-400">Total Wali Terdaftar</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalWali ?? App\Models\User::where('role_id',3)->count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Siswa</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalSiswa ?? App\Models\Siswa::count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Siswa Terhubung</p>
                <p class="text-xl font-bold text-[#6b21a5]">{{ $wali->siswa ? $wali->siswa->count() : 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card rounded-2xl p-5 animate-slide-in delay-3">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-[#6b21a5] mr-2"></i>
            Aksi Cepat
        </h3>
        <div class="space-y-2">
            <a href="{{ route('admin.wali.index') }}" class="flex items-center p-2 hover:bg-purple-50 rounded-lg transition">
                <i class="fas fa-list text-[#6b21a5] w-6"></i>
                <span class="text-sm text-gray-700">Lihat Semua Wali</span>
            </a>
            <a href="{{ route('admin.wali.create') }}" class="flex items-center p-2 hover:bg-purple-50 rounded-lg transition">
                <i class="fas fa-plus text-[#6b21a5] w-6"></i>
                <span class="text-sm text-gray-700">Tambah Wali Baru</span>
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="successMessage">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="errorMessage">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if($errors->any())
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="validationMessage">
    <i class="fas fa-exclamation-triangle"></i>
    <span>Terjadi kesalahan validasi. Periksa kembali form Anda.</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        const validationMsg = document.getElementById('validationMessage');
        
        if (successMsg) {
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
        }
        if (errorMsg) {
            errorMsg.style.opacity = '0';
            setTimeout(() => errorMsg.remove(), 500);
        }
        if (validationMsg) {
            validationMsg.style.opacity = '0';
            setTimeout(() => validationMsg.remove(), 500);
        }
    }, 5000);

    // Search functionality untuk filter siswa
    document.getElementById('searchSiswa')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.siswa-item');
        
        items.forEach(item => {
            const label = item.querySelector('label').textContent.toLowerCase();
            if (label.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Hitung jumlah siswa yang dipilih
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('input[name="siswa_id[]"]:checked');
        const count = checkboxes.length;
        
        // Update info jumlah yang dipilih
        const countInfo = document.getElementById('selectedCountInfo');
        if (countInfo) {
            countInfo.textContent = count > 0 ? `${count} siswa dipilih` : '0 siswa dipilih';
        }
        
        return count;
    }

    // Select All functionality
    document.getElementById('selectAllBtn')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="siswa_id[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    });

    // Deselect All functionality
    document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="siswa_id[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    });

    // Event listener untuk checkbox
    document.querySelectorAll('input[name="siswa_id[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initial count
    updateSelectedCount();

    // Format nomor HP (opsional)
    document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
        // Hanya izinkan angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush