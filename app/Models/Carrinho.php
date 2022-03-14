<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $fillable = [
       'id',
       'user_id',
       'desconto_valor', 
       'desconto_qtd',
       'tp_desconto_unificado',
       'total_desconto',
       'total', 
       'cliente_id',
       'status', 
    ];
    
    public function carItem()
    {
        return $this->hasMany('App\Models\CarrinhoItem');
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
