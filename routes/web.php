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

Route::resource('venda', App\Http\Controllers\VendaController::class)->middleware('vendedor');
//Route::get('/principal', [App\Http\Controllers\VendaController::class, 'index'])->Middleware('vendedor')->name('principal');
//Route::post('/carrinho', [App\Http\Controllers\VendaController::class, 'store'])->Middleware('vendedor')->name('carrinho');
Route::get('/itens_carrinho/{unificado?}', [App\Http\Controllers\VendaController::class, 'itens_carrinho'])->Middleware('vendedor')->name('itens_carrinho');
Route::put('/unifica_valor_Itens/{itensCarr?}', [App\Http\Controllers\VendaController::class, 'unifica_valor_Itens'])->Middleware('vendedor')->name('unifica_valor_Itens');
Route::get('/busca_produto/', [App\Http\Controllers\VendaController::class, 'busca_produto_ajax'])->Middleware('vendedor')->name('busca_produto_ajax');
Route::put('salvar_venda',  [App\Http\Controllers\VendaController::class, 'salvar_venda'])->middleware('vendedor')->name('salvar_venda');

Route::delete('/deleta_obs/{observacao}',  [App\Http\Controllers\VendedorClienteController::class, 'deleta_obs_ajax'])->middleware('vendedor')->name('deleta_obs');
Route::get('venda_salva',  [App\Http\Controllers\VendedorClienteController::class, 'venda_salva'])->middleware('vendedor')->name('venda_salva');
Route::put('substitui_carrinho/{carrinho_id?}', [App\Http\Controllers\VendedorClienteController::class, 'substitui_carrinho'])->middleware('vendedor')->name('substitui_carrinho');

Route::resource('vendedor.cliente', App\Http\Controllers\VendedorClienteController::class)->middleware('vendedor');