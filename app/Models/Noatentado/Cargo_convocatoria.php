<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo_convocatoria extends Model
{
    use HasFactory;
    protected $fillable=['carg_nombre','cod_con'];
    protected $table='claustros.cargo_convocatoria';
    protected $primaryKey='cod_carg';
}
