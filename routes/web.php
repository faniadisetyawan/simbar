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
        Route::get('/', 'BidangController@index');
        Route::get('/create', 'BidangController@create');
        Route::get('/{id}', 'BidangController@show');
        Route::get('/{id}/edit', 'BidangController@edit');
        Route::post('/', 'BidangController@store');
        Route::put('/{id}', 'BidangController@update');
        Route::put('/{id}/restore', 'BidangController@restore');
        Route::delete('/{id}', 'BidangController@destroy');
        Route::delete('/{id}/trash', 'BidangController@trash');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@index');
    });

    Route::group(['prefix' => 'persediaan'], function () {
        Route::get('/', 'PersediaanMasterController@index');
        Route::post('/', 'PersediaanMasterController@store');
        Route::post('/import', 'PersediaanMasterController@import');
    });
});

Route::get('/usulan', 'UsulanController@index');
