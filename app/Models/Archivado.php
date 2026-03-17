<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivado extends Model
{
    protected $fillable=['cod_carch','cod_res','cod_det'];
    protected $primaryKey='cod_arc';
}
