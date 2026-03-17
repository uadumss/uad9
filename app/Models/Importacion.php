<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Importacion extends Model
{
    use SoftDeletes;
    protected $fillable=['imp_usuario','imp_id','imp_fecha','imp_tipo','imp_archivo','imp_gestion','imp_identificador','imp_deshecho','imp_sistema'];
    protected $primaryKey='cod_imp';
}
