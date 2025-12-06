@extends('layouts.main')
@section('content')
<div id="reportsPage" class="content-page p-6">

    <h3 class="text-2xl font-bold mb-6 text-gray-800">Laporan Perpustakaan</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="stat-card bg-gradient-to-r from-blue-400 to-blue-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium">Total Peminjaman</h4>
            </div>
            <div id="reportTotalBorrow" class="text-4xl font-extrabold mt-3">{{ $totalPeminjaman }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-green-400 to-green-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium">Dikembalikan</h4>
            </div>
            <div id="reportTotalReturn" class="text-4xl font-extrabold mt-3">{{ $dikembalikan }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium">Terlambat</h4>
            </div>
            <div id="reportLateReturn" class="text-4xl font-extrabold mt-3">{{ $terlambat }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-red-400 to-red-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium">Buku Hilang</h4>
            </div>
            <div id="reportLostBooks" class="text-4xl font-extrabold mt-3">{{ $bukuHilang }}</div>
        </div>

    </div>

    <div class="card bg-white p-6 rounded-2xl shadow-lg mb-6">
        <h4 class="font-semibold text-gray-700 mb-3">Detail Laporan</h4>
        <div id="reportDetails" class="text-sm text-gray-600">
            <table class="w-full border-collapse table-auto text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Siswa</th>
                        <th class="p-2 border">Buku</th>
                        <th class="p-2 border">Tgl Pinjam</th>
                        <th class="p-2 border">Tgl Kembali</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiDetails as $t)
                        <tr>
                            <td class="p-2 border">{{ $t->siswa->nama ?? '-' }}</td>
                            <td class="p-2 border">{{ $t->buku->judul ?? '-' }}</td>
                            <td class="p-2 border">{{ $t->tanggal_pinjam }}</td>
                            <td class="p-2 border">{{ $t->tanggal_kembali ?? '-' }}</td>
                            <td class="p-2 border">{{ ucfirst($t->status) }}</td>
                            <td class="p-2 border">{{ number_format($t->denda,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- <button id="downloadReportBtn" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl shadow-lg hover:scale-105 transform transition font-semibold">
         Download Laporan
    </button> -->

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const downloadBtn = document.getElementById('downloadReportBtn');

    downloadBtn.addEventListener('click', function() {
        const type = prompt("Ketik 'pdf' atau 'csv' untuk download:");
        if(type === 'pdf') {
            window.open("{{ route('laporan.admin.pdf') }}", "_blank");
        } else if(type === 'csv') {
            window.open("{{ route('laporan.admin.csv') }}", "_blank");
        } else {
            alert("Tipe file tidak valid!");
        }
    });
});
</script>
@endpush
