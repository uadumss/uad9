<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $fillable=['name','leyenda','guard_name','objeto'];
    protected $primaryKey='id';
    protected $table='seguridad.permissions';
}
