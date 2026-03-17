<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A_cargo extends Model
{
    use HasFactory;
    protected $fillable=['ac_inicio','ac_fin','id_responsable','id','ac_hab'];
    protected $primaryKey='cod_ac';
}
