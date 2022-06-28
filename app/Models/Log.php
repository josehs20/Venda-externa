<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [ 'log', 'empresa_id' ];

    public function empresa() {
        return $this->belongsTo("App\Models\Empresa");
    }
}
