<?php

namespace App\Console\Commands;

use App\Services\RecaudacionesService;
use Illuminate\Console\Command;

class TestConexionRecaudaciones extends Command
{
    protected $signature = 'test:recaudaciones {--verbose}';
    protected $description = 'Prueba la conexión y detección de errores de RecaudacionesService';

    public function handle()
    {
        $this->info('=== Prueba de Conexión a Recaudaciones ===');
        $this->newLine();

        $service = app(RecaudacionesService::class);
        $verbose = $this->option('verbose');

        // Prueba 1: Validar que el servicio se mapee correctamente
        $this->line('📋 Prueba 1: Mapeo de mensajes de error');
        $testCases = [
            [
                'mensaje' => 'Sin conexión con recaudaciones',
                'status' => 0,
                'esperado' => 'API_NO_DISPONIBLE',
                'descripcion' => 'Mensaje sin conexión simple'
            ],
            [
                'mensaje' => 'Error en la comunicacion con la API de recaudaciones',
                'status' => 502,
                'esperado' => 'API_NO_DISPONIBLE',
                'descripcion' => 'Error de comunicación explícito'
            ],
            [
                'mensaje' => 'timeout',
                'status' => 0,
                'esperado' => 'API_NO_DISPONIBLE',
                'descripcion' => 'Timeout detectado'
            ],
            [
                'mensaje' => 'connection refused',
                'status' => 0,
                'esperado' => 'API_NO_DISPONIBLE',
                'descripcion' => 'Conexión rechazada'
            ],
            [
                'mensaje' => 'unable to resolve host',
                'status' => 0,
                'esperado' => 'API_NO_DISPONIBLE',
                'descripcion' => 'No se puede resolver host'
            ],
            [
                'mensaje' => 'No configurado',
                'status' => 0,
                'esperado' => 'SISTEMA_NO_CONFIGURADO',
                'descripcion' => 'Sistema no configurado'
            ],
            [
                'mensaje' => 'Control no encontrado',
                'status' => 404,
                'esperado' => 'CONTROL_NO_ENCONTRADO',
                'descripcion' => 'Control no encontrado (404)'
            ],
            [
                'mensaje' => 'Demasiadas solicitudes',
                'status' => 429,
                'esperado' => 'RATE_LIMIT',
                'descripcion' => 'Rate limit (429)'
            ],
        ];

        $pasadas = 0;
        $fallidas = 0;

        foreach ($testCases as $test) {
            $resultado = $service->mapearMensajeErrorComun($test['mensaje'], $test['status']);
            $esCorrecta = $resultado['code'] === $test['esperado'];

            $simbolo = $esCorrecta ? '✓' : '✗';
            $estado = $esCorrecta ? 'CORRECTO' : 'FALLO';

            $this->line("$simbolo {$test['descripcion']}: $estado");
            
            if (!$esCorrecta) {
                $this->warn("  Esperado: {$test['esperado']}, Obtenido: {$resultado['code']}");
                $fallidas++;
            } else {
                $pasadas++;
            }

            if ($verbose) {
                $this->line("  Mensaje: {$test['mensaje']} (status: {$test['status']})");
                $this->line("  Respuesta: {$resultado['code']} - {$resultado['message']}");
            }
        }

        $this->newLine();
        $this->info("=== Resumen ===");
        $this->line("✓ Pruebas pasadas: <fg=green>$pasadas</>");
        if ($fallidas > 0) {
            $this->line("✗ Pruebas fallidas: <fg=red>$fallidas</>");
        }

        // Prueba 2: Intentar conexión real (si está configurado)
        $this->newLine();
        $this->line('📋 Prueba 2: Intento de conexión real');
        $baseUrl = rtrim((string) config('services.recaudaciones.url'), '/');
        $token = (string) config('services.recaudaciones.token');

        if ($baseUrl === '' || $token === '') {
            $this->warn('⚠️  Recaudaciones no está configurado en .env');
            $this->line('Agregue RECAUDACIONES_URL y RECAUDACIONES_TOKEN en .env');
        } else {
            $this->info("✓ Configuración encontrada");
            $this->line("  URL: $baseUrl");
            $this->line("  Token: " . substr($token, 0, 10) . "...");

            try {
                $response = $service->buscarPorControl(122, 999999);
                $this->line("Respuesta: " . json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } catch (\Throwable $e) {
                $this->error("Error al consultar: " . $e->getMessage());
            }
        }

        return 0;
    }
}
