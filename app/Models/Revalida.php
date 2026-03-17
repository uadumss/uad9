<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revalida extends Model
{
    protected $fillable=['cod_nac','re_fecha','re_universidad','cod_tit'];
    protected $primaryKey='cod_tit';
}
