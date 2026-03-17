<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;
    protected $fillable=['uni_nombre','uni_institucion','uni_nivel','uni_hab','uni_abreviacion'];
    protected $table='unidad';
    protected $primaryKey='cod_uni';
}
