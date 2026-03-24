<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
//use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportFuncionarioConsulta implements FromArray,WithHeadings
{
    use Exportable;
    protected $resultado;

    public function __construct($resultado)
    {
        $this->resultado= $resultado;
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
            'Universidad',
            'Tipo de Universidad',
            'Educación Superior',
            'Revalidación',
            'Documento Verificado',
            'Documentos Faltantes'
        ];
    }
    public function array(): array
    {
        $resultado = [];
        foreach($this->resultado as $item) {
            $tipo_funcionario = $this->getNombreTipo($item->fun_doc_adm);
            
            // Si el funcionario tiene documentos, crear una fila por documento
            if(isset($item->documentos) && count($item->documentos) > 0) {
                $es_primera_fila = true;
                foreach($item->documentos as $doc) {
                    $estado = '';
                    $faltantes = '';
                    
                    if($es_primera_fila && isset($item->estado_carpeta)) {
                        $faltantes = $item->estado_carpeta['completo'] ? '' : implode(', ', $item->estado_carpeta['faltantes']);
                        $es_primera_fila = false;
                    }
                    
                    $resultado[] = [
                        'nombre' => $item->fun_nombre,
                        'ci' => $item->fun_ci,
                        'facultad' => $item->fun_facultad,
                        'carrera' => $item->fun_carrera,
                        'tipo_funcionario' => $tipo_funcionario,
                        'titulo' => $doc['titulo'],
                        'tipo_documento' => $doc['tipo'],
                        'universidad' => $doc['universidad'],
                        'tipo_universidad' => $doc['tipo_universidad'],
                        'edu_superior' => $doc['edu_superior'],
                        'revalida' => $doc['revalida'],
                        'verificado' => $doc['verificado'],
                        'faltantes' => $faltantes
                    ];
                }
            } else {
                // Si no tiene documentos, crear una fila con datos vacíos
                $resultado[] = [
                    'nombre' => $item->fun_nombre,
                    'ci' => $item->fun_ci,
                    'facultad' => $item->fun_facultad,
                    'carrera' => $item->fun_carrera,
                    'tipo_funcionario' => $tipo_funcionario,
                    'titulo' => '',
                    'tipo_documento' => '',
                    'universidad' => '',
                    'tipo_universidad' => '',
                    'edu_superior' => '',
                    'revalida' => '',
                    'verificado' => '',
                    'faltantes' => isset($item->estado_carpeta) ? implode(', ', $item->estado_carpeta['faltantes']) : 'Sin documentos'
                ];
            }
        }
        return $resultado;
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
    /*public function prepareRows($rows)
    {
        return $rows->transform(function ($titulo) {
            if($titulo->tit_fecha_emision!=''){
                $titulo->tit_fecha_emision = date('d/m/Y',strtotime($titulo->tit_fecha_emision));
            }
            $titulo->cod_tit=$this->numero;
            $this->numero++;
            return $titulo;
        });
    }
    public function map($resultado): array
    {
        return [
            $resultado->fun_nombre,
        ];
    }
*/
}
