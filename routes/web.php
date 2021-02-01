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
Route::post('/api/user/store', 'UserController@store');
Route::post('/api/user/login', 'UserController@login');
Route::put('/api/user/update', 'UserController@update');
Route::delete('/api/user/destroy', 'UserController@destroy');



///ADDRESS
Route::post('/api/address/store', 'AddressController@store');
