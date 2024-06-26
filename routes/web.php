<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('storage-link', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout');
    Route::get('/profile', 'UserController@profile')->middleware('auth')->name('auth.profile');
    Route::post('/login', 'AuthController@handleLogin');
    Route::post('/logout', 'AuthController@handleLogout');
});

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::group(['prefix' => 'master', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'bidang'], function () {
        Route::get('/', 'BidangController@index')->name('master.bidang.index');
        Route::get('/create', 'BidangController@create')->name('master.bidang.create');
        Route::get('/{id}/edit', 'BidangController@edit')->name('master.bidang.edit');
        Route::post('/', 'BidangController@store')->name('master.bidang.store');
        Route::put('/{id}', 'BidangController@update')->name('master.bidang.update');
        Route::put('/{id}/restore', 'BidangController@restore')->name('master.bidang.restore');
        Route::delete('/{id}', 'BidangController@destroy')->name('master.bidang.destroy');
        Route::delete('/{id}/trash', 'BidangController@trash')->name('master.bidang.trash');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@index')->name('master.users.index');
        Route::put('/{id}', 'UserController@update')->name('master.users.update');
        Route::put('/{id}/change-password', 'UserController@changePassword')->name('master.users.change-password');
        Route::put('/{id}/upload-photo', 'UserController@uploadPhoto')->name('master.users.upload-photo');
    });

    Route::group(['prefix' => 'persediaan'], function () {
        Route::get('/', 'PersediaanMasterController@index')->name('master.persediaan.index');
        Route::get('/create', 'PersediaanMasterController@create')->name('master.persediaan.create');
        Route::post('/', 'PersediaanMasterController@store')->name('master.persediaan.store');
        Route::post('/import', 'PersediaanMasterController@import')->name('master.persediaan.import');
        Route::put('/{id}', 'PersediaanMasterController@update')->name('master.persediaan.update');
        Route::put('/{id}/restore', 'PersediaanMasterController@restore')->name('master.persediaan.restore');
        Route::delete('/{id}', 'PersediaanMasterController@destroy')->name('master.persediaan.destroy');
        Route::delete('/{id}/trash', 'PersediaanMasterController@trash')->name('master.persediaan.trash');
    });
});

Route::group(['prefix' => 'pembukuan', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'saldo-awal'], function () {
        Route::get('/{slug}', 'Pembukuan\SaldoAwalController@index')->name('pembukuan.saldo-awal.index');
        Route::get('/{slug}/create', 'Pembukuan\SaldoAwalController@create')->name('pembukuan.saldo-awal.create');
        Route::post('/', 'Pembukuan\SaldoAwalController@store')->name('pembukuan.saldo-awal.store');
        Route::post('/import', 'Pembukuan\SaldoAwalController@import')->name('pembukuan.saldo-awal.import');
    });

    Route::group(['prefix' => 'perolehan'], function () {
        Route::get('/{slug}', 'Pembukuan\PerolehanController@index')->name('pembukuan.perolehan.index');
        Route::get('/{slug}/create', 'Pembukuan\PerolehanController@create')->name('pembukuan.perolehan.create');
        Route::get('/{slug}/docs/{docSlug}', 'Pembukuan\PerolehanController@showByDocs')->name('pembukuan.perolehan.showByDocs');
        Route::post('/{slug}', 'Pembukuan\PerolehanController@store')->name('pembukuan.perolehan.store');
        Route::put('/{slug}/barang/{id}', 'Pembukuan\PerolehanController@updateBarang')->name('pembukuan.perolehan.updateBarang');
        Route::put('/{slug}/docs/{docSlug}', 'Pembukuan\PerolehanController@updateDoc')->name('pembukuan.perolehan.updateDoc');
        Route::put('/{slug}/upload', 'Pembukuan\PerolehanController@uploadDokumen')->name('pembukuan.perolehan.uploadDokumen');
        Route::delete('/barang/{id}', 'Pembukuan\PerolehanController@destroyBarang')->name('pembukuan.perolehan.destroyBarang');
    });

    Route::group(['prefix' => 'reklasifikasi'], function () {
        Route::get('/', 'Pembukuan\ReklasifikasiController@index')->name('pembukuan.reklasifikasi.index');
        Route::get('/create', 'Pembukuan\ReklasifikasiController@create')->name('pembukuan.reklasifikasi.create');
        Route::get('/docs/{docSlug}', 'Pembukuan\ReklasifikasiController@showByDocs')->name('pembukuan.reklasifikasi.showByDocs');
        Route::post('/', 'Pembukuan\ReklasifikasiController@store')->name('pembukuan.reklasifikasi.store');
        Route::put('/barang/{id}', 'Pembukuan\ReklasifikasiController@updateBarang')->name('pembukuan.reklasifikasi.updateBarang');
        Route::put('/docs/{docSlug}', 'Pembukuan\ReklasifikasiController@updateDoc')->name('pembukuan.reklasifikasi.updateDoc');
        Route::put('/upload', 'Pembukuan\ReklasifikasiController@uploadDokumen')->name('pembukuan.reklasifikasi.uploadDokumen');
        Route::delete('/barang/{id}', 'Pembukuan\ReklasifikasiController@destroyBarang')->name('pembukuan.reklasifikasi.destroyBarang');
    });

    Route::group(['prefix' => 'penghapusan'], function () {
        Route::get('/', 'Pembukuan\PenghapusanController@index')->name('pembukuan.penghapusan.index');
        Route::get('/create', 'Pembukuan\PenghapusanController@create')->name('pembukuan.penghapusan.create');
        Route::get('/docs/{docSlug}', 'Pembukuan\PenghapusanController@showByDocs')->name('pembukuan.penghapusan.showByDocs');
        Route::post('/', 'Pembukuan\PenghapusanController@store')->name('pembukuan.penghapusan.store');
        Route::put('/upload', 'Pembukuan\PenghapusanController@uploadDokumen')->name('pembukuan.penghapusan.uploadDokumen');
        Route::put('/barang/{id}', 'Pembukuan\PenghapusanController@updateBarang')->name('pembukuan.penghapusan.updateBarang');
        Route::put('/docs/{docSlug}', 'Pembukuan\PenghapusanController@updateDoc')->name('pembukuan.penghapusan.updateDoc');
        Route::delete('/{id}', 'Pembukuan\PenghapusanController@destroyBarang')->name('pembukuan.penghapusan.destroyBarang');
    });

    Route::group(['prefix' => 'stock-opname'], function () {
        Route::get('/', 'Pembukuan\StockOpnameController@index')->name('pembukuan.stock-opname.index');
        Route::get('/create', 'Pembukuan\StockOpnameController@create')->name('pembukuan.stock-opname.create');
        Route::get('/docs/{docSlug}', 'Pembukuan\StockOpnameController@showByDocs')->name('pembukuan.stock-opname.showByDocs');
        Route::post('/store', 'Pembukuan\StockOpnameController@store')->name('pembukuan.stock-opname.store');
        Route::put('/upload', 'Pembukuan\StockOpnameController@uploadDokumen')->name('pembukuan.stock-opname.uploadDokumen');
        Route::put('/docs/{docSlug}', 'Pembukuan\StockOpnameController@updateDoc')->name('pembukuan.stock-opname.updateDoc');
        Route::delete('/{id}', 'Pembukuan\StockOpnameController@destroyBarang')->name('pembukuan.stock-opname.destroyBarang');
    });
});

