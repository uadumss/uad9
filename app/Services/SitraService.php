<?php

namespace App\Services;

use App\Models\Funciones;
use App\Models\Titulo;
use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class SitraService
{
    /**
     * Consulta a la API externa del SITRA
     */
    public function consultarSitra(string $ci, string $numero, string $tipo)
    {
        $documento = Funciones::DocumentoSitra($tipo);
        $ruta = "http://sitra.umss.net/consulta/api/ci/" . $ci . "/numero/" . $numero . "/tipo/" . $documento;
        
        try {
            $data = json_decode(file_get_contents($ruta));
            return $data;
        } catch (\Throwable $e) {
            return (object)[];
        }
    }

    /**
     * Verifica si los nombres coinciden entre SITRA y la base local
     */
    public function nombresCompatibles(string $nombreLocal, string $nombreSitra): bool
    {
        $local = $this->normalizarTexto($nombreLocal);
        $sitra = $this->normalizarTexto($nombreSitra);

        if ($local === '' || $sitra === '') {
            return false;
        }

        if ($local === $sitra) {
            return true;
        }

        return strpos($local, $sitra) !== false || strpos($sitra, $local) !== false;
    }

    /**
     * Normaliza el parámetro 'buscar_en' para el SITRA
     */
    public function normalizarBuscarEn(string $buscarEn): string
    {
        $buscarEn = trim($buscarEn);
        if ($buscarEn === '') {
            return '';
        }

        $buscarEn = explode('-', $buscarEn)[0] ?? '';
        return strtolower(trim($buscarEn));
    }

    /**
     * Obtiene el código de búsqueda para SITRA a partir de un trámite y un formulario
     */
    public function obtenerBuscarEn(Tramite $tramita, string $buscarEnFormulario = ''): string
    {
        $buscarEn = $this->normalizarBuscarEn($buscarEnFormulario);
        if ($buscarEn !== '') {
            return $buscarEn;
        }

        return $this->normalizarBuscarEn((string)($tramita->tre_buscar_en ?? ''));
    }

    /**
     * Determina si el tipo de documento debe validarse en SITRA
     */
    public function debeValidar(string $buscarEn): bool
    {
        return trim((string)Funciones::DocumentoSitra($buscarEn)) !== '';
    }

    /**
     * Busca el título/respaldo en la base local (UAD9/SID)
     */
    public function buscarRespaldoInterno(int $idPer, string $numero, string $buscarEn, string $gestion = ''): ?Titulo
    {
        if ($idPer <= 0 || trim($numero) === '' || trim($buscarEn) === '') {
            return null;
        }

        $query = Titulo::where('id_per', '=', $idPer)
            ->whereRaw('CAST(tit_nro_titulo AS INTEGER) = ?', [(int)$numero]);

        if (trim($gestion) !== '') {
            $query->where('tit_gestion', '=', trim($gestion));
        }

        if ($buscarEn === 'da') {
            $query->whereIn('tit_tipo', ['da', 'ca']);
        } elseif ($buscarEn === 'tpos') {
            $query->whereIn('tit_tipo', ['tpos', 'di']);
        } else {
            $query->where('tit_tipo', '=', $buscarEn);
        }

        \Log::info('DEBUG QUERY TITULO (SitraService)', [
            'id_per' => $idPer,
            'numero' => $numero,
            'gestion' => $gestion,
            'buscar_en' => $buscarEn
        ]);

        return $query->first();
    }

    /**
     * Resuelve el título buscando con y sin gestión
     */
    public function resolverTitulo(int $idPer, string $numero, string $buscarEn, string $gestion = ''): ?Titulo
    {
        $buscarEn = trim($buscarEn);
        if ($idPer <= 0 || trim($numero) === '' || $buscarEn === '') {
            return null;
        }

        $buscarBase = explode('-', $buscarEn)[0];
        $titulo = $this->buscarRespaldoInterno($idPer, $numero, $buscarBase, $gestion);
        if ($titulo) {
            return $titulo;
        }

        return $this->buscarRespaldoInterno($idPer, $numero, $buscarBase, '');
    }

    /**
     * Resuelve el título buscando a través de diferentes IDs de persona (si comparten CI)
     */
    public function resolverTituloPorPersonaYBuscarEn(int $idPer, string $buscarEn): ?Titulo
    {
        if ($idPer <= 0) {
            return null;
        }

        $buscarBase = strtolower(trim(explode('-', $buscarEn)[0] ?? ''));
        $idsPersona = [$idPer];
        $ciPersona = trim((string)DB::table('personas')->where('id_per', '=', $idPer)->value('per_ci'));
        
        if ($ciPersona !== '') {
            $idsCi = DB::table('personas')->where('per_ci', '=', $ciPersona)->pluck('id_per')->all();
            if (!empty($idsCi)) {
                $idsPersona = $idsCi;
            }
        }

        $query = Titulo::whereIn('id_per', $idsPersona)
            ->whereNotNull('tit_fecha_emision');

        if ($buscarBase === 'da') {
            $query->whereIn('tit_tipo', ['da', 'ca']);
        } elseif ($buscarBase === 'tpos') {
            $query->whereIn('tit_tipo', ['tpos', 'di']);
        } else {
            if ($buscarBase !== '') {
                $query->where('tit_tipo', '=', $buscarBase);
            }
        }

        return $query->orderByDesc('cod_tit')->first();
    }

    private function normalizarTexto(string $texto): string
    {
        $texto = trim($texto);
        if ($texto === '') {
            return '';
        }

        $texto = strtoupper($texto);
        $texto = preg_replace('/[ÁÀÂÄ]/u', 'A', $texto);
        $texto = preg_replace('/[ÉÈÊË]/u', 'E', $texto);
        $texto = preg_replace('/[ÍÌÎÏ]/u', 'I', $texto);
        $texto = preg_replace('/[ÓÒÔÖ]/u', 'O', $texto);
        $texto = preg_replace('/[ÚÙÛÜ]/u', 'U', $texto);
        $texto = str_replace(['Ñ', 'Ç'], ['N', 'C'], $texto);
        
        return preg_replace('/[^A-Z0-9\s]/', '', $texto) ?? '';
    }
}
