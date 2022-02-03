<?php

namespace App\Model;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nome', 'pasta', 'ultima_sincronizacao', 'sincronizar'];
    protected $casts = [
        'ultima_sincronizacao' => 'datetime'
    ];

    public function produtos() {
        return $this->hasMany('App\Model\Produto');
    }

    public function lojas() {
        return $this->hasMany('App\Model\Loja');
    }

    public function logs() {
        return $this->hasMany('App\Model\Log');
    }

    public function arquivos() {
        return $this->hasMany('App\Model\ArquivoImportado');
    }
}
