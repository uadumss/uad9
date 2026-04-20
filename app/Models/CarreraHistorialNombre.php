<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarreraHistorialNombre extends Model
{
    protected $table = 'carrera_historial_nombres';
    protected $primaryKey = 'cod_chn';

    protected $fillable = [
        'cod_car',
        'nombre_anterior',
        'nombre_nuevo',
        'abreviacion_anterior',
        'abreviacion_nueva',
        'fecha_cambio',
    ];
}
