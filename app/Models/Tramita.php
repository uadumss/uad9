<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramita extends Model
{
    protected $fillable=['id_per','cod_apo','tra_numero','tra_gestion','tra_fecha_solicitud','tra_fecha_firma','tra_fecha_recojo',
        'tra_anulado','tra_tipo_apoderado','tra_obs','tra_tipo_tramite'];
    protected $primaryKey='cod_tra';
}
