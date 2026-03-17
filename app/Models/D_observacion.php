<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_observacion extends Model
{
    use HasFactory;
    protected $fillable=['cod_doc','od_obs','od_solucion','od_fecha','od_fecha_solucion'];
    protected $table='doc_adm.d_observacions';
    protected $primaryKey='cod_od';
}
