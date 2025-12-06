@extends('layouts.main')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    {{-- HEADER --}}
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Siswa</h2>

    {{-- KARTU RIWAYAT --}}
    <div class="bg-white rounded-2xl shadow-lg p-6">

        <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Peminjaman Saya</h3>

        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse text-gray-700">
                <thead>
                    <tr class="bg-gray-200 text-left text-sm uppercase text-gray-600">
                        <th class="p-3">Buku</th>
                        <th class="p-3">Tgl Pinjam</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>

                <tbody class="text-sm">
                    @foreach ($riwayat as $r)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3">{{ $r->buku->judul }}</td>
                            <td class="p-3">{{ date('d M Y', strtotime($r->tanggal_pinjam)) }}</td>
                            <td class="p-3">
                                @if($r->status === 'dipinjam')
                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">Dipinjam</span>
                                @elseif($r->status === 'dikembalikan')
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">Dikembalikan</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">Terlambat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
