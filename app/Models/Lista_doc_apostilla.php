<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_doc_apostilla extends Model
{
    use HasFactory;
    protected $fillable = ['lis_nombre', 'lis_cuenta','lis_monto','lis_hab','lis_resolucion','lis_tipo','lis_desc','lis_alias'];
    protected $primaryKey = 'cod_lis';
    protected $table='apostilla.lista_doc_apostilla';
}
