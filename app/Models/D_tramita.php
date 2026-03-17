<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class D_tramita extends Model
{
    protected $fillable=['cod_tre','cod_tra','dtra_tipo','dtra_cod_tit','dtra_qr','dtra_glosa','dtra_anulado',
        'dtra_fecha_recojo','dtra_fecha_firma','dtra_numero_tramite','dtra_gestion_tramite','dtra_numero','dtra_gestion','dtra_obs',
        'dtra_estado_doc','dtra_valorado','dtra_costo','dtra_generado','dtra_titulo','dtra_fecha_literal','dtra_interno','dtra_entregado',
        'dtra_valorado_busqueda','dtra_buscar_en','dtra_glosa_posicion','dtra_cod_glosa','dtra_solo_sello','dtra_fecha_registro','dtra_ptaang',
        'dtra_valorado_reintegro','dtra_verificacion_sitra','dtra_supletorio','dtra_control','cod_con','cod_apo','dtra_tipo_apoderado','dtra_entregado_persona'
        ,'dtra_control_reimpresion','dtra_sin_valorado'];
    protected $primaryKey='cod_dtra';
    protected $table='d_tramitas';

}
