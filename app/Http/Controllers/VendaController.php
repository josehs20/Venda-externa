<?php

namespace App\Http\Controllers;

use App\Jobs\ExportaVendaJob;
use App\Models\Carrinho;
use App\Models\CarrinhoItem;
use App\Models\Cliente;
use App\Models\Igrade;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $msg = false)
    {
        $produtos_carrinho_quantidade['itemCarrinho'][0] = false;
        $produtos_carrinho_quantidade['itemCarrinhoGrade'][0] = false;

        $produtos = Produto::with('grades')->where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(30);
        $carrinho = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'aberto')->first();

        // dd($carrinho );

        if ($carrinho) {
            foreach ($carrinho->carItem as $key => $item) {

                if ($item->i_grade_id) {
                    $produtos_carrinho_quantidade['itemCarrinhoGrade'][$item->i_grade_id] = $item->quantidade;
                    $produtos_carrinho_quantidade['itemCarrinhoGrade']['produto_id'] = $item->produto_id;
                } else {
                    $produtos_carrinho_quantidade['itemCarrinho'][$item->produto_id] = $item->quantidade;
                  
                }
            }
        }

        return view('home', compact('produtos', 'carrinho', 'produtos_carrinho_quantidade'));
    }

    public function busca_produto_ajax()
    {
        $dados['produtos'] = Produto::with('grades')->where('loja_id', auth()->user()->loja_id)->where('situacao', 'A')->whereRaw("nome like '%{$_GET['busca']}%'")->orderBy('nome')->take(30)->get();

        // foreach ($dados['produtos'] as $p) {
        //     if ($p['grades']) {
        //         $p = $p['grades']['iGrades'];
        //     }
        //     // $dados['busca'];
        // }
        echo  json_encode($dados);
    }

    public function itens_carrinho($user_id = null, $msg = null)
    {
        $clientes_user = Cliente::where('loja_id', auth()->user()->loja_id)->orderBy('nome')->paginate(50);
       
        $carrinho = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
 
        return view('vendedor.itemCarrinho', compact('carrinho', 'clientes_user', 'msg'));
    }

    public function vendas_finalizadas()
    {
        $carrinho = Carrinho::with('carITem')->where('user_id', auth()->user()->id)->where('status', 'fechado')->get();

        return view('vendedor.vendas-finalizadas', compact('carrinho'));
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
        $carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        $produto = Produto::find($_POST['id']);
        $item = $carrinho ? CarrinhoItem::where('carrinho_id', $carrinho->id)->where('produto_id', $produto->id)->first() : null;

        //variavel para verificar se o produto contém grade ou não via ajax
        $i_grade_qtd = $_POST['i_grade_qtd'];
        $qtd =  $_POST['qtd'];

        //caso carrinho não tenha nenhum carrinho aberto já é aberto automaticamente
        if (!$carrinho) {

            $carrinho = new Carrinho();
            $carrinho->user_id = auth()->user()->id;
            $carrinho->status = 'Aberto';
            $carrinho->save();

            if ($i_grade_qtd) {
                $this->add_item_grade($i_grade_qtd, $produto, $carrinho);
            } else {
                $this->add_item($produto, $carrinho, $qtd);
            }

            $this->atualiza_carrinho_desconto_unico($carrinho);

            $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                ->where('status', 'Aberto')->first();

            $dado['count_item'] = $count_item->carItem->count();
            $dado['produto_adicionado'] = $produto->nome;
            $dado['ok'] = true;
            $dado['msg'] = 'produto novo e carrinho aberto';
            echo json_encode($dado);
            return;
        }
        if ($i_grade_qtd) {
            //condição tem qeu ser sa
            $dado['proditem'] =   $this->add_item_grade($i_grade_qtd, $produto, $carrinho);
            // atualização de desconto unico ou sem desconto para itens diferentes que são inseridos no carrinho
            $this->atualiza_carrinho_desconto_unico($carrinho);

            //conta quantidade no carrinho ajax
            $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                ->where('status', 'Aberto')->first();

            $dado['count_item'] = $count_item->carItem->count();
            $dado['produto_adicionado'] = $produto->nome;
            $dado['ok'] = true;
            $dado['msg'] = 'produto com grade inserido';
            echo json_encode($dado);

            return;
        }
        if (!$item) {

            $this->add_item($produto, $carrinho, $qtd);

            $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                ->where('status', 'Aberto')->first();
            $this->atualiza_carrinho_desconto_unico($carrinho);
            $dado['count_item'] = $count_item->carItem->count();
            $dado['produto_adicionado'] = $produto->nome;
            $dado['ok'] = true;
            $dado['msg'] = "item novo sem grade";
            echo json_encode($dado);
            return;
        } else {
            $quantidade = $qtd;

            if ($item->tipo_desconto) {

                $desconto_final = $item->tipo_desconto == 'porcento' ? ($item->qtd_desconto / 100) * ($quantidade * $item->preco) : $item->qtd_desconto;
                $valor_final_item = ($quantidade * $item->preco) - $desconto_final;
                //dd($desconto_final);
                $item->update([
                    'quantidade' => $quantidade,
                    'qtd_desconto' => $item->qtd_desconto,
                    'valor_desconto' => $desconto_final,
                    'valor'          => $valor_final_item,
                ]);
                $this->atualiza_carrinho_desconto_parcial($item);

                $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                    ->where('status', 'Aberto')->first();

                $dado['count_item'] = $count_item->carItem->count();
                $dado['produto_adicionado'] = $produto->nome;
                $dado['ok'] = "add";
                $dado['msg'] = "item atualizado sem grade";
                echo json_encode($dado);
                return;
            } else {

                $item->update([
                    'quantidade' => $quantidade,
                    'valor' => $item->preco * $quantidade,
                ]);

                $this->atualiza_carrinho_desconto_unico($carrinho);
                $count_item = Carrinho::with('carItem')->where('user_id', auth()->user()->id)
                    ->where('status', 'Aberto')->first();

                $dado['count_item'] = $count_item->carItem->count();
                $dado['produto_adicionado'] = $produto->nome;
                $dado['ok'] = "add";
                $dado['msg'] = "Quantidade Atualizada";
                echo json_encode($dado);
            }
        }
    }

    public function add_item($produto, $carrinho, $qtd)
    {
        $carrinho->carItem()->create([
            'produto_id' => $produto->id,
            'preco'      => $produto->preco,
            'quantidade' => $qtd,
            'valor'      => $produto->preco,
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
        // dd($request->all());
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
        //  dd($desconto_final);
        $carrinho->update([
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
    public function update(Request $request, $venda)
    {
        //id do item da venda

        $item = CarrinhoItem::find($venda);

        if ($request->quantidade && !$request->tipo_desconto) {
            $carrinho = Carrinho::find($item->carrinho_id);

            // dd($item);
            $item->update([
                'quantidade' => $request->quantidade,
                'valor' => $item->preco * $request->quantidade,
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
        $carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        if ($carrinho->carItem()->count() === 1) {
            $carrinho->delete();
            Session::flash('success', 'Todos Itens Retirados');
            return redirect(route('venda.index'));
        } else {
            if (CarrinhoItem::find($item)) {
                CarrinhoItem::find($item)->delete();
                $this->atualiza_carrinho_desconto_unico($carrinho);

                Session::flash('success', 'Item Retirado Com Sucesso');

                return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
            }
        }
    }

    public function salvar_venda(Request $request)
    {

        $carrinho_aberto =     Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        if ($request->cliente_id) {
            if ($request->salvaSubstitui) {

                $carrinho_aberto->update([
                    'status' => 'Salvo',
                    'cliente_id' => $carrinho_aberto->cliente_id ? $carrinho_aberto->cliente_id : $request->cliente_id,
                ]);

                Carrinho::find($request->salvaSubstitui)->update([
                    'status' => 'Aberto',
                ]);
            }

            $carrinho_aberto->update([
                'status' => 'Salvo',
                'cliente_id' => $carrinho_aberto->cliente_id ? $carrinho_aberto->cliente_id : $request->cliente_id,
            ]);
        }
        Session::flash('success', 'Itens Salvos Com Sucesso');
      
        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
    }

    public function atualiza_carrinho_desconto_parcial($item)
    {

        $itens = CarrinhoItem::where('carrinho_id', $item->carrinho_id ? $item->carrinho_id : $item)->get();

        foreach ($itens as $item) {
            // dd($item->valor_desconto);
            $valor_itens_desconto[] = $item->valor_desconto;
            $valor_itens_bruto[] = $item->preco * $item->quantidade;
            $valor_itens[] = $item->valor;
        }

        Carrinho::find($item->carrinho_id)->update([
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
        //caso tenha algum tipo de desconto
        if ($carrinho->tp_desconto == 'porcento_unico' or $carrinho->tp_desconto == 'dinheiro_unico') {
            $desconto_final = $carrinho->tp_desconto == 'porcento_unico' ? ($carrinho->desconto_qtd / 100) * (array_sum($valor_itens)) : $carrinho->desconto_qtd;
            $valor_final_item = array_sum($valor_itens) - $desconto_final;

            $carrinho->update([
                'valor_desconto' => $desconto_final,
                'valor_bruto' => array_sum($valor_itens),
                'total' => $valor_final_item,
            ]);
            return;
        }

        $carrinho->update([
            'valor_desconto' => array_sum($valor_itens_desconto),
            'valor_bruto' => array_sum($valor_itens_bruto),
            'total' => array_sum($valor_itens),
        ]);
    }


    public function finaliza_venda(Request $request, $carrinho)
    {
        $cliente = Cliente::where('loja_id', auth()->user()->loja_id)->where('alltech_id', $request->cliente_alltech_id)->orWhere('docto', $request->cliente_alltech_id)->first();

        Carrinho::find($carrinho)->update([
            'status' => 'fechado',
            'cliente_id' => $cliente ? $cliente->id : '0000',
            'total' => $request->hiddenInputValorTotalModal,
            'tipo_pagamento' => $request->tipo_pagamento,
            'parcelas' => $request->parcelas ? $request->parcelas : 1,
            'tp_desconto_sb_venda' => $request->tp_desconto_sb_venda == "0" ? null : $request->tp_desconto_sb_venda,
            'valor_desconto_sb_venda' => $request->hiddenInputValorDescontoSobreVendaModal,
            'desconto_qtd_sb_venda' => $request->qtd_desconto_sobre_venda,
        ]);

        $carrinho = Carrinho::find($carrinho);
        //monta json para exportação
        $this->jsonVendaStorageJob($carrinho);
        //vai entrar função json para exportação da venda e jobs
        Session::flash('success', 'Venda Finalizada Com Sucesso');
        return redirect(route('venda.index'));
    }

    public function jsonVendaStorageJob($carrinho)
    {
        $dados['id'] = $carrinho->id;
        $dados['status'] = $carrinho->status;
        $dados['loja_alltech_id'] = $carrinho->usuario->loja->alltech_id;
        $dados['id_vendedor'] = $carrinho->usuario->id;
        $dados['nome_vendedor'] = $carrinho->usuario->name;
        $dados['cliente_alltech_id'] = $carrinho->cliente->alltech_id;
        $dados['emissao'] = $carrinho->updated_at->format('Ymd');
        $dados['qtd_desc'] = $carrinho->desconto_qtd;
        $dados['tipo_desc'] = ($carrinho->tp_desconto == 'parcial') ? 'parcial' : (($carrinho->tp_desconto == 'porcento_unico') ? '%' : '$');
        $dados['v_desc'] = $carrinho->valor_desconto;
        $dados['v_bruto'] = $carrinho->valor_bruto;
        $dados['v_total'] = $carrinho->total;
        $dados['tipo_pg'] = $carrinho->tipo_pagamento;
        $dados['parcelas'] = $carrinho->parcelas;
        $dados['tp_desc_sb_venda'] = ($carrinho->tp_desconto_sb_venda && $carrinho->tp_desconto_sb_venda == 'porcento') ? '%' : (($carrinho->tp_desconto_sb_venda && $carrinho->tp_desconto_sb_venda == 'dinheiro') ? '$' : null);
        $dados['qtd_desc_sb_venda'] = $carrinho->desconto_qtd_sb_venda ? $carrinho->desconto_qtd_sb_venda : null;
        $dados['v_desc_sb_venda'] = $carrinho->valor_desconto_sb_venda ? $carrinho->valor_desconto_sb_venda : null;

        $i = 1;
        foreach ($carrinho->carItem as $key => $item) {

            $dados['itens'][$i]['produto_alltech_id'] = $item->produto->alltech_id;
            $dados['itens'][$i]['alltech_id_grade'] = $item->i_grade_id ? $item->iGrade->id_grade_alltech_id : null;
            $dados['itens'][$i]['alltech_id_i_grade'] = $item->i_grade_id ? $item->iGrade->alltech_id : null;
            $dados['itens'][$i]['preco'] = $item->preco;
            $dados['itens'][$i]['quantidade'] = $item->quantidade;
            $dados['itens'][$i]['tipo_desconto'] = ($item->tipo_desconto && $item->tipo_desconto == 'porcento') ? '%' : (($item->tipo_desconto && $item->tipo_desconto == 'dinheiro') ? '$' : null);
            $dados['itens'][$i]['qtd_desconto'] = $item->qtd_desconto;
            $dados['itens'][$i]['valor_desconto'] = $item->valor_desconto;
            $dados['itens'][$i]['valor_item'] = $item->valor;

            $i++;
        }
        //monnta arquivo para exportação em job
        $json = json_encode($dados);
        $dir = $carrinho->usuario->loja->empresa->pasta;

        Storage::disk('local')->makeDirectory($dir);
        $files = Storage::disk('local')->files($dir);
        $count = 1;

        if (count($files) == 0) {

            Storage::put($dir . '/VONLINE-' . $count . '.json', $json);
            $file = $dir . '/VONLINE-' . $count . '.json';
        } else {

            foreach ($files as $key => $file) {
                if (str_contains($file, 'VONLINE')) {
                    $count++;
                }
            }

            Storage::put($dir . '/VONLINE-' . $count . '.json', $json);
            $file = $dir . '/VONLINE-' . $count . '.json';
        }
        //Class de Jobs para exportação 
        // ExportaVendaJob::dispatch($file, $dir);
        return;
    }
}
