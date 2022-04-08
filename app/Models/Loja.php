<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    protected $fillable = ['nome', 'alltech_id', 'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function empresaVenda()
    {
        return $this->belongsTo('App\Model\EmpresaVenda');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function vendedorUser()
    {
        return $this->belongsToMany('App\Models\VendedorUser');
    }


    public function grades()
    {
        return $this->hasMany('App\Models\Grade');
    }

    public function igrades()
    {
        return $this->hasMany('App\Models\Igrade');
    }

    public function produtos()
    {
        return $this->hasMany('App\Models\Produto');
    }
  
    public function clientes()
    {
        return $this->hasMany('App\Models\Cliente');
    }

}
