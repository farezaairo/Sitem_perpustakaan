<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use PDF;
use Response;

class LaporanAdminController extends Controller
{

    // Halaman laporan admin
    public function index()
    {
        $totalPeminjaman = Transaksi::count();
        $dikembalikan = Transaksi::where('status', 'dikembalikan')->count();
        $terlambat = Transaksi::where('status', 'terlambat')->count();
        $bukuHilang = Transaksi::where('status', 'hilang')->count();

        $transaksiDetails = Transaksi::with(['siswa', 'buku'])
                                ->orderBy('tanggal_pinjam', 'desc')
                                ->get();

        return view('pages.admin.laporan', compact(
            'totalPeminjaman', 'dikembalikan', 'terlambat', 'bukuHilang', 'transaksiDetails'
        ));
    }

    // Download PDF
    public function downloadPDF()
    {
        $transaksi = Transaksi::with(['siswa', 'buku'])->orderBy('tanggal_pinjam', 'desc')->get();
        $pdf = PDF::loadView('pages.admin.laporan_pdf', compact('transaksi'));
        return $pdf->download('laporan_admin.pdf');
    }

    // Download CSV
    public function downloadCSV()
    {
        $transaksi = Transaksi::with(['siswa', 'buku'])->orderBy('tanggal_pinjam', 'desc')->get();

        $filename = "laporan_admin.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Siswa', 'Buku', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Denda']);

        foreach($transaksi as $t) {
            fputcsv($handle, [
                $t->siswa->nama ?? '-',
                $t->buku->judul ?? '-',
                $t->tanggal_pinjam,
                $t->tanggal_kembali ?? '-',
                $t->status,
                $t->denda
            ]);
        }

        fclose($handle);

        return Response::download($filename)->deleteFileAfterSend(true);
    }
}
