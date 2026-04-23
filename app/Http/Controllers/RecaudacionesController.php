<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecaudacionesService;

class RecaudacionesController extends Controller
{
    public function buscarPorControlYDocumento(Request $request)
    {
        $payload = $request->validate([
            'unidad' => ['required', 'integer'],
            'recibo' => ['required', 'integer'],
            'documento' => ['required', 'string'],
        ]);

        $resultado = app(RecaudacionesService::class)->buscarPorControlYDocumento(
            (int) $payload['unidad'],
            (int) $payload['recibo'],
            (string) $payload['documento']
        );

        return response()->json($resultado, (int) ($resultado['status'] ?? 200));
    }

    public function buscarPorControl(Request $request)
    {
        $payload = $request->validate([
            'unidad' => ['required', 'integer'],
            'recibo' => ['required', 'integer'],
        ]);

        $resultado = app(RecaudacionesService::class)->buscarPorControl(
            (int) $payload['unidad'],
            (int) $payload['recibo']
        );

        return response()->json($resultado, (int) ($resultado['status'] ?? 200));
    }

    public function buscarPorDocumento(Request $request)
    {
        $payload = $request->validate([
            'unidad' => ['required', 'integer'],
            'documento' => ['required', 'string'],
        ]);

        $resultado = app(RecaudacionesService::class)->buscarPorDocumento(
            (int) $payload['unidad'],
            (string) $payload['documento']
        );

        return response()->json($resultado, (int) ($resultado['status'] ?? 200));
    }

