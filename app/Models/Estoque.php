<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'loja_id',
        'codbar',
        'tam',
        'situacao',
        'cor',
        'alltech_id',
        'produto_id',
        'saldo'
    ];

    public function loja()
    {
        return $this->belongsTo('App\Model\Loja');
    }

    public function produto()
    {
        return $this->hasMany('App\Model\Produto');
    }

}
