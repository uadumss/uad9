<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    protected $fillable=['tre_nombre','tre_costo','tre_duracion','tre_desc','tre_titulo','tre_glosa','tre_tipo','tre_hab'
        ,'tre_buscar_en','tre_titulo_interno','tre_numero_cuenta','tre_solo_sello'];
    protected $primaryKey='cod_tre';
}
