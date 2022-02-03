<?php

namespace App\Model;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    protected $fillable = ['nome', 'alltech_id', 'empresa_id'];

    public function empresa() {
        return $this->belongsTo('App\Model\Empresa');
    }

    public function empresaVenda() {
        return $this->belongsTo('App\Model\EmpresaVenda');
    }

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function vendedorUser() {
        return $this->belongsToMany('App\Model\VendedorUser');
    }

    public function caixas() {
        return $this->hasMany('App\Model\Caixa');
    }

    public function produtos() {
        return $this->hasMany('App\Model\Produto');
    }

    public function estoques() {
        return $this->hasMany('App\Model\Estoque');
    }

    public function vendas() {
        return $this->hasMany('App\Model\Venda');
    }

    public function devolucoes() {
        return $this->hasMany('App\Model\Devolucao');
    }

    public function vendaItens() {
        return $this->hasMany('App\Model\VendaItem');
    }

    public function devolucaoItens() {
        return $this->hasMany('App\Model\DevolucaoItem');
    }

    public function clientes() {
        return $this->hasMany('App\Model\Cliente');
    }

    public function receitas() {
        return $this->hasMany('App\Model\Receita');
    }

    public function despesas() {
        return $this->hasMany('App\Model\Despesa');
    }

    public function fornecedor() {
        return $this->hasMany('App\Model\Fornecedor');
    }
}
