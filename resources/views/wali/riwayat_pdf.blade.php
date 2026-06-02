<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kartu SPP - {{ $siswa->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', 'Segoe UI', Arial, sans-serif;
            background: white;
            font-size: 12px;
            padding: 20px;
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #0B2A4A;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .kop h1 {
            font-size: 18px;
            font-weight: bold;
            color: #0B2A4A;
        }
        .kop h2 {
            font-size: 14px;
            font-weight: bold;
        }
        .kop p {
            font-size: 10px;
        }
        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0 10px;
            background: #f0f4f8;
            padding: 5px;
        }
        .info-siswa {
            border: 1px solid #000;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-siswa table {
            width: 100%;
        }
        .info-siswa td {
            padding: 2px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 10px;
        }
        th {
            background: #e5e7eb;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }
        .ttd {
            text-align: center;
            width: 45%;
        }
        .garis-ttd {
            margin-top: 30px;
            border-top: 1px solid black;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .catatan {
            font-size: 8px;
            margin-top: 15px;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }
        .empty {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="kop">
    <h1>YAYASAN PENDIDIKAN PGRI PELAIHARI</h1>
    <h2>SMA PGRI PELAIHARI</h2>
    <p>Jl. Pendidikan No. 123, Pelaihari, Kalimantan Selatan</p>
    <p>Telp. (0512) 12345 | Email: smapgri@pelaihari.sch.id</p>
</div>

<div class="judul">
    KARTU SUMBANGAN PEMBINAAN PENDIDIKAN (SPP)<br>
    TAHUN PELAJARAN {{ $tahun_ajaran }}
</div>

<div class="info-siswa">
    <table>
        <tr>
            <td width="120">Nama Siswa</td>
            <td width="200">{{ $siswa->nama_lengkap }}</td>
            <td width="100">NIS</td>
            <td>{{ $siswa->nis ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
            <td>Alamat</td>
            <td>{{ $siswa->alamat ?? '-' }}</td>
        </tr>
    </table>
</div>

@if($riwayat->count() > 0)
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Tanggal Bayar</th>
            <th>Besar SPP (Rp)</th>
            <th>Metode</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $index => $payment)
            @php
                $bulanNama = \Carbon\Carbon::create()->month($payment->bulan)->translatedFormat('F');
                $tanggalBayar = \Carbon\Carbon::parse($payment->tanggal_bayar)->translatedFormat('d F Y');
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $bulanNama }}</td>
                <td>{{ $payment->tahun }}</td>
                <td>{{ $tanggalBayar }}</td>
                <td class="text-right">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
                <td>{{ $payment->metode_pembayaran ?? '-' }}</td>
                <td>LUNAS</td>
            </tr>
            @endforeach
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL PEMBAYARAN</td>
                <td colspan="4" class="text-left">Rp {{ number_format($total_nominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@else
    <div class="empty">
        Belum ada pembayaran SPP yang tercatat.<br>
        Silakan lakukan pembayaran sesuai tagihan.
    </div>
@endif

<div class="footer">
    <div class="ttd">
        <p>Mengetahui,<br>Kepala Sekolah</p>
        <div class="garis-ttd"></div>
        <p><strong>Dr. Ir. Muhammad Arifin, S.Kom., M.Kom.</strong><br>NIP. 2005001012000121001</p>
    </div>
    <div class="ttd">
        <p>Pelaihari, {{ now()->translatedFormat('d F Y') }}<br>Bendahara</p>
        <div class="garis-ttd"></div>
        <p><strong>Admin Keuangan</strong></p>
    </div>
</div>

<div class="catatan">
    <em>Kartu ini dicetak secara elektronik. Berlaku sebagai bukti riwayat pembayaran SPP.</em><br>
    Dicetak pada: {{ $tanggal_cetak }}
</div>
</body>
</html>