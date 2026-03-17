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
        $cabecera= ['Nombre', 	'CI' ,'Facultad','Carrera'];
        return $cabecera;
    }
    public function array(): array
    {
        return $this->resultado;
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