    public function extraerDatosDocumento(Request $request)
    {
        $data = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,bmp,tiff,webp'],
            'language' => ['nullable', 'string'],
        ]);

        $apiKey = (string) config('services.ocrspace.api_key');
        $verifySsl = filter_var(config('services.ocrspace.verify_ssl', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($verifySsl === null) {
            $verifySsl = true;
        }

        if ($apiKey === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Falta OCR_SPACE_API_KEY en .env',
            ], 500);
        }

        $archivo = $request->file('archivo');
        $language = $data['language'] ?? 'spa';

        try {
            $response = Http::asMultipart()
                ->withHeaders(['apikey' => $apiKey])
                ->withOptions(['verify' => $verifySsl])
                ->timeout(60)
                ->attach('file', fopen($archivo->getRealPath(), 'r'), $archivo->getClientOriginalName())
                ->post('https://api.ocr.space/parse/image', [
                    'language' => $language,
                    'isOverlayRequired' => 'false',
                    'isTable' => 'true',
                    'OCREngine' => '2',
                    'scale' => 'true',
                ]);

            if (!$response->successful()) {
                Log::warning('OCR.Space respondió con error HTTP al extraer datos de recibo.', [
                    'status' => $response->status(),
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'OCR.Space respondio con error HTTP',
                    'status' => $response->status(),
                ], $response->status());
            }

            $ocr = $response->json();
            $resultados = $ocr['ParsedResults'] ?? [];
            $texto = '';
            foreach ($resultados as $r) {
                $texto .= '\n' . ($r['ParsedText'] ?? '');
            }

            $textoNormalizado = preg_replace('/\s+/', ' ', trim((string) $texto));
            $campos = $this->extraerCamposRecibo($textoNormalizado);

            return response()->json([
                'ok' => true,
                'campos' => $campos,
                'texto' => $textoNormalizado,
                'ocr' => [
                    'isErroredOnProcessing' => $ocr['IsErroredOnProcessing'] ?? null,
                    'processingTimeInMilliseconds' => $ocr['ProcessingTimeInMilliseconds'] ?? null,
                    'errorMessage' => $ocr['ErrorMessage'] ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al procesar OCR del documento.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error al procesar OCR del documento',
            ], 500);
        }
    }

    public function extraerDatosDiploma(Request $request)
    {
        $data = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,bmp,tiff,webp'],
            'language' => ['nullable', 'string'],
        ]);

        $apiKey = (string) config('services.ocrspace.api_key');
        $verifySsl = filter_var(config('services.ocrspace.verify_ssl', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($verifySsl === null) {
            $verifySsl = true;
        }

        if ($apiKey === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Falta OCR_SPACE_API_KEY en .env',
            ], 500);
        }

        $archivo = $request->file('archivo');
        $language = $data['language'] ?? 'spa';

        try {
            $response = Http::asMultipart()
                ->withHeaders(['apikey' => $apiKey])
                ->withOptions(['verify' => $verifySsl])
                ->timeout(60)
                ->attach('file', fopen($archivo->getRealPath(), 'r'), $archivo->getClientOriginalName())
                ->post('https://api.ocr.space/parse/image', [
                    'language' => $language,
                    'isOverlayRequired' => 'false',
                    'isTable' => 'true',
                    'OCREngine' => '2',
                    'scale' => 'true',
                ]);

            if (!$response->successful()) {
                Log::warning('OCR.Space respondió con error HTTP al extraer datos de diploma.', [
                    'status' => $response->status(),
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'OCR.Space respondio con error HTTP',
                    'status' => $response->status(),
                ], $response->status());
            }

            $ocr = $response->json();
            $resultados = $ocr['ParsedResults'] ?? [];
            $texto = '';
            foreach ($resultados as $r) {
                $texto .= '\n' . ($r['ParsedText'] ?? '');
            }

            $textoNormalizado = preg_replace('/\s+/', ' ', trim((string) $texto));
            $campos = $this->extraerCamposDiploma($textoNormalizado);

            return response()->json([
                'ok' => true,
                'campos' => $campos,
                'texto' => $textoNormalizado,
                'ocr' => [
                    'isErroredOnProcessing' => $ocr['IsErroredOnProcessing'] ?? null,
                    'processingTimeInMilliseconds' => $ocr['ProcessingTimeInMilliseconds'] ?? null,
                    'errorMessage' => $ocr['ErrorMessage'] ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al procesar OCR del diploma.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error al procesar OCR del diploma',
            ], 500);
        }
    }

    private function extraerCamposRecibo(string $texto): array
    {
        return [
            'nro' => $this->extraerPrimero('/\bNro\s*[:.]?\s*(\d{5,12})\b/i', $texto),
            'control' => $this->extraerPrimero('/\bNro\.?\s*Control\s*[:.]?\s*(\d{3,10})\b/i', $texto),
            'ci' => $this->extraerPrimero('/\bDocumento\s*[:.]?\s*(\d{5,12})\b/i', $texto),
            'codigo_sis' => $this->extraerPrimero('/código\s*:?\s*(\d{6,15})/iu', $texto),
        ];
    }

    private function extraerCamposDiploma(string $texto): array
    {
        $numeroDiploma = $this->extraerPrimero('/\b[A-Z]-\s*(\d{7,8})\b/u', $texto);
        $numeroSerie = $this->extraerPrimero('/\b[A-Z]-\s*\d{7,8}\s+(\d{3,5})\b/u', $texto);

        if ($numeroSerie === null) {
            $numeroSerie = $this->extraerPrimero('/\bSELLO\s+ARCHIVO\s+UMSS\s*(\d{3,5})\b/iu', $texto);
        }

        return [
            'numero_diploma' => $numeroDiploma,
            'numero_serie' => $numeroSerie,
            'nombre' => $this->extraerNombre($texto),
            'titulo' => $this->extraerTitulo($texto),
        ];
    }

    private function extraerPrimero(string $regex, string $texto): ?string
    {
        if (preg_match($regex, $texto, $m)) {
            return $m[1] ?? null;
        }

        return null;
    }

    private function extraerNombre(string $texto): ?string
    {
        // Extrae nombre después de "POR CUANTO:"
        if (preg_match('/por\s+cuanto\s*:\s*([A-ZÁÉÍÓÚÑ\s]+?)(?:nacido|naci)/iu', $texto, $m)) {
            return $this->limpiarTextoCampo($m[1]);
        }
        return null;
    }

    private function extraerTitulo(string $texto): ?string
    {
        // Acepta OCR con o sin la palabra "DIPLOMA" (a veces llega solo "ACADÉMICO de:")
        if (preg_match('/(?:diploma\s+)?acad[eé]mico\s+de\s*:\s*([A-ZÁÉÍÓÚÑ\s]+?)(?:en\s+reconocimiento|cochabamba|ing\.?|dr\.?|rector|secretario|decano)/iu', $texto, $m)) {
            return $this->limpiarTextoCampo($m[1]);
        }
        return null;
    }

    private function limpiarTextoCampo(string $valor): string
    {
        $valor = preg_replace('/\s+/', ' ', trim($valor));

        return trim((string) $valor, " \t\n\r\0\x0B:;,.!?");
    }

}
