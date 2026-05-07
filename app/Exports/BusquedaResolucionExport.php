<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusquedaResolucionExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $query;
    protected $numero = 1;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return clone $this->query;
    }

    public function headings(): array
    {
        return [
            'NRO',
            'NRO RESOLUCION',
            'FECHA',
            'TIPO',
            'DESCRIPCION',
            'OBJETO',
            'TEMA',
        ];
    }

    public function map($resolucion): array
    {
        $fila = [
            $this->numero,
            $resolucion->res_numero,
            $resolucion->res_fecha ? date('d/m/Y', strtotime($resolucion->res_fecha)) : '',
            strtoupper((string) $resolucion->res_tipo),
            $resolucion->res_desc,
            $resolucion->res_objeto,
            $resolucion->res_tema,
        ];

        $this->numero++;

        return $fila;
    }
}
