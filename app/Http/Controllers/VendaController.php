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
    public function loginnovo()
    {
       return view('auth.index');
    }
     public function teste()
    {
        
        $dados['busca'] = Produto::where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$_GET['busca']}%'")->orderBy('nome')->paginate(20);

        //$result = count($resu);
        echo  json_encode($dados);
     
    }
    public function itens_carrinho($unificado = null, $zerar = null)
    {
        $tp_desconto = null;
        // dd($zerar);
        //pega retorno de da função unifica_valor_Itens
        if ($unificado) {
            $itens = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
            $count_item = $itens;
            //dd($itens);
            $valor_itens_total = $itens->carItem()->selectRaw("sum(preco * quantidade) total")->where('carrinho_id', $itens->id)->first();
            $valor_itens_desconto = $itens->total_desconto;

            $total_desconto_valor = $itens->desconto_valor;
            $tp_desconto = $itens->tp_desconto_unificado;

            return view('itemCarrinho', compact('itens', 'count_item', 'valor_itens_total', 'valor_itens_desconto', 'total_desconto_valor', 'tp_desconto'));
        } else {

            $itens = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->first();
            $count_item = $itens;

            //valores com e sem desconto
            $valor_itens_total = $itens ? $itens->carItem()->selectRaw("sum(preco * quantidade) total")->where('carrinho_id', $itens->id)->first() : null;
            $valor_itens_desconto = $itens ? $itens->carItem()->sum('valor') : null;

            $total_desconto_valor = $itens ? $itens->carItem()->where('carrinho_id', $itens->id)->sum('valor_desconto') : null;

            if ($itens) {
                $itens->update([
                    'desconto_valor' => null,
                    'desconto_qtd' => null,
                    'tp_desconto_unificado' => 'Não Unificado',
                    'total_desconto' => null,
                    'total' => $valor_itens_desconto,
                ]);
            }
            return view('itemCarrinho', compact('itens', 'count_item', 'valor_itens_total', 'total_desconto_valor', 'valor_itens_desconto', 'tp_desconto'));
        }
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
    public function store()
    {
        $check = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        $produto = Produto::find($_POST['id']);
        $up_carrinho = $check ? CarrinhoItem::where('carrinho_id', $check->id)->where('produto_id', $produto->id)->first() : null;

        if (!$check) {
            $car = new Carrinho();
            $car->user_id = auth()->user()->id;
            $car->status = 'Aberto';
            $car->save();
        }

        if ($produto) {
            if ($up_carrinho) {

                $up_carrinho->update([
                    'quantidade' => $up_carrinho->quantidade + 1,
                ]);

                $dado['produto_adicionado'] = $produto->nome;
                $dado['ok'] = "add";
                echo json_encode($dado);
            } else {
                $itens = new CarrinhoItem();
                $itens->produto_id     = $produto->id;
                $itens->carrinho_id    = !$check ? $car->id : $check->id;
                $itens->alltech_id     = $produto->alltech_id;
                $itens->nome           = $produto->nome;
                $itens->preco           = $produto->preco;
                $itens->save();

                $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                    ->where('status', 'Aberto')->first();

                $dado['count_item'] = $count_item->carItem->count();
                $dado['produto_adicionado'] = $produto->nome;
                $dado['ok'] = true;
                echo json_encode($dado);
            }
        }
        //$produto = Produto::find($produto_id);

        // $check = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        // $up_carrinho = $check ? CarrinhoItem::where('carrinho_id', $check->id)->where('produto_id', $produto_id)->first() : null;

        // $desconto_final = $request->desc_tipo == 'Porcentagem' ? ($request->qtd_desconto / 100) * ($request->quantidade * $produto->preco) : $request->qtd_desconto;

        // if (!$check) {

        //     $car = new Carrinho();
        //     $car->user_id = auth()->user()->id;
        //     $car->status = 'Aberto';
        //     $car->save();
        // }

        // if ($produto) {

        //     if ($up_carrinho) {

        //         $up_qtd = ($request->quantidade + $up_carrinho->quantidade);

        //         if ($request->qtd_desconto) {
        //             $desconto_final = $request->desc_tipo == 'Porcentagem'
        //                 ? ($request->qtd_desconto / 100) * ($up_qtd * $produto->preco) : $request->qtd_desconto;
        //         } else {
        //             $desconto_final = $request->desc_tipo == 'Porcentagem' && $up_carrinho->tipo_desconto == 'Porcentagem'
        //                 ? ($up_carrinho->qtd_desconto / 100) * ($up_qtd * $produto->preco) : $request->qtd_desconto;
        //         }
        //         if (!$this->verifica_custo_venda($produto, $desconto_final, $up_qtd)) {
        //             Session::flash('message', "Não Autorizado Custo Maior Que Venda!!");

        //             return redirect()->back();
        //         }

        //         $up_carrinho->update([
        //             'quantidade' => $up_qtd,
        //             'tipo_desconto' => $request->desc_tipo,
        //             'qtd_desconto' => $request->qtd_desconto ? $request->qtd_desconto : $up_carrinho->qtd_desconto,
        //             'valor_desconto' => $desconto_final,
        //             'valor'      => ($produto->preco * $up_qtd) - $desconto_final,
        //         ]);
        //     } else {
        //         if (!$this->verifica_custo_venda($produto, $desconto_final, $request->quantidade)) {
        //             Session::flash('message', "Não Autorizado Custo Maior Que Venda!!");

        //             return redirect()->back();
        //         }

        //         $itens = new CarrinhoItem();
        //         $itens->produto_id     = $produto->id;
        //         $itens->carrinho_id    = !$check ? $car->id : $check->id;
        //         $itens->alltech_id     = $produto->alltech_id;
        //         $itens->nome           = $produto->nome;
        //         $itens->quantidade     = $request->quantidade;
        //         $itens->preco          = $produto->preco;
        //         $itens->tipo_desconto  = $request->desc_tipo;
        //         $itens->valor_desconto = $desconto_final;
        //         $itens->qtd_desconto   = $request->qtd_desconto ? $request->qtd_desconto : null;
        //         $itens->valor          = $request->qtd_desconto ? ($produto->preco * $request->quantidade) - $desconto_final : ($produto->preco * $request->quantidade);
        //         $itens->save();
        //     }
        // }

        // Session::flash('message', "Adicionado Com Sucesso!!");

        // return redirect()->back();
        return;
    }
    public function verifica_custo_venda($produto, $desconto_final, $quantidade)
    {
        $custo = ($produto->custo * $quantidade);
        $preco_final = ($produto->preco * $quantidade) - $desconto_final;

        if (($preco_final - $custo) <= 0) {

            return false;
        } else {
            return true;
        }
    }

    public function unifica_valor_Itens(Request $request, $itensCarr)
    {
        $itens_carr_valor = CarrinhoItem::selectRaw("sum(preco * quantidade) total")->where('carrinho_id', $itensCarr)->first();

        $id_itens = CarrinhoItem::with('produto')->where('carrinho_id', $itensCarr)->get();
        $custo = 0;
        foreach ($id_itens as $value) {

            $custo +=  ($value->produto[0]->custo * $value->quantidade);
        }
        if ($request->qtd_unificado) {
            $desconto_final = $request->tipo_unificado == 'Porcentagem'
                ? ($request->qtd_unificado / 100) * $itens_carr_valor->total : $request->qtd_unificado;
        }

        $preco_final = ($itens_carr_valor->total - $desconto_final);
        // dd($preco_final - $custo);
        if ($preco_final <= $custo) {
            Session::flash('message', "Não Autorizado Custo Maior Que Venda!!");

            return redirect()->back();
            return false;
        } else {
            Carrinho::find($itensCarr)->update([
                'desconto_valor' => $desconto_final,
                'desconto_qtd' => $request->qtd_unificado,
                'tp_desconto_unificado' => $request->tipo_unificado == 'Porcentagem' ? 'Porcentagem_unificado' : 'Dinheiro_unificado',
                'total_desconto' => $preco_final,
                'total' => $itens_carr_valor->total,
            ]);
            Session::flash('message', "Desconto Autorizado!!");
            return redirect(route('itens_carrinho', ['unificado' => 1]));
            return true;
        }

        //  dd($request->qtd_unificado);
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

    // public function teste(Request $request)
    // {
    //     $produtos = Produto::where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(20);

    //     $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

    //     return view('home', compact('produtos', 'count_item'));
    // }
}
