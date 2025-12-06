@extends('layouts.main')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Katalog Buku</h2>
    <div class="flex space-x-2">
        <input type="text" id="searchInput" placeholder="Cari buku..." class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <select id="kategoriFilter" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Semua Kategori</option>
            <option value="Fiksi">Fiksi</option>
            <option value="Non-Fiksi">Non-Fiksi</option>
            <option value="Sains">Sains</option>
            <option value="Sejarah">Sejarah</option>
            <option value="Teknologi">Teknologi</option>
        </select>
        <button id="btnSearch" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Cari</button>
    </div>
</div>

<div id="bukuContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($books as $b)
    <div class="border rounded-2xl shadow-lg p-3 flex flex-col transition-transform transform hover:scale-105">
        <img src="{{ $b->gambar_sampul ?? 'https://via.placeholder.com/150' }}" class="h-48 object-cover rounded-lg">
        <div class="mt-2 flex-1">
            <h3 class="font-bold text-lg">{{ $b->judul }}</h3>
            <p class="text-sm text-gray-600">Penulis: {{ $b->penulis }}</p>
            <p class="text-sm text-gray-600">Kategori: {{ $b->kategori }}</p>
            <p class="text-sm text-gray-600">Stok: {{ $b->stok }} | Tersedia: {{ $b->tersedia }}</p>
            <span class="mt-1 inline-block text-xs font-semibold px-2 py-1 rounded-full {{ $b->tersedia > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $b->tersedia > 0 ? 'Tersedia' : 'Habis' }}
            </span>
        </div>
        <div class="mt-2">
            @if($b->tersedia > 0)
            <button onclick="ajukanPeminjaman('{{ $b->id_buku }}')" 
                class="w-full px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Ajukan Peminjaman
            </button>
            @else
            <button disabled class="w-full px-3 py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                Habis
            </button>
            @endif
        </div>
    </div>
    @empty
    <p class="col-span-4 text-center text-gray-500">Belum ada buku tersedia.</p>
    @endforelse
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function ajukanPeminjaman(idBuku) {
    if(!confirm('Ajukan peminjaman buku ini?')) return;

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    axios.post("{{ route('siswa.pinjam') }}", {
        id_buku: idBuku
    }, {
        headers: {
            'X-CSRF-TOKEN': token
        }
    })
    .then(res => {
        alert(res.data.message);
        window.location.reload();
    })
    .catch(err => {
        console.error(err);
        alert(err.response?.data?.message || 'Gagal mengajukan peminjaman.');
    });
}

// Search & Filter
document.getElementById('btnSearch').addEventListener('click', function(){
    const search = document.getElementById('searchInput').value;
    const kategori = document.getElementById('kategoriFilter').value;
    let url = '{{ route("catalog.index") }}?search=' + encodeURIComponent(search) + '&kategori=' + encodeURIComponent(kategori);
    window.location.href = url;
});
</script>
@endpush
