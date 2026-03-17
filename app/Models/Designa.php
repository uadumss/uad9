<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designa extends Model
{
    protected $fillable=['cod_tar','id','id_responsable','des_fech_asig','des_fech_ret','des_hab',
                        'des_concluido','des_rep_con','des_hab_con'];
    protected $primaryKey='cod_des';
}
