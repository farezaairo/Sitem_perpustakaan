@extends('layouts.main')

@section('content')
<div id="myborrowPage" class="content-page">

    <div class="p-6">
        <div class="content-header mb-4">
            <h3 class="text-xl font-semibold">Riwayat Peminjaman</h3>
            <p class="text-sm text-gray-500">Daftar buku yang pernah anda pinjam.</p>
        </div>

        <div class="card bg-white rounded-xl p-4 shadow">
            <div class="overflow-x-auto">
                <table class="w-full table-auto border border-gray-200" id="myBorrowTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Buku</th>
                            <th class="p-2 border">Tgl Pinjam</th>
                            <th class="p-2 border">Tgl Kembali</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Denda</th>
                            <th class="p-2 border">QR Code</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($riwayat as $r)
                        <tr class="text-center">
                            <td class="p-2 border">{{ $r->buku->judul ?? '-' }}</td>
                            <td class="p-2 border">{{ $r->tanggal_pinjam }}</td>
                            <td class="p-2 border">{{ $r->tanggal_kembali ?? '-' }}</td>
                            <td class="p-2 border capitalize">
                                <span class="px-2 py-1 rounded-full text-white text-xs
                                    {{ $r->status === 'dikembalikan' ? 'bg-green-500' : ($r->status === 'terlambat' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="p-2 border text-right">{{ $r->denda ?? 0 }}</td>
                            <td class="p-2 border">
                                {{-- QR Code Inline --}}
                                <div class="mb-2">
                                    {!! QrCode::size(80)->generate("Transaksi ID: {$r->id_transaksi}\nBuku: {$r->buku->judul}\nTanggal Pinjam: {$r->tanggal_pinjam}") !!}
                                </div>
                                {{-- Tombol Download --}}
                                <a href="{{ route('riwayat.qr', $r->id_transaksi) }}" 
                                   class="inline-block px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-xs">
                                   Download QR
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-3">Belum ada riwayat peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
