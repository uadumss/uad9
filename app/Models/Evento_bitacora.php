<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento_bitacora extends Model
{
    protected $fillable=['cod_bit','eve_antiguo','eve_nuevo','eve_operacion','eve_tabla','eve_sistema','eve_cod_objeto'];
    protected $primaryKey='cod_eve';
    protected $table='seguridad.evento_bitacoras';

}
