<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'master'], function () {
    Route::group(['prefix' => 'persediaan'], function () {
        Route::get('/has-stok', 'API\PersediaanMasterController@hasStok');
    });
});

Route::get('/kartu-persediaan', 'API\MutasiController@kartuPersediaan');
Route::get('/current-price/{id}', 'API\MutasiController@currentPrice');
