<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $fillable=['cod_fac','car_nombre','car_abreviacion','car_campo_amplio','car_campo_especifico','car_campo_detallado'];
    protected $primaryKey='cod_car';
}
