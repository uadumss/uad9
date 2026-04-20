<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facultad extends Model
{
    protected $fillable=['fac_nombre','fac_abreviacion'];
    protected $primaryKey='cod_fac';

    public function historialNombres(): HasMany
    {
        return $this->hasMany(FacultadHistorialNombre::class,'cod_fac','cod_fac')
            ->orderByDesc('fecha_cambio')
            ->orderByDesc('cod_fhn');
    }
}
