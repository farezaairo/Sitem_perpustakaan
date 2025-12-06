<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        return view('pages.admin.buku'); 
    }


    public function list()
    {
        $buku = Buku::all();
        return response()->json($buku);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'gambar_sampul' => 'nullable|url',
        ]);

        $buku = Buku::create([
            'id_buku' => ('B').substr(uniqid(), -9), 
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'tersedia' => $request->stok,
            'gambar_sampul' => $request->gambar_sampul,
        ]);

        return response()->json($buku);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'tersedia' => $request->stok,
            'gambar_sampul' => $request->gambar_sampul,
        ]);

        return response()->json($buku);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
