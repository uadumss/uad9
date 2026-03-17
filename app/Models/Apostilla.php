<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apostilla extends Model
{
    use HasFactory;
    protected $fillable=['cod_apos','id_per','cod_apo','apos_numero','apos_clave','apos_qr','apos_fecha_ingreso','apos_fecha_firma','apos_fecha_recojo','apos_obs','apos_estado'
                        ,'apos_hab','apos_anulado','apos_verificacion','apos_gestion','apos_apoderado','apos_entregado','created_at','updated_at'];
    protected $table='apostilla.apostilla';
    protected $primaryKey='cod_apos';
    protected $keyType = 'string';
    public $incrementing = false;
}
