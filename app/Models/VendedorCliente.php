<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendedorCliente extends Model
{
    protected $fillable = [
        'nome',
        'telefone',
        'observacao',
        'user_id',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function userCliente()
    {
        return $this->belongsTo('App\Models\User');
    }
}
