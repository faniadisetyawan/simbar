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
    Route::get('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');
});

Route::get('/dashboard', 'DashboardController@index');

Route::group(['prefix' => 'master'], function () {
    Route::get('/bidang', 'BidangController@index');
    Route::get('/users', 'UserController@index');
});

Route::get('/usulan', 'UsulanController@index');
