<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class T_observacion extends Model
{
    protected $fillable=['cod_tit','obs_observacion','obs_solucion','obs_fecha_solucion','obs_fecha'];
    protected $primaryKey='cod_obs';
}
