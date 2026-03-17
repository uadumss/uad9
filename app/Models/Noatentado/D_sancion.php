<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_sancion extends Model
{
    use HasFactory;
    protected $fillable=['cod_san','dsan_detalle'];
    protected $table='noatentado.d_sancion';
    protected $primaryKey='cod_dsan';
}
