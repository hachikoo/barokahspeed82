<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    TransaksiController,
    MekanikController,
    SparepartController,
    JasaController,
    KonsumenController,
    KendaraanController,
    UnitController,
    LaporanController
};


Route::get('/cek-db', function () {
    try {
        return DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});
/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);


/*
|--------------------------------------------------------------------------
| MASTER DATA
|--------------------------------------------------------------------------
*/

// ================= SPAREPART =================
Route::prefix('master/sparepart')->name('sparepart.')->group(function () {
    Route::get('/get-data', [SparepartController::class, 'getData'])->name('get-data');
    Route::get('/generate-code', [SparepartController::class, 'generateCode'])->name('generate-code');
    Route::get('/low-stock', [SparepartController::class, 'getLowStock'])->name('low-stock');

    Route::post('/mutasi', [SparepartController::class, 'mutasiStok'])->name('mutasi');
    Route::post('/tambah-stok', [SparepartController::class, 'tambahStok'])->name('tambah-stok');
    Route::post('/toggle-status/{id}', [SparepartController::class, 'toggleStatus'])->name('toggle-status');
});
Route::resource('master/sparepart', SparepartController::class)->names('sparepart')->except('show');


// ================= UNIT =================
Route::prefix('master/unit')->name('unit.')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('index');
    Route::get('/get-data', [UnitController::class, 'getData'])->name('get-data');
    Route::post('/store', [UnitController::class, 'store'])->name('store');
    Route::put('/{id}', [UnitController::class, 'update'])->name('update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');

    Route::post('/simpan-cepat', [UnitController::class, 'simpanCepat'])->name('simpan-cepat');
    Route::post('/store-ajax', [UnitController::class, 'storeUnitAjax'])->name('store-ajax');
});


// ================= KONSUMEN =================
Route::prefix('master/konsumen')->name('konsumen.')->group(function () {
    Route::get('/', [KonsumenController::class, 'index'])->name('index');
    Route::get('/get-data', [KonsumenController::class, 'getData'])->name('get-data');
    Route::post('/store', [KonsumenController::class, 'store'])->name('store');
    Route::put('/update/{id}', [KonsumenController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [KonsumenController::class, 'destroy'])->name('destroy');

    Route::get('/riwayat-kendaraan/{kendaraan_id}', [KonsumenController::class, 'getRiwayatKendaraan'])->name('riwayat-kendaraan');
});
Route::resource('master/konsumen', KonsumenController::class)->names('konsumen');


// ================= MEKANIK =================
Route::prefix('master/mekanik')->name('mekanik.')->group(function () {
    Route::get('/', [MekanikController::class, 'index'])->name('index');
    Route::get('/get-data', [MekanikController::class, 'getData'])->name('get-data');
    Route::post('/store', [MekanikController::class, 'store'])->name('store');
    Route::put('/update/{id}', [MekanikController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [MekanikController::class, 'destroy'])->name('destroy');
});
Route::resource('master/mekanik', MekanikController::class)->names('mekanik');


// ================= JASA =================
Route::prefix('master/jasa')->name('jasa.')->group(function () {
    Route::get('/', [JasaController::class, 'index'])->name('index');
    Route::get('/get-data', [JasaController::class, 'getData'])->name('get-data');
    Route::post('/store', [JasaController::class, 'store'])->name('store');
    Route::put('/update/{id}', [JasaController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [JasaController::class, 'destroy'])->name('delete');
});
Route::resource('master/jasa', JasaController::class)->names('jasa');


// ================= KENDARAAN =================
Route::resource('master/kendaraan', KendaraanController::class)->names('kendaraan');


/*
|--------------------------------------------------------------------------
| TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::prefix('transaksi')->name('transaksi.')->group(function () {

    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/baru', [TransaksiController::class, 'createServis'])->name('baru');
    Route::get('/part-only', [TransaksiController::class, 'createPart'])->name('part-only');

    Route::get('/search-part', [TransaksiController::class, 'searchPart'])->name('search-part');
    Route::get('/cek-konsumen', [TransaksiController::class, 'cekKonsumen'])->name('cek-konsumen');
    Route::get('/cek-kendaraan-lengkap/{no_polisi}', [TransaksiController::class, 'getKendaraanLengkap'])->name('cek-kendaraan-lengkap');
    Route::get('/get-kendaraan/{konsumen_id}', [TransaksiController::class, 'getKendaraan'])->name('get-kendaraan');

    Route::post('/simpan', [TransaksiController::class, 'store'])->name('simpan');
    Route::get('/pembayaran/{id}', [TransaksiController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/cetak-struk/{id}', [TransaksiController::class, 'cetakStruk'])->name('cetak');
});


/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
Route::prefix('laporan')->name('laporan.')->group(function () {

    Route::get('/laporan-servis', [LaporanController::class, 'index'])->name('servis');
    Route::get('/laporan-servis/print', [LaporanController::class, 'print'])->name('print');

    Route::get('/sparepart', [LaporanController::class, 'penjualanSparepart'])->name('sparepart');

    Route::get('/keuangan', [LaporanController::class, 'laporanKeuangan'])->name('keuangan');
    Route::get('/keuangan/pdf', [LaporanController::class, 'exportPdf'])->name('keuangan.pdf');

    Route::get('/detail-transaksi/{no_faktur}', [LaporanController::class, 'getDetailModal'])->name('detail.modal');
    Route::get('/riwayat-servis', [LaporanController::class, 'riwayatServis'])->name('riwayat');
});
