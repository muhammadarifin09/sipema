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
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada tagihan untuk bulan ini.
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection