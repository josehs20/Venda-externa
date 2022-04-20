<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['loja_id', 'alltech_id', 'nome', 'custo', 'preco', 'situacao', 'grade_id'];

    public function loja() {
        return $this->belongsTo('App\Models\Loja');
    }

    public function iGrades() {
        return $this->belongsTo('App\Models\Igrade');
    }

    public function grades() {
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }

    public function carrinhoItem()
    {
       return $this->hasOne('App\Models\CarrinhoItem');
    }
    public function carrinho()
    {
       return $this->belongsTo('App\Models\Carrinho', 'carrinho_id', 'id');
    }

    public function descricaoProduto() {
        switch($this->situacao) {
            case 'A': return 'Ativo';
            break;
            
            case 'I': return 'Inativo';
            break;

        }
    }
}
