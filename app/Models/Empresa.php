<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nome', 'pasta', 'ultima_sincronizacao', 'sincronizar'];
    protected $casts = [
        'ultima_sincronizacao' => 'datetime'
    ];

    public function lojas() {
        return $this->hasMany('App\Models\Loja');
    }

}
