@extends('layouts.admin')

@section('title', 'Tambah Wali Murid - SMA PGRI Pelaihari')

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
                <h1 class="text-3xl font-bold text-white drop-shadow-lg">Tambah Wali Murid</h1>
            </div>
            <p class="text-white/80 mt-1">Tambah data orang tua/wali murid baru</p>
        </div>
    </div>
</div>

<!-- Form Tambah -->
<div class="glass-card rounded-2xl p-6 animate-slide-in delay-1">
    <form action="{{ route('admin.wali.store') }}" method="POST">
        @csrf

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
                           value="{{ old('name') }}"
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
                           value="{{ old('email') }}"
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
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 081234567890">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Masukkan nomor HP aktif untuk komunikasi
                    </p>
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="form-label flex items-center">
                        <i class="fas fa-lock text-[#6b21a5] mr-2"></i>
                        Password <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-input @error('password') border-red-500 @enderror" 
                           placeholder="Minimal 6 karakter"
                           required>
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
                                   {{ in_array($s->id, old('siswa_id', [])) ? 'checked' : '' }}>
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
                            <p class="text-sm">Tidak ada siswa yang tersedia</p>
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
                        • Password minimal 6 karakter<br>
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
                Simpan Wali Murid
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
            Informasi
        </h3>
        <p class="text-sm text-gray-600">
            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
            Wali murid yang baru ditambahkan akan memiliki status "Belum Terhubung" jika tidak memilih siswa.
        </p>
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
    // Auto hide messages
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const validationMsg = document.getElementById('validationMessage');
        
        if (successMsg) {
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
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

    // Format nomor HP (hanya angka)
    document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
        // Hanya izinkan angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush