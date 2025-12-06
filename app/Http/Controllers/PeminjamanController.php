<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
        ]);

        $id_siswa = Auth::user()->siswa->id ?? null;
        $buku = Buku::findOrFail($request->id_buku);

        if($buku->tersedia < 1){
            return response()->json(['message' => 'Buku tidak tersedia'], 400);
        }

    
        $existing = Transaksi::where('id_siswa', $id_siswa)
            ->where('id_buku', $buku->id_buku)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->first();

        if($existing){
            return response()->json(['message' => 'Anda sudah meminjam buku ini'], 400);
        }

        $transaksi = Transaksi::create([
            'id_transaksi' => 'TRX'.time(),
            'id_siswa' => $id_siswa,
            'id_buku' => $buku->id_buku,
            'tanggal_pinjam' => Carbon::now()->toDateString(),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

    
        $buku->tersedia -= 1;
        $buku->save();

        return response()->json(['message' => 'Berhasil meminjam buku', 'transaksi' => $transaksi]);
    }
}
