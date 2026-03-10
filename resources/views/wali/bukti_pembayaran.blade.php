<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family: Arial, sans-serif;
    margin:40px;
}

.header{
    text-align:center;
    margin-bottom:25px;
}

.header h2{
    margin:0;
    font-size:18px;
    letter-spacing:2px;
}

.header h1{
    margin-top:8px;
    font-size:22px;
}

table{
    width:100%;
    border-collapse: collapse;
    margin-top:20px;
}

table th, table td{
    border:1px solid #000;
    padding:8px;
    font-size:12px;
}

table th{
    background:#f2f2f2;
}

.total{
    margin-top:20px;
    font-size:14px;
    text-align:right;
}

.footer{
    margin-top:25px;
    font-size:12px;
}

</style>

</head>

<body>

@php
$namaBulan = [
1 => 'Januari',
2 => 'Februari',
3 => 'Maret',
4 => 'April',
5 => 'Mei',
6 => 'Juni',
7 => 'Juli',
8 => 'Agustus',
9 => 'September',
10 => 'Oktober',
11 => 'November',
12 => 'Desember'
];
@endphp

@php
\Carbon\Carbon::setLocale('id');
@endphp

<div class="header">
<h1>BUKTI PEMBAYARAN SPP </h1>
<h2>SMA PGRI PELAIHARI</h2>
</div>

<table>

<thead>
<tr>
<th>No</th>
<th>Nama Siswa</th>
<th>Bulan</th>
<th>Tahun</th>
<th>Nominal</th>
<th>Metode</th>
<th>Tanggal Bayar</th>
</tr>
</thead>

<tbody>

@foreach($pembayarans as $key => $p)

<tr>
<td>{{ $key+1 }}</td>

<td>{{ $p->tagihan->siswa->nama_lengkap }}</td>

<td>
{{ $namaBulan[$p->tagihan->bulan] ?? $p->tagihan->bulan }}
</td>

<td>{{ $p->tagihan->tahun }}</td>

<td>
Rp {{ number_format($p->jumlah_bayar,0,',','.') }}
</td>

<td>
@if($p->metode_bayar == 'midtrans')
Bayar lewat Web
@else
{{ $p->metode_bayar }}
@endif
</td>

<td>
{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}
</td>

</tr>

@endforeach

</tbody>

</table>

<p class="total">
Total Pembayaran : <b>Rp {{ number_format($total,0,',','.') }}</b>
</p>

<div class="footer">
Dicetak pada : {{ date('d F Y H:i') }}
</div>

</body>
</html>