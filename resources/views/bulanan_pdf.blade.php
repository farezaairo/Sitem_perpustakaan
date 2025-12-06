<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
   <h2>Laporan Bulanan Perpustakaan - {{ $namaBulanTahun }}</h2>
    <table>
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ $t->siswa->nama ?? '-' }}</td>
                <td>{{ $t->buku->judul ?? '-' }}</td>
                <td>{{ $t->tanggal_pinjam }}</td>
                <td>{{ $t->tanggal_kembali ?? '-' }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>{{ number_format($t->denda,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
