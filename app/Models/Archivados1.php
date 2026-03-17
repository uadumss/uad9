<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivados1 extends Model
{
    use HasFactory;
    protected $fillable=['cod_carch','cod_res','cod_det'];
    protected $primaryKey='cod_arc';
    protected $table='archivados1';
}
