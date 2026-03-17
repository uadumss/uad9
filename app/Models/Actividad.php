<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{

    protected $fillable=['id','act_nombre','act_inicio','act_fin','act_cotidiano','act_desc'];
    protected $primaryKey='cod_act';
}
