<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;
use App\Models\Titularidad;

class FormularioConformidad extends Model
{
    use HasFactory;

    protected $table = 'doc_adm.formularios_conformidad';
    protected $primaryKey = 'cod_fcon';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cod_fun',
        'codigo',
        'lugar_trabajo',
        'carrera',
        'observaciones',
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'cod_fcon', 'cod_fcon');
    }

    public function titularidades()
    {
        return $this->hasMany(Titularidad::class, 'cod_fcon', 'cod_fcon');
    }
}
