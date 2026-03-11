@extends('layouts.admin')

@section('title', 'Data Tagihan SPP')

@section('content')

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">
                Data Tagihan SPP
            </h1>
            <p class="text-white/80 mt-1">
                Monitoring tagihan siswa per bulan
            </p>
        </div>

        <!-- Generate Button -->
        <form action="{{ route('admin.tagihan.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <button type="submit" class="btn-primary">
                <i class="fas fa-sync-alt mr-2"></i>
                Generate Tagihan
            </button>
        </form>
    </div>
</div>

<!-- Filter Bulan -->
<div class="glass-card rounded-2xl p-4 mb-6">
    <form method="GET" action="{{ route('admin.tagihan.index') }}">
        <div class="flex gap-4 items-center">

            <select name="bulan" class="form-select w-40">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <input type="number"
                   name="tahun"
                   value="{{ $tahun }}"
                   class="form-input w-32"
                   min="2020"
                   max="2100">

            <button class="btn-secondary">
                Filter
            </button>

        </div>
    </form>
</div>

<!-- Table -->
<div class="table-container">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Nama Siswa</th>
                    <th class="px-6 py-3 text-left">Kelas</th>
                    <th class="px-6 py-3 text-left">Nominal</th>
                    <th class="px-6 py-3 text-left">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>

            @forelse($data as $index => $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium">
                        {{ $item->siswa->nama_lengkap ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->tanggal_jatuh_tempo }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status == 'belum_bayar')
                            <span class="badge-warning">Belum Bayar</span>
                        @elseif($item->status == 'lunas')
                            <span class="badge-success">Lunas</span>
                        @else
                            <span class="badge-info">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            @if($item->status != 'lunas')
                                <!-- Tombol Hapus hanya untuk status belum bayar -->
                                <button 
                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->siswa->nama_lengkap ?? 'Siswa' }}', '{{ $item->nama_bulan ?? '' }} {{ $item->tahun ?? '' }}')"
                                    class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs transition-all duration-200 flex items-center space-x-1">
                                    <i class="fas fa-trash"></i>
                                    <span>Hapus</span>
                                </button>
                            @else
                                <!-- Tombol disabled untuk status lunas -->
                                <button 
                                    disabled
                                    class="px-3 py-1.5 bg-gray-300 text-gray-500 rounded-lg text-xs cursor-not-allowed flex items-center space-x-1">
                                    <i class="fas fa-trash"></i>
                                    <span>Hapus</span>
                                </button>
                                <span class="text-xs text-gray-400 italic">(Tidak bisa dihapus)</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada tagihan untuk bulan ini.
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl max-w-md mx-4 w-full p-6">
        <!-- Icon Warning -->
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
        </div>
        
        <!-- Judul -->
        <h3 class="text-lg font-semibold text-center text-gray-800 mb-2">
            Hapus Tagihan
        </h3>
        
        <!-- Pesan -->
        <p class="text-sm text-gray-600 text-center mb-6" id="deleteMessage">
            Apakah Anda yakin ingin menghapus tagihan ini?
        </p>
        
        <!-- Tombol Aksi -->
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                Batal
            </button>
            
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center justify-center space-x-2">
                    <i class="fas fa-trash"></i>
                    <span>Ya, Hapus</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>

<script>
function confirmDelete(id, namaSiswa, periode) {
    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteMessage = document.getElementById('deleteMessage');
    
    // Set action form dengan route yang benar
    deleteForm.action = `{{ url('admin/tagihan') }}/${id}`;
    
    // Set pesan
    deleteMessage.innerHTML = `Apakah Anda yakin ingin menghapus tagihan <strong>${namaSiswa}</strong> untuk periode <strong>${periode}</strong>? <br><br> <span class="text-red-500">Tindakan ini tidak dapat dibatalkan!</span>`;
    
    // Tampilkan modal
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    
    // Set warna background berdasarkan type
    if (type === 'success') {
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2';
    } else if (type === 'error') {
        toast.className = 'fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2';
    } else if (type === 'warning') {
        toast.className = 'fixed bottom-4 right-4 bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2';
    }
    
    toastMessage.textContent = message;
    
    // Tampilkan toast
    setTimeout(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 100);
    
    // Sembunyikan toast setelah 3 detik
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

// Tutup modal jika klik di luar
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}

// Tampilkan notifikasi jika ada session success/error
@if(session('success'))
    showToast("{{ session('success') }}", 'success');
@endif

@if(session('error'))
    showToast("{{ session('error') }}", 'error');
@endif
</script>

<!-- Style tambahan -->
<style>
/* Styling untuk tombol hapus */
.btn-hapus {
    transition: all 0.2s ease;
}

.btn-hapus:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
}

/* Animasi modal */
#deleteModal {
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Styling untuk toast */
#toastNotification {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 640px) {
    .table-container {
        overflow-x: auto;
    }
    
    table {
        min-width: 800px;
    }
    
    #deleteModal .bg-white {
        margin: 1rem;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .flex.gap-3 {
        flex-direction: column;
    }
}
</style>

@endsection