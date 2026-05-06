<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarreraAcreditacionHistorial extends Model
{
    protected $table = 'carrera_acreditacion_historiales';
    protected $primaryKey = 'cod_cah';

    protected $fillable = [
        'cod_cac',
        'cod_car',
        'operacion',
        'version',
        'acreditada',
        'tipo',
        'sistema',
        'anio',
        'proc_sc',
        'proc_nc',
        'proc_total',
        'fecha_acreditacion',
        'fecha_vencimiento',
        'resolucion_inicio',
        'resolucion_fin',
        'resolucion_fecha_emision',
        'resolucion_numero',
        'resolucion_anio',
        'estado',
        'puntaje',
        'certificado',
        'observacion',
        'fecha_cambio',
    ];
}
