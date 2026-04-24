<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class ExportInventarioTomos implements FromArray
{
    use Exportable;

    private array $filas;

    public function __construct(array $filas, int $anchoFila, string $titulo)
    {
        $this->filas = [];

        $this->filas[] = $this->completarFila([$titulo], $anchoFila);
        $this->filas[] = $this->completarFila([], $anchoFila);

        foreach ($filas as $fila) {
            $this->filas[] = $this->completarFila($fila, $anchoFila);
        }
    }

    public function array(): array
    {
        return $this->filas;
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
