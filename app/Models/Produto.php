<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['loja_id', 'alltech_id', 'nome', 'custo', 'preco', 'situacao'];

    public function loja() {
        return $this->belongsTo('App\Model\Loja');
    }

    public function estoques() {
        return $this->hasMany('App\Model\Estoque');
    }

    public function saldo($loja_id) {
        return $this->estoques()->count() > 0 ? $this->estoques()->first()->saldo : 0;
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
