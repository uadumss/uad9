<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reporte_periodo extends Model
{

    protected $fillable=['rt_desc','rt_fech_ini','rt_fech_fin','rt_obs','rt_fech_rev','rt_cal','rt_apr','id','rt_num','rt_bandera_obs','rt_bandera_corregido'];
    protected $primaryKey='cod_rt';


}
