<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarreraCampo extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_car',
        'campo_amplio',
        'campo_especifico',
        'campo_detallado',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'cod_car', 'cod_car');
    }
}
