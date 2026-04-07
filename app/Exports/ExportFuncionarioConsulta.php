<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Iterator;

class ExportFuncionarioConsulta implements FromIterator, WithHeadings
{
    use Exportable;
    protected $resultado;

    public function __construct($resultado)
    {
        $this->resultado = $resultado;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'CI',
            'Facultad',
            'Carrera',
            'Tipo de Funcionario',
            'Título/Diploma',
            'Tipo de Documento',
            'Es Tesis',
            'Título de Tesis',
            'Universidad',
            'Tipo de Universidad',
            'Educación Superior',
            'Revalidación',
            'Documento Verificado',
            'Legalizado',
            'UMSS',
            'Documentos Faltantes'
        ];
    }

    public function iterator(): Iterator
    {
        // Usar generador para procesar de uno en uno sin cargar todo en memoria
        foreach($this->resultado as $item) {
            $tipo_funcionario = $this->getNombreTipo($item->fun_doc_adm);
            
            // Si el funcionario tiene documentos, crear una fila por documento
            if(isset($item->documentos) && count($item->documentos) > 0) {
                $es_primera_fila = true;
                foreach($item->documentos as $doc) {
                    $faltantes = '';
                    
                    if($es_primera_fila && isset($item->estado_carpeta)) {
                        $faltantes = $item->estado_carpeta['completo'] ? '' : implode(', ', $item->estado_carpeta['faltantes']);
                        $es_primera_fila = false;
                    }
                    
                    yield [
                        'nombre' => $item->fun_nombre,
                        'ci' => $item->fun_ci,
                        'facultad' => $item->fun_facultad,
                        'carrera' => $item->fun_carrera,
                        'tipo_funcionario' => $tipo_funcionario,
                        'titulo' => $doc['titulo'],
                        'tipo_documento' => $doc['tipo'],
                        'es_tesis' => $doc['es_tesis'],
                        'titulo_tesis' => $doc['titulo_tesis'],
                        'universidad' => $doc['universidad'],
                        'tipo_universidad' => $doc['tipo_universidad'],
                        'edu_superior' => $doc['edu_superior'],
                        'revalida' => $doc['revalida'],
                        'verificado' => $doc['verificado'],
                        'legalizado' => $doc['legalizado'],
                        'umss' => $doc['umss'],
                        'faltantes' => $faltantes
                    ];
                }
            } else {
                // Si no tiene documentos, crear una fila con datos vacíos
                yield [
                    'nombre' => $item->fun_nombre,
                    'ci' => $item->fun_ci,
                    'facultad' => $item->fun_facultad,
                    'carrera' => $item->fun_carrera,
                    'tipo_funcionario' => $tipo_funcionario,
                    'titulo' => '',
                    'tipo_documento' => '',
                    'es_tesis' => '',
                    'titulo_tesis' => '',
                    'universidad' => '',
                    'tipo_universidad' => '',
                    'edu_superior' => '',
                    'revalida' => '',
                    'verificado' => '',
                    'legalizado' => '',
                    'umss' => '',
                    'faltantes' => isset($item->estado_carpeta) ? implode(', ', $item->estado_carpeta['faltantes']) : 'Sin documentos'
                ];
            }
        }
    }

    private function getNombreTipo($tipo)
    {
        switch($tipo) {
            case 'D':
                return 'DOCENTE';
            case 'A':
                return 'ADMINISTRATIVO';
            case 'E':
                return 'DOCENTE Y ADMINISTRATIVO';
            default:
                return $tipo;
        }
    }
}
