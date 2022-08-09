<?php

namespace App\Http\Controllers;

use App\Jobs\ExportaVendaJob;
use App\Models\Carrinho;
use App\Models\CarrinhoItem;
use App\Models\Cliente;
use App\Models\Estoque;
use App\Models\Igrade;
use App\Models\Produto;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/estoques/produtos/?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,un
        &filtro_produto=nome:like:%'.$request->nome.'%');

        $item_carrinho = [];
        // $produtos_carrinho_quantidade['itemCarrinhoGrade'][0] = null;

        // $produtos = Produto::with('grades', 'estoque')->where('loja_id', auth()->user()->loja_id)
        //     ->where('situacao', 'A')->whereRaw("nome like '%{$request->nome}%'")->whereNotNull('preco')
        //     ->whereNotNull('refcia')->whereHas('estoque', function (Builder $query) {
        //         $query->whereNotNull('codbar');
        //     })->orderBy('nome')->paginate(30);
        $estoques = $this->paginate($response->object());
        $estoques->withPath('/venda?');
     
        // $carrinho = Carrinho::with('carItens')->where('user_id', auth()->user()->id)->where('status', 'aberto')->first();
        $carrinho = $this->veririfica_carrinho_aberto();

        if ($carrinho) {
            foreach ($carrinho->carItens as $key => $item) {
                $item_carrinho[$item->estoque_id_alltech]['quantidade'] = $item->quantidade;
            }
        }
      
        return view('home', compact('estoques', 'carrinho', 'item_carrinho'));
    }

    public function paginate($items, $perPage = 50, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    // public function busca_produto_ajax()
    // {
    //     $dados['produtos'] = Produto::with('grades')->where('loja_id', auth()->user()->loja_id)
    //         ->where('situacao', 'A')->whereRaw("nome like '%{$_GET['busca']}%'")->orderBy('nome')
    //         ->take(30)->get();
    //     echo  json_encode($dados);
    // }

    public function itens_carrinho($user_id = null, $msg = null)
    {
        $clientes_user = Cliente::where('loja_id', auth()->user()->loja_id)->orderBy('nome')->take(100)->get();

        $carrinho = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        return view('vendedor.itemCarrinho', compact('carrinho', 'clientes_user', 'msg'));
    }

    public function vendas_finalizadas(Request $request)
    {

        $busca = $request->nome_n_pedido;

        $carrinhos = Carrinho::with('carItem', 'cliente')->where('user_id', auth()->user()->id)
            ->where('status', 'like', 'fechado')->where('n_pedido', 'like', '%' . $busca . '%')
            ->orWhereHas('cliente', function (Builder $query) use ($busca) {

                $query->where('loja_id', auth()->user()->loja_id)->where('nome', 'like', '%' . $busca . '%');
            })->get()->reject(function ($c) {
                return $c->user_id != auth()->user()->id;
            });

        $carrinhos = $carrinhos->count() ? $carrinhos->toQuery()->paginate(30) : [];

        return view('vendedor.vendas-finalizadas', compact('carrinhos'));
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
    public function store() //metodo usado pelo ajax //usada só em home
    {
        // $carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        // $produto = Produto::find($_POST['id']);
        // $item = $carrinho ? CarrinhoItem::where('carrinho_id', $carrinho->id)->where('produto_id', $produto->id)->first() : null;
        $item_dados = [
            'quantidade' => $_POST['quantidade'],
            'preco' => $_POST['preco'],
            'valor' => $_POST['preco'] * $_POST['quantidade'],
            'estoque_id_alltech' =>  $_POST['id'],
        ];

        $carrinho = $this->veririfica_carrinho_aberto();

        $response = $this->adiciona_item_carrinho($carrinho, $item_dados);


        return json_encode($response);
        //variavel para verificar se o produto contém grade ou não via ajax
        // $i_grade_qtd = $_POST['i_grade_qtd'];
        // $qtd =  $_POST['qtd'];

        //caso carrinho não tenha nenhum carrinho aberto já é aberto automaticamente
        // if (!$carrinho) {

        //     $carrinho = new Carrinho();
        //     $carrinho->user_id = auth()->user()->id;
        //     $carrinho->status = 'Aberto';
        //     $carrinho->save();

        //     if ($i_grade_qtd) {
        //         $this->add_item_grade($i_grade_qtd, $produto, $carrinho);
        //     } else {
        //         $this->add_item($produto, $carrinho, $qtd);
        //     }

        //     $this->atualiza_carrinho_desconto_unico($carrinho);

        //     $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
        //         ->where('status', 'Aberto')->first();

        //     $dado['count_item'] = $count_item->carItem->count();
        //     $dado['produto_adicionado'] = $produto->nome;
        //     $dado['ok'] = true;
        //     $dado['msg'] = 'produto novo e carrinho aberto';
        //     echo json_encode($dado);
        //     return;
        // }
        // if ($i_grade_qtd) {
        //     //condição tem qeu ser sa
        //     $dado['proditem'] =   $this->add_item_grade($i_grade_qtd, $produto, $carrinho);
        //     // atualização de desconto unico ou sem desconto para itens diferentes que são inseridos no carrinho
        //     $this->atualiza_carrinho_desconto_unico($carrinho);

        //     //conta quantidade no carrinho ajax
        //     $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
        //         ->where('status', 'Aberto')->first();

        //     $dado['count_item'] = $count_item->carItem->count();
        //     $dado['produto_adicionado'] = $produto->nome;
        //     $dado['ok'] = true;
        //     $dado['msg'] = 'produto com grade inserido';
        //     echo json_encode($dado);

        //     return;
        // }
        // if (!$item) {

        //     $this->add_item($produto, $carrinho, $qtd);

        //     $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
        //         ->where('status', 'Aberto')->first();
        //     $this->atualiza_carrinho_desconto_unico($carrinho);
        //     $dado['count_item'] = $count_item->carItem->count();
        //     $dado['produto_adicionado'] = $produto->nome;
        //     $dado['ok'] = true;
        //     $dado['msg'] = "item novo sem grade";
        //     echo json_encode($dado);
        //     return;
        // } else {
        //     $quantidade = $qtd;

        //     if ($item->tipo_desconto) {

        //         $desconto_final = $item->tipo_desconto == 'porcento' ? ($item->qtd_desconto / 100) * ($quantidade * $item->preco) : $item->qtd_desconto;
        //         $valor_final_item = ($quantidade * $item->preco) - $desconto_final;
        //         //dd($desconto_final);
        //         $item->update([
        //             'quantidade' => $quantidade,
        //             'qtd_desconto' => $item->qtd_desconto,
        //             'valor_desconto' => $desconto_final,
        //             'valor'          => $valor_final_item,
        //         ]);
        //         $this->atualiza_carrinho_desconto_parcial($item);

        //         $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
        //             ->where('status', 'Aberto')->first();

        //         $dado['count_item'] = $count_item->carItem->count();
        //         $dado['produto_adicionado'] = $produto->nome;
        //         $dado['ok'] = "add";
        //         $dado['msg'] = "item atualizado sem grade";
        //         echo json_encode($dado);
        //         return;
        //     } else {

        //         $item->update([
        //             'quantidade' => $quantidade,
        //             'valor' => $item->preco * $quantidade,
        //         ]);

        //         $this->atualiza_carrinho_desconto_unico($carrinho);
        //         $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
        //             ->where('status', 'Aberto')->first();

        //         $dado['count_item'] = $count_item->carItem->count();
        //         $dado['produto_adicionado'] = $produto->nome;
        //         $dado['ok'] = "add";
        //         $dado['msg'] = "Quantidade Atualizada";
        //         echo json_encode($dado);
        //     }
        // }
    }

    public function veririfica_carrinho_aberto()
    {
        $carrinho = Carrinho::with('carItens')->where('user_id', auth()->user()->id)->where('status', 'aberto')->first();

        if (!$carrinho) {
            $carrinho = Carrinho::create([
                'status' => 'aberto',
                'user_id' => auth()->user()->id,
            ]);
        }

        return $carrinho;
    }

    public function adiciona_item_carrinho($carrinho, $item_dados)
    {
        $item_carrinho = $carrinho->carItens()->where('estoque_id_alltech', $item_dados['estoque_id_alltech'])->first();

        if (!$item_carrinho) {
            $item_carrinho = $carrinho->carItens()->create($item_dados);
            return ['itens' => $item_carrinho, 'dados' => $this->get_itens_carrinho(), 'msg' => 'Item adicionado com sucesso'];
        } else {
            $item_carrinho = $item_carrinho->update($item_dados);
            return  ['itens' => $item_carrinho, 'dados' => $this->get_itens_carrinho(), 'msg' => 'Item atualizado com sucesso'];
        }
    }

    public function get_itens_carrinho() //ajax
    {
        $carrinho = Carrinho::with('carItens')->where('status', 'aberto')->where('user_id', auth()->user()->id)->first();

        $ids_estoque = [];

        foreach ($carrinho->carItens as $key => $v) {
            $ids_estoque[] = $v->estoque_id_alltech;
        }

        //prepara url, se busca for por algum identificador id ou codbar pode passar 
        //um array separando por virgula
        $ids_estoque = implode(',', $ids_estoque);

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/estoques/produtos/?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,un
            &filtro_estoque=id:=:' . $ids_estoque);

        $response = $response->collect();
        foreach ($carrinho->carItens as $key => $item) {
            $produtos_api = $response->firstWhere('id', $item->estoque_id_alltech);
            $item['nome'] = $produtos_api['produto']['nome'];
            $item['tam'] = $produtos_api['i_grade'] ? $produtos_api['i_grade']['tam'] : null;
        }
   
        //retorna carrinho com itens já com o nome de cada um 
        return json_encode($carrinho);
    }

    //
    public function get_produtos_alltech_api($atr_estoque, $atr_produto = null)
    {
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/produtos/estoques?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,un
        &filtro=id:=:4175');
    }

    public function add_item($produto, $carrinho, $qtd)
    {
        $carrinho->carItem()->create([
            'produto_id' => $produto->id,
            'preco'      => $produto->preco,
            'quantidade' => $qtd,
            'valor'      => $produto->preco * $qtd,
        ]);
    }
    public function add_item_grade($i_grade_qtd, $produto, $carrinho)
    {
        foreach ($i_grade_qtd as $key => $value) {

            if (!empty($value)) {
                $i_grade = Igrade::find($value[0]);
                $item = $carrinho->carItem()->where('produto_id', $produto->id)->where('i_grade_id', $i_grade->id)->first();
                // $item = $item && $item->i_grade_id == $value[0] ? true : false;

                //calcula preco novo com grade de produto
                if ($i_grade->fator != '0') {
                    $soma_final = $i_grade->tipo == '%' ? ($i_grade->fator / 100) * ($produto->preco) : $i_grade->fator;
                    $produto_preco = $produto->preco + $soma_final;
                    $dados[] = $produto_preco;
                } else {
                    $produto_preco = $produto->preco;
                }
                //caso tenha o mesmo id  de produto e grade diferente entra no if "Cria item novo"
                if (!$item) {

                    $carrinho->carItem()->create([
                        'produto_id'    => $produto->id,
                        'preco'         => $produto_preco,
                        'quantidade'    => $value[1],
                        'valor'         => $produto_preco * $value[1],
                        'i_grade_id'    => $value[0],
                    ]);
                } else {
                    $item->update([
                        'preco'         => $produto_preco,
                        'quantidade'    => $value[1],
                        'valor'         => $produto_preco * $value[1],
                    ]);
                }
            }
        }
        //echo json_encode($dado['proditem']);
        return $dado['proditem'] = $item;
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

    public function unifica_valor_Itens(Request $request, $carrinho)
    {
        $carrinho = Carrinho::with('carItem')->find($carrinho);
        //dd($request->all());
        foreach ($carrinho->carItem as $item) {
            $item->update([
                'tipo_desconto' => null,
                'qtd_desconto' => null,
                'valor_desconto' => null,
                'valor' => $item->preco * $item->quantidade,
            ]);
            $valor_itens_bruto[] = $item->valor;
        }
        $desconto_final = $request->tipo_unificado == 'porcento' ? ($request->qtd_unificado / 100) * (array_sum($valor_itens_bruto)) : $request->qtd_unificado;
        $valor_final_item = array_sum($valor_itens_bruto) - $desconto_final;

        $carrinho->update([
            'valor_desconto_sb_venda' => null,
            'tp_desconto_sb_venda' => null,
            'desconto_qtd_sb_venda' => null,
            'desconto_qtd' => $request->qtd_unificado,
            'tp_desconto' => $request->tipo_unificado == 'porcento' ? 'porcento_unico' : 'dinheiro_unico',
            'valor_desconto' => $desconto_final,
            'valor_bruto' => array_sum($valor_itens_bruto),
            'total' => $valor_final_item,
        ]);
        Session::flash('success', 'Desconto Unificado Com Sucesso');
        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
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
    public function edit($venda)
    {
        //dd($venda);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //update para itens do carrinho 
    public function update(Request $request, $venda)
    {
        //id do item da venda

        $item = CarrinhoItem::find($venda);

        //caso tenha uma quantidade de desconto o desconto é parcial e sempre atualiza a quantidade caso informada 

        if ($request->quantidade && !$request->qtd_desconto) {
            $carrinho = Carrinho::find($item->carrinho_id);

            $item->update([
                'quantidade' => $request->quantidade,
                'valor' => $item->preco * $request->quantidade,
                'qtd_desconto' => null,
                'tipo_desconto' => null,
                'valor_desconto' => null,
            ]);

            $this->atualiza_carrinho_desconto_unico($carrinho);
            Session::flash('success', 'Item Alterado Com Sucesso');
            return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
        } else {

            $item->update([
                'tipo_desconto' => $request->tipo_desconto == 'porcento' ? 'porcento' : 'dinheiro'
            ]);

            $desconto_final = $item->tipo_desconto == 'porcento' ? ($request->qtd_desconto / 100) * ($request->quantidade * $item->preco) : $request->qtd_desconto;
            $valor_final_item = ($request->quantidade * $item->preco) - $desconto_final;
            //dd($desconto_final);
            $item->update([
                'quantidade' => $request->quantidade,
                'qtd_desconto' => $request->qtd_desconto,
                'valor_desconto' => $desconto_final,
                'valor'          => $valor_final_item,
            ]);
            $this->atualiza_carrinho_desconto_parcial($item);
            Session::flash('success', 'Item Alterado Com Sucesso');
            return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
        }
    }
    public function zera_desconto($carrinho)
    {
        $carrinho = Carrinho::with('carITem')->find($carrinho);

        foreach ($carrinho->carItem as $key => $item) {
            $item->update([
                'tipo_desconto' => null,
                'qtd_desconto' => null,
                'valor_desconto' => null,
                'valor' => $item->preco * $item->quantidade,
            ]);
            $valor_itens[] = $item->valor;
        }
        //dd(array_sum($valor_itens));
        $carrinho->update([
            'valor_desconto_sb_venda' => null,
            'tp_desconto_sb_venda' => null,
            'desconto_qtd_sb_venda' => null,
            'desconto_qtd' => null,
            'tp_desconto' => null,
            'valor_desconto' => null,
            'total' => array_sum($valor_itens),
        ]);
        Session::flash('success', 'Desconto Zerado Com Sucesso');
        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($venda)
    {
        Carrinho::find($venda)->delete();

        Session::flash('success', 'Venda Cancelado Com Sucesso');

        return redirect(route('venda.index'));
    }
    public function destroy_item($item)
    {
        $item = CarrinhoItem::find($item);

        if ($item->car->carItem()->count() === 1) {
            $item->car->delete();
            Session::flash('success', 'Todos Itens Retirados');
            return redirect(route('venda.index'));
        }

        $item->delete();
        $carrinho =  $item->car;
        if ($item->car->tp_desconto == 'porcento_unico') {

            $this->atualiza_carrinho_desconto_unico($carrinho);

            Session::flash('success', 'Item Retirado Com Sucesso');

            return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
        } else {
            $this->atualiza_carrinho_desconto_unico($carrinho);

            Session::flash('success', 'Item Retirado Com Sucesso');

            return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
        }
    }

    public function gerar_numero_pedido()
    {
        $num = false;
        $num = '000000';
        $users_loja = User::where('loja_id', auth()->user()->loja_id)->get('id')->map(function ($user) {
            $user_ids =  $user->id;
            return $user_ids;
        });

        $n_pedido = Carrinho::whereIn('user_id', $users_loja)->orderBy('n_pedido', 'desc')->first('n_pedido');

        $num .= $n_pedido->n_pedido + 1;
        $num = substr($num, -6);

        return $num;
    }

    public function salvar_venda(Request $request)
    {
        $carrinho_aberto =     Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        $num = false;
        $request->all();
        if (!$carrinho_aberto->n_pedido) {
            //numeração de pedido
            $num = $this->gerar_numero_pedido();
        }

        if ($request->cliente_id) {
            if ($request->salvaSubstitui) {

                $carrinho_aberto->update([
                    'status' => 'Salvo',
                    'n_pedido' => !$carrinho_aberto->n_pedido ? $num : $carrinho_aberto->n_pedido,
                    'cliente_id' => $carrinho_aberto->cliente_id ? $carrinho_aberto->cliente_id : $request->cliente_id,
                ]);

                Carrinho::find($request->salvaSubstitui)->update([
                    'status' => 'Aberto',
                ]);
            } else {

                $carrinho_aberto->update([
                    'status' => 'Salvo',
                    'n_pedido' => !$carrinho_aberto->n_pedido ? $num : $carrinho_aberto->n_pedido,
                    'cliente_id' => $carrinho_aberto->cliente_id ? $carrinho_aberto->cliente_id : $request->cliente_id,
                ]);
            }
        }
        Session::flash('success', 'Itens Salvos Com Sucesso');

        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
    }

    public function atualiza_carrinho_desconto_parcial($item)
    {

        $itens = CarrinhoItem::where('carrinho_id', $item->carrinho_id ? $item->carrinho_id : $item)->get();

        foreach ($itens as $item) {
            $valor_itens_desconto[] = $item->valor_desconto;
            $valor_itens_bruto[] = $item->preco * $item->quantidade;
            $valor_itens[] = $item->valor;
        }

        Carrinho::find($item->carrinho_id)->update([
            'valor_desconto_sb_venda' => null,
            'tp_desconto_sb_venda' => null,
            'desconto_qtd_sb_venda' => null,
            'tp_desconto' => 'parcial',
            'valor_desconto' => array_sum($valor_itens_desconto),
            'valor_bruto' => array_sum($valor_itens_bruto),
            'total' => array_sum($valor_itens),
        ]);
    }
    public function atualiza_carrinho_desconto_unico($carrinho)
    {
        $itens = $carrinho->carItem()->get();

        foreach ($itens as $item) {
            $valor_itens_desconto[] = $item->valor_desconto;
            $valor_itens_bruto[] = $item->preco * $item->quantidade;
            $valor_itens[] = $item->valor;
        }
        // dd($valor_itens_bruto);

        //caso tenha algum tipo de desconto pq pode ser usado para funções que insere produto sem nenhum desconto estipulado

        if ($carrinho->tp_desconto == 'porcento_unico' or $carrinho->tp_desconto == 'dinheiro_unico') {
            $desconto_final = $carrinho->tp_desconto == 'porcento_unico' ? ($carrinho->desconto_qtd / 100) * (array_sum($valor_itens)) : $carrinho->desconto_qtd;
            $valor_final_item = array_sum($valor_itens) - $desconto_final;

            $carrinho->update([
                'valor_desconto_sb_venda' => null,
                'desconto_qtd_sb_venda' => null,
                'tp_desconto_sb_venda' => null,
                'valor_desconto' => $desconto_final,
                'valor_bruto' => array_sum($valor_itens),
                'total' => $valor_final_item,
            ]);
            return;
        }

        $carrinho->update([
            'valor_desconto_sb_venda' => null,
            'desconto_qtd_sb_venda' => null,
            'tp_desconto_sb_venda' => null,
            'valor_desconto' => array_sum($valor_itens_desconto),
            'valor_bruto' => array_sum($valor_itens_bruto),
            'total' => array_sum($valor_itens),
        ]);
    }


    public function finaliza_venda(Request $request, $carrinho)
    {
        $cliente = Cliente::where('loja_id', auth()->user()->loja_id)->where('alltech_id', $request->cliente_alltech_id)->orWhere('docto', $request->cliente_alltech_id)->first();
        $upCarr =  Carrinho::find($carrinho);

        $fuso = new DateTimeZone('America/Sao_Paulo');
        $data = new DateTime(date('Ymd'));
        $data = $data->setTimezone($fuso);

        $dadosCarrinho = [
            'valor_desconto' => $upCarr->valor_desconto == '0' ? null : $upCarr->valor_desconto,
            'cliente_id' => $cliente ? $cliente->id : '0000',
            'data' => $data->format('Ymd'),
            'n_pedido'  => $this->gerar_numero_pedido(),
            'total' => $request->hiddenInputValorTotalModal ? $request->hiddenInputValorTotalModal : null,
            'tipo_pagamento' => $request->tipo_pagamento,
            'parcelas' => $request->parcelas ? $request->parcelas : 1,
            'tp_desconto_sb_venda' => $request->tp_desconto_sb_venda == "0" ? null : $request->tp_desconto_sb_venda,
            'valor_desconto_sb_venda' => $request->hiddenInputValorDescontoSobreVendaModal ? $request->hiddenInputValorDescontoSobreVendaModal : null,
            'desconto_qtd_sb_venda' => $request->qtd_desconto_sobre_venda ? $request->qtd_desconto_sobre_venda : null,
            'forma_pagamento' => $request->forma_pagamento ? $request->forma_pagamento : null,
            'valor_entrada' => $request->valor_entrada ? $request->valor_entrada : null,
        ];


        if ($cliente) {

            $upCarr->update($dadosCarrinho);

            $carrinho = Carrinho::with('carItem')->find($carrinho);
            //valida desconto para finalizacao da venda
            if (!$this->valida_desconto_venda($carrinho)) {

                Session::flash('descInvalido', 'Venda não permitida, quantidade de desconto inválida');
                return redirect()->back();
            }

            //dd('b');
            $upCarr->update(['status' => 'fechado']);

            //monta json para exportação
            $this->jsonVendaStorageJob($carrinho);

            Session::flash('success', 'Venda Finalizada Com Sucesso');
            return redirect(route('venda.index'));
        } else {
            Session::flash('error', 'Verifique os dados do cliente');

            return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
        }
    }

    public function finaliza_venda_aprovada($carrinho)
    {
        Carrinho::find($carrinho)->update(['status' => 'fechado']);
        $carrinho = Carrinho::find($carrinho);

        $this->jsonVendaStorageJob($carrinho);
        //  dd($carrinho);
        Session::flash('success', 'Venda Finalizada Com Sucesso');
        return redirect(route('venda.index'));
    }

    public function desc_invalido($carrinho)
    {
        Carrinho::find($carrinho)->update(['status' => 'descInvalido']);

        Session::flash('solicitacaoDesconto');
        return redirect(route('venda.index'));
    }

    public function vendas_invalidas(Request $request)
    {

        $busca = $request->nome_n_pedido;

        $carrinhos = Carrinho::with('carItem', 'cliente')->where('user_id', auth()->user()->id)
            ->where('status', 'like', 'descInvalido')->where('n_pedido', 'like', '%' . $busca . '%')
            ->orWhereHas('cliente', function (Builder $query) use ($busca) {

                $query->where('loja_id', auth()->user()->loja_id)->where('nome', 'like', '%' . $busca . '%');
            })->get()->reject(function ($c) {
                return $c->status == 'Salvo' || $c->status == 'fechado' ||
                    $c->status == 'Aberto';
            });

        $carrinhos = $carrinhos->count() ? $carrinhos->toQuery()->paginate(30) : [];

        return view('vendedor.vendas-invalidas', compact('carrinhos'));
    }

    public function venda_aprovada($carrinho)
    {
        $carrinho = Carrinho::find($carrinho);

        return view('vendedor.venda-aprovada', compact('carrinho'));
    }



    //------------------------------validação e montagem de exportacao-------------------------------//

    public function valida_desconto_venda($carrinho)
    {
        //50%   

        $descontoTotal = $carrinho->valor_desconto + $carrinho->valor_desconto_sb_venda;
        foreach ($carrinho->carItem as $value) {
            $custoTotal[] = $value->produto->custo * $value->quantidade;
            $precoTotal[] = $value->produto->preco * $value->quantidade;
        }

        $lucroMin = array_sum($precoTotal) - ((array_sum($precoTotal) * 30) / 100);
        $lucroMin = array_sum($precoTotal) - $lucroMin;

        $lucro = (array_sum($precoTotal) - array_sum($custoTotal)) - $descontoTotal;

        if ($lucro > $lucroMin) {

            return true;
        }
        // dd($carrinho);
        return false;
    }

    public function jsonVendaStorageJob($carrinho)
    {

        $dados['id'] = $carrinho->id;
        $dados['status'] = $carrinho->status;
        $dados['loja_alltech_id'] = $carrinho->usuario->loja->alltech_id;
        $dados['vendedor_alltech_id'] = $carrinho->usuario->funcionario->alltech_id;
        $dados['cliente'] = $carrinho->cliente->docto;
        $dados['emissao'] = str_replace('-', '', $carrinho->data);
        $dados['qtd_desc'] = $carrinho->desconto_qtd;
        $dados['tipo_desc'] = ($carrinho->tp_desconto == 'parcial') ? 'parcial' : (($carrinho->tp_desconto == 'porcento_unico') ? '%' : (($carrinho->tp_desconto == 'dinheiro_unico') ? '$' : null));
        $dados['v_desc'] = $carrinho->valor_desconto;
        $dados['v_bruto'] = $carrinho->valor_bruto;
        $dados['tipo_pg'] = $carrinho->tipo_pagamento;
        $dados['parcelas'] = $carrinho->parcelas;
        $dados['tp_desc_sb_venda'] = ($carrinho->tp_desconto_sb_venda && $carrinho->tp_desconto_sb_venda == 'porcento') ? '%' : (($carrinho->tp_desconto_sb_venda && $carrinho->tp_desconto_sb_venda == 'dinheiro') ? '$' : null);
        $dados['qtd_desc_sb_venda'] = $carrinho->desconto_qtd_sb_venda ? $carrinho->desconto_qtd_sb_venda : null;
        $dados['v_desc_sb_venda'] = $carrinho->valor_desconto_sb_venda ? $carrinho->valor_desconto_sb_venda : null;
        $dados['forma_pagamento'] = $carrinho->forma_pagamento;
        $dados['valor_entrada'] = $carrinho->valor_entrada;
        $dados['v_total'] = $carrinho->total;

        $estoque = Estoque::where('loja_id', auth()->user()->loja_id)->get();
        $i = 1;
        foreach ($carrinho->carItem as $key => $item) {

            if ($item->i_grade_id) {
                $produto_codbar = $estoque->where('produto_id', $item->produto_id)->where('tam', $item->iGrade->tam)->first();
            } else {
                $produto_codbar = $estoque->where('produto_id', $item->produto_id)->first();
            }
            // ($carrinho->tp_desconto == 'parcial') ? 'parcial' : (($carrinho->tp_desconto == 'porcento_unico') ? '%' : (($carrinho->tp_desconto == 'dinheiro_unico') ? '$' : null));

            $dados['itens'][$i]['codbar'] = ($produto_codbar && $produto_codbar->codbar) ? $produto_codbar->codbar : (($produto_codbar->alltech_id) ? $produto_codbar->alltech_id : null);
            $dados['itens'][$i]['preco'] = $item->preco;
            $dados['itens'][$i]['quantidade'] = $item->quantidade;
            $dados['itens'][$i]['tipo_desconto'] = ($item->tipo_desconto && $item->tipo_desconto == 'porcento') ? '%' : (($item->tipo_desconto && $item->tipo_desconto == 'dinheiro') ? '$' : null);
            $dados['itens'][$i]['qtd_desconto'] = $item->qtd_desconto;
            $dados['itens'][$i]['valor_desconto'] = $item->valor_desconto;
            $dados['itens'][$i]['valor_item'] = $item->valor;

            $i++;
        }
        // dd($dados);
        //monta arquivo para exportação em job
        $json = json_encode($dados, JSON_PRETTY_PRINT);

        // dd(json_encode($dados, JSON_PRETTY_PRINT));
        $dir = $carrinho->usuario->loja->empresa->pasta;

        //Class Job para exportação 
        ExportaVendaJob::dispatch($json, $dir, $carrinho);
        return;
    }
}
