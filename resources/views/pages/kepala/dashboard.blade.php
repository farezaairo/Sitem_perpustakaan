@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-0">Dashboard Kepala Perpustakaan</h2>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6 mb-8">

        <div class="p-5 bg-gradient-to-r from-blue-400 to-blue-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Total Buku</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $total_books }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-green-400 to-green-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Buku Tersedia</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $available_books }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-purple-400 to-purple-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Peminjam Aktif</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $total_borrowers }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Sedang Dipinjam</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $borrowed_books }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-orange-400 to-orange-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Total Siswa</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $total_students }}</div>
        </div>

    </div>

    {{-- STAT CARD TAMBAHAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">

        <div class="p-5 bg-gradient-to-r from-red-400 to-red-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Total Denda</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">Rp {{ number_format($total_fines) }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-blue-400 to-blue-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Transaksi Bulan Ini</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $thisMonth }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Keterlambatan</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $late }}</div>
        </div>

        <div class="p-5 bg-gradient-to-r from-green-400 to-green-600 text-white shadow-xl rounded-2xl">
            <h4 class="text-sm font-medium">Total Buku Dipinjam</h4>
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $totalBukuDipinjam }}</div>
        </div>

    </div>

    {{-- TRANSAKSI TERBARU --}}
    @if($recent_transactions->count() > 0)
    <div class="bg-white shadow-lg rounded-2xl p-6 overflow-x-auto">
        <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-800">Transaksi Terbaru</h3>
        <table class="min-w-full table-auto border-collapse text-sm md:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="p-3 text-left">Siswa</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent_transactions as $t)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $t->siswa->nama }}</td>
                        <td class="p-3">{{ $t->buku->judul }}</td>
                        <td class="p-3">{{ date('d M Y', strtotime($t->tanggal_pinjam)) }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full font-semibold
                                {{ $t->status === 'dipinjam' ? 'bg-blue-100 text-blue-600' : 
                                   ($t->status === 'dikembalikan' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="p-3">Rp {{ number_format($t->denda) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
