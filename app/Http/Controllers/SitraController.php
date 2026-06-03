<?php

namespace App\Http\Controllers;

use App\Services\SitraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SitraController extends Controller
{
    /**
     * Autocompletar título y fecha de emisión basado en CI, número y serie de título
     * 
     * @param Request $request - Debe contener: ci, nro_titulo, nro_serie, tipo (opcional)
     * @return JSON
     */
    public function autocompletarTitulo(Request $request, SitraService $sitraService)
    {
        Log::info('TIPO CRUDO REQUEST', [
        'all' => $request->all()
        ]);
        // Validar datos requeridos
        $request->validate([
            'ci'         => 'required|string',
            'nro_titulo' => 'required|string',
            'tipo'       => 'nullable|string',
        ]);

        $ci = trim($request->input('ci', ''));
        $nroTitulo = trim($request->input('nro_titulo', ''));
        $tipo = trim($request->input('tipo', 'tp'));

        Log::info('📋 Inicio', [
            'ci'        => $ci,
            'nro_titulo' => $nroTitulo,
            'tipo'      => $tipo,
        ]);

        // Validar que no estén vacíos
        if (empty($ci) || empty($nroTitulo)) {
            Log::warning('⚠️ Parámetros vacíos', [
                'ci_empty'        => empty($ci),
                'nro_titulo_empty' => empty($nroTitulo),
                
            ]);

            return response()->json([
                'ok'    => false,
                'error' => 'Parámetros incompletos',
            ], 400);
        }

        try {
            // Extraer el número de serie sin prefijo (si viene con prefijo)
            // Ejemplo: "T-220304" → "220304"
            

            // Buscar persona por CI
            $persona = \DB::table('personas')
                ->where('per_ci', '=', $ci)
                ->first();

            if (!$persona) {
                Log::info('ℹ️ SitraController@autocompletarTitulo - Persona no encontrada en BD local', [
                    'ci' => $ci,
                ]);

                return response()->json([
                    'ok'    => false,
                    'error' => 'Persona no registrada en el sistema',
                ], 404);
            }

            $idPersona = $persona->id_per;

            Log::info('✅ Persona encontrada', [
                'id_per'       => $idPersona,
                'per_nombre'   => $persona->per_nombre,
                'per_apellido' => $persona->per_apellido,
            ]);

            // **PASO 1: Buscar en la tabla local de títulos**
            Log::info('🔍 Buscando en BD local (titulos)...', [
                'id_per' => $idPersona,
                'nro_titulo' => $nroTitulo,
            ]);

            $titulo = \DB::table('titulos')
                ->where('id_per', '=', $idPersona)
                ->where('tit_nro_titulo', '=', $nroTitulo)
                ->first();

            if ($titulo) {
                Log::info('✅ Título encontrado en BD local', [
                    'cod_tit'             => $titulo->cod_tit,
                    'tit_titulo'          => $titulo->tit_titulo,
                    'tit_fecha_emision'   => $titulo->tit_fecha_emision,
                ]);

                return response()->json([
                    'ok'            => true,
                    'titulo'        => $titulo->tit_titulo ?? '',
                    'fecha_emision' => $titulo->tit_fecha_emision ? date('Y-m-d', strtotime($titulo->tit_fecha_emision)) : '',
                    'serie' => $respuestaSitra->serie ?? '',
                    'fuente'        => 'local',
                ]);
            }

            Log::info('ℹ️ Título NO encontrado en BD local, consultando SITRA...');

            // **PASO 2: Consultar SITRA si no está en BD local**
            // SITRA espera la serie con prefijo completo
            $respuestaSitra = $sitraService->consultarSitra($ci, $nroTitulo, $tipo);

            Log::info('📡 Respuesta de SITRA recibida', [
                'respuesta' => $respuestaSitra,
            ]);

            if (!$respuestaSitra || empty($respuestaSitra)) {
                Log::warning('⚠️ SITRA no devolvió datos', [
                    'ci'        => $ci,
                    'nro_titulo' => $nroTitulo,
                    'tipo'      => $tipo,
                ]);

                return response()->json([
                    'ok'    => false,
                    'error' => 'No se encontraron datos en SITRA',
                    'fuente' => 'sitra',
                ], 404);
            }

            // Extraer datos de SITRA
            $tituloSitra = $respuestaSitra->titulo ?? $respuestaSitra->Titulo ?? '';
            $fechaSitra = $respuestaSitra->fecha_impresion ?? $respuestaSitra->Fecha_Impresion ?? '';
            $serieSitra = $respuestaSitra->serie ?? '';

            // Convertir fecha al formato YYYY-MM-DD si es necesario
            $fechaEmision = '';
            if (!empty($fechaSitra)) {
                $fechaEmision = $this->normalizarFecha($fechaSitra);
            }

            Log::info('✅ Datos extraídos de SITRA', [
                'titulo'          => $tituloSitra,
                'fecha_original'  => $fechaSitra,
                'fecha_formateada' => $fechaEmision,
                'serie'            => $serieSitra,
                'tipo_enviado' => $tipo,
            ]);

            return response()->json([
                'ok'            => true,
                'titulo'        => $tituloSitra,
                'fecha_emision' => $fechaEmision,
                'serie'         => $serieSitra,
                'fuente'        => 'sitra',
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ Error en SitraController@autocompletarTitulo', [
                'error'     => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'ok'    => false,
                'error' => 'Error interno del servidor',
            ], 500);
        }
    }

    /**
     * Normaliza una fecha a formato YYYY-MM-DD
     * Soporta formatos: DD/MM/YYYY, DD-MM-YYYY, YYYY-MM-DD
     */
    private function normalizarFecha($fecha)
    {
        $fecha = trim($fecha);

        if (empty($fecha)) {
            return '';
        }

        // Si ya está en formato YYYY-MM-DD, devolverlo
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return $fecha;
        }

        // Intentar parsear como DD/MM/YYYY o DD-MM-YYYY
        $partes = preg_split('/[\/\-]/', $fecha);

        if (count($partes) === 3) {
            [$parte1, $parte2, $parte3] = $partes;

            // Si la primera parte es 4 dígitos, asumir que es año
            if (strlen($parte1) === 4) {
                return sprintf('%s-%s-%s', $parte1, str_pad($parte2, 2, '0', STR_PAD_LEFT), str_pad($parte3, 2, '0', STR_PAD_LEFT));
            }

            // Si la tercera parte es 4 dígitos, asumir formato DD/MM/YYYY
            if (strlen($parte3) === 4) {
                return sprintf('%s-%s-%s', $parte3, str_pad($parte2, 2, '0', STR_PAD_LEFT), str_pad($parte1, 2, '0', STR_PAD_LEFT));
            }
        }

        Log::warning('⚠️ No se pudo normalizar la fecha', [
            'fecha_original' => $fecha,
        ]);

        return '';
    }
}
