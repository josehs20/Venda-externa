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
Route::delete('deleta_item_carrinho/{item?}', [App\Http\Controllers\VendaController::class, 'destroyItem'])->middleware('vendedor')->name('destroyItem');
Route::get('/itens_carrinho/{user_id?}/{msg?}', [App\Http\Controllers\VendaController::class, 'itens_carrinho'])->Middleware('vendedor')->name('itens_carrinho');
Route::get('/busca_produto/', [App\Http\Controllers\VendaController::class, 'busca_produto_ajax'])->Middleware('vendedor')->name('busca_produto_ajax');
Route::put('salvar_venda',  [App\Http\Controllers\VendaController::class, 'salvar_venda'])->middleware('vendedor')->name('salvar_venda');
Route::put('/unifica_valor_Itens/{carrinho?}', [App\Http\Controllers\VendaController::class, 'unifica_valor_Itens'])->Middleware('vendedor')->name('unifica_valor_Itens');
Route::get('busca_cliente', [App\Http\Controllers\VendaController::class, 'busca_cliente_ajax'])->middleware('vendedor')->name('busca_cliente_ajax');
Route::put('/zeraDesconto/{carrinho?}', [App\Http\Controllers\VendaController::class, 'zeraDesconto'])->Middleware('vendedor')->name('zeraDesconto');

Route::delete('/deleta_obs/{observacao}',  [App\Http\Controllers\VendedorClienteController::class, 'deleta_obs_ajax'])->middleware('vendedor')->name('deleta_obs');
Route::get('venda_salva',  [App\Http\Controllers\VendedorClienteController::class, 'venda_salva'])->middleware('vendedor')->name('venda_salva');
Route::put('substitui_carrinho/{carrinho_id?}', [App\Http\Controllers\VendedorClienteController::class, 'substitui_carrinho'])->middleware('vendedor')->name('substitui_carrinho');

Route::resource('vendedor.cliente', App\Http\Controllers\VendedorClienteController::class)->middleware('vendedor');