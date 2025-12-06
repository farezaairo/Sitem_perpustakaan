<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    
    public function index()
    {
        return view('pages.admin.siswa');
    }


    public function list(Request $request)
    {
        $search = $request->query('search');

        $query = Siswa::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            });
        }

        return response()->json($query->orderBy('nama')->get());
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'kelas' => 'required|string|max:50',
            'email' => 'required|email|max:150',
            'username' => 'required|string|max:80|unique:siswa,username',
            'password' => 'required|string|min:6',
        ]);

        // AUTO ID S001
        $lastId = Siswa::max('id_siswa');
        if (!$lastId) {
            $newId = 'S001';
        } else {
            $num = (int) substr($lastId, 1);
            $num++;
            $newId = 'S' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }

        // AUTO NIS
        $lastNis = Siswa::max('nis');
        $newNis = $lastNis ? $lastNis + 1 : 2025001;

        $siswa = Siswa::create([
            'id_siswa' => $newId,
            'nis' => $newNis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($siswa);
    }

    
    public function update(Request $request, $id)
    {
        // ID kamu adalah id_siswa, bukan id
        $siswa = Siswa::where('id_siswa', $id)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:150',
            'kelas' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'username' => 'required|string|max:80|unique:siswa,username,'.$id.',id_siswa',
            'password' => 'nullable|min:6'
        ]);

        // Update data
        $siswa->nama = $request->nama;
        $siswa->kelas = $request->kelas;
        $siswa->email = $request->email;
        $siswa->username = $request->username;

        // Tambahkan update password jika diisi
        if (!empty($request->password)) {
            $siswa->password = bcrypt($request->password);
        }

        $siswa->save();

        return response()->json($siswa);
    }


    public function destroy($id)
    {
        // Menyesuaikan dengan id_siswa
        $siswa = Siswa::where('id_siswa', $id)->firstOrFail();
        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }
}
