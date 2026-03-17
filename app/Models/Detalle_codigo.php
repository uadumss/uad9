<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_codigo extends Model
{
    use HasFactory;

    protected $fillable=['det_nombre','cod_carch'];
    protected $table='detalle_codigo';
    protected $primaryKey='cod_det';
}
