<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendedorCliente extends Model
{
    protected $table = 'vendedor_clientes';
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cidade',
        'rua',
        'numero_rua',
        'carriho_id',
        'user_id',
    ];

    public function userCliente()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function infoCliente()
    {
        return $this->hasMany('App\Models\InfoCliente');
    }
    public function carrinho()
    {
        return $this->hasMany('App\Models\Carrinho');
    }
}
