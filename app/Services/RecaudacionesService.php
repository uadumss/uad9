<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaudacionesService
{
    public function buscarPorControlYDocumento(int $unidad, int $recibo, string $documento): array
    {
        return $this->consultar([
            'unidad' => $unidad,
            'recibo' => $recibo,
            'documento' => $documento,
        ]);
    }

    public function buscarPorControl(int $unidad, int $recibo): array
    {
        return $this->consultar([
            'unidad' => $unidad,
            'recibo' => $recibo,
        ]);
    }

    public function buscarPorDocumento(int $unidad, string $documento): array
    {
        return $this->consultar([
            'unidad' => $unidad,
            'documento' => $documento,
        ]);
    }

    public function consultar(array $payload): array
    {
        $baseUrl = rtrim((string) config('services.recaudaciones.url'), '/');
        $token = (string) config('services.recaudaciones.token');
        $verifySsl = filter_var(config('services.recaudaciones.verify_ssl', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($verifySsl === null) {
            $verifySsl = true;
        }

        if ($baseUrl === '' || $token === '') {
            return $this->error('SISTEMA_NO_CONFIGURADO', 'El sistema de recaudaciones no esta configurado. Contacte al area de sistemas.', 500);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(20)
                ->withOptions(['verify' => $verifySsl])
                ->post($baseUrl, $payload);

            if ($response->successful()) {
                return [
                    'ok' => true,
                    'status' => $response->status(),
                    'data' => $response->json(),
                ];
            }

            Log::warning('La API de recaudaciones respondió con error.', [
                'status' => $response->status(),
                'payload' => $payload,
            ]);

            $mensaje = (string) data_get($response->json(), 'error.message', '');
            if (trim($mensaje) === '') {
                $mensaje = (string) data_get($response->json(), 'message', '');
            }

            $error = $this->mapearMensajeError($mensaje, $response->status());

            return $this->error($error['code'], $error['message'], $response->status());
        } catch (RequestException $e) {
            Log::warning('Error de comunicación con la API de recaudaciones.', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return $this->error('API_NO_DISPONIBLE', 'Error en la comunicacion con la API de recaudaciones', 502);
        } catch (\Throwable $e) {
            Log::error('Error inesperado en recaudaciones.', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return $this->error('API_RECAUDACIONES_ERROR', 'Error inesperado en recaudaciones', 500);
        }
    }

    private function error(string $code, string $message, int $status): array
    {
        return [
            'ok' => false,
            'code' => $code,
            'message' => $message,
            'status' => $status,
        ];
    }

    /**
     * Método centralizado para mapear errores de recaudaciones.
     * Detecta y clasifica mensajes de error para normalizar respuestas.
     * Uso: Todos los controllers deben usar este método en lugar de implementar su propia lógica.
     */
    public function mapearMensajeErrorComun(string $mensajeApi, int $status = 0): array
    {
        $mensajeApi = trim($mensajeApi);
        $msgNorm = mb_strtolower($mensajeApi);

        // RATE LIMIT - Demasiadas solicitudes
        if ($status === 429 || strpos($msgNorm, 'too many') !== false || strpos($msgNorm, 'demasiadas solicitudes') !== false || strpos($msgNorm, 'rate limit') !== false) {
            return [
                'code' => 'RATE_LIMIT',
                'message' => 'Demasiadas solicitudes a recaudaciones. Intente nuevamente en unos segundos.',
            ];
        }

        // SIN CONEXIÓN / TIMEOUT - Detectar PRIMERO antes de otros errores
        if (
            $status === 0 ||
            $status === 502 ||
            $status === 503 ||
            $status === 504 ||
            strpos($msgNorm, 'error de comunicación') !== false ||
            strpos($msgNorm, 'error inesperado en recaudaciones') !== false ||
            strpos($msgNorm, 'error en la comunicacion con la api') !== false ||
            strpos($msgNorm, 'sin conexion') !== false ||
            strpos($msgNorm, 'sin conexión') !== false ||
            strpos($msgNorm, 'timeout') !== false ||
            strpos($msgNorm, 'unable to resolve') !== false ||
            strpos($msgNorm, 'connection refused') !== false ||
            strpos($msgNorm, 'connection reset') !== false ||
            strpos($msgNorm, 'connection timed out') !== false ||
            strpos($msgNorm, 'no se pudo conectar') !== false ||
            strpos($msgNorm, 'no se pudo establecer') !== false
        ) {
            return [
                'code' => 'API_NO_DISPONIBLE',
                'message' => 'Sin conexión con recaudaciones. Intente nuevamente.',
            ];
        }

        // CONFIGURACIÓN NO DISPONIBLE
        if (
            strpos($msgNorm, 'configuracion') !== false ||
            strpos($msgNorm, 'configuración') !== false ||
            strpos($msgNorm, 'services/.env') !== false ||
            strpos($msgNorm, 'no esta configurado') !== false ||
            strpos($msgNorm, 'no está configurado') !== false ||
            strpos($msgNorm, 'sistema no configurado') !== false
        ) {
            return [
                'code' => 'SISTEMA_NO_CONFIGURADO',
                'message' => 'El sistema de recaudaciones no esta configurado. Contacte al area de sistemas.',
            ];
        }

        // CONTROL NO ENCONTRADO
        if (
            $status === 404 ||
            strpos($msgNorm, 'not found') !== false ||
            strpos($msgNorm, 'no se encuentra') !== false ||
            strpos($msgNorm, 'no encontrado') !== false ||
            strpos($msgNorm, 'control') !== false ||
            strpos($msgNorm, 'recibo') !== false ||
            strpos($msgNorm, 'valido') !== false ||
            strpos($msgNorm, 'válido') !== false
        ) {
            return [
                'code' => 'CONTROL_NO_ENCONTRADO',
                'message' => 'No se encontró información del número de control en recaudaciones.',
            ];
        }

        // ERROR DE SOLICITUD HTTP (400-499)
        if ($status > 0 && $status < 500 && $status !== 404) {
            return [
                'code' => 'API_RECAUDACIONES_ERROR',
                'message' => 'No se pudo validar el control en recaudaciones. Verifique los datos e intente nuevamente.',
            ];
        }

        // ERROR GENÉRICO (por defecto)
        return [
            'code' => 'API_RECAUDACIONES_ERROR',
            'message' => 'No se pudo validar el control en recaudaciones. Intente nuevamente.',
        ];
    }

    private function mapearMensajeError(string $mensajeApi, int $status = 0): array
    {
        return $this->mapearMensajeErrorComun($mensajeApi, $status);
    }
}