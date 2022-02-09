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
     
        $produtos = Produto::where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(20);

        $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        return view('home', compact('produtos', 'count_item'));
    }
    public function itens_carrinho()
    {
        $itens = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->first();
        $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->first();
        return view('itemCarrinho', compact('itens', 'count_item'));
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

        $up_carrinho = CarrinhoItem::where('produto_id', $produto_id)->first();

        $desconto_final = $request->desc_tipo == 'Porcentagem' ? ($request->qtd_desconto / 100) * ($request->quantidade * $produto->preco) : $request->qtd_desconto;

        if (!$check) {

            $car = new Carrinho();
            $car->user_id = auth()->user()->id;
            $car->status = 'Aberto';
            $car->save();
        }
        if ($produto) {

            if ($up_carrinho) {

                $up_qtd = ($request->quantidade + $up_carrinho->quantidade);

                if ($request->qtd_desconto) {
                    $desconto_final = $request->desc_tipo == 'Porcentagem'
                        ? ($request->qtd_desconto / 100) * ($up_qtd * $produto->preco) : $request->qtd_desconto;
                } else {
                    $desconto_final = $request->desc_tipo == 'Porcentagem' && $up_carrinho->tipo_desconto == 'Porcentagem'
                        ? ($up_carrinho->qtd_desconto / 100) * ($up_qtd * $produto->preco) : $request->qtd_desconto;
                }

                $up_carrinho->update([
                    'quantidade' => $up_qtd,
                    'tipo_desconto' => $request->desc_tipo,
                    'qtd_desconto' => $request->qtd_desconto ? $request->qtd_desconto : $up_carrinho->qtd_desconto,
                    'valor_desconto' => $desconto_final,
                    'valor'      => ($produto->preco * $up_qtd) - $desconto_final,
                ]);
            } else {
                $itens = new CarrinhoItem();
                $itens->produto_id     = $produto->id;
                $itens->carrinho_id    = !$check ? $car->id : $check->id;
                $itens->alltech_id     = $produto->alltech_id;
                $itens->nome           = $produto->nome;
                $itens->quantidade     = $request->quantidade;
                $itens->preco          = $produto->preco;
                $itens->tipo_desconto  = $request->desc_tipo;
                $itens->valor_desconto = $desconto_final;
                $itens->qtd_desconto   = $request->qtd_desconto ? $request->qtd_desconto : null;
                $itens->valor          = $request->qtd_desconto ? ($produto->preco * $request->quantidade) - $desconto_final : ($produto->preco * $request->quantidade);
                $itens->save();
            }
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
