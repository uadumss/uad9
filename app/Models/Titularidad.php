<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titularidad extends Model
{
    use HasFactory;
    protected $fillable=['cod_fun','cod_car','dt_materia','dt_fecha','dt_gestion','dt_detalle','dt_categoria','dt_numero_resolucion','dt_fecha_resolucion'
        ,'dt_verificado','dt_obs','dt_universidad'];
    protected $table='doc_adm.titularidads';
    protected $primaryKey='cod_dt';
}
