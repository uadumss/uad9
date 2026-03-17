<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;
    protected $fillable=['cod_tem','id','tem_des','tem_titulo','tema_hab'];
    protected $table='public.temas';
    protected $primaryKey='cod_tem';
    protected $keyType = 'string';
    public $incrementing = false;
}
