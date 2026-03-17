<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable=['bit_inicio','bit_fin','bit_usuario','bit_id','bit_host'];
    protected $primaryKey='cod_bit';
    protected $table='seguridad.bitacoras';
}
