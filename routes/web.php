<?php

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

/*
|--------------------------------------------------------------------------
| Web Routes - Barokah Speed System
|--------------------------------------------------------------------------
*/

// --- DASHBOARD ---
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);


// --- MODUL MASTER DATA ---

Route::prefix('master/sparepart')->name('sparepart.')->group(function () {
    Route::get('/get-data', [SparepartController::class, 'getData'])->name('get-data');
    Route::get('/generate-code', [SparepartController::class, 'generateCode'])->name('generate-code');
    Route::get('/low-stock', [SparepartController::class, 'getLowStock'])->name('low-stock');

    Route::post('/mutasi', [SparepartController::class, 'mutasiStok'])->name('mutasi');

    // Cukup pakai satu ini saja Bos
    Route::post('/tambah-stok', [SparepartController::class, 'tambahStok'])->name('tambah-stok');
});



Route::post('/sparepart/toggle-status/{id}', [SparepartController::class, 'toggleStatus'])->name('sparepart.toggle-status');

Route::resource('master/sparepart', SparepartController::class)->names('sparepart')->except(['show']);

// 2. UNIT MOTOR
Route::prefix('master/unit')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('unit.index');
    Route::get('/get-data', [UnitController::class, 'getData'])->name('unit.get-data');
    Route::post('/store', [UnitController::class, 'store'])->name('unit.store');

    // Taruh yang menggunakan {id} di bawah
    Route::put('/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');
});



// 3. KONSUMEN & RIWAYAT
Route::prefix('master/konsumen')->name('konsumen.')->group(function () {
    Route::get('/', [KonsumenController::class, 'index'])->name('index');
    Route::get('/get-data', [KonsumenController::class, 'getData'])->name('get-data');
    Route::post('/store', [KonsumenController::class, 'store'])->name('store'); // URL menjadi: master/konsumen/store
    Route::put('/update/{id}', [KonsumenController::class, 'update'])->name('update');

    Route::get('/riwayat-kendaraan/{kendaraan_id}', [KonsumenController::class, 'getRiwayatKendaraan'])->name('riwayat-kendaraan');

    Route::delete('/delete/{id}', [KonsumenController::class, 'destroy'])->name('destroy');
});


Route::resource('master/konsumen', KonsumenController::class)->names('konsumen');

// 4. MEKANIK (Gunakan Resource agar standar)
Route::resource('master/mekanik', MekanikController::class)->names('mekanik');



Route::prefix('mekanik')->group(function () {
    // Menampilkan Halaman Utama (Halaman Blade)
    Route::get('/', [MekanikController::class, 'index'])->name('mekanik.index');

    // Route khusus untuk Fetch API (Infinite Scroll & Search)
    // URL: /mekanik/get-data
    Route::get('/get-data', [MekanikController::class, 'getData'])->name('mekanik.get-data');

    // Simpan Data Baru (URL: /mekanik/store)
    Route::post('/store', [MekanikController::class, 'store'])->name('mekanik.store');

    // Update Data (URL: /mekanik/update/{id})
    // Menggunakan PUT agar lebih standar RESTful, atau tetap POST jika ingin simpel
    Route::put('/update/{id}', [MekanikController::class, 'update'])->name('mekanik.update');

    // Hapus/Nonaktifkan Data (URL: /mekanik/delete/{id})
    Route::delete('/delete/{id}', [MekanikController::class, 'destroy'])->name('mekanik.destroy');
});

Route::prefix('master/jasa')->group(function () {
    // Halaman Utama
    Route::get('/', [JasaController::class, 'index'])->name('jasa.index');

    // API untuk Fetch Data (Infinite Scroll & Search)
    Route::get('/get-data', [JasaController::class, 'getData'])->name('jasa.get-data');

    // Simpan Data Baru
    Route::post('/store', [JasaController::class, 'store'])->name('jasa.store');

    // Update Data
    Route::put('/update/{id}', [JasaController::class, 'update'])->name('jasa.update');

    // Hapus Data
    Route::delete('/delete/{id}', [JasaController::class, 'destroy'])->name('jasa.delete');
});

// 5. JASA & KENDARAAN
Route::resource('master/jasa', JasaController::class)->names('jasa');
Route::resource('master/kendaraan', KendaraanController::class)->names('kendaraan');


// --- MODUL TRANSAKSI ---
// --- MODUL TRANSAKSI ---
Route::prefix('transaksi')->name('transaksi.')->group(function () {
    // Halaman Utama & Form
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/baru', [TransaksiController::class, 'createServis'])->name('baru');
    Route::get('/part-only', [TransaksiController::class, 'createPart'])->name('part-only');

    // Fitur Ajax & API Transaksi
    Route::controller(TransaksiController::class)->group(function () {
        Route::get('/search-part', 'searchPart')->name('search-part');
        Route::get('/cek-konsumen', 'cekKonsumen')->name('cek-konsumen');



        // Pencarian Kendaraan (Gunakan satu rute utama untuk efisiensi script)
        Route::get('/cek-kendaraan-lengkap/{no_polisi}', 'getKendaraanLengkap')->name('cek-kendaraan-lengkap');

        // Rute opsional jika masih dibutuhkan untuk pencarian via ID Konsumen
        Route::get('/get-kendaraan/{konsumen_id}', 'getKendaraan')->name('get-kendaraan');
    });

    // Proses Simpan & Output
    Route::post('/simpan', [TransaksiController::class, 'store'])->name('simpan');
    Route::get('/pembayaran/{id}', [TransaksiController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/cetak-struk/{id}', [TransaksiController::class, 'cetakStruk'])->name('cetak');
});

Route::post('/master/unit/simpan-cepat', [UnitController::class, 'simpanCepat'])->name('master.unit.simpan-cepat');
Route::post('/units/store-ajax', [UnitController::class, 'storeUnitAjax'])->name('units.storeAjax');


// Laporan Servis

// Satukan semua laporan dalam satu group biar tidak bentrok
Route::prefix('laporan')->name('laporan.')->group(function () {

    // 1. Laporan Servis (Unit Masuk)
    Route::get('/laporan-servis', [LaporanController::class, 'index'])->name('index');
    Route::get('/laporan-servis/print', [LaporanController::class, 'print'])->name('print');

    // 2. Laporan Penjualan Sparepart
    Route::get('/sparepart', [LaporanController::class, 'penjualanSparepart'])->name('sparepart');

    // 3. Laporan Keuangan
    Route::get('/keuangan', [LaporanController::class, 'laporanKeuangan'])->name('keuangan');
    Route::get('/keuangan/pdf', [LaporanController::class, 'exportPdf'])->name('keuangan.pdf');

    // 4. Modal Detail
    Route::get('/detail-transaksi/{no_faktur}', [LaporanController::class, 'getDetailModal'])->name('detail.modal');

    // 5. Riwayat Servis
    Route::get('/riwayat-servis', [LaporanController::class, 'riwayatServis'])->name('riwayat');
});
Route::resource('sparepart', SparepartController::class);
Route::resource('mekanik', MekanikController::class);
Route::resource('jasa', JasaController::class);
