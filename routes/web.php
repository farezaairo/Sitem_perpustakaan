<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\KepalaDashboardController;

use App\Http\Controllers\BukuController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanAdminController;


// ======================================================
// LOGIN
// ======================================================
Route::get('/', function () {
    return view('pages.login');
})->name('login');

Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ======================================================
// ADMIN ROUTES
// ======================================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])
        ->name('dashboard.admin');

    Route::get('/dashboard-admin/filter-peminjaman', [AdminDashboardController::class, 'filterPeminjaman'])
    ->name('dashboard.admin.filter');
    

    // Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/list', [BukuController::class, 'list'])->name('buku.list');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // CRUD Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('admin.siswa');
    Route::get('/siswa/list', [SiswaController::class, 'list'])->name('siswa.list');
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::put('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/list', [TransaksiController::class, 'list'])->name('transaksi.list');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])
        ->name('transaksi.kembalikan');

    // Laporan Admin
    Route::get('/laporan/admin', [LaporanAdminController::class, 'index'])->name('laporan.admin');
    Route::get('/laporan/admin/pdf', [LaporanAdminController::class, 'downloadPDF'])
        ->name('laporan.admin.pdf');
    Route::get('/laporan/admin/csv', [LaporanAdminController::class, 'downloadCSV'])
        ->name('laporan.admin.csv');
});


// ======================================================
// SISWA ROUTES
// ======================================================
Route::middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])
        ->name('dashboard.siswa');

    Route::get('/catalog', [CatalogController::class, 'index'])
        ->name('catalog.index');

    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('riwayat.index');

    Route::get('/riwayat/qr/{id_transaksi}', [RiwayatController::class, 'downloadQr'])
        ->name('riwayat.qr');

    Route::post('/siswa/pinjam', [PeminjamanController::class, 'store'])
        ->name('siswa.pinjam');
});


// ======================================================
// KEPALA PERPUSTAKAAN ROUTES
// ======================================================
Route::middleware(['auth', 'role:kepala_perpustakaan'])->group(function () {

    Route::get('/dashboard-kepala', [KepalaDashboardController::class, 'index'])
        ->name('dashboard.kepala');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/bulanan/laporan', [LaporanController::class, 'bulananLaporan'])
        ->name('bulanan.laporan');

    Route::get('/bulanan', [LaporanController::class, 'bulananPage'])
        ->name('bulanan.page');

    Route::get('/bulanan_pdf', [LaporanController::class, 'downloadBulananPDF'])
        ->name('bulanan_pdf');

    Route::get('/bulanan_csv', [LaporanController::class, 'downloadBulananCSV'])
        ->name('bulanan_csv');
});


// ======================================================
// DEBUG
// ======================================================
Route::get('/cek-auth', function () {
    return [
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'csrf' => csrf_token(),
    ];
});
