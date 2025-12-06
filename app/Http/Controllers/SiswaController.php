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

    public function list()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
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

       
        $lastId = Siswa::max('id_siswa');
        if (!$lastId) {
            $newId = 'S001';
        } else {
            $num = (int) substr($lastId, 1);
            $num++;
            $newId = 'S' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }

       
        $lastNis = Siswa::max('nis');
        if (!$lastNis) {
            $newNis = '2025001';
        } else {
            $newNis = $lastNis + 1;
        }

       
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
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:150',
            'kelas' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'username' => 'required|string|max:80|unique:siswa,username,'.$id.',id',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'email' => $request->email,
            'username' => $request->username,
        ]);

        return response()->json($siswa);
    }


    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }
}
