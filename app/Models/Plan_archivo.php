<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan_archivo extends Model
{
    protected $fillable=['plan_numero','plan_titulo','plan_tipo'];
    protected $primaryKey='cod_plan';
}
