<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{

    // Dashboard Laporan Kepala Perpustakaan
    public function index()
    {
        // Statistik
        $totalBukuDipinjam = Transaksi::where('status', 'dipinjam')->count();
        $bukuHilang = Transaksi::where('status', 'hilang')->count();
        $pengembalianTerlambat = Transaksi::where('status', 'terlambat')->count();
        $totalDenda = Transaksi::sum('denda');

        // Ambil data transaksi per bulan untuk chart
        $chartData = Transaksi::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $months = $chartData->pluck('month')->map(function($m) {
            return Carbon::create()->month($m)->format('F');
        });

        return view('pages.kepala.laporan', compact(
            'totalBukuDipinjam',
            'bukuHilang',
            'pengembalianTerlambat',
            'totalDenda',
            'chartData',
            'months'
        ));
    }

   
    public function bulananpage()
{
    $transaksi = Transaksi::with(['siswa', 'buku'])
        ->whereMonth('tanggal_pinjam', now()->month)
        ->get();

   
    $totalPeminjaman = $transaksi->count();
    $dikembalikan = $transaksi->where('status', 'dikembalikan')->count();
    $terlambat = $transaksi->where('status', 'terlambat')->count();
    $bukuHilang        = $transaksi->where('status', 'hilang')->count();

    return view('pages.kepala.bulanan.laporan', compact(
        'transaksi',
        'totalPeminjaman',
        'dikembalikan',
        'terlambat',
         'bukuHilang'
    ));
}

public function bulananLaporan(Request $request)
{
    $bulan = $request->bulan ? date('m', strtotime($request->bulan . '-01')) : now()->month;

    $transaksi = Transaksi::with(['siswa', 'buku'])
        ->whereMonth('tanggal_pinjam', $bulan)
        ->orderBy('tanggal_pinjam', 'DESC')
        ->get();

    // Statistik
    $totalPeminjaman = $transaksi->count();
    $dikembalikan   = $transaksi->where('status', 'dikembalikan')->count();
    $terlambat      = $transaksi->where('status', 'terlambat')->count();
    $bukuHilang     = $transaksi->where('status', 'hilang')->count();

    return response()->json([
        'data' => $transaksi,
        'stats' => [
            'totalPeminjaman' => $totalPeminjaman,
            'dikembalikan' => $dikembalikan,
            'terlambat' => $terlambat,
            'bukuHilang' => $bukuHilang,
        ]
    ]);
}


public function downloadBulananPDF(Request $request)
{
    $bulan = $request->bulan ?? now()->format('Y-m');

    // Ubah Y-m menjadi bulan berbentuk: November 2025
    $carbonDate = Carbon::createFromFormat('Y-m', $bulan);
    $namaBulanTahun = $carbonDate->translatedFormat('F Y');

    $transaksi = Transaksi::with(['siswa', 'buku'])
        ->where('tanggal_pinjam', 'like', "$bulan%")
        ->orderBy('tanggal_pinjam', 'DESC')
        ->get();

    return \PDF::loadView('bulanan_pdf', [
        'transaksi' => $transaksi,
        'namaBulanTahun' => $namaBulanTahun
    ])->download("laporan_bulanan_$bulan.pdf");
}

public function downloadBulananCSV(Request $request)
{
    $bulan = $request->bulan ?? now()->format('Y-m');

    $carbonDate = Carbon::createFromFormat('Y-m', $bulan);
    $namaBulanTahun = $carbonDate->translatedFormat('F Y');

    $transaksi = Transaksi::with(['siswa', 'buku'])
        ->where('tanggal_pinjam', 'like', "$bulan%")
        ->orderBy('tanggal_pinjam', 'DESC')
        ->get();

    $filename = "laporan_bulanan_$bulan.csv";
    $handle = fopen($filename, 'w+');

    fputcsv($handle, ["Laporan Bulanan Perpustakaan - $namaBulanTahun"]);
    fputcsv($handle, []); // baris kosong
    fputcsv($handle, ['Siswa', 'Buku', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Denda']);

    foreach ($transaksi as $t) {
        fputcsv($handle, [
            $t->siswa->nama ?? '-',
            $t->buku->judul ?? '-',
            $t->tanggal_pinjam,
            $t->tanggal_kembali ?? '-',
            ucfirst($t->status),
            $t->denda ?? 0
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}


}