<?php

namespace App\Imports;

use App\Models\Documento;
//use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Funcionario;
use App\Models\D_observacion;
use App\Models\T_observacion;
use App\Models\Titularidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithValidation, WithChunkReading};

class ImportarTitularidad implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rowNormalizado = [];
        foreach ($row as $key => $value) {
            $keyNormalizado = strtolower(trim(str_replace(' ', '', $key)));
            $rowNormalizado[$keyNormalizado] = $value;
        }

        if (empty($rowNormalizado['ci']) || empty($rowNormalizado['nombres'])) {
            return null;
        }

        $tipoFuncionario = 'A';
        if (!empty($rowNormalizado['sector'])) {
            $sectorLower = strtolower(trim($rowNormalizado['sector']));
            if (strpos($sectorLower, 'doc') !== false) {
                $tipoFuncionario = 'D';
            } elseif (strpos($sectorLower, 'adm') !== false) {
                $tipoFuncionario = 'A';
            }
        }

        try {
            $datosActualizacion = [
                'fun_nombre' => $rowNormalizado['nombres'],
                'fun_doc_adm' => $tipoFuncionario,
                'fun_carrera' => $rowNormalizado['actividad'] ?? '',
                'fun_facultad' => $rowNormalizado['da'] ?? '',
                'fun_estado' => 'A',
            ];
            
            $funcionario = Funcionario::updateOrCreate(
                ['fun_ci' => $rowNormalizado['ci']],
                $datosActualizacion
            );

            return null;
        } catch (\Exception $e) {
            \Log::error('Error importando CI: ' . $rowNormalizado['ci'] . ' - ' . $e->getMessage());
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
