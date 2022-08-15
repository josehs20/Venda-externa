<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Cliente extends Model
{
    protected $fillable = [
        'loja_id',
        'alltech_id',
        'nome',
        'docto',
        'tipo',
        'email',
        'fone1',
        'fone2',
        'celular',
        'cidade_ibge_id',
        'cep',
        'bairro',
        'rua',
        'numero',
        'compto',
    ];

    public function loja()
    {
        return $this->belongsTo('App\Models\Loja');
    }

    public function infoCliente()
    {
        return $this->hasMany('App\Models\InfoCliente');
    }

    public function enderecos()
    {
        return $this->hasOne('App\Models\Endereco');
    }
}
