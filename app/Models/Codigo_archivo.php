<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Codigo_archivo extends Model
{
    protected $fillable=['carch_numero','carch_desc','carch_titulo','carch_tema','cod_plan'];
    protected $primaryKey='cod_carch';
}
