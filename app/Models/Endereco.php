<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'enderecos';
    protected $fillable = [
        'cliente_id',
        'cidade_ibge_id',
        'cep',
        'bairro',
        'rua',
        'numero',
        'compto',
        'tipo',
    ];

    public function cidadeIbge() {
        return $this->belongsTo('App\Models\CidadeIbge', 'cidade_ibge_id', 'id');
    }
}
