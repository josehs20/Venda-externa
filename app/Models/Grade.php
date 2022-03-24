<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $fillable = [
        'alltech_id',
        'nome',
    ];

    public function loja()
    {
        return $this->belongsTo('App\Models\Loja');
    }

    public function iGrades() {
        return $this->hasMany('App\Models\Igrade', 'grade_id', 'id');
    }
}
