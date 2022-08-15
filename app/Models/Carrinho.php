<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Carrinho extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'data',
        'n_pedido',
        'valor_desconto',
        'qtd_desconto',
        'tp_desconto',
        'valor_bruto',
        'total',
        'tipo_pagamento',
        'forma_pagamento',
        'cliente_id_alltech',
        'status',
        'parcelas',
        'tp_desconto_sb_venda',
        'valor_desconto_sb_venda',
        'desconto_qtd_sb_venda',
        'valor_entrada',

    ];

    public function carItens()
    {
        return $this->hasMany('App\Models\CarrinhoItem');
    }

    // public function produto()
    // {
    //     return $this->hasMany('App\Models\Produto');
    // }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function vendedorCliente()
    {
        return $this->belongsTo('App\Models\VendedorCliente');
    }
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }


    static function veririfica_carrinho_aberto()
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
}
