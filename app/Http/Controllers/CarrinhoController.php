<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alltech;
use App\Models\Carrinho;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{

    public function index(Request $request)
    {
        $cliente_carrinho = false;
        // $clientes = Alltech::get_cliente_nome();

        $carrinho = json_decode(Alltech::get_itens_carrinho());

        if ($carrinho->cliente_id_alltech) {
            $cliente_carrinho = Alltech::get_clientes_alltech([$carrinho->cliente_id_alltech]);
        }

        return view('vendedor.item-carrinho', compact('carrinho', 'cliente_carrinho'));
    }

    public function itens_carrinho()
    {
        return Alltech::get_itens_carrinho();
    }

    public function vendas_salvas(Request $request)
    {
        $busca = $request->nome_n_pedido;

        $carrinhos = Carrinho::with('carItens')->where('user_id', auth()->user()->id)
            ->where('status', 'salvo')->where('n_pedido', 'like', is_numeric($busca) ?  '%' . $busca . '%' : '%' . '' . '%')->get();

        $dados = $this->get_carrinhos_estoque_produto_cliente($carrinhos, $busca);

        $carrinhos = $dados['carrinhos'];
        $clientes = $dados['clientes'];
        $produtos = $dados['produtos'];

        $carrinhos = Alltech::paginate($carrinhos);
        $carrinhos = $carrinhos->withPath('/vendas_salvas?');

        return view('vendedor.vendas-salvas', compact('carrinhos', 'clientes', 'produtos'));
    }

    public function vendas_finalizadas(Request $request)
    {
        $busca = $request->nome_n_pedido;

        $carrinhos = Carrinho::with('carItens')->where('user_id', auth()->user()->id)
            ->where('status', 'fechado')->where('n_pedido', 'like', is_numeric($busca) ?  '%' . $busca . '%' : '%' . '' . '%')->get();

        $dados = $this->get_carrinhos_estoque_produto_cliente($carrinhos, $busca);

        $carrinhos = $dados['carrinhos'];
        $clientes = $dados['clientes'];
        $produtos = $dados['produtos'];

        $carrinhos = Alltech::paginate($carrinhos);
        $carrinhos = $carrinhos->withPath('/vendas-finalizadas?');

        return view('vendedor.vendas-finalizadas', compact('carrinhos', 'clientes', 'produtos'));
    }

    public function substitui_carrinho($carrinho = null)
    {
        $carrinhoAtual = Carrinho::where('user_id', auth()->user()->id)->where('status', 'aberto')->first();
        if ($carrinho) {
            $carrinho_substituido = Carrinho::find($carrinho);
            $carrinho_substituido->update(['status' => 'aberto']);
            if ($carrinhoAtual->cliente_id_alltech) {
                $carrinhoAtual->update(['status' => 'salvo']);
            } else {
                $carrinhoAtual->delete();
            }
            Session::flash('success', 'Carrinho substituído com sucesso');
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
        }
    }

    public function vendas_invalidas(Request $request)
    {
        $busca = $request->nome_n_pedido;

        $carrinhos = Carrinho::with('carItens')->where('user_id', auth()->user()->id)
            ->where('status', 'descInv')->where('n_pedido', 'like', is_numeric($busca) ?  '%' . $busca . '%' : '%' . '' . '%')->get();

        $dados = $this->get_carrinhos_estoque_produto_cliente($carrinhos, $busca);

        $carrinhos = $dados['carrinhos'];
        $clientes = $dados['clientes'];
        $produtos = $dados['produtos'];

        $carrinhos = Alltech::paginate($carrinhos);
        $carrinhos = $carrinhos->withPath('/vendas-finalizadas?');

        return view('vendedor.vendas-invalidas', compact('carrinhos', 'clientes', 'produtos'));
    }

    public function get_carrinhos_estoque_produto_cliente($carrinhos_get, $busca)
    {
        $clientes_alltech = $carrinhos_get->pluck('cliente_id_alltech')->toArray();
        if (!is_numeric($busca)) {
            $clientes_alltech = Alltech::get_cliente_nome($busca);
        } else {
            $clientes_alltech = Alltech::get_clientes_alltech($clientes_alltech);
        }

        $estoques = [];
        foreach ($carrinhos_get as $key => $car) {
            $p = $car->carItens()->get('estoque_id_alltech');
            foreach ($p as $key => $id) {
                $estoques[] =  $id->estoque_id_alltech;
            }
        }

        $estoques = Alltech::get_estoque_produtos($estoques);
        $produtos = [];
        foreach ($estoques as $key => $p) {
            $produtos[$p->id] = $p;
        }

        $carrinhos = [];
        $clientes = [];
        foreach ($clientes_alltech as $key => $c) {
            $clientes[$c->id] = $c;
            $car = $carrinhos_get->where('cliente_id_alltech', $c->id);
            if (count($car)) {
                $carrinhos[$c->id] = $car;
            }
        }

        return ['carrinhos' => $carrinhos, 'clientes' => $clientes, 'produtos' => $produtos];
    }
}
