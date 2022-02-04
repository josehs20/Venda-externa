<?php

use GuzzleHttp\Middleware;
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

Route::get('/login', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/principal', [App\Http\Controllers\VendaController::class, 'index'])->Middleware('vendedor')->name('principal');
Route::post('/carrinho', [App\Http\Controllers\VendaController::class, 'store'])->Middleware('vendedor')->name('carrinho');