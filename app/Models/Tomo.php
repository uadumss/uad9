<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tomo extends Model
{
    protected $fillable=['tom_numero','tom_gestion','tom_rango','tom_obs','tom_tipo','tom_cerrado','tom_usr'];
    protected $primaryKey='cod_tom';
}
