<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Transaksi;

class CatalogController extends Controller
{
    public function index()
    {
        $books = Buku::all();
        return view('pages.siswa.catalog', compact('books'));
    }
    public function pinjamBuku(Request $request)
{
    $siswaId = Auth::user()->siswa->id ?? null;

    if (!$siswaId) {
        return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
    }

    $buku = Buku::find($request->id_buku);
    if (!$buku || $buku->tersedia <= 0) {
        return response()->json(['message' => 'Buku tidak tersedia'], 400);
    }


    Transaksi::create([
        'id_transaksi' => 'TRX'.time(),
        'id_siswa' => $siswaId,
        'id_buku' => $buku->id_buku,
        'tanggal_pinjam' => now(),
        'tanggal_jatuh_tempo' => now()->addDays(7),
        'status' => 'dipinjam',
        'denda' => 0
    ]);

    
    $buku->tersedia -= 1;
    $buku->save();

    return response()->json(['message' => 'Peminjaman berhasil diajukan']);
}
}
