<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();

        if ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->peran === 'kepala_perpustakaan') {
            return redirect()->route('kepala.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }

    
    //  DASHBOARD ADMIN
     
    public function admin()
    {
        $today = Carbon::today()->toDateString();
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $yearStart = Carbon::now()->startOfYear()->toDateString();

        return view('dashboard.admin', [
            // Statistik 
            'totalBuku'        => Buku::count(),
            'totalSiswa'       => Siswa::count(),
            'totalTransaksi'   => Transaksi::count(),

            
            'peminjam_today'   => Transaksi::whereDate('tanggal_pinjam', $today)->count(),
            'peminjam_month'   => Transaksi::whereDate('tanggal_pinjam', '>=', $monthStart)->count(),
            'peminjam_year'    => Transaksi::whereDate('tanggal_pinjam', '>=', $yearStart)->count(),

            
            'dipinjam_today'   => Transaksi::whereDate('tanggal_pinjam', $today)
                                            ->where('status','dipinjam')->count(),

            'dipinjam_month'   => Transaksi::whereDate('tanggal_pinjam','>=',$monthStart)
                                            ->where('status','dipinjam')->count(),

            'dipinjam_year'    => Transaksi::whereDate('tanggal_pinjam','>=',$yearStart)
                                            ->where('status','dipinjam')->count(),
        ]);
    }

    
    //  DASHBOARD SISWA
    
    public function siswa()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        $transaksi = Transaksi::where('id_siswa', $siswa->id ?? 0)
                        ->orderBy('tanggal_pinjam','desc')
                        ->take(10)
                        ->get();

        return view('dashboard.siswa', [
            'user' => $user,
            'siswa' => $siswa,
            'riwayat' => $transaksi
        ]);
    }


    // DASHBOARD KEPALA PERPUSTAKAAN
    
    public function kepala()
    {
        return view('dashboard.kepala_perpustakaan', [
            'totalBuku' => Buku::count(),
            'totalTransaksi' => Transaksi::count(),
            'totalSiswa' => Siswa::count(),
        ]);
    }
}
