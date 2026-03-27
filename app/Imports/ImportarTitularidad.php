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
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithEvents};
use Maatwebsite\Excel\Events\AfterImport;
use Carbon\Carbon;

class ImportarTitularidad implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithEvents
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
                'fun_fecha_importacion' => Carbon::now(),
                'fun_habilitado' => true,
            ];
            
            $existe = Funcionario::where('fun_ci', $rowNormalizado['ci'])->exists();

            if ($existe) {
                // Ya existe, solo actualiza la fecha de importación y estado de habilitado
                Funcionario::where('fun_ci', $rowNormalizado['ci'])->update([
                    'fun_fecha_importacion' => Carbon::now(),
                    'fun_habilitado' => true,
                ]);
            } else {
                // No existe, crea con todos los datos
                Funcionario::create(array_merge(
                    ['fun_ci' => $rowNormalizado['ci']],
                    $datosActualizacion
                ));
            }

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

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Sincronizar secuencia de PostgreSQL después de importar
                DB::statement("SELECT setval('doc_adm.funcionarios_cod_fun_seq', COALESCE((SELECT MAX(cod_fun) FROM doc_adm.funcionarios) + 1, 1))");
            },
        ];
    }
}