@extends('layouts.app')

@section('title', 'Tagihan SPP - SMA PGRI Pelaihari')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm px-5 pt-8 pb-3 md:pt-4 md:pb-4 shadow-sm sticky top-0 z-20">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('wali.dashboard') }}" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <p class="text-gray-600 text-sm">Tagihan SPP</p>
                    <h2 class="text-gray-800 text-lg font-bold">SMA PGRI Pelaihari</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-5 md:px-8 lg:px-12">
        <div class="max-w-7xl mx-auto">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-300 rounded-2xl p-5 shadow-xl mb-6 border border-white/30 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/5 rounded-full"></div>
                <div class="relative z-10 md:flex md:items-center md:justify-between">
                    <div>
                        <p class="text-white/80 text-xs uppercase tracking-wider flex items-center">
                            <i class="fas fa-file-invoice mr-2"></i>Daftar Tagihan SPP
                        </p>
                        @if(isset($siswaList) && $siswaList->count() > 0)
                            @php
                                $selectedSiswa = $siswaList->firstWhere('id', $selectedSiswaId);
                            @endphp
                            <h2 class="text-white text-xl md:text-2xl font-bold mt-1">{{ $selectedSiswa->nama_lengkap ?? 'Pilih Siswa' }}</h2>
                            <p class="text-white/70 text-sm mt-1 flex items-center">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Kelas {{ $selectedSiswa->kelas->nama_kelas ?? '-' }} • NIS: {{ $selectedSiswa->nis ?? '-' }}
                            </p>
                        @else
                            <h2 class="text-white text-xl md:text-2xl font-bold">Belum Ada Siswa</h2>
                            <p class="text-white/70 text-sm mt-1">Hubungi admin untuk menghubungkan siswa</p>
                        @endif
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 inline-flex items-center">
                            <i class="fas fa-file-invoice-dollar text-white mr-2"></i>
                            <span class="text-white text-sm">Tagihan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Siswa -->
            @if(isset($siswaList) && $siswaList->count() > 1)
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-600 text-sm"><i class="fas fa-users mr-1"></i>Pilih Siswa:</span>
                    @foreach($siswaList as $siswa)
                    <a href="{{ route('wali.tagihan.index', ['siswa_id' => $siswa->id]) }}" 
                       class="px-4 py-2 rounded-lg text-sm transition {{ $selectedSiswaId == $siswa->id ? 'bg-[#0B2A4A] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $siswa->nama_lengkap }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Filter & Pencarian -->
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-filter mr-2"></i>
                    <select id="statusFilter" class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                        <option value="all">Semua</option>
                        <option value="belum_bayar">Belum Bayar</option>
                    </select>
                </div>
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-search mr-2"></i>
                    <input type="text" id="searchInput" placeholder="Cari bulan..." class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                </div>
            </div>

            <!-- Tabel Tagihan (Mobile Card) -->
            <div class="bg-white rounded-2xl shadow-md border border-blue-600 overflow-hidden mb-6">
                <div class="p-4 space-y-4 sm:hidden" id="mobileTagihanList">
                    @forelse($tagihans as $index => $tagihan)
                    @php
                        $bulanNama = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $displayBulan = is_numeric($tagihan->bulan) 
                            ? ($bulanNama[$tagihan->bulan] ?? $tagihan->bulan)
                            : $tagihan->bulan;
                    @endphp
                    <div class="bg-gray-50 rounded-xl p-4 shadow-sm tagihan-item" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($displayBulan) }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">{{ $displayBulan }} {{ $tagihan->tahun }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo }}</p>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Bayar</span>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <span class="font-bold text-gray-800 text-xl">Rp {{ number_format($tagihan->nominal,0,',','.') }}</span>
                            <button onclick="konfirmasiBayar({{ $tagihan->id }}, {{ $tagihan->has_previous_unpaid ? 'true' : 'false' }})" 
                                    class="px-5 py-2 bg-[#305CDE] text-white rounded-lg text-sm hover:bg-[#0000FF] transition">
                                <i class="fas fa-credit-card mr-1"></i>Bayar
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-check-circle text-5xl mb-3 text-green-500"></i>
                        <p class="text-lg">✅ Semua tagihan sudah lunas!</p>
                        <p class="text-sm mt-1">Tidak ada tagihan yang perlu dibayar.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Desktop View (Table) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full" id="tagihanTable">
                        <thead class="bg-gradient-to-r from-[#0B2A4A] to-[#1E3A5F] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Bulan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tahun</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tagihans as $index => $tagihan)
                            @php
                                $bulanNama = [
                                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                                    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                                    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                                ];
                                $displayBulan = is_numeric($tagihan->bulan) 
                                    ? ($bulanNama[$tagihan->bulan] ?? $tagihan->bulan)
                                    : $tagihan->bulan;
                            @endphp
                            <tr class="hover:bg-gray-50 tagihan-row" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($displayBulan) }}">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $displayBulan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $tagihan->tahun }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($tagihan->nominal,0,',','.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $tagihan->tanggal_jatuh_tempo }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Bayar</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="konfirmasiBayar({{ $tagihan->id }}, {{ $tagihan->has_previous_unpaid ? 'true' : 'false' }})" 
                                            class="px-4 py-2 bg-[#0B2A4A] text-white rounded-lg text-xs hover:bg-[#1E3A5F] transition">
                                        Bayar
                                    </button>
                                </td>
                            </table>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-500">
                                    <i class="fas fa-check-circle text-5xl mb-3 text-green-500"></i>
                                    <p class="text-lg">✅ Semua tagihan sudah lunas!</p>
                                </table>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-sm text-gray-600">
                    Menampilkan {{ $tagihans->count() }} tagihan belum lunas
                </div>
            </div>

            <div class="text-center md:text-left">
                <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Mobile -->
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm border-t border-gray-100 px-5 py-2 shadow-lg md:hidden z-20">
        <div class="flex justify-around items-center">
            <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center"><i class="fas fa-home text-gray-400 text-xl"></i><span class="text-[10px] text-gray-400 mt-1">Beranda</span></a>
            <a href="{{ route('wali.tagihan.index') }}" class="flex flex-col items-center"><i class="fas fa-file-invoice text-[#0B2A4A] text-xl"></i><span class="text-[10px] text-[#0B2A4A] mt-1">Tagihan</span></a>
            <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center"><i class="fas fa-history text-gray-400 text-xl"></i><span class="text-[10px] text-gray-400 mt-1">Riwayat</span></a>
            <a href="{{ route('wali.profile') }}" class="flex flex-col items-center"><i class="fas fa-user text-gray-400 text-xl"></i><span class="text-[10px] text-gray-400 mt-1">Profil</span></a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>

