<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $fillable=['fac_nombre','fac_abreviacion'];
    protected $primaryKey='cod_fac';
}
