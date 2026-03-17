<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    protected $fillable=['cod_res','cod_aut','cod_aut2'];
    protected $primaryKey='cod_fir';
}
