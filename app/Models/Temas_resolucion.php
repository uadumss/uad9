<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temas_resolucion extends Model
{
    use HasFactory;
    protected $fillable=['cod_tr','cod_tem','cod_res'];
    protected $primaryKey='cod_tr';
    protected $table='public.tema_resolucion';
    protected $keyType = 'string';
    public $incrementing = false;

}
