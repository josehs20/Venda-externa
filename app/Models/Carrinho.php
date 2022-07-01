<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'data',
        'n_pedido',
        'valor_desconto',
        'desconto_qtd',
        'tp_desconto',
        'valor_bruto',
        'total',
        'tipo_pagamento',
        'forma_pagamento',
        'cliente_id',
        'status',
        'parcelas',
        'tp_desconto_sb_venda',
        'valor_desconto_sb_venda',
        'desconto_qtd_sb_venda',
        'valor_entrada',

    ];

    public function carItem()
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
}
