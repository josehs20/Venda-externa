<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Igrade extends Model
{
    protected $table = 'igrades';
    protected $fillable = [
        'alltech_id',
        'id_grade_alltech_id',
        'grade_id',
        'loja_id',
        'tam',
        'fator',
        'tipo', 
    ];

    public function loja()
    {
        return $this->belongsTo('App\Models\Loja');
    }

    public function produtos() {
        return $this->belongsTo('App\Models\Produto');
    }

    public function grades() {
        return $this->belongsTo('App\Models\Grades');
    }

}
