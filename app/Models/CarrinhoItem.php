<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoItem extends Model
{
    protected $table = 'carrinho_itens';
    protected $fillable = [
        'alltech_id',
        'nome',
        'quantidade', 
        'preco',
        'desconto',
        'valor',
        'produto_id',
        'carrinho_id',
    ];

    public function car()
    {
        return $this->belongsTo('App\Models\Carrinho');
    }
}
