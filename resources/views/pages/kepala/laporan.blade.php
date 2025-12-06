@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Laporan Statistik Bulanan</h2>
        <p class="text-gray-600 mt-1">Ringkasan kegiatan perpustakaan bulan ini</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-5 rounded-2xl shadow-lg">
            <h4 class="text-sm font-medium">Total Buku Dipinjam</h4>
            <div class="text-3xl font-bold mt-2">{{ $totalBukuDipinjam }}</div>
        </div>

        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-5 rounded-2xl shadow-lg">
            <h4 class="text-sm font-medium">Pengembalian Terlambat</h4>
            <div class="text-3xl font-bold mt-2">{{ $pengembalianTerlambat }}</div>
        </div>

        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-5 rounded-2xl shadow-lg">
            <h4 class="text-sm font-medium">Buku Hilang</h4>
            <div class="text-3xl font-bold mt-2">{{ $bukuHilang }}</div>
        </div>

        <div class="bg-gradient-to-r from-red-400 to-red-600 text-white p-5 rounded-2xl shadow-lg">
            <h4 class="text-sm font-medium">Total Denda</h4>
            <div class="text-3xl font-bold mt-2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Chart Bulanan --}}
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Jumlah Peminjaman per Bulan</h3>
        <canvas id="borrowChart" height="120"></canvas>
    </div>

    {{-- Detail Statistik (opsional) --}}
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Statistik</h3>
        <table class="min-w-full border border-gray-200 text-left rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Bulan</th>
                    <th class="p-3 border">Jumlah Peminjaman</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chartData as $data)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border">{{ \Carbon\Carbon::create()->month($data->month)->format('F') }}</td>
                        <td class="p-3 border font-medium">{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('borrowChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: @json($chartData->pluck('total')),
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, precision: 0 },
            x: { ticks: { autoSkip: false } }
        }
    }
});
</script>
@endpush
