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
    return view('welcome');
});
////

Route::get('/test', 'PruebasController@index');


//////////////API
//USERS
Route::post('/api/user/login', 'UserController@login');


Route::get('/api/user/all', 'UserController@list');
Route::post('/api/user/store', 'UserController@store');
Route::put('/api/user/update', 'UserController@update');
Route::delete('/api/user/destroy', 'UserController@destroy');



///ADDRESS
Route::get('/api/address/all', 'AddressController@list');
Route::get('/api/address/user', 'AddressController@userAddress');
Route::post('/api/address/store', 'AddressController@store');
Route::put('/api/address/update', 'AddressController@update');
Route::delete('/api/address/destroy', 'AddressController@destroy');
