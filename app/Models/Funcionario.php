<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;
    protected $fillable=['cod_nac','fun_apellido','fun_nombre','fun_ci','fun_telefonos','fun_email','fun_sexo','fun_fecha_ingreso','fun_nacionalidad',
        'fun_doc_adm','fun_obs','fun_titular','fun_db','fun_da','fun_tp','fun_ddu','fun_obs_personal','fun_estado','fun_facultad','fun_carrera',
        'dt_universidad','fun_actualizado','fun_folder','fun_fecha_ingreso_u','fun_fecha_folder','fun_cargo','fun_tipo_contrato'];
    protected $primaryKey='cod_fun';
    protected $table='doc_adm.funcionarios';
}
