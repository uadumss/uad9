<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noatentado extends Model
{
    use HasFactory;
    protected $fillable=['cod_dtra','noa_observacion','id_per','noa_cargo','cod_carg','noa_identificador','noa_unidad'];
    protected $table="noatentado.noatentado";
    protected $primaryKey='cod_noa';
}
