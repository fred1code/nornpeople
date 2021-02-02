<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});
////


//////////////API
//USERS
Route::post('/api/user/login', 'UserController@login');


Route::get('/api/user/all', 'UserController@list')->middleware(ApiAuthMiddleware::class);
Route::post('/api/user/store', 'UserController@store');
Route::put('/api/user/update', 'UserController@update')->middleware(ApiAuthMiddleware::class);
Route::delete('/api/user/destroy', 'UserController@destroy');



///ADDRESS
Route::get('/api/address/all', 'AddressController@list')->middleware(ApiAuthMiddleware::class);
Route::get('/api/address/user', 'AddressController@userAddress');
Route::post('/api/address/store', 'AddressController@store');
Route::put('/api/address/update', 'AddressController@update')->middleware(ApiAuthMiddleware::class);
Route::delete('/api/address/destroy', 'AddressController@destroy');
