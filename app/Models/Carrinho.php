<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $fillable = [
       'id',
       'user_id',
       'valor_desconto', 
       'desconto_qtd',
       'tp_desconto',
       'valor_bruto',
       'total', 
       'cliente_id',
       'status', 
    ];
    
    public function carItem()
    {
        return $this->hasMany('App\Models\CarrinhoItem');
    }

    // public function produto()
    // {
    //     return $this->hasMany('App\Models\Produto');
    // }

    public function vendedorCliente()
    {
        return $this->belongsTo('App\Models\VendedorCliente');
    }
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }
}
