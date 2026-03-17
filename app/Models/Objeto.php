<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    protected $fillable = ['obj_nombre', 'obj_subsistema'];
    protected $primaryKey = 'cod_obj';
    protected $table='seguridad.objetos';
}
