<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $fillable = [
       'id',
       'user_id',
       'desconto_total', 
       'valor_total', 
       'status', 
    ];

    public function carItem()
    {
        return $this->hasMany('App\Models\CarrinhoItem');
    }
}
