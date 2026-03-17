<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabaja extends Model
{
    use HasFactory;
    protected $fillable=['cod_car','cod_fun'];
    protected $primaryKey='cod_trb';
    protected $table='doc_adm.trabajas';
}
