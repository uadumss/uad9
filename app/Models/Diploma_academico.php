<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diploma_academico extends Model
{
    protected $fillable=['cod_tit','cod_car'];
    protected $primaryKey='cod_tit';
}
