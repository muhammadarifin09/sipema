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
            <!-- <div class="flex items-center space-x-3">
                <a href="{{ route('wali.notifikasi.index') }}" class="relative">
                    <i class="fas fa-bell text-gray-500 text-xl"></i>
                    @if($jumlahNotif ?? 0 > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">{{ $jumlahNotif }}</span>
                    @endif
                </a>
       
            </div> -->
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="px-4 py-5 md:px-8 lg:px-12">
        <div class="max-w-7xl mx-auto">
            {{-- Welcome Card --}}
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

            {{-- Filter Siswa (jika lebih dari 1) --}}
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

            {{-- Statistik Kartu --}}
            
     

            {{-- Info Nominal Tambahan --}}
            <!-- @if(isset($statistik) && ($statistik['total_nominal_lunas'] > 0 || $statistik['total_nominal_belum_bayar'] > 0))
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex justify-between items-center">
                    <span class="text-green-700 text-sm"><i class="fas fa-check-circle mr-1"></i>Total Lunas</span>
                    <span class="font-semibold text-green-700">Rp {{ number_format($statistik['total_nominal_lunas'],0,',','.') }}</span>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 flex justify-between items-center">
                    <span class="text-red-700 text-sm"><i class="fas fa-exclamation-circle mr-1"></i>Total Belum</span>
                    <span class="font-semibold text-red-700">Rp {{ number_format($statistik['total_nominal_belum_bayar'],0,',','.') }}</span>
                </div>
            </div>
            @endif -->

            {{-- Filter & Pencarian --}}
            <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-filter mr-2"></i>
                    <select id="statusFilter" class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                        <option value="all">Semua</option>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-search mr-2"></i>
                    <input type="text" id="searchInput" placeholder="Cari bulan..." class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                </div>
            </div>

            {{-- Tabel Tagihan --}}
            <div class="bg-white rounded-2xl shadow-md border border-blue-600 overflow-hidden mb-6">
                {{-- Mobile View (Card) --}}
                <div class="block sm:hidden divide-y divide-gray-100" id="mobileTagihanList">
                    @forelse($tagihans as $index => $tagihan)
                    <div class="p-4 tagihan-item" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($tagihan->nama_bulan) }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $tagihan->nama_bulan }} {{ $tagihan->tahun }}</h3>
                                <p class="text-xs text-gray-500">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo }}</p>
                            </div>
                            @if($tagihan->status == 'lunas')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lunas</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Bayar</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Rp {{ number_format($tagihan->nominal,0,',','.') }}</span>
                            @if($tagihan->status == 'belum_bayar')
                            <button onclick="bayarTagihan({{ $tagihan->id }})" class="px-4 py-2 bg-[#305CDE] text-white rounded-lg text-xs hover:bg-[#0000FF]">
                                <i class="fas fa-credit-card mr-1"></i>Bayar
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-file-invoice text-3xl mb-2"></i>
                        <p>Belum ada tagihan SPP.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Desktop View (Table) --}}
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
                            <tr class="hover:bg-gray-50 tagihan-row" data-status="{{ $tagihan->status }}" data-bulan="{{ strtolower($tagihan->nama_bulan) }}">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $tagihan->nama_bulan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $tagihan->tahun }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($tagihan->nominal,0,',','.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $tagihan->tanggal_jatuh_tempo }}</td>
                                <td class="px-6 py-4">
                                    @if($tagihan->status == 'lunas')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lunas</span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($tagihan->status == 'belum_bayar')
                                    <button onclick="bayarTagihan({{ $tagihan->id }})" class="px-4 py-2 bg-[#0B2A4A] text-white rounded-lg text-xs hover:bg-[#1E3A5F]">
                                        Bayar
                                    </button>
                                    @else
                                    <button disabled class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs cursor-not-allowed">
                                        Selesai
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-500">
                                    <i class="fas fa-file-invoice text-3xl mb-2"></i>
                                    <p>Belum ada tagihan SPP.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Tabel --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-sm text-gray-600 flex justify-between items-center">
                    <span>Menampilkan {{ $tagihans->count() }} data</span>
                    {{-- Pagination bisa ditambahkan jika diperlukan --}}
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="text-center md:text-left">
                <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation Mobile --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm border-t border-gray-100 px-5 py-2 shadow-lg md:hidden z-20">
        <div class="flex justify-around items-center">
            <a href="{{ route('wali.dashboard') }}" class="flex flex-col items-center">
                <i class="fas fa-home text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Beranda</span>
            </a>
            <a href="{{ route('wali.tagihan.index') }}" class="flex flex-col items-center">
                <i class="fas fa-file-invoice text-[#0B2A4A] text-xl"></i>
                <span class="text-[10px] text-[#0B2A4A] mt-1">Tagihan</span>
            </a>
            <a href="{{ route('wali.riwayat.index') }}" class="flex flex-col items-center">
                <i class="fas fa-history text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Riwayat</span>
            </a>
            <a href="{{ route('wali.profile') }}" class="flex flex-col items-center">
                <i class="fas fa-user text-gray-400 text-xl"></i>
                <span class="text-[10px] text-gray-400 mt-1">Profil</span>
            </a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>

{{-- Midtrans Snap --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    // Filter & Search
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

    function bayarTagihan(tagihanId) {
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
                alert('Gagal memproses pembayaran');
            }
        })
        .catch(err => alert('Terjadi kesalahan'));
    }
</script>

<style>
    /* Animasi slide-in sudah tidak diperlukan, tapi bisa dipertahankan jika mau */
    .bg-gradient-to-br {
        background-size: 100% 100%;
    }
    /* Styling tambahan untuk konsistensi */
</style>
@endsection