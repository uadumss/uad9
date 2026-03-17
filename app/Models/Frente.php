<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frente extends Model
{
    use HasFactory;
    protected $fillable=['fre_nombre','fre_tipo','fre_vigente','fre_fecha_inicio','fre_fecha_fin','fre_docente','cod_fac','cod_car'];
    protected $table='claustros.frentes';
    protected $primaryKey='cod_fre';
}
