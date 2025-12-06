@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-0">Dashboard Admin</h2>
        <div class="flex items-center gap-3">
            <span class="font-semibold text-gray-700 text-sm md:text-base">
                {{ auth()->user()->nama }}
            </span>
        </div>
    </div>

    {{-- STATISTIK UTAMA --}}
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
            <div class="text-3xl md:text-4xl font-extrabold mt-2">{{ $total_borrowers }}</div>
        </div>

    </div>

  {{-- FILTER PEMINJAMAN --}}
<div class="bg-white shadow-lg rounded-2xl p-6 mb-8">
    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">Filter Peminjaman</h3>

    <select id="filterType" class="px-3 py-2 border rounded-lg mb-6">
        <option value="">-- Pilih Filter --</option>
        <option value="hari">Peminjaman Hari Ini</option>
        <option value="bulan">Peminjaman Bulan Ini</option>
        <option value="tahun">Peminjaman Tahun Ini</option>
    </select>

    {{-- GRID STATISTIK --}}
    <div id="filterStats" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="p-4 bg-blue-100 rounded-xl text-center">
            <p class="text-gray-600 font-medium">Total Peminjaman</p>
            <p class="text-2xl font-bold text-blue-700" id="totalLoans">0</p>
        </div>
        <div class="p-4 bg-blue-200 rounded-xl text-center">
            <p class="text-gray-600 font-medium">Dipinjam</p>
            <p class="text-2xl font-bold text-blue-800" id="totalDipinjam">0</p>
        </div>
        <div class="p-4 bg-green-100 rounded-xl text-center">
            <p class="text-gray-600 font-medium">Dikembalikan</p>
            <p class="text-2xl font-bold text-green-700" id="totalDikembalikan">0</p>
        </div>
        <div class="p-4 bg-red-100 rounded-xl text-center">
            <p class="text-gray-600 font-medium">Terlambat</p>
            <p class="text-2xl font-bold text-red-700" id="totalTerlambat">0</p>
        </div>
        <div class="p-4 bg-gray-100 rounded-xl text-center">
            <p class="text-gray-600 font-medium">Hilang</p>
            <p class="text-2xl font-bold text-gray-700" id="totalHilang">0</p>
        </div>
    </div>

    {{-- TABEL DETAIL --}}
    <div id="filterResult" class="mt-4 hidden overflow-x-auto">
        <table class="min-w-full table-auto border-collapse text-sm md:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="p-3 text-left">Siswa</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody id="filterTableBody"></tbody>
        </table>
    </div>
</div>


    {{-- TRANSAKSI TERBARU --}}
    @if(isset($recent_transactions) && $recent_transactions->count() > 0)
    <div class="bg-white shadow-lg rounded-2xl p-6 overflow-x-auto">
        <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-800">Transaksi Terbaru</h3>

        <table class="min-w-full table-auto border-collapse text-sm md:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="p-3 text-left">Siswa</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($recent_transactions as $t)
                <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $t->siswa->nama }}</td>
                        <td class="p-3">{{ $t->buku->judul }}</td>
                        <td class="p-3">{{ date('d M Y', strtotime($t->tanggal_pinjam)) }}</td>

                        <td class="p-3">
                            @if ($t->status === 'dipinjam')
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full font-semibold">Dipinjam</span>

                            @elseif ($t->status === 'dikembalikan')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full font-semibold">Dikembalikan</span>

                            @elseif ($t->status === 'terlambat')
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full font-semibold">Terlambat</span>

                            @elseif ($t->status === 'hilang')
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full font-semibold">Hilang</span>

                            @else
                                <span class="bg-yellow-200 text-yellow-700 px-3 py-1 rounded-full font-semibold">
                                    Status Tidak Dikenal
                                </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    @endif

</div>

                        <script>
                        document.getElementById('filterType').addEventListener('change', function() {
                        
                            let type = this.value;
                        
                            if (!type) {
                                document.getElementById('filterResult').classList.add('hidden');
                                document.getElementById('filterStats').classList.add('hidden');
                                return;
                            }
                        
                            fetch(`/dashboard-admin/filter-peminjaman?type=${type}`)
                                .then(response => response.json())
                                .then(data => {
                                    let tbody = document.getElementById('filterTableBody');
                                    tbody.innerHTML = "";
                        
                                    // Reset stats
                                    let total = 0, dipinjam = 0, dikembalikan = 0, terlambat = 0, hilang = 0;
                        
                                    if (data.length === 0) {
                                        tbody.innerHTML = `
                                            <tr>
                                                <td colspan="4" class="text-center p-3 text-gray-500">
                                                    Tidak ada data
                                                </td>
                                            </tr>`;
                                    } 
                                    else {
                                        data.forEach(t => {
                                            total++;
                                            if(t.status === 'dipinjam') dipinjam++;
                                            else if(t.status === 'dikembalikan') dikembalikan++;
                                            else if(t.status === 'terlambat') terlambat++;
                                            else if(t.status === 'hilang') hilang++;
                        
                                            tbody.innerHTML += `
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="p-3">${t.siswa.nama}</td>
                                                    <td class="p-3">${t.buku.judul}</td>
                                                    <td class="p-3">${new Date(t.tanggal_pinjam).toLocaleDateString('id-ID')}</td>
                                                    <td class="p-3">
                                                        <span class="px-3 py-1 rounded-full text-white ${
                                                            t.status === 'dipinjam' ? 'bg-blue-600' :
                                                            t.status === 'dikembalikan' ? 'bg-green-600' :
                                                            t.status === 'hilang' ? 'bg-gray-700' :
                                                            'bg-red-600'
                                                        }">
                                                            ${t.status}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `;
                                        });
                                    }
                        
                                    // Tampilkan stats
                                    document.getElementById('totalLoans').textContent = total;
                                    document.getElementById('totalDipinjam').textContent = dipinjam;
                                    document.getElementById('totalDikembalikan').textContent = dikembalikan;
                                    document.getElementById('totalTerlambat').textContent = terlambat;
                                    document.getElementById('totalHilang').textContent = hilang;
                        
                                    document.getElementById('filterResult').classList.remove('hidden');
                                    document.getElementById('filterStats').classList.remove('hidden');
                                });
                        });
                        </script>
@endsection

