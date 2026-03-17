<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona_prueba extends Model
{
    use HasFactory;
    protected $fillable=['per_ci','per_pasaporte','per_apellido','per_nombre','per_cod_sis',
        'per_sexo','per_telefono','per_celular','per_contacto','cod_nac','per_ci_exp','per_email','per_sistema'];
    protected $table='personas_prueba';
    protected $primaryKey='id_per';
}
