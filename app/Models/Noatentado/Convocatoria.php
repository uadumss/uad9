<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    use HasFactory;
    protected $fillable=[
        'con_tipo',
        'con_nombre',
        'con_fecha_publicacion',
        'con_fecha_eleccion',
        'con_fecha_entrega',
        'con_pdf',
        'con_gestion',
        'con_dirigido_a',
        'cod_fac',
        'cod_car',
        'cod_uni',
        'con_hab',
        'con_clase',
        'con_convocante',
        'con_tipo_convocante',
        'con_periodo_inicial',
        'con_periodo_final'
    ];

    protected $primaryKey='cod_con';
    protected $table="claustros.convocatoria";
}
