<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_apostilla extends Model
{
    use HasFactory;
    protected $fillable=['cod_dapo','cod_apos','cod_lis','dapo_numero','dapo_qr','dapo_fecha_ingreso','dapo_fecha_firma'
        ,'dapo_fecha_entrega','dapo_estado','dapo_obs','dapo_hab','dapo_anulado','dapo_numero_documento','dapo_gestion_documento','dapo_buscar_en'];
    protected $table='apostilla.detalle_apostilla';
    protected $primaryKey='cod_dapo';
    protected $keyType = 'string';
    public $incrementing = false;
}
