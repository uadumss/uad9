<?php

namespace App\Models\Noatentado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalaCandidato extends Model
{
    use HasFactory;

    protected $table='noatentado.escala_candidatos';
    protected $primaryKey='cod_esc_noa';

    protected $fillable=[
        'cantidad_min',
        'cantidad_max',
        'costo',
        'aporte_umss',
        'monto_total',
        'orden',
        'habilitado',
    ];

    protected $casts=[
        'cantidad_min'=>'integer',
        'cantidad_max'=>'integer',
        'costo'=>'float',
        'aporte_umss'=>'float',
        'monto_total'=>'float',
        'orden'=>'integer',
        'habilitado'=>'boolean',
    ];
}
