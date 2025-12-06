<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use Carbon\Carbon;
use App\Models\Siswa;


class KepalaDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // TOTAL DENDA
        $total_fines = Transaksi::sum('denda');

        // TRANSAKSI BULAN INI
        $thisMonth = Transaksi::whereYear('tanggal_pinjam', $now->year)
                              ->whereMonth('tanggal_pinjam', $now->month)
                              ->count();

        // KETERLAMBATAN BULAN INI (case-insensitive)
        $late = Transaksi::whereRaw('LOWER(status) = ?', ['terlambat'])->count();

        // TOTAL BUKU DIPINJAM BULAN INI
        $totalBukuDipinjam = Transaksi::whereIn('status',['dipinjam','dikembalikan','terlambat'])->count();

        // TOTAL BUKU & BUKU TERSEDIA
        $total_books = Buku::count();
        $available_books = Buku::sum('tersedia');

        // TOTAL SISWA
        $total_students = Siswa::count();

        // PEMINJAM AKTIF & SEDANG DIPINJAM
        $borrowed_books = Transaksi::where('status','dipinjam')->count();
        $total_borrowers = Transaksi::whereIn('status',['dipinjam','dikembalikan','terlambat'])
                                    ->distinct('id_siswa')
                                    ->count('id_siswa');

        
        $recent_transactions = Transaksi::with(['siswa','buku'])
            ->orderBy('tanggal_pinjam','desc')
            ->limit(10)
            ->get();

        return view('pages.kepala.dashboard', compact(
            'total_fines','thisMonth','late','totalBukuDipinjam',
            'total_books','available_books','total_borrowers','borrowed_books','total_students',
            'recent_transactions'
        ));
    }

    public function bulanan()
{
    $thisMonth = Transaksi::whereMonth('tanggal_pinjam', now()->month)->get();
    return view('pages.kepala.bulanan.laporan', compact('thisMonth'));
}

}
