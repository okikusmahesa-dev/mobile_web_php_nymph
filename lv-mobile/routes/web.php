<?php

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

Route::get('/', function () {return view('layouts.index');});
Route::get('/login', function () {return view('layouts.login');});
Route::post('/postlogin','AuthController@postlogin');
//Pin
Route::get('/inPin','PinController@index');
Route::get('/checkTr800000','PinController@tr800000');
Route::get('/checkTr800001','PinController@tr800001');
Route::post('/pinDo','PinController@checkpin');
//Orderbook
Route::get('/curPrice','OrderbookController@index');
Route::post('/curPriceTr100000','OrderbookController@tr100000');
Route::post('/curPriceTr100001','OrderbookController@tr100001');
Route::get('/stkSrc','OrderbookController@index');

Route::get('/logout', 'AuthController@logout');
