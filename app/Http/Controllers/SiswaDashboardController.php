<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        
        $siswa = Auth::user()->siswa; 

        
        $riwayat = Transaksi::with('buku')
            ->where('id_siswa', $siswa->id)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('pages.siswa.dashboard', compact('riwayat'));
    }
}
