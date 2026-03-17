<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    protected $fillable=['tar_nombre','tar_fi','tar_ff','tar_desc','tar_hab','id_responsable','cod_act','tar_concluido','tar_por','tar_cotidiano'];
    protected $primaryKey='cod_tar';
}
