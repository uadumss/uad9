<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoSecuencial extends Model
{
    use HasFactory;
    
    protected $table = 'doc_adm.codigo_secuencial';
    protected $primaryKey = 'cod_seq';
    protected $fillable = ['tipo', 'anio', 'ultimo_numero'];
    protected $casts = [
        'anio' => 'integer',
        'ultimo_numero' => 'integer',
    ];
}
