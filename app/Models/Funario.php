<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funario extends Model
{
    protected $table = 'funarios';
    protected $fillable = [
        'nome',
        'alltech_id',
        'tipo',
        'status',
        'lucroMimVenda',
        'user_id',
    ];


    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
