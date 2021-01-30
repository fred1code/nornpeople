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


Route::post('/user-create/{data}', function ($data) {
    return '';
});

Route::post('/user-delte/{id}', function ($id) {
    return '';
});

Route::post('/user-update/{data}', function ($data) {
    return '';
});

Route::post('/address-create/{data}', function ($data) {
    return '';
});

Route::post('/address-update/{data}', function ($data) {
    return '';
});

Route::post('/address-delete/{data}', function ($data) {
    return '';
});

Route::post('/login/{data}', function ($data) {
    return '';
});

Route::get('/users', function () {
    return '';
});

Route::get('/address', function () {
    return '';
});
