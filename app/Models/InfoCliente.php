<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCliente extends Model
{
    protected $table = 'info_clientes';
    protected $fillable = [
        'observacao',
        'data',
        'cliente_id',
    ];
  
    public function vendedorCliente()
    {
        return $this->belongsTo('App\Models\VendedorCliente');
    }
}
