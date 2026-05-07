<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportesResolucionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $reportes;

    public function __construct($reportes)
    {
        $this->reportes = $reportes;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->reportes;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NUM',
            'FECHA',
            'REFERENCIA',
            'NOMBRE',
            'DESCRIPTOR',
            'TIPO DE RESOLUCION',
            'CÓDIGO',
            'AÑO',
            'TOMO'
        ];
    }

    /**
     * @var mixed $reporte
     */
    public function map($reporte): array
    {
        return [
            $reporte->res_numero ?? '',
            $reporte->res_fecha ? date('d/m/Y', strtotime($reporte->res_fecha)) : '',
            $reporte->res_desc ?? '',
            $reporte->res_objeto ?? '',
            $reporte->res_tema ?? '',
            strtoupper($reporte->res_tipo ?? ''),
            $reporte->codigos ?? '',
            $reporte->tom_gestion ?? $reporte->res_gestion ?? '',
            $reporte->tom_numero ?? ''
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4e73df']
                ],
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 30,
            'D' => 30,
            'E' => 25,
            'F' => 18,
            'G' => 15,
            'H' => 10,
            'I' => 15,
        ];
    }
}
