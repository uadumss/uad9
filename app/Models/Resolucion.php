<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    protected $fillable=['cod_tom','res_numero','res_tema','res_objeto','res_desc','res_pdf','res_ant','res_tipo','res_vistos','res_resuelve',
                            'res_considerando','res_fecha','res_obs','res_importacion','res_gestion','res_enlace'];
    protected $primaryKey='cod_res';
}
