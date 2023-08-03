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

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/login', 'AuthController@handleLogin');
    Route::post('/logout', 'AuthController@handleLogout');
});

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::group(['prefix' => 'master'], function () {
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

    Route::group(['prefix' => 'perolehan', 'middleware' => 'auth'], function () {
        Route::get('/{slug}', 'Pembukuan\PerolehanController@index')->name('pembukuan.perolehan.index');
        Route::get('/{slug}/create', 'Pembukuan\PerolehanController@create')->name('pembukuan.perolehan.create');
        Route::get('/{slug}/docs/{docSlug}', 'Pembukuan\PerolehanController@showByDocs')->name('pembukuan.perolehan.showByDocs');
        Route::post('/{slug}', 'Pembukuan\PerolehanController@store')->name('pembukuan.perolehan.store');
        Route::put('/{slug}/barang/{id}', 'Pembukuan\PerolehanController@updateBarang')->name('pembukuan.perolehan.updateBarang');
        Route::put('/{slug}/upload', 'Pembukuan\PerolehanController@uploadDokumen')->name('pembukuan.perolehan.uploadDokumen');
        Route::delete('/barang/{id}', 'Pembukuan\PerolehanController@destroyBarang')->name('pembukuan.perolehan.destroyBarang');
    });
});

Route::group(['prefix' => 'penyaluran', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'nota-permintaan'], function () {
        Route::get('/', 'Penyaluran\NotaPermintaanController@index')->name('penyaluran.nota-permintaan.index');
        Route::get('/create', 'Penyaluran\NotaPermintaanController@create')->name('penyaluran.nota-permintaan.create');
        Route::get('/docs/{docSlug}', 'Penyaluran\NotaPermintaanController@showByDocs')->name('penyaluran.nota-permintaan.showByDocs');
        Route::post('/', 'Penyaluran\NotaPermintaanController@store')->name('penyaluran.nota-permintaan.store');
        Route::put('/barang/{id}', 'Penyaluran\NotaPermintaanController@updateBarang')->name('penyaluran.nota-permintaan.updateBarang');
        Route::put('/upload', 'Penyaluran\NotaPermintaanController@uploadDokumen')->name('penyaluran.nota-permintaan.uploadDokumen');
        Route::delete('/barang/{id}', 'Penyaluran\NotaPermintaanController@destroyBarang')->name('penyaluran.nota-permintaan.destroyBarang');
    });

    Route::group(['prefix' => 'spb'], function () {
        Route::get('/', 'Penyaluran\SpbController@index')->name('penyaluran.spb.index');
        Route::get('/create', 'Penyaluran\SpbController@create')->name('penyaluran.spb.create');
        Route::get('/docs/{docSlug}', 'Penyaluran\SpbController@showByDocs')->name('penyaluran.spb.showByDocs');
        Route::post('/', 'Penyaluran\SpbController@store')->name('penyaluran.spb.store');
        Route::put('/upload', 'Penyaluran\SpbController@uploadDokumen')->name('penyaluran.spb.uploadDokumen');
        Route::delete('/barang/{id}', 'Penyaluran\SpbController@destroyBarang')->name('penyaluran.spb.destroyBarang');
    });

    Route::group(['prefix' => 'sppb'], function () {
        Route::get('/', 'Penyaluran\SppbController@index')->name('penyaluran.sppb.index');
        Route::post('/', 'Penyaluran\SppbController@store')->name('penyaluran.sppb.store');
    });
});

Route::get('/usulan', 'UsulanController@index');
