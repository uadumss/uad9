<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    protected $fillable=['tit_nro_titulo','tit_nro_folio','tit_grado','tit_fecha_emision','tit_pdf','tit_antecedentes','tit_tipo',
        'tit_titulo','tit_anulado','id_per','cod_tom','cod_tipo_tit','cod_gra','cod_mod','tit_revalida','tit_obs','tit_importacion',
        'tit_gestion','tit_ref','tit_usr','tit_otra_modalidad','tit_reconocimiento','tit_fecha_folio'];
    protected $primaryKey='cod_tit';
}
