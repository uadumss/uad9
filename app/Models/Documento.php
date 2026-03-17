<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $fillable=['cod_fun','doc_titulo','doc_tipo','doc_gestion','doc_fecha_emision','doc_universidad','doc_verificado','doc_legalizado',
                        'doc_edu_superior','doc_numero_revalida','doc_umss','doc_extranjero','doc_obs','doc_grado'];
    protected $primaryKey='cod_doc';
    protected $table='doc_adm.documentos';
}
