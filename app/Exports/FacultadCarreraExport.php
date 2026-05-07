<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FacultadCarreraExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $registros;

    public function __construct(Collection $registros)
    {
        $this->registros = $registros;
    }

    /**
    * @return Collection
    */
    public function collection()
    {
        return $this->registros;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'FACULTAD',
            'ABREVIACION FACULTAD',
            'CARRERA',
            'ABREVIACION CARRERA',
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
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 24,
            'C' => 52,
            'D' => 24,
        ];
    }
}
