<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Pembayaran SPP</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0b4f8c;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #0b4f8c;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #555;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 8px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #0b4f8c;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            background: #10b981;
            color: white;
            border-radius: 12px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN REKAP PEMBAYARAN SPP</h1>
        <p>SMA PGRI Pelaihari</p>
        <p>Periode: 
            @if($filter['tanggal_awal'] && $filter['tanggal_akhir'])
                {{ \Carbon\Carbon::parse($filter['tanggal_awal'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filter['tanggal_akhir'])->format('d/m/Y') }}
            @elseif($filter['bulan'] && $filter['tahun'])
                {{ \Carbon\Carbon::create()->month($filter['bulan'])->translatedFormat('F') }} {{ $filter['tahun'] }}
            @else
                Semua Data
            @endif
        </p>
    </div>

    <div class="filter-info">
        <strong>Ringkasan:</strong> Total Siswa: {{ $totalSiswa }} | Total Transaksi: {{ $totalJumlahBayar }} | Total Nominal: Rp {{ number_format($totalNominal, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th class="text-center">Jumlah Bayar</th>
                <th class="text-right">Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nis ?? '-' }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td>{{ $item->nama_kelas ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah_bayar }}</td>
                <td class="text-right">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>