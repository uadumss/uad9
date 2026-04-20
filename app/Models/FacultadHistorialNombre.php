<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultadHistorialNombre extends Model
{
    protected $table = 'facultad_historial_nombres';
    protected $primaryKey = 'cod_fhn';

    protected $fillable = [
        'cod_fac',
        'nombre_anterior',
        'nombre_nuevo',
        'abreviacion_anterior',
        'abreviacion_nueva',
        'fecha_cambio',
    ];
}
