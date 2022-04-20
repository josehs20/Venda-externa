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

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('venda', App\Http\Controllers\VendaController::class)->middleware('vendedor');
Route::delete('/deleta_item_carrinho/{item?}', [App\Http\Controllers\VendaController::class, 'destroy_item'])->middleware('vendedor')->name('destroy_item');
Route::get('/itens_carrinho/{user_id?}/{msg?}', [App\Http\Controllers\VendaController::class, 'itens_carrinho'])->Middleware('vendedor')->name('itens_carrinho');
Route::get('/busca_produto/', [App\Http\Controllers\VendaController::class, 'busca_produto_ajax'])->Middleware('vendedor')->name('busca_produto_ajax');
Route::put('/salvar_venda',  [App\Http\Controllers\VendaController::class, 'salvar_venda'])->middleware('vendedor')->name('salvar_venda');
Route::put('/unifica_valor_Itens/{carrinho?}', [App\Http\Controllers\VendaController::class, 'unifica_valor_Itens'])->Middleware('vendedor')->name('unifica_valor_Itens');
Route::put('/zera_desconto/{carrinho?}', [App\Http\Controllers\VendaController::class, 'zera_desconto'])->Middleware('vendedor')->name('zera_desconto');
Route::get('/venda_finalizada/', [App\Http\Controllers\VendaController::class, 'venda_finalizada'])->Middleware('vendedor')->name('venda_finalizada');
Route::put('/finaliza_venda/{carrinho?}', [App\Http\Controllers\VendaController::class, 'finaliza_venda'])->Middleware('vendedor')->name('finaliza_venda');
Route::get('vendas_finalizadas', [App\Http\Controllers\VendaController::class, 'vendas_finalizadas'])->Middleware('vendedor')->name('vendas_finalizadas');
//Route::resource('vendedor.cliente', App\Http\Controllers\VendedorClienteController::class)->middleware('vendedor');

Route::resource('clientes', App\Http\Controllers\ClienteController::class)->middleware('vendedor');
Route::get('/busca_cliente', [App\Http\Controllers\ClienteController::class, 'busca_cliente_ajax'])->middleware('vendedor')->name('busca_cliente_ajax');
Route::get('venda_salva',  [App\Http\Controllers\ClienteController::class, 'venda_salva'])->middleware('vendedor')->name('venda_salva');
Route::put('substitui_carrinho/{carrinho_id?}', [App\Http\Controllers\ClienteController::class, 'substitui_carrinho'])->middleware('vendedor')->name('substitui_carrinho');
Route::post('add_observacao/{cliente?}', [App\Http\Controllers\ClienteController::class, 'add_observacao'])->middleware('vendedor')->name('add_observacao');
Route::delete('/deleta_obs/{observacao}',  [App\Http\Controllers\ClienteController::class, 'deleta_obs_ajax'])->middleware('vendedor')->name('deleta_obs');
