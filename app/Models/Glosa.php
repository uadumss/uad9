<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glosa extends Model
{
    protected $fillable=['glo_titulo','glo_glosa','cod_tre'];
    protected $primaryKey='cod_glo';
    protected $table='glosas';
}
