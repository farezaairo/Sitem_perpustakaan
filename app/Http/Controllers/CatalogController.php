<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Transaksi;
use Auth;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->search) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search){
                $q->whereRaw("LOWER(judul) LIKE ?", ["%$search%"])
                  ->orWhereRaw("LOWER(penulis) LIKE ?", ["%$search%"]);
            });
        }

        if ($request->kategori) {
            $kategori = strtolower(trim($request->kategori));
            $query->whereRaw("LOWER(TRIM(kategori)) = ?", [$kategori]);
        }

        $books = $query->get();

        return view('pages.siswa.catalog', compact('books'));
    }

    public function filter(Request $request)
    {
        $query = Buku::query();

        if ($request->search) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search){
                $q->whereRaw("LOWER(judul) LIKE ?", ["%$search%"])
                  ->orWhereRaw("LOWER(penulis) LIKE ?", ["%$search%"]);
            });
        }

        if ($request->kategori) {
            $kategori = strtolower(trim($request->kategori));
            $query->whereRaw("LOWER(TRIM(kategori)) = ?", [$kategori]);
        }

        return response()->json($query->get());
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
