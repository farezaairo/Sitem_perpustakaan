<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Transaksi;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
       
        $total_books = Buku::count();
        $available_books = Buku::sum('tersedia');
        $borrowed_books = Transaksi::where('status', 'dipinjam')->count();
        $total_borrowers = Siswa::count();

       
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Hari ini
        $today_loans = Transaksi::whereDate('tanggal_pinjam', $today)->count();

        // Bulan ini
        $month_loans = Transaksi::whereMonth('tanggal_pinjam', $currentMonth)->count();

        // Tahun ini
        $year_loans = Transaksi::whereYear('tanggal_pinjam', $currentYear)->count();

       
        $recent_transactions = Transaksi::with(['siswa', 'buku'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->limit(10)
            ->get();

        return view('pages.admin.dashboard', compact(
            'total_books',
            'available_books',
            'borrowed_books',
            'total_borrowers',
            'today_loans',
            'month_loans',
            'year_loans',
            'recent_transactions'
        ));
    }

    public function filterPeminjaman(Request $request)
{
    $type = $request->query('type');
    $query = Transaksi::with(['siswa', 'buku']);

    if ($type === 'hari') {
        $query->whereDate('tanggal_pinjam', now());
    } elseif ($type === 'bulan') {
        $query->whereMonth('tanggal_pinjam', now()->month)
              ->whereYear('tanggal_pinjam', now()->year);
    } elseif ($type === 'tahun') {
        $query->whereYear('tanggal_pinjam', now()->year);
    } else {
        return response()->json([]);
    }

    $transactions = $query->get();

    return response()->json($transactions);
}

}