<!-- ========== MODAL KONFIRMASI ========== -->
<div id="confirmModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="modalContent">
        <div class="p-6">
            <!-- Icon & Header -->
            <div class="flex justify-center mb-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-blue-600 text-3xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Perhatian!</h3>
            <p class="text-gray-600 text-center mb-6" id="modalMessage">
                Anda akan membayar tagihan yang tidak sesuai urutan bulan.<br>
                Sistem akan secara otomatis MELUNASI semua tagihan sebelumnya yang belum dibayar.<br>
                Apakah Anda ingin melanjutkan pembayaran?
            </p>
            <!-- Tombol -->
            <div class="flex gap-3">
                <button id="modalCancelBtn" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-medium">
                    Batal
                </button>
                <button id="modalConfirmBtn" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                    Lanjut Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // Element modal
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('modalContent');
    const modalMessage = document.getElementById('modalMessage');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    // Variabel untuk menyimpan callback jika lanjut
    let pendingTagihanId = null;
    let pendingProses = null;

    // Fungsi menampilkan modal dengan pesan kustom
    function showConfirmModal(message, onConfirm) {
        modalMessage.innerHTML = message;
        pendingProses = onConfirm;
        modal.classList.remove('hidden');
        // Animasi muncul
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            pendingProses = null;
        }, 200);
    }

    // Event listener tombol modal
    modalConfirmBtn.onclick = () => {
        if (pendingProses) {
            pendingProses();
        }
        hideModal();
    };
    modalCancelBtn.onclick = () => {
        hideModal();
    };
    // Klik di luar modal juga menutup
    modal.onclick = (e) => {
        if (e.target === modal) hideModal();
    };

    // Fungsi konfirmasi dengan modal kustom
    function konfirmasiBayar(tagihanId, hasPreviousUnpaid) {
        if (hasPreviousUnpaid) {
            const message = `⚠️ <span class="font-semibold">Perhatian!</span><br><br>
                            Anda akan membayar tagihan yang <span class="font-semibold">tidak sesuai urutan bulan</span>.<br>
                            Sistem akan secara otomatis <span class="text-blue-600 font-semibold">MELUNASI semua tagihan sebelumnya</span> yang belum dibayar.<br><br>
                            Apakah Anda ingin melanjutkan pembayaran?`;
            showConfirmModal(message, () => {
                prosesBayar(tagihanId);
            });
        } else {
            prosesBayar(tagihanId);
        }
    }

    function prosesBayar(tagihanId) {
        fetch(`/wali/tagihan/${tagihanId}/bayar`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, { 
                    onSuccess: () => location.reload(), 
                    onPending: () => location.reload(), 
                    onError: () => alert('Pembayaran gagal'), 
                    onClose: () => console.log('Popup ditutup') 
                });
            } else { 
                alert('Gagal memproses pembayaran: ' + (data.error || 'unknown error')); 
            }
        })
        .catch(err => { 
            console.error(err); 
            alert('Terjadi kesalahan, silakan coba lagi'); 
        });
    }

    // Filter & Search (sama seperti sebelumnya)
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    function filterTable() {
        const status = statusFilter.value;
        const term = searchInput.value.toLowerCase();
        document.querySelectorAll('.tagihan-item, .tagihan-row').forEach(el => {
            const elStatus = el.dataset.status;
            const elBulan = el.dataset.bulan;
            const matchStatus = status === 'all' || elStatus === status;
            const matchSearch = elBulan.includes(term);
            el.style.display = (matchStatus && matchSearch) ? '' : 'none';
        });
    }
    statusFilter?.addEventListener('change', filterTable);
    searchInput?.addEventListener('keyup', filterTable);
</script>
<style>
    /* Animasi modal */
    #modalContent {
        transition: transform 0.2s ease-out, opacity 0.2s ease-out;
    }
    .scale-95 { transform: scale(0.95); }
    .scale-100 { transform: scale(1); }
    .opacity-0 { opacity: 0; }
    .opacity-100 { opacity: 1; }
    /* Perbaikan teks modal agar support HTML */
    #modalMessage {
        line-height: 1.5;
    }
</style>
@endsection