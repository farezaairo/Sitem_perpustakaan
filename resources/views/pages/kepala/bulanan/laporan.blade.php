@extends('layouts.main')
@section('content')
<div id="reportsPage" class="content-page p-6">

    <h3 class="text-2xl font-bold mb-6 text-gray-800">Laporan Bulanan Perpustakaan</h3>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="stat-card bg-gradient-to-r from-blue-400 to-blue-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <h4 class="text-sm font-medium">Total Peminjaman</h4>
            <div id="reportTotalBorrow" class="text-4xl font-extrabold mt-3">{{ $totalPeminjaman }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-green-400 to-green-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <h4 class="text-sm font-medium">Dikembalikan</h4>
            <div id="reportTotalReturn" class="text-4xl font-extrabold mt-3">{{ $dikembalikan }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <h4 class="text-sm font-medium">Terlambat</h4>
            <div id="reportLateReturn" class="text-4xl font-extrabold mt-3">{{ $terlambat }}</div>
        </div>

        <div class="stat-card bg-gradient-to-r from-red-400 to-red-600 text-white p-5 rounded-2xl shadow-lg hover:scale-105 transform transition">
            <h4 class="text-sm font-medium">Buku Hilang</h4>
            <div id="reportLostBooks" class="text-4xl font-extrabold mt-3">{{ $bukuHilang }}</div>
        </div>

    </div>

    {{-- Search, Filter, Download --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center mb-4">
        <input id="searchInput" type="text" placeholder="Cari siswa atau buku..." 
            class="border p-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-64">
        <input id="monthFilter" type="month" 
            class="border p-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button id="downloadReportBtn" 
            class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-xl shadow-lg hover:scale-105 transform transition font-semibold">
            Download Laporan
        </button>
    </div>

    {{-- Table Card --}}
    <div class="card bg-white p-6 rounded-2xl shadow-lg mb-6">
        <div id="monthlyReportContent" class="overflow-x-auto">
            <p class="text-gray-400 text-center py-4">Memuat data laporan...</p>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-center gap-2" id="pagination"></div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const reportContainer = document.getElementById('monthlyReportContent');
    const downloadBtn = document.getElementById('downloadReportBtn');
    const searchInput = document.getElementById('searchInput');
    const monthFilter = document.getElementById('monthFilter');
    const pagination = document.getElementById('pagination');

    let allData = [];
    let currentPage = 1;
    const perPage = 10;

    
    function loadMonthlyReport() {
        reportContainer.innerHTML = '<p class="text-gray-400 text-center py-4">Memuat data laporan...</p>';

        axios.get("{{ route('bulanan.laporan') }}", {
            params: { bulan: monthFilter.value }
        })
        .then(res => {
          
            const payload = res.data;
            let data = [];

            if (Array.isArray(payload)) {
                data = payload;
            } else if (Array.isArray(payload.data)) {
                data = payload.data;
            } else if (Array.isArray(payload.transaksi)) {
                data = payload.transaksi;
            }

            allData = data;
            currentPage = 1;

            // Ambil stats dari response jika ada, kalau tidak hitung sendiri
            let stats = payload.stats ?? null;
            if (!stats) {
                stats = computeStatsFromData(allData);
            }

            // Update stat cards
            updateStatCards(stats);

            renderTable();
        })
        .catch(err => {
            console.error(err);
            reportContainer.innerHTML =
                '<p class="text-red-500 text-center py-4">Gagal memuat data laporan.</p>';
            // reset stat cards to 0 to avoid stale numbers
            updateStatCards({ totalPeminjaman: 0, dikembalikan: 0, terlambat: 0, bukuHilang: 0 });
        });
    }

   
    function computeStatsFromData(data) {
        const total = data.length;
        const dikembalikan = data.filter(d => d.status === 'dikembalikan').length;
        const terlambat = data.filter(d => d.status === 'terlambat').length;
        const hilang = data.filter(d => d.status === 'hilang').length;
        return {
            totalPeminjaman: total,
            dikembalikan: dikembalikan,
            terlambat: terlambat,
            bukuHilang: hilang
        };
    }

    function updateStatCards(stats) {
        document.getElementById('reportTotalBorrow').textContent = stats.totalPeminjaman ?? 0;
        document.getElementById('reportTotalReturn').textContent = stats.dikembalikan ?? 0;
        document.getElementById('reportLateReturn').textContent = stats.terlambat ?? 0;
        document.getElementById('reportLostBooks').textContent = stats.bukuHilang ?? 0;
    }

   
    function renderTable() {
        let filtered = allData;

        // Search filter
        const keyword = (searchInput.value || '').toLowerCase().trim();
        if (keyword) {
            filtered = filtered.filter(t =>
                (t.siswa?.nama ?? '').toLowerCase().includes(keyword) ||
                (t.buku?.judul ?? '').toLowerCase().includes(keyword)
            );
        }

        // Pagination
        const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const pageData = filtered.slice(start, end);

        if (pageData.length === 0) {
            reportContainer.innerHTML =
                '<p class="text-gray-400 text-center py-4">Tidak ada transaksi.</p>';
            pagination.innerHTML = '';
            return;
        }

        let html = `
        <table class="min-w-full border border-gray-200 text-left rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Siswa</th>
                    <th class="p-3 border">Buku</th>
                    <th class="p-3 border">Tgl Pinjam</th>
                    <th class="p-3 border">Tgl Kembali</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Denda</th>
                </tr>
            </thead>
            <tbody>`;

        pageData.forEach(t => {
            const siswaNama = t.siswa?.nama ?? '-';
            const bukuJudul = t.buku?.judul ?? '-';
            const tanggalPinjam = t.tanggal_pinjam ?? '-';
            const tanggalKembali = t.tanggal_kembali ?? '-';
            const status = t.status ?? '-';
            const denda = (t.denda ?? 0);

            
            const badgeClass = status === 'dipinjam' ? 'bg-blue-500' :
                               status === 'dikembalikan' ? 'bg-green-500' :
                               status === 'terlambat' ? 'bg-red-600' : 'bg-gray-400';

            html += `
            <tr class="hover:bg-gray-50 transition">
                <td class="p-3 border font-medium">${escapeHtml(siswaNama)}</td>
                <td class="p-3 border">${escapeHtml(bukuJudul)}</td>
                <td class="p-3 border">${escapeHtml(tanggalPinjam)}</td>
                <td class="p-3 border">${escapeHtml(tanggalKembali)}</td>
                <td class="p-3 border capitalize">
                    <span class="px-2 py-1 rounded-full text-white text-xs ${badgeClass}">${escapeHtml(status)}</span>
                </td>
                <td class="p-3 border text-right">${denda}</td>
            </tr>`;
        });

        html += '</tbody></table>';
        reportContainer.innerHTML = html;

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            html += `
            <button class="px-3 py-1 rounded-lg border ${
                i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'
            } mx-1" data-page="${i}">${i}</button>`;
        }

        pagination.innerHTML = html;

        Array.from(pagination.children).forEach(btn => {
            btn.addEventListener('click', function() {
                currentPage = parseInt(this.dataset.page) || 1;
                renderTable();
            });
        });
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        renderTable();
    });

    monthFilter.addEventListener('change', () => {
       
        loadMonthlyReport();
    });

    downloadBtn.addEventListener('click', function() {
        const type = prompt("Ketik 'pdf' atau 'csv' untuk download:");
        const bulan = monthFilter.value || new Date().toISOString().slice(0,7);
        if (type === 'pdf') {
            window.open("{{ route('bulanan_pdf') }}?bulan=" + bulan, "_blank");
        } else if (type === 'csv') {
            window.open("{{ route('bulanan_csv') }}?bulan=" + bulan, "_blank");
        } else {
            alert("Tipe file tidak valid.");
        }
    });

    loadMonthlyReport();

});
</script>
@endpush
