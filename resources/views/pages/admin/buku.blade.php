@extends('layouts.main')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Daftar Buku</h2>
    <button id="btnTambahBuku" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-600 transform hover:scale-105 transition">
        + Tambah Buku
    </button>
</div>

{{-- SEARCH & FILTER --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">

    {{-- SEARCH --}}
    <div class="w-full md:w-1/2">
        <label class="text-gray-700 font-medium mb-1">Cari Judul Buku</label>
        <input type="text" id="searchBook" placeholder="Ketik judul buku..." 
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- FILTER KATEGORI --}}
    <div class="w-full md:w-1/3">
        <label class="text-gray-700 font-medium mb-1">Filter Kategori</label>
        <select id="filterKategori" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Semua Kategori</option>
            <option value="Fiksi">Fiksi</option>
            <option value="Non-Fiksi">Non-Fiksi</option>
            <option value="Sains">Sains</option>
            <option value="Sejarah">Sejarah</option>
            <option value="Teknologi">Teknologi</option>
        </select>
    </div>

</div>

{{-- CONTAINER --}}
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
                <label class="block text-gray-700 font-medium mb-1">Judul</label>
                <input type="text" id="bookJudul" class="w-full border border-gray-300 p-3 rounded-lg" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Penulis</label>
                <input type="text" id="bookPengarang" class="w-full border border-gray-300 p-3 rounded-lg" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Kategori</label>
                <select id="bookKategori" class="w-full border border-gray-300 p-3 rounded-lg" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Fiksi">Fiksi</option>
                    <option value="Non-Fiksi">Non-Fiksi</option>
                    <option value="Sains">Sains</option>
                    <option value="Sejarah">Sejarah</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Stok</label>
                <input type="number" id="bookStok" min="1" class="w-full border border-gray-300 p-3 rounded-lg" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Gambar Sampul (URL)</label>
                <input type="url" id="bookGambar" class="w-full border border-gray-300 p-3 rounded-lg">
            </div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" id="btnBatal" class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg shadow hover:bg-gray-400 transition">Batal</button>
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

    const bukuContainer = document.getElementById('bukuContainer');
    const searchInput = document.getElementById('searchBook');
    const filterKategori = document.getElementById('filterKategori'); 
    const skeletonTemplate = document.getElementById('skeletonCard') ? document.getElementById('skeletonCard').content : null;
    const bookForm = document.getElementById('bookForm');

   
    if (!bukuContainer) {
        console.error('Element #bukuContainer tidak ditemukan di DOM.');
        return;
    }

    
    function debounce(fn, wait = 200) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

   
    function showSkeleton() {
        bukuContainer.innerHTML = '';
        if (!skeletonTemplate) return;
        for (let i = 0; i < 4; i++) {
            bukuContainer.appendChild(skeletonTemplate.cloneNode(true));
        }
    }

    
    function loadBuku() {
        showSkeleton();

        const search = searchInput ? searchInput.value.trim() : '';
        const kategori = filterKategori ? filterKategori.value.trim() : '';

        axios.get("{{ route('buku.list') }}", {
            params: { search: search, kategori: kategori }
        })
        .then(res => {
            const data = Array.isArray(res.data) ? res.data : [];

            bukuContainer.innerHTML = '';

            if (data.length === 0) {
                bukuContainer.innerHTML = '<p class="col-span-4 text-center text-gray-500">Tidak ada buku ditemukan.</p>';
                return;
            }

            const html = data.map(b => {
                const gambar = b.gambar_sampul || 'https://via.placeholder.com/150';
                const tersedia = (typeof b.tersedia !== 'undefined') ? b.tersedia : b.stok ?? 0;

               
                return `
                <div class="border rounded-2xl shadow-lg p-3 flex flex-col transition-transform transform hover:scale-105">
                    <img src="${escapeHtml(gambar)}" class="h-48 object-cover rounded-lg" alt="${escapeHtml(b.judul || '')}">
                    <div class="mt-2 flex-1">
                        <h3 class="font-bold text-lg">${escapeHtml(b.judul || '')}</h3>
                        <p class="text-sm text-gray-600">Penulis: ${escapeHtml(b.penulis || '')}</p>
                        <p class="text-sm text-gray-600">Kategori: ${escapeHtml(b.kategori || '')}</p>
                        <p class="text-sm text-gray-600">Stok: ${escapeHtml(String(b.stok ?? '0'))} | Tersedia: ${escapeHtml(String(tersedia))}</p>
                        <span class="mt-1 inline-block text-xs font-semibold px-2 py-1 rounded-full ${tersebarClass(tersedia)}">
                            ${tersedia > 0 ? 'Tersedia' : 'Habis'}
                        </span>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        <button type="button" data-action="edit-book"
                            data-id="${escapeAttr(b.id_buku)}"
                            data-judul="${escapeAttr(b.judul)}"
                            data-penulis="${escapeAttr(b.penulis)}"
                            data-kategori="${escapeAttr(b.kategori)}"
                            data-stok="${escapeAttr(String(b.stok ?? ''))}"
                            data-gambar="${escapeAttr(b.gambar_sampul || '')}"
                            class="flex-1 px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</button>

                        <button type="button" data-action="delete-book" data-id="${escapeAttr(b.id_buku)}"
                            class="flex-1 px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
                    </div>
                </div>`;
            }).join('');

            bukuContainer.innerHTML = html;
        })
        .catch(err => {
            bukuContainer.innerHTML = '<p class="col-span-4 text-red-500 text-center">Gagal memuat data buku.</p>';
            console.error('Error loadBuku:', err);
        });
    }

    
    function escapeHtml(str) {
        if (str === undefined || str === null) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
    function escapeAttr(str) { return escapeHtml(str); }
    function tersebarClass(tersedia) {
        return (tersedia > 0) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    }

    
    bukuContainer.addEventListener('click', function(e) {
        const editBtn = e.target.closest('[data-action="edit-book"]');
        if (editBtn) {
            const data = {
                id: editBtn.dataset.id,
                judul: editBtn.dataset.judul,
                penulis: editBtn.dataset.penulis,
                kategori: editBtn.dataset.kategori,
                stok: editBtn.dataset.stok,
                gambar: editBtn.dataset.gambar
            };
            
            openBookModalSafe(data);
            return;
        }

        const delBtn = e.target.closest('[data-action="delete-book"]');
        if (delBtn) {
            const id = delBtn.dataset.id;
            if (!id) return;
            if (!confirm('Hapus buku ini?')) return;
            axios.delete(`/buku/${id}`, { data: { _token: bookForm.elements._token ? bookForm.elements._token.value : '' } })
            .then(()=>{ loadBuku(); alert('Buku berhasil dihapus!'); })
            .catch(err=>{ alert('Gagal menghapus buku'); console.error(err); });
            return;
        }
    });

    
    function openBookModalSafe(data = {}) {
        const modal = document.getElementById('bookModal');
        if (!modal) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        const title = document.getElementById('bookModalTitle');
        if (title) title.innerText = data.id ? 'Edit Buku' : 'Tambah Buku';
    
        document.getElementById('bookId').value = data.id || '';
        document.getElementById('bookJudul').value = data.judul || '';
        document.getElementById('bookPengarang').value = data.penulis || '';
        document.getElementById('bookKategori').value = data.kategori || '';
        document.getElementById('bookStok').value = data.stok || '';
        document.getElementById('bookGambar').value = data.gambar || '';
    }

    
    const localBookForm = document.getElementById('bookForm');
    if (localBookForm) {
        localBookForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('bookId').value;
            const url = id ? `/buku/${id}` : `/buku`;
            const csrf = localBookForm.elements._token ? localBookForm.elements._token.value : '';
            const payload = {
                judul: document.getElementById('bookJudul').value,
                penulis: document.getElementById('bookPengarang').value,
                kategori: document.getElementById('bookKategori').value,
                stok: document.getElementById('bookStok').value,
                gambar_sampul: document.getElementById('bookGambar').value,
                _token: csrf,
                ...(id && { _method: 'PUT' })
            };
            axios.post(url, payload)
            .then(()=>{ document.getElementById('btnBatal').click(); loadBuku(); alert('Buku berhasil disimpan!'); })
            .catch(err => { alert('Gagal menyimpan buku'); console.error(err); });
        });
    }


    const btnTambah = document.getElementById('btnTambahBuku');
    const btnBatal = document.getElementById('btnBatal');
    if (btnTambah) btnTambah.addEventListener('click', ()=> openBookModalSafe());
    if (btnBatal) btnBatal.addEventListener('click', ()=> {
        const modal = document.getElementById('bookModal');
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    });


    if (searchInput) searchInput.addEventListener('input', debounce(loadBuku, 250));
    if (filterKategori) filterKategori.addEventListener('change', loadBuku);

    loadBuku();

});
</script>
@endpush
