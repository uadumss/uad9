<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Carrera;
use App\Models\CarreraCampo;

class CarreraCamposImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // Guardamos logs para ver si no encuentra carreras
        $noEncontradas = [];

        foreach ($rows as $row) {
            $nombreCarrera = $row['nombre_carrera'] ?? null;
            $campoAmplio = $row['campo_amplio'] ?? null;
            $campoEspecifico = $row['campo_especifico'] ?? null;
            $campoDetallado = $row['campo_detallado'] ?? null;

            if (!$nombreCarrera) {
                continue;
            }

            // Buscar carrera por nombre exacto
            $carrera = Carrera::where('car_nombre', $nombreCarrera)->first();

            if ($carrera) {
                CarreraCampo::updateOrCreate(
                    ['cod_car' => $carrera->cod_car],
                    [
                        'campo_amplio' => $campoAmplio,
                        'campo_especifico' => $campoEspecifico,
                        'campo_detallado' => $campoDetallado,
                    ]
                );
            } else {
                $noEncontradas[] = $nombreCarrera;
            }
        }

        // Si hay errores, los mostramos al finalizar
        if (count($noEncontradas) > 0) {
            echo "ATENCIÓN: No se encontraron las siguientes carreras en la base de datos (revisa los espacios o tildes):\n";
            foreach ($noEncontradas as $noEnc) {
                echo "- $noEnc\n";
            }
        }
    }
}
