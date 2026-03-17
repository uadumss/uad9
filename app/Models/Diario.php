<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diario extends Model
{
    protected $fillable=['dia_reporte','dia_fech','dia_calificacion','dia_obs','id','cod_des','cod_tar','dia_res_nombre','dia_corregir',
                        'dia_aceptado','dia_fech_revision','res_id_res','dia_porcen','dia_fech_mod','dia_final','dia_fech_reportado'];
    protected $primaryKey='cod_dia';

}
