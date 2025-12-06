<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class RiwayatController extends Controller
{
    public function index()
    {
        $id_siswa = Auth::user()->siswa->id ?? null;

        $riwayat = Transaksi::with('buku')
            ->where('id_siswa', $id_siswa)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('pages.siswa.riwayat', compact('riwayat'));
    }


    public function downloadQr($id_transaksi)
    {
        $transaksi = Transaksi::with('buku')->findOrFail($id_transaksi);
        $qrData = "Transaksi ID: {$transaksi->id_transaksi}\nBuku: {$transaksi->buku->judul}\nTanggal Pinjam: {$transaksi->tanggal_pinjam}";

        return QrCode::format('png')->size(300)->generate($qrData);
    }
}
