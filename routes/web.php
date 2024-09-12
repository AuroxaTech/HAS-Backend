<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return "Cleared!";
});
//Route::get('/logout', [ApiController::class, 'logout']);

//Route::get('/home', function () {
//    return view('home');
//});
Route::get('/test', function () {
    return view('test');
});