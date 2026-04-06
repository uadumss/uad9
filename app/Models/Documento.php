<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormularioConformidad;

class Documento extends Model
{
    use HasFactory;
    protected $fillable=['cod_fun','cod_fcon','doc_titulo','doc_tipo','doc_gestion','doc_fecha_emision','doc_universidad','doc_verificado','doc_legalizado',
                        'doc_edu_superior','doc_numero_revalida','doc_umss','doc_extranjero','doc_obs','doc_grado','doc_pdf','doc_enviado_dpa'];
    protected $primaryKey='cod_doc';
    protected $table='doc_adm.documentos';

    public function formularioConformidad()
    {
        return $this->belongsTo(FormularioConformidad::class, 'cod_fcon', 'cod_fcon');
    }
}
