@extends('layouts.main')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Daftar Buku</h2>
    <button id="btnTambahBuku" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-600 transform hover:scale-105 transition">
        + Tambah Buku
    </button>
</div>

<div id="bukuContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <template id="skeletonCard">
        <div class="rounded-2xl overflow-hidden shadow-lg animate-pulse flex flex-col gap-2">
            <div class="h-48 bg-gray-300"></div>
            <div class="h-6 bg-gray-300 rounded w-3/4 mx-3 mt-3"></div>
            <div class="h-4 bg-gray-300 rounded w-5/6 mx-3"></div>
            <div class="h-4 bg-gray-300 rounded w-1/2 mx-3 mb-3"></div>
        </div>
    </template>
</div>

{{-- MODAL --}}
<div id="bookModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative transform scale-90 transition-transform duration-300">
        <h3 id="bookModalTitle" class="text-2xl font-bold mb-5 text-gray-800">Tambah Buku</h3>
        <form id="bookForm" class="space-y-4">
            @csrf
            <input type="hidden" id="bookId">
            <div>
                <label for="bookJudul" class="block text-gray-700 font-medium mb-1">Judul</label>
                <input type="text" id="bookJudul" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label for="bookPengarang" class="block text-gray-700 font-medium mb-1">Penulis</label>
                <input type="text" id="bookPengarang" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label for="bookKategori" class="block text-gray-700 font-medium mb-1">Kategori</label>
                <select id="bookKategori" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Fiksi">Fiksi</option>
                    <option value="Non-Fiksi">Non-Fiksi</option>
                    <option value="Sains">Sains</option>
                    <option value="Sejarah">Sejarah</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
            </div>
            <div>
                <label for="bookStok" class="block text-gray-700 font-medium mb-1">Stok</label>
                <input type="number" id="bookStok" min="1" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div>
                <label for="bookGambar" class="block text-gray-700 font-medium mb-1">Gambar Sampul (URL)</label>
                <input type="url" id="bookGambar" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" id="btnBatal" class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg shadow hover:bg-gray-400 hover:text-gray-800 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg shadow hover:from-blue-600 hover:to-indigo-600 transform hover:scale-105 transition font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const bookModal = document.getElementById('bookModal');
    const bookForm = document.getElementById('bookForm');
    const bukuContainer = document.getElementById('bukuContainer');
    const skeletonTemplate = document.getElementById('skeletonCard').content;

    function openBookModal() {
        bookModal.classList.remove('hidden');
        bookModal.classList.add('flex');
        bookModal.firstElementChild.classList.add('scale-100');
        bookForm.reset();
        document.getElementById('bookModalTitle').innerText = "Tambah Buku";
        document.getElementById('bookId').value = '';
    }

    function closeBookModal() {
        bookModal.classList.add('hidden');
        bookModal.classList.remove('flex');
    }

    window.editBook = function(id, judul, penulis, kategori, stok, gambar) {
        openBookModal();
        document.getElementById('bookModalTitle').innerText = "Edit Buku";
        document.getElementById('bookId').value = id;
        document.getElementById('bookJudul').value = judul;
        document.getElementById('bookPengarang').value = penulis;
        document.getElementById('bookKategori').value = kategori;
        document.getElementById('bookStok').value = stok;
        document.getElementById('bookGambar').value = gambar;
    }

    window.hapusBuku = function(id){
        if(confirm('Hapus buku ini?')){
            axios.delete(`/buku/${id}`, {data: {_token: bookForm.elements._token.value}})
            .then(()=>{ loadBuku(); alert('Buku berhasil dihapus!'); })
            .catch(err=>{ alert('Terjadi error saat menghapus buku'); console.error(err); });
        }
    }

    function loadBuku(){
        bukuContainer.innerHTML = '';
        // Render 4 skeletons
        for(let i=0;i<4;i++){
            const clone = skeletonTemplate.cloneNode(true);
            bukuContainer.appendChild(clone);
        }

        axios.get("{{ route('buku.list') }}")
        .then(res=>{
            bukuContainer.innerHTML = '';
            if(res.data.length === 0){
                bukuContainer.innerHTML = '<p class="col-span-4 text-center text-gray-500">Belum ada data buku.</p>';
                return;
            }
            const cards = res.data.map(b=>{
                const gambar = b.gambar_sampul ?? 'https://via.placeholder.com/150';
                return `
                    <div class="border rounded-2xl shadow-lg p-3 flex flex-col transition-transform transform hover:scale-105">
                        <img src="${gambar}" class="h-48 object-cover rounded-lg">
                        <div class="mt-2 flex-1">
                            <h3 class="font-bold text-lg">${b.judul}</h3>
                            <p class="text-sm text-gray-600">Penulis: ${b.penulis}</p>
                            <p class="text-sm text-gray-600">Kategori: ${b.kategori}</p>
                            <p class="text-sm text-gray-600">Stok: ${b.stok} | Tersedia: ${b.tersedia}</p>
                            <p class="text-sm text-gray-600">ISBN: ${b.isbn ?? '-'}</p>
                            <span class="mt-1 inline-block text-xs font-semibold px-2 py-1 rounded-full ${b.tersedia > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                ${b.tersedia > 0 ? 'Tersedia' : 'Habis'}
                            </span>
                        </div>
                        <div class="mt-2 flex space-x-2">
                            <button onclick="editBook('${b.id_buku}','${b.judul}','${b.penulis}','${b.kategori}','${b.stok}','${b.gambar_sampul ?? ''}')" 
                                class="flex-1 px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</button>
                            <button onclick="hapusBuku('${b.id_buku}')" 
                                class="flex-1 px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
                        </div>
                    </div>
                `;
            }).join('');
            bukuContainer.innerHTML = cards;
        }).catch(err=>{
            bukuContainer.innerHTML = '<p class="col-span-4 text-red-500 text-center">Gagal memuat data buku.</p>';
            console.error(err);
        });
    }

    bookForm.addEventListener('submit', function(e){
        e.preventDefault();
        const id = document.getElementById('bookId').value;
        const url = id ? `/buku/${id}` : `/buku`;
        const csrfToken = bookForm.elements._token.value;
        const data = {
            judul: document.getElementById('bookJudul').value,
            penulis: document.getElementById('bookPengarang').value,
            kategori: document.getElementById('bookKategori').value,
            stok: document.getElementById('bookStok').value,
            gambar_sampul: document.getElementById('bookGambar').value,
            _token: csrfToken,
            ...(id && { _method: 'PUT' }) 
        };
        axios.post(url, data)
        .then(()=>{ closeBookModal(); loadBuku(); alert('Buku berhasil disimpan!'); })
        .catch(err=>{ alert('Terjadi error saat menyimpan buku'); console.error(err); });
    });

    document.getElementById('btnTambahBuku').addEventListener('click', openBookModal);
    document.getElementById('btnBatal').addEventListener('click', closeBookModal);

    loadBuku();
});
</script>
@endpush
