@extends('layouts.main')

@section('content')
<div id="transactionsPage" class="content-page p-6">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Data Transaksi</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex flex-col">
                <label for="filterTransactionStatus" class="text-gray-700 font-medium mb-1">Filter Status</label>
                <select id="filterTransactionStatus" class="p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm uppercase text-gray-600">
                        <th class="p-3">Siswa</th>
                        <th class="p-3">Buku</th>
                        <th class="p-3">Tgl Pinjam</th>
                        <th class="p-3">Tgl Kembali</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Denda</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transactionsContainer" class="text-sm">
                    <tr><td colspan="7" class="text-center py-4">Memuat...</td></tr>
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    const transactionsContainer = document.getElementById('transactionsContainer');
    const filterStatus = document.getElementById('filterTransactionStatus');

    function loadTransactions(){
        transactionsContainer.innerHTML = '<tr><td colspan="7" class="text-center py-4">Memuat...</td></tr>';

        axios.get("{{ route('transaksi.list') }}")
        .then(res=>{
            let data = res.data;

            // Filter status
            if(filterStatus.value){
                data = data.filter(t => t.status === filterStatus.value);
            }

            if(data.length === 0){
                transactionsContainer.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada data transaksi.</td></tr>';
                return;
            }

            transactionsContainer.innerHTML = data.map(t=>{
                let actionButtons = `<button onclick="hapusTransaction('${t.id_transaksi}')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>`;
                if(t.status === 'dipinjam' || t.status === 'terlambat'){
                    actionButtons = `<button onclick="kembalikanTransaction('${t.id_transaksi}')" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Kembalikan</button>` + actionButtons;
                }
                return `
                    <tr>
                        <td class="p-3">${t.siswa.nama}</td>
                        <td class="p-3">${t.buku.judul}</td>
                        <td class="p-3">${t.tanggal_pinjam}</td>
                        <td class="p-3">${t.tanggal_kembali ?? '-'}</td>
                        <td class="p-3">${t.status}</td>
                        <td class="p-3">${t.denda}</td>
                        <td class="p-3 flex gap-2">${actionButtons}</td>
                    </tr>
                `;
            }).join('');
        })
        .catch(err=>{
            transactionsContainer.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-red-500">Gagal memuat data transaksi.</td></tr>';
            console.error(err);
        });
    }

    window.kembalikanTransaction = function(id){
        if(confirm('Apakah buku ini dikembalikan?')){
            axios.post(`/transaksi/${id}/kembalikan`, {_token:'{{ csrf_token() }}'})
            .then(()=>{ 
                alert('Buku berhasil dikembalikan!');
                loadTransactions();
            })
            .catch(err=>{
                alert('Gagal mengembalikan buku');
                console.error(err);
            });
        }
    }

    window.hapusTransaction = function(id){
        if(confirm('Hapus transaksi ini?')){
            axios.delete(`/transaksi/${id}`, {data:{_token:'{{ csrf_token() }}'}})
            .then(()=>{ 
                alert('Transaksi berhasil dihapus!');
                loadTransactions();
            })
            .catch(err=>{
                alert('Gagal menghapus transaksi');
                console.error(err);
            });
        }
    }

    filterStatus.addEventListener('change', loadTransactions);

    loadTransactions();

});
</script>
@endpush
