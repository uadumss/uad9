<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    protected $fillable=['apo_ci','apo_nombre','apo_apellido','apo_tipo','apo_obs','apo_hab','apo_sistema'];
    protected $table='apoderados';
    protected $primaryKey='cod_apo';
}
