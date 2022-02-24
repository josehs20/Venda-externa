<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendedorClienteController;

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
Route::get('/itens_carrinho/{unificado?}', [App\Http\Controllers\VendaController::class, 'itens_carrinho'])->Middleware('vendedor')->name('itens_carrinho');
Route::put('/unifica_valor_Itens/{itensCarr?}', [App\Http\Controllers\VendaController::class, 'unifica_valor_Itens'])->Middleware('vendedor')->name('unifica_valor_Itens');
Route::get('/busca_produto/', [App\Http\Controllers\VendaController::class, 'busca_produto_ajax'])->Middleware('vendedor')->name('busca_produto_ajax');

Route::post('/adiciona_obs/{cliente?}', [App\Http\Controllers\VendedorClienteController::class, 'adiciona_obs'])->Middleware('vendedor')->name('adiciona_obs');
Route::delete('/deleta_obs/{observacao}',  [App\Http\Controllers\VendedorClienteController::class, 'deleta_obs'])->middleware('vendedor')->name('deleta_obs');

Route::resource('vendedor.cliente', App\Http\Controllers\VendedorClienteController::class)->middleware('vendedor');