Route::group(['prefix' => 'penyaluran', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'nota-permintaan'], function () {
        Route::get('/', 'Penyaluran\NotaPermintaanController@index')->name('penyaluran.nota-permintaan.index');
        Route::get('/create', 'Penyaluran\NotaPermintaanController@create')->name('penyaluran.nota-permintaan.create');
        Route::get('/docs/{docSlug}', 'Penyaluran\NotaPermintaanController@showByDocs')->name('penyaluran.nota-permintaan.showByDocs');
        Route::post('/', 'Penyaluran\NotaPermintaanController@store')->name('penyaluran.nota-permintaan.store');
        Route::put('/barang/{id}', 'Penyaluran\NotaPermintaanController@updateBarang')->name('penyaluran.nota-permintaan.updateBarang');
        Route::put('/docs/{docSlug}', 'Penyaluran\NotaPermintaanController@updateDoc')->name('penyaluran.nota-permintaan.updateDoc');
        Route::delete('/barang/{id}', 'Penyaluran\NotaPermintaanController@destroyBarang')->name('penyaluran.nota-permintaan.destroyBarang');
    });

    Route::group(['prefix' => 'spb'], function () {
        Route::get('/', 'Penyaluran\SpbController@index')->name('penyaluran.spb.index');
        Route::get('/create', 'Penyaluran\SpbController@create')->name('penyaluran.spb.create');
        Route::get('/docs/{docSlug}', 'Penyaluran\SpbController@showByDocs')->name('penyaluran.spb.showByDocs');
        Route::post('/', 'Penyaluran\SpbController@store')->name('penyaluran.spb.store');
        Route::put('/upload', 'Penyaluran\SpbController@uploadDokumen')->name('penyaluran.spb.uploadDokumen');
        Route::put('/docs/{docSlug}', 'Penyaluran\SpbController@updateDoc')->name('penyaluran.spb.updateDoc');
        Route::delete('/barang/{id}', 'Penyaluran\SpbController@destroyBarang')->name('penyaluran.spb.destroyBarang');
    });

    Route::group(['prefix' => 'sppb'], function () {
        Route::get('/', 'Penyaluran\SppbController@index')->name('penyaluran.sppb.index');
        Route::get('/create', 'Penyaluran\SppbController@create')->name('penyaluran.sppb.create');
        Route::get('/docs/{docSlug}', 'Penyaluran\SppbController@showByDocs')->name('penyaluran.sppb.showByDocs');
        Route::post('/', 'Penyaluran\SppbController@store')->name('penyaluran.sppb.store');
        Route::put('/docs/{docSlug}', 'Penyaluran\SppbController@updateDoc')->name('penyaluran.sppb.updateDoc');
    });
});

Route::get('/usulan', 'UsulanController@index');

Route::group(['prefix' => 'laporan', 'middleware' => 'auth'], function () {
    Route::get('/perolehan/{slug}', 'MutasiController@perolehan')->name('laporan.perolehan');
    Route::get('/reklasifikasi', 'MutasiController@reklasifikasi')->name('laporan.reklasifikasi');
    Route::get('/penghapusan', 'MutasiController@penghapusan')->name('laporan.penghapusan');
    Route::get('/stock-opname', 'MutasiController@stockOpname')->name('laporan.stock-opname');
    Route::get('/penyaluran', 'MutasiController@penyaluran')->name('laporan.penyaluran');
    Route::get('/kartu-persediaan', 'MutasiController@kartuPersediaan')->name('laporan.kartu-persediaan');
    Route::get('/mutasi-persediaan', 'MutasiController@index')->name('laporan.mutasi-persediaan');
});
