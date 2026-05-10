@extends('layouts.admin')

@section('title', 'Tracking Status Pembayaran Siswa')

@section('content')
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">📊 Tracking Status Pembayaran Siswa</h1>
            <p class="text-white/80 mt-1">Seluruh siswa dengan rincian tagihan per bulan (lunas / belum lunas)</p>
        </div>
        <a href="{{ route('admin.tagihan.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Tagihan Per Bulan
        </a>
    </div>
</div>

<!-- Statistics Cards (warna putih semua) -->
<div class="grid grid-cols-3 md:grid-cols-3 gap-6 mb-6 animate-slide-in delay-1">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
                <h3 class="text-3xl font-bold text-[#0b4f8c]">{{ count($data) }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Siswa dengan Tunggakan</p>
                <h3 class="text-3xl font-bold text-orange-600">{{ collect($data)->filter(fn($d) => $d['total_unpaid'] > 0)->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Tunggakan</p>
                <h3 class="text-3xl font-bold text-red-600">Rp {{ number_format(collect($data)->sum('total_unpaid'), 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-2xl text-red-600"></i>
            </div>
        </div>
    </div>
    

</div>

<!-- Tabel Data dengan kolom Nama, NIS, Status (Lunas/Belum), Detail -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Status Pembayaran Keseluruhan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    @php
                        $statusOverall = $item['total_unpaid'] > 0 ? 'Belum Lunas' : 'Lunas';
                        $statusBadgeClass = $item['total_unpaid'] > 0 ? 'badge-warning' : 'badge-success';
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $item['siswa']->nis ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item['siswa']->nama_lengkap ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item['siswa']->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $statusBadgeClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $statusOverall }}
                            </span>
                
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="showDetail({{ json_encode($item['months']) }}, '{{ $item['siswa']->nama_lengkap }}', '{{ $item['siswa']->nis ?? '-' }}')" 
                                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs transition">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-receipt text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg mb-2">Belum ada data tagihan</p>
                                <p class="text-gray-400 text-sm">Generate tagihan terlebih dahulu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail (menampilkan NIS, nama, dan rincian per bulan) -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-3xl mx-4 w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="modalTitle">Detail Tagihan</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="modalContent" class="max-h-96 overflow-y-auto">
            <!-- Dinamis dari JS -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeDetailModal()" class="btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>

<script>
function showDetail(months, siswaName, nis) {
    const modal = document.getElementById('detailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    modalTitle.innerHTML = `Detail Tagihan - ${siswaName} (NIS: ${nis})`;
    
    if (!months || months.length === 0) {
        modalContent.innerHTML = '<div class="text-center text-gray-500 py-8">Belum ada tagihan untuk siswa ini.</div>';
        modal.style.display = 'flex';
        return;
    }
    
    let html = `
        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
            <p><strong>Nama:</strong> ${siswaName}</p>
            <p><strong>NIS:</strong> ${nis}</p>
        </div>
        <table class="min-w-full border rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Bulan</th>
                    <th class="px-4 py-2 text-left">Tahun</th>
                    <th class="px-4 py-2 text-right">Nominal</th>
                    <th class="px-4 py-2 text-left">Jatuh Tempo</th>
                    <th class="px-4 py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    months.forEach(month => {
        let statusBadge = month.status == 'lunas' 
            ? '<span class="badge-success text-xs px-2 py-1 rounded-full">Lunas</span>'
            : '<span class="badge-warning text-xs px-2 py-1 rounded-full">Belum Bayar</span>';
        html += `<tr class="border-b">
                    <td class="px-4 py-2">${month.bulan_nama}</td>
                    <td class="px-4 py-2">${month.tahun}</td>
                    <td class="px-4 py-2 text-right">Rp ${new Intl.NumberFormat('id-ID').format(month.nominal)}</td>
                    <td class="px-4 py-2">${month.jatuh_tempo}</td>
                    <td class="px-4 py-2 text-center">${statusBadge}</td>
                </tr>`;
    });
    
    html += '</tbody></table>';
    modalContent.innerHTML = html;
    modal.style.display = 'flex';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) closeDetailModal();
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    if (type === 'success') {
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2';
    } else if (type === 'error') {
        toast.className = 'fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 translate-y-20 opacity-0 z-50 text-sm flex items-center space-x-2';
    }
    toastMessage.textContent = message;
    setTimeout(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 100);
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

@if(session('success'))
    showToast("{{ session('success') }}", 'success');
@endif
@if(session('error'))
    showToast("{{ session('error') }}", 'error');
@endif
</script>

<style>
.badge-success {
    background: #d1fae5;
    color: #065f46;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
}
.badge-warning {
    background: #fed7aa;
    color: #92400e;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
}
#detailModal {
    backdrop-filter: blur(4px);
}
</style>
@endsection