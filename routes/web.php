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
    // if (auth()->user()) {
    //     return redirect()->route('venda.index');
    // }
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('vendedor')->group(function () {
   
    //venda
    Route::resource('venda', App\Http\Controllers\VendaController::class);
    Route::put('/unifica-desconto', [App\Http\Controllers\VendaController::class, 'unifica_desconto'])->name('unifica_desconto');
    Route::put('/zerar-desconto', [App\Http\Controllers\VendaController::class, 'zerar_desconto'])->name('zerar_desconto');
    Route::delete('/deleta-item/{item?}', [App\Http\Controllers\VendaController::class, 'deleta_item'])->name('deleta_item');
    Route::put('/salvar-venda',  [App\Http\Controllers\VendaController::class, 'salvar_venda'])->name('salvar_venda');
    Route::put('/finaliza-venda/{carrinho?}', [App\Http\Controllers\VendaController::class, 'finaliza_venda'])->name('finaliza_venda');
    Route::put('/desconto-invalido/{carrinho?}', [App\Http\Controllers\VendaController::class, 'desc_invalido'])->name('descInvalido');
   
    //carrinho
    Route::put('/substitui-carrinho/{carrinho_id?}', [App\Http\Controllers\CarrinhoController::class, 'substitui_carrinho'])->name('substitui_carrinho');
    Route::get('/home-carrinho', [App\Http\Controllers\CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::get('/itens-carrinho', [App\Http\Controllers\CarrinhoController::class, 'itens_carrinho'])->name('itens_carrinho');
    Route::get('/vendas-salvas',  [App\Http\Controllers\CarrinhoController::class, 'vendas_salvas'])->name('vendas_salvas');
    Route::get('vendas-finalizadas', [App\Http\Controllers\CarrinhoController::class, 'vendas_finalizadas'])->name('vendas_finalizadas');
    Route::get('/vendas-invalidas', [App\Http\Controllers\CarrinhoController::class, 'vendas_invalidas'])->name('vendas_invalidas');

    
    // Route::delete('/deleta_item_carrinho/{item?}', [App\Http\Controllers\VendaController::class, 'destroy_item'])->name('destroy_item');
    // Route::get('/itens_carrinho', [App\Http\Controllers\VendaController::class, 'itens_carrinho'])->name('itens_carrinho');
    // Route::get('/busca_produto', [App\Http\Controllers\VendaController::class, 'busca_produto_ajax'])->name('busca_produto_ajax');
    // Route::put('/salvar_venda',  [App\Http\Controllers\VendaController::class, 'salvar_venda'])->name('salvar_venda');
    // Route::put('/unifica_valor_Itens/{carrinho?}', [App\Http\Controllers\VendaController::class, 'unifica_valor_Itens'])->name('unifica_valor_Itens');
    // Route::put('/zera_desconto/{carrinho?}', [App\Http\Controllers\VendaController::class, 'zera_desconto'])->name('zera_desconto');
    // Route::get('/venda_finalizada', [App\Http\Controllers\VendaController::class, 'venda_finalizada'])->name('venda_finalizada');
    // Route::put('/finaliza_venda/{carrinho?}', [App\Http\Controllers\VendaController::class, 'finaliza_venda'])->name('finaliza_venda');
    // Route::put('/desconto-invalido/{carrinho?}', [App\Http\Controllers\VendaController::class, 'desc_invalido'])->name('descInvalido');
    // Route::get('/venda-aprovada/{carrinho?}', [App\Http\Controllers\VendaController::class, 'venda_aprovada'])->name('venda_aprovada');
    // Route::put('/finalizavenda-aprovada/{carrinho?}', [App\Http\Controllers\VendaController::class, 'finaliza_venda_aprovada'])->name('finaliza_venda_aprovada');

    //Cliente
    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
    Route::get('/busca-cliente', [App\Http\Controllers\ClienteController::class, 'busca_cliente_ajax'])->name('busca_cliente_ajax');
    // Route::get('/venda-salva',  [App\Http\Controllers\ClienteController::class, 'venda_salva'])->name('venda_salva');
    // Route::put('/substitui_carrinho/{carrinho_id?}', [App\Http\Controllers\ClienteController::class, 'substitui_carrinho'])->name('substitui_carrinho');
    Route::post('/add-observacao/{cliente?}', [App\Http\Controllers\ClienteController::class, 'add_observacao'])->name('add_observacao');
    Route::delete('/deleta-obs/{observacao}',  [App\Http\Controllers\ClienteController::class, 'deleta_obs_ajax'])->name('deleta_obs');

});
