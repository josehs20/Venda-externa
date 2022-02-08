<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\CarrinhoItem;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Ui\Presets\React;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $produtos = Produto::where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$request->produto}%'")->orderBy('nome')->paginate(20);

        return view('home', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $produto_id)
    {
        $produto = Produto::find($produto_id);

        $check = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        $desconto_final = $request->desc_tipo == 'Porcentagem' ? ($request->qtd_desconto / 100) * ($request->quantidade * $produto->preco) : $request->qtd_desconto;

        if ($produto) {
            if (!$check) {
                
                $car = new Carrinho();
                $car->user_id = auth()->user()->id;
                $car->status = 'Aberto';
                $car->save();
            }
           // dd($check);
            $itens = new CarrinhoItem();
            $itens->produto_id    = $produto->id;
            $itens->carrinho_id   = $check->id ? $check->id : $car->id;
            $itens->alltech_id    = $produto->alltech_id;
            $itens->nome          = $produto->nome;
            $itens->preco         = $produto->preco;
            $itens->quantidade    = $request->quantidade;
            $itens->desconto      = $desconto_final;
            $itens->tipo_desconto = $request->desc_tipo;
            $itens->valor         = $request->qtd_desconto ? ($produto->preco * $request->quantidade) - $desconto_final : $produto->preco * $request->quantidade;
            $itens->save();
        }
        
        Session::flash('message', "Adicionado Com Sucesso!!");
        
        return redirect()->back();      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
