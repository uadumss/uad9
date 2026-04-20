<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarreraAcreditacion extends Model
{
    protected $table = 'carrera_acreditaciones';
    protected $primaryKey = 'cod_cac';

    protected $fillable = [
        'cod_car',
        'acreditada',
        'tipo',
        'sistema',
        'anio',
        'proc_sc',
        'proc_nc',
        'proc_total',
        'fecha_acreditacion',
        'fecha_vencimiento',
        'estado',
        'puntaje',
        'certificado',
        'observacion',
        'fecha_registro',
    ];
}
