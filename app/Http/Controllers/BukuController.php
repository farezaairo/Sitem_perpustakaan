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


   public function list(Request $request)
{
    $query = Buku::query();

    $search = trim((string) $request->query('search', ''));
    $kategori = trim((string) $request->query('kategori', ''));


    if ($search !== '') {
        $searchLower = mb_strtolower($search, 'UTF-8');
        $query->whereRaw("LOWER(judul) LIKE ?", ["%{$searchLower}%"]);
    }

    
    if ($kategori !== '') {
        $kategoriLower = mb_strtolower($kategori, 'UTF-8');
        $query->whereRaw("LOWER(TRIM(kategori)) = ?", [$kategoriLower]);
    }

    $buku = $query->orderBy('judul')->get();

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
