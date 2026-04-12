<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaCuadis extends Model
{
    protected $table='personas_cuadis';
    protected $primaryKey='id_per';
    public $incrementing=false;
    protected $keyType='int';

    protected $casts=[
        'pcu_hab'=>'boolean',
    ];

    protected $fillable=[
        'id_per',
        'pcu_hab',
        'pcu_respaldo',
        'pcu_observacion',
    ];
}
