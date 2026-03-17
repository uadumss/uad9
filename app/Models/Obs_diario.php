<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obs_diario extends Model
{
    protected $fillable=['od_rep','od_obs','od_fech','id_responsable','cod_dia','dia_fech_mod'];
    protected $primaryKey='cod_od';
}
