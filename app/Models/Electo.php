<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electo extends Model
{
    use HasFactory;
    protected $fillable=['ele_titular','ele_docente','ele_fecha_inicio','ele_fecha_fin','ele_obs','ele_estado','ele_vigente','cod_fre','id_per'
        ,'ele_tipo','cod_fac','cod_car','ele_fecha_renuncia'];
    protected $table='claustros.electos';
    protected $primaryKey='cod_ele';
}
