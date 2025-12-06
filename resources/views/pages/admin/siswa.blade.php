@extends('layouts.main')

@section('content')
<div id="studentsPage" class="content-page p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Data Siswa</h3>
        <button id="addStudentBtn" class="bg-blue-500 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-600 hover:scale-105 transform transition font-semibold">
            + Tambah Siswa
        </button>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl p-6 shadow-lg">

        {{-- SEARCH --}}
        <div class="mb-6">
            <input type="text" id="searchStudents" placeholder="Cari siswa..." class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm uppercase text-gray-600">
                        <th class="p-3">Nama</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Username</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="studentsContainer" class="text-sm">
                    {{-- Data siswa akan di-load via AJAX --}}
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- Modal Tambah/Edit --}}
<div id="studentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl w-full max-w-lg transform scale-0 transition-transform">
        <h3 id="studentModalTitle" class="text-xl font-bold mb-4">Tambah Siswa</h3>
        <form id="studentForm">
            @csrf
            <input type="hidden" id="studentId">
            <div class="mb-4">
                <label>Nama</label>
                <input type="text" id="studentNama" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label>Kelas</label>
                <input type="text" id="studentKelas" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" id="studentEmail" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label>Username</label>
                <input type="text" id="studentUsername" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" id="studentPassword" class="w-full p-2 border rounded">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="btnCancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const studentModal = document.getElementById('studentModal');
    const studentForm = document.getElementById('studentForm');
    const studentsContainer = document.getElementById('studentsContainer');

    function openStudentModal() {
        studentModal.classList.remove('hidden');
        studentModal.classList.add('flex');
        studentModal.firstElementChild.classList.add('scale-100');
        studentForm.reset();
        document.getElementById('studentModalTitle').innerText = "Tambah Siswa";
        document.getElementById('studentId').value = '';
    }

    function closeStudentModal() {
        studentModal.classList.add('hidden');
        studentModal.classList.remove('flex');
    }

    window.editStudent = function(id, nama, kelas, email, username) {
        openStudentModal();
        document.getElementById('studentModalTitle').innerText = "Edit Siswa";
        document.getElementById('studentId').value = id;
        document.getElementById('studentNama').value = nama;
        document.getElementById('studentKelas').value = kelas;
        document.getElementById('studentEmail').value = email;
        document.getElementById('studentUsername').value = username;
    }

    window.deleteStudent = function(id){
        if(confirm('Hapus siswa ini?')){
            axios.delete(`/siswa/${id}`, {data: {_token: studentForm.elements._token.value}})
            .then(()=>{ loadStudents(); alert('Siswa berhasil dihapus!'); })
            .catch(err=>{ alert('Terjadi error saat menghapus siswa'); console.error(err); });
        }
    }

    function loadStudents(){
        studentsContainer.innerHTML = '<tr><td colspan="5" class="text-center py-4">Memuat...</td></tr>';
        axios.get("{{ route('siswa.list') }}")
        .then(res=>{
            studentsContainer.innerHTML = '';
            if(res.data.length === 0){
                studentsContainer.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">Belum ada data siswa.</td></tr>';
                return;
            }
            const rows = res.data.map(s=>{
                return `
                    <tr>
                        <td class="p-3">${s.nama}</td>
                        <td class="p-3">${s.kelas ?? '-'}</td>
                        <td class="p-3">${s.email ?? '-'}</td>
                        <td class="p-3">${s.username}</td>
                        <td class="p-3 flex space-x-2">
                            <button onclick="editStudent('${s.id}','${s.nama}','${s.kelas ?? ''}','${s.email ?? ''}','${s.username}')" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</button>
                            <button onclick="deleteStudent('${s.id}')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
            studentsContainer.innerHTML = rows;
        })
        .catch(err=>{
            studentsContainer.innerHTML = '<tr><td colspan="5" class="text-center text-red-500 py-4">Gagal memuat data siswa.</td></tr>';
            console.error(err);
        });
    }

    studentForm.addEventListener('submit', function(e){
    e.preventDefault();

    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    const id = document.getElementById('studentId').value;
    const url = id ? `/siswa/${id}` : `/siswa`;

    const payload = {
        nama: document.getElementById('studentNama').value.trim(),
        kelas: document.getElementById('studentKelas').value.trim(),
        email: document.getElementById('studentEmail').value.trim(),
        username: document.getElementById('studentUsername').value.trim(),
        password: document.getElementById('studentPassword').value,
    };

    // Jika update, override method
    if(id){
        payload._method = 'PUT';
    }

    axios.post(url, payload)
    .then(res=>{
        closeStudentModal();
        loadStudents();
        alert('Siswa berhasil disimpan!');
    })
    .catch(error=>{
        if(error.response && error.response.status === 422){
            const errs = error.response.data.errors;
            let msg = "Validasi gagal:\n\n";
            Object.keys(errs).forEach(key=>{
                msg += `• ${key}: ${errs[key].join(', ')}\n`;
            });
            alert(msg); 
            console.error("VALIDATION ERRORS", errs);
            return;
        }
        alert("Terjadi error lain. Cek console.");
        console.error(error);
    });
});


    document.getElementById('addStudentBtn').addEventListener('click', openStudentModal);
    document.getElementById('btnCancel').addEventListener('click', closeStudentModal);

    loadStudents();
});
</script>
@endpush
