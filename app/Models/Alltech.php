<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Alltech
{
    static function get_itens_carrinho()
    {
        //$carrinho = Carrinho::with('carItens')->where('status', 'aberto')->where('user_id', auth()->user()->id)->first();
        $carrinho = Carrinho::veririfica_carrinho_aberto();

        $ids_estoque = [];

        foreach ($carrinho->carItens as $key => $v) {
            $ids_estoque[] = $v->estoque_id_alltech;
        }

        $ids_estoque = implode(',', $ids_estoque);

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/estoques/produtos/?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,custo,un
            &filtro_estoque=id:=:' . $ids_estoque);

        $response = $response->collect();

        foreach ($carrinho->carItens as $key => $item) {
            $produtos_api = $response->firstWhere('id', $item->estoque_id_alltech);
            $item['nome'] = $produtos_api['produto']['nome'];
            $item['custo'] = $produtos_api['produto']['custo'];
            $item['codbar'] = $produtos_api['codbar'] ? $produtos_api['codbar'] : null;
            $item['tam'] = $produtos_api['i_grade'] ? $produtos_api['i_grade']['tam'] : null;
        }

        //retorna carrinho com itens já com o nome de cada um 
        return $carrinho;
    }

    //recebe um array de com os valores
    static function get_estoque_produtos($array)
    {
        $ids = implode(',', array_unique($array));

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/estoques/produtos/?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,custo,un
            &filtro_estoque=id:=:' . $ids);

        $response = $response->object();

        return $response;
    }

    static function get_cliente_nome($nome = null)
    {
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/clientes/', [
                'relations' => 'enderecos',
                'filtro_cliente' => 'nome:like:%' . $nome . '%',
                'limit' => 500,
            ]);

        $response = $response->collect();

        //converte para obj e rejeita vendedor a vista
        $response = json_decode($response->reject(function ($c) {
            return $c['alltech_id'] == '999999';
        })->toJson());

        return $response;
    }
    //recebe um array de com os valores 
    //já tem relacionamento com enderecos
    static function get_clientes_alltech($array)
    {
        $ids = implode(',', array_unique($array));

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/clientes/', [
                'relations' => 'enderecos',
                'filtro_cliente' => 'id:in:' . $ids
            ]);

        $response = $response->object();

        return $response;
    }

    static function get_cidades_ibge($array)
    {
        $ids = implode(',', array_unique($array));

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/cidades_ibge/', [
                'cidades_ibge' => 'id:In:' . $ids,
            ]);

        $response = $response->object();

        return $response;
    }

    static function create_cliente($dados)
    {
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')->post(
            'http://localhost:8000/api/v1/create-clientes/',
            [
                'json' => json_encode($dados),
            ]
        );

        return $response;
    }

    static function update_cliente($dados, $id)
    {
        // $urlCliente = null;
        // $urlendereco = null;

        // foreach ($dados as $key => $dado) {
        //     foreach ($dado as $keyDado => $d) {
        //         if ($key == 'update_cliente') {
        //             $urlCliente .= ';' . $keyDado . '=' . $d;
        //         } else {
        //             $urlendereco .= ';' . $keyDado . '=' . $d;
        //         }
        //     }
        // }
        // $dados['update_cliente'] = substr($urlCliente, 1);
        // $dados['endereco'] = substr($urlendereco, 1);

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')->put(
            'http://localhost:8000/api/v1/update-clientes/' . $id,
            [
                'json' => json_encode($dados),
                // 'endereco' => $dados['endereco']
            ]
        );

        return $response;
    }

    static function exportVenda($json)
    {
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')->get(
            'http://localhost:8000/api/v1/export-venda/',
            [
                'json' => $json
            ]
        );

        return $response->object();
    }

    static function paginate($items, $perPage = 50, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
