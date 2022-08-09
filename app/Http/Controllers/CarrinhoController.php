<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CarrinhoController extends Controller
{

    public function index()
    {
        
        $clientes_user = Cliente::where('loja_id', auth()->user()->loja_id)->orderBy('nome')->take(100)->get();

        $carrinho = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        return view('vendedor.itemCarrinho', compact('carrinho', 'clientes_user', 'msg'));
    }
}
