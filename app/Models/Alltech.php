<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Alltech {

    static function get_itens_carrinho()
    {
        $carrinho = Carrinho::with('carItens')->where('status', 'aberto')->where('user_id', auth()->user()->id)->first();

        $ids_estoque = [];

        foreach ($carrinho->carItens as $key => $v) {
            $ids_estoque[] = $v->estoque_id_alltech;
        }
  
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

    //recebe um array de com os valores
    static function get_estoque_produtos($array)
    {
        $ids = implode(',', array_unique($array));

        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/estoques/produtos/?atr_estoque=id,codbar,tam,loja_id,saldo,i_grade_id,produto_id&atr_produto=nome,preco,un
            &filtro_estoque=id:=:' . $ids);

        $response = $response->object();

        return $response;
    }

    static function get_cliente_nome($nome = null)
    {
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')
            ->get('http://localhost:8000/api/v1/clientes/', [
                'relations' => 'enderecos',
                'nome' => '%' . $nome . '%',
            ]);

        $response = $response->object();

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
                'filtro_cliente' => 'id:=:' . $ids
            ]);


        $response = $response->object();

        return $response;
    }
}