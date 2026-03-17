<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sancionado extends Model
{
    use HasFactory;
    protected $fillable=['cod_san','id_per','cod_res','san_sentencia','san_referencia','san_resolucion','san_observacion'];
    protected $table='noatentado.sancionados';
    protected $primaryKey='cod_san';
    protected $keyType = 'string';
    public $incrementing = false;
}
