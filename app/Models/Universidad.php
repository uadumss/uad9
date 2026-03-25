<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    protected $table = 'doc_adm.universidades';
    protected $fillable = ['nombre', 'sigla', 'tipo'];
    public $timestamps = true;
}
