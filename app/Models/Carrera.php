<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $fillable=['cod_fac','car_nombre','car_abreviacion'];
    protected $primaryKey='cod_car';
}
