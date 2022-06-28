<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArquivoExportado extends Model
{
    use HasFactory;
    protected $table = 'arquivo_exportados';
    protected $fillable = ['nome', 'processado'];
}
