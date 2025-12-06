<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Siswa;
use App\Models\Buku;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('pages.admin.transaksi');
    }

    public function list(Request $request)
    {
        $query = Transaksi::with(['siswa', 'buku']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transaksi = $query->orderBy('tanggal_pinjam','desc')->get();

        return response()->json($transaksi);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'id_buku' => 'required|exists:buku,id_buku',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $transaksi = Transaksi::create($request->all());
        return response()->json($transaksi, 201);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->all());
        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return response()->json(['message'=>'Transaksi berhasil dihapus']);
    }
  public function kembalikan($id)
{
    $transaksi = Transaksi::findOrFail($id);

    if ($transaksi->status === 'dikembalikan') {
        return response()->json(['message'=>'Transaksi sudah dikembalikan'], 400);
    }

    $tanggalJatuhTempo = strtotime($transaksi->tanggal_jatuh_tempo);
    $sekarang = time();

    $demoSatuanDetik = 60;   
    $dendaPerHari = 1000;    
    
    $selisihDetik = $sekarang - $tanggalJatuhTempo;

    if ($selisihDetik > 0) {
        
        $hariTelat = floor($selisihDetik / $demoSatuanDetik);
        $denda = $hariTelat * $dendaPerHari;
    } else {
        $denda = 0;
    }

    $transaksi->status = 'dikembalikan';
    $transaksi->tanggal_kembali = now();
    $transaksi->denda = $denda;

    $buku = $transaksi->buku;
    $buku->tersedia += 1;
    $buku->save();

    $transaksi->save();

    return response()->json(['message'=>'Buku berhasil dikembalikan', 'denda' => $denda]);
}

public function buku()
{
    return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
}


}
