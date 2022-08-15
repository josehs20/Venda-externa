<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoItem extends Model
{
    protected $table = 'carrinho_itens';
    protected $fillable = [
        'quantidade',
        'preco',
        'tp_desconto',
        'qtd_desconto',
        'valor_desconto',
        'valor',
        'produto_id',
        'carrinho_id',
        'estoque_id_alltech',
        // 'i_grade_id',
    ];

    public function produto() {
        return $this->hasOne('App\Models\Produto', 'id', 'produto_id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Carrinho', 'carrinho_id', 'id');
    }

    public function iGrade()
    {
        return $this->belongsTo('App\Models\Igrade', 'i_grade_id', 'id');
    }
}
