<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilitacion extends Model
{
    use HasFactory;
    protected $fillable=['cod_hab','hab_fecha','hab_accion','id_user'];
    protected $table='habilitacion';
    protected $primaryKey='cod_hab';
}
