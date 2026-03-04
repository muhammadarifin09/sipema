@extends('layouts.app')

@section('title', 'Tagihan SPP')

@section('content')

<!-- Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white drop-shadow-lg">
        Tagihan SPP Anak Saya
    </h1>
    <p class="text-white/80 mt-1">
        Berikut daftar tagihan SPP yang harus dibayarkan.
    </p>
</div>

<!-- Table -->
<div class="table-container bg-white rounded-2xl p-6 shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Bulan</th>
                    <th class="px-6 py-3 text-left">Tahun</th>
                    <th class="px-6 py-3 text-left">Nominal</th>
                    <th class="px-6 py-3 text-left">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tagihans as $index => $tagihan)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>

                    <td class="px-6 py-4">
    {{ $tagihan->nama_bulan }}
</td>

                    <td class="px-6 py-4">
                        {{ $tagihan->tahun }}
                    </td>

                    <td class="px-6 py-4 font-medium">
                        Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        Tanggal {{ $tagihan->tanggal_jatuh_tempo }}
                    </td>

                    <td class="px-6 py-4">
                        @if($tagihan->status == 'belum_bayar')
                            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-lg">
                                Belum Bayar
                            </span>
                        @elseif($tagihan->status == 'lunas')
                            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-lg">
                                Lunas
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg">
                                Menunggu
                            </span>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        Belum ada tagihan SPP.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection