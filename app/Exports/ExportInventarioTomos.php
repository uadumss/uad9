<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportInventarioTomos implements WithMultipleSheets
{
    use Exportable;

    private array $filasPrincipal;
    private int $anchoPrincipal;
    private array $filasTotales;
    private int $anchoTotales;
    private string $titulo;
    private int $filaTotalGeneralTotales;

    public function __construct(
        array $filasPrincipal,
        int $anchoPrincipal,
        array $filasTotales,
        int $anchoTotales,
        string $titulo,
        int $filaTotalGeneralTotales
    ) {
        $this->filasPrincipal = $filasPrincipal;
        $this->anchoPrincipal = $anchoPrincipal;
        $this->filasTotales = $filasTotales;
        $this->anchoTotales = $anchoTotales;
        $this->titulo = $titulo;
        $this->filaTotalGeneralTotales = $filaTotalGeneralTotales;
    }

    public function sheets(): array
    {
        return [
            new InventarioTomosSheet(
                'INVENTARIO',
                $this->titulo,
                $this->filasPrincipal,
                $this->anchoPrincipal,
                false,
                0,
                false
            ),
            new InventarioTomosSheet(
                'TOTALES',
                'TABLA DE TOTALES',
                $this->filasTotales,
                $this->anchoTotales,
                true,
                $this->filaTotalGeneralTotales,
                true
            ),
        ];
    }
}

class InventarioTomosSheet implements FromArray, WithEvents, WithTitle
{
    private string $nombreHoja;
    private string $titulo;
    private array $filas;
    private int $anchoFila;
    private bool $resaltarFilaFinal;
    private int $filaFinalDatos;
    private bool $esHojaTotales;

    public function __construct(
        string $nombreHoja,
        string $titulo,
        array $filas,
        int $anchoFila,
        bool $resaltarFilaFinal,
        int $filaFinalDatos,
        bool $esHojaTotales
    ) {
        $this->nombreHoja = $nombreHoja;
        $this->titulo = $titulo;
        $this->filas = $filas;
        $this->anchoFila = $anchoFila;
        $this->resaltarFilaFinal = $resaltarFilaFinal;
        $this->filaFinalDatos = $filaFinalDatos;
        $this->esHojaTotales = $esHojaTotales;
    }

    public function title(): string
    {
        return $this->nombreHoja;
    }

    public function array(): array
    {
        $contenido = [];
        $contenido[] = $this->completarFila([$this->titulo], $this->anchoFila);
        $contenido[] = $this->completarFila([], $this->anchoFila);

        foreach ($this->filas as $fila) {
            $contenido[] = $this->completarFila($fila, $this->anchoFila);
        }

        return $contenido;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $ultimaColumna = Coordinate::stringFromColumnIndex($this->anchoFila);
                $ultimaFila = count($this->filas) + 2;
                $sheet = $event->sheet->getDelegate();

                $event->sheet->mergeCells('A1:'.$ultimaColumna.'1');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $event->sheet->getStyle('A3:'.$ultimaColumna.'3')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B7280']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $event->sheet->getStyle('A3:'.$ultimaColumna.$ultimaFila)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                if ($this->esHojaTotales) {
                    $event->sheet->getColumnDimension('A')->setWidth(40);
                    if ($this->anchoFila >= 2) {
                        $event->sheet->getColumnDimension('B')->setWidth(14);
                    }
                    if ($this->anchoFila >= 3) {
                        $event->sheet->getColumnDimension('C')->setWidth(14);
                    }

                    // En hoja de totales, columnas numericas empiezan en B y los datos en fila 5.
                    for ($fila = 5; $fila <= $ultimaFila; $fila++) {
                        for ($col = 2; $col <= $this->anchoFila; $col++) {
                            $letra = Coordinate::stringFromColumnIndex($col);
                            $celda = $letra.$fila;
                            $valor = $sheet->getCell($celda)->getValue();
                            if ($valor === null || $valor === '') {
                                $sheet->setCellValueExplicit($celda, 0, DataType::TYPE_NUMERIC);
                            }
                        }
                    }
                } else {
                    $event->sheet->getColumnDimension('A')->setWidth(6);
                    if ($this->anchoFila >= 2) {
                        $event->sheet->getColumnDimension('B')->setWidth(10);
                    }
                    for ($i = 3; $i <= $this->anchoFila; $i++) {
                        $col = Coordinate::stringFromColumnIndex($i);
                        $event->sheet->getColumnDimension($col)->setWidth(18);
                    }

                    // En hoja principal, columnas numericas empiezan en C y los datos en fila 4.
                    for ($fila = 4; $fila <= $ultimaFila; $fila++) {
                        for ($col = 3; $col <= $this->anchoFila; $col++) {
                            $letra = Coordinate::stringFromColumnIndex($col);
                            $celda = $letra.$fila;
                            $valor = $sheet->getCell($celda)->getValue();
                            if ($valor === null || $valor === '') {
                                $sheet->setCellValueExplicit($celda, 0, DataType::TYPE_NUMERIC);
                            }
                        }
                    }
                }

                if ($this->resaltarFilaFinal && $this->filaFinalDatos > 0) {
                    $filaExcel = $this->filaFinalDatos + 2;
                    $event->sheet->getStyle('A'.$filaExcel.':'.$ultimaColumna.$filaExcel)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B7280']],
                    ]);
                }
            },
        ];
    }

    private function completarFila(array $fila, int $anchoFila): array
    {
        $faltantes = $anchoFila - count($fila);
        if ($faltantes <= 0) {
            return $fila;
        }

        return array_merge($fila, array_fill(0, $faltantes, ''));
    }
}
