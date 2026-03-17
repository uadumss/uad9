<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autoridad extends Model
{
    protected $fillable=['aut_nombre','aut_cargo','aut_hab','aut_inicio','aut_fin'];
    protected $primaryKey='cod_aut';
}
