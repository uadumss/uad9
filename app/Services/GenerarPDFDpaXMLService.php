<?php

namespace App\Services;

use App\Models\Documento;
use App\Models\Funcionario;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerarPDFDpaXMLService
{
    /**
     * Generar PDF usando plantilla XML (docente/administrativo)
     * 
     * @param Funcionario $funcionario
     * @param array $codsDocumentos Array de códigos de documentos seleccionados
     * @param string $tipoFuncionario 'D' para docente o 'A' para administrativo
     * @param array $datosCarta Campos editables de la carta
     * @return string Ruta del archivo PDF generado
     */
    public function generarPDF(Funcionario $funcionario, array $codsDocumentos, string $tipoFuncionario = 'D', array $datosCarta = [])
    {
        try {
            $tipoNormalizado = strtoupper(trim($tipoFuncionario));
            $tipoTemplate = ($tipoNormalizado === 'D') ? 'docente' : 'administrativo';
            $rutaTemplateXml = resource_path("templates/dpa/{$tipoTemplate}.xml");

            \Log::info("GenerarPDFDpaXMLService: Usando plantilla XML: {$rutaTemplateXml}");

            // Obtener documentos
            $documentos = Documento::whereIn('cod_doc', $codsDocumentos)
                ->where('cod_fun', $funcionario->cod_fun)
                ->get();

            \Log::info("GenerarPDFDpaXMLService: Documentos encontrados: " . $documentos->count());

            if ($documentos->isEmpty()) {
                throw new Exception('No se encontraron documentos');
            }

            $plantilla = $this->cargarPlantillaXml($rutaTemplateXml, $tipoTemplate);
            $datosFinales = $this->construirDatosCarta($plantilla, $datosCarta, $funcionario);
            $html = $this->generarHtmlCarta($datosFinales, $documentos, $funcionario);

            // Guardar PDF temporal
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $nombreArchivo = 'dpa-' . $funcionario->cod_fun . '-' . date('Y-m-d_H-i-s');
            $rutaPdfFinal = storage_path("app/temp/{$nombreArchivo}.pdf");

            $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');
            $pdf->save($rutaPdfFinal);

            if (!file_exists($rutaPdfFinal)) {
                throw new Exception('No se pudo crear el PDF final');
            }

            \Log::info("GenerarPDFDpaXMLService: PDF generado en: {$rutaPdfFinal}");
            return $rutaPdfFinal;

        } catch (Exception $e) {
            \Log::error("GenerarPDFDpaXMLService ERROR: " . $e->getMessage());
            throw new Exception('Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Cargar campos base desde plantilla XML
     */
    private function cargarPlantillaXml(string $rutaTemplateXml, string $tipoTemplate): array
    {
        $defaults = [
            'lugar_fecha' => 'Cochabamba, {{fecha}}',
            'referencia' => 'REF.: {{ref}}',
            'sidoc' => 'Sidoc.: {{sidoc}}',
            'trato' => 'Señor',
            'nombre_destinatario' => '',
            'cargo_destinatario' => '',
            'estado_destinatario' => 'Presente',
            'asunto' => $tipoTemplate === 'docente'
                ? 'REF.: ENTREGA DE DOCUMENTACIÓN ACADÉMICA DOCENTE VERIFICADA Y LEGALIZADA'
                : 'REF.: ENTREGA DE DOCUMENTACIÓN ACADÉMICA DE FUNCIONARIOS ADMINISTRATIVOS VERIFICADA Y LEGALIZADA',
            'saludo' => 'De mi consideración:',
            'texto_principal' => '',
            'despedida' => 'Sin otro particular, saludo a usted atentamente,',
            'adjunto' => 'Adj. Lo indicado',
        ];

        if (!file_exists($rutaTemplateXml)) {
            return $defaults;
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($rutaTemplateXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            libxml_clear_errors();
            return $defaults;
        }
        libxml_clear_errors();

        $defaults['lugar_fecha'] = $this->limpiarTextoPlantilla((string)($xml->encabezado->lugar_fecha ?? $defaults['lugar_fecha']));
        $defaults['referencia'] = $this->limpiarTextoPlantilla((string)($xml->encabezado->referencia ?? $defaults['referencia']));
        $defaults['sidoc'] = $this->limpiarTextoPlantilla((string)($xml->encabezado->sidoc ?? $defaults['sidoc']));

        $defaults['trato'] = $this->limpiarTextoPlantilla((string)($xml->destinatario->trato ?? $defaults['trato']));
        $defaults['nombre_destinatario'] = $this->limpiarTextoPlantilla((string)($xml->destinatario->nombre ?? $defaults['nombre_destinatario']));
        $defaults['cargo_destinatario'] = $this->limpiarTextoPlantilla((string)($xml->destinatario->cargo ?? $defaults['cargo_destinatario']));
        $defaults['estado_destinatario'] = $this->limpiarTextoPlantilla((string)($xml->destinatario->estado ?? $defaults['estado_destinatario']));

        $defaults['asunto'] = $this->limpiarTextoPlantilla((string)($xml->asunto ?? $defaults['asunto']));
        $defaults['saludo'] = $this->limpiarTextoPlantilla((string)($xml->cuerpo->saludo ?? $defaults['saludo']));
        $defaults['texto_principal'] = $this->limpiarTextoPlantilla((string)($xml->cuerpo->texto_principal ?? $defaults['texto_principal']));
        $defaults['despedida'] = $this->limpiarTextoPlantilla((string)($xml->cuerpo->despedida ?? $defaults['despedida']));
        $defaults['adjunto'] = $this->limpiarTextoPlantilla((string)($xml->cuerpo->adjunto ?? $defaults['adjunto']));

        return $defaults;
    }

    /**
     * Construir datos finales con overrides del formulario
     */
    private function construirDatosCarta(array $plantilla, array $datosCarta, Funcionario $funcionario): array
    {
        $fechaInput = isset($datosCarta['fecha']) ? trim((string)$datosCarta['fecha']) : '';
        $fechaFormateada = $fechaInput !== '' ? $this->formatearFechaLargaEs($fechaInput) : $this->formatearFechaLargaEs(date('Y-m-d'));
        $fechaDefault = 'Cochabamba, ' . $fechaFormateada;
        $refValor = isset($datosCarta['ref']) ? trim((string)$datosCarta['ref']) : '';
        $sidocValor = isset($datosCarta['sidoc']) ? trim((string)$datosCarta['sidoc']) : '';
        $refDefault = $this->formatearLineaConPrefijo($refValor, 'REF.:');
        $sidocDefault = $this->formatearLineaConPrefijo($sidocValor, 'Sidoc.:');

        $vars = [
            'fecha' => $fechaFormateada,
            'ref' => $refValor,
            'sidoc' => $sidocValor,
            'trato' => $datosCarta['trato'] ?? '',
            'nombre_destinatario' => $datosCarta['nombre_destinatario'] ?? '',
            'cargo_destinatario' => $datosCarta['cargo_destinatario'] ?? '',
            'saludo' => $datosCarta['saludo'] ?? '',
            'despedida' => $datosCarta['despedida'] ?? '',
        ];

        $resultado = [];
        foreach ($plantilla as $key => $valorPlantilla) {
            $valorFormulario = isset($datosCarta[$key]) ? trim((string)$datosCarta[$key]) : '';
            $base = $valorFormulario !== '' ? $valorFormulario : $valorPlantilla;
            $resultado[$key] = $this->aplicarMarcadores($base, $vars);
        }

        // Forzar formato formal de cabecera aun cuando el usuario ingrese solo el valor.
        $resultado['lugar_fecha'] = $fechaDefault;
        $resultado['referencia'] = $refDefault;
        $resultado['sidoc'] = $sidocDefault;

        return $resultado;
    }

    /**
     * Quitar marcadores no deseados de plantillas externas
     */
    private function limpiarTextoPlantilla(string $texto): string
    {
        $texto = preg_replace('/\[cite:\s*\d+\]/i', '', $texto);
        $texto = preg_replace('/@if\s*\(.*?\)|@else|@endif|@foreach\s*\(.*?\)|@endforeach/', '', $texto);
        return trim((string)$texto);
    }

    /**
     * Reemplazar placeholders tipo {{campo}} o {{ $campo }}
     */
    private function aplicarMarcadores(string $texto, array $variables): string
    {
        $resultado = $texto;
        foreach ($variables as $key => $value) {
            $resultado = str_replace('{{' . $key . '}}', $value, $resultado);
            $resultado = str_replace('{{ ' . $key . ' }}', $value, $resultado);
            $resultado = str_replace('{{ $' . $key . ' }}', $value, $resultado);
        }
        return trim($resultado);
    }

    /**
     * Generar HTML final del PDF
     */
    private function generarHtmlCarta(array $datosCarta, $documentos, Funcionario $funcionario): string
    {
        $filasTabla = $this->generarFilasTabla($documentos, $funcionario);

        return '<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 18mm 18mm 18mm 18mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; margin: 0; }
        .pagina { padding-top: 10mm; }
        .cabecera { width: 100%; margin-bottom: 8px; }
        .cabecera-top { width: 100%; text-align: right; }
        .meta-linea { line-height: 1.25; margin-bottom: 2px; }
        .destinatario { line-height: 1.25; margin-top: 12px; margin-bottom: 18px; }
        .destinatario div { margin: 0; }
        .asunto { margin: 8px 0 14px; font-weight: bold; line-height: 1.25; text-align: right; }
        .texto { margin: 10px 0; text-align: justify; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0 10px; }
        th, td { border: 1px solid #333; padding: 5px; vertical-align: top; font-size: 10.5px; }
        th { background: #f0f0f0; text-align: center; }
        .c { text-align: center; }
        .firma { margin-top: 18px; }
        .espacio-sello { height: 72px; }
        .adjunto { font-size: 11.5px; }
        .cargo { font-weight: bold; }
    </style>
</head>
<body>
    <div class="pagina">
        <div class="cabecera">
            <div class="cabecera-top">
                <div class="meta-linea">' . $this->esc($datosCarta['lugar_fecha']) . '</div>
                <div class="meta-linea">' . $this->esc($datosCarta['referencia']) . '</div>
                <div class="meta-linea">' . $this->esc($datosCarta['sidoc']) . '</div>
            </div>
        </div>

        <div class="destinatario">
            <div>' . $this->esc($datosCarta['trato']) . '</div>
            <div>' . $this->esc($datosCarta['nombre_destinatario']) . '</div>
            <div class="cargo">' . $this->esc($datosCarta['cargo_destinatario']) . '</div>
            <div>' . $this->esc($datosCarta['estado_destinatario']) . '</div>
        </div>

        <div class="asunto">' . $this->esc($datosCarta['asunto']) . '</div>
        <div class="texto">' . nl2br($this->esc($datosCarta['saludo'])) . '</div>
        <div class="texto">' . nl2br($this->esc($datosCarta['texto_principal'])) . '</div>

        <table>
            <thead>
                <tr>
                    <th style="width:6%">Nro.</th>
                    <th style="width:29%">Nombre</th>
                    <th style="width:29%">Grado academico</th>
                    <th style="width:16%">Universidad</th>
                    <th style="width:12%">Fecha de emision</th>
                    <th style="width:8%">N de registro</th>
                </tr>
            </thead>
            <tbody>
                ' . $filasTabla . '
            </tbody>
        </table>

        <div class="firma">' . nl2br($this->esc($datosCarta['despedida'])) . '</div>
        <div class="espacio-sello"></div>
        <div class="adjunto">' . $this->esc($datosCarta['adjunto']) . '</div>
    </div>
</body>
</html>';
    }

    /**
     * Generar filas HTML para la tabla
     */
    private function generarFilasTabla($documentos, Funcionario $funcionario): string
    {
        // Ordenar documentos según el orden especificado
        $documentosOrdenados = $this->ordenarDocumentos($documentos);

        $filas = '';
        $contador = 1;

        foreach ($documentosOrdenados as $doc) {
            $fecha = '';
            if (!empty($doc->doc_fecha_emision)) {
                $timestamp = strtotime($doc->doc_fecha_emision);
                if ($timestamp !== false) {
                    $fecha = date('d/m/Y', $timestamp);
                }
            }

            $filas .= '<tr>'
                . '<td class="c">' . $contador . '</td>'
                . '<td>' . $this->esc($funcionario->fun_nombre ?? '') . '</td>'
                . '<td>' . $this->esc($this->obtenerGradoAcademico($doc)) . '</td>'
                . '<td>' . $this->esc($doc->doc_universidad ?? $funcionario->dt_universidad ?? '') . '</td>'
                . '<td class="c">' . $this->esc($fecha) . '</td>'
                . '<td class="c">' . $this->esc((string)($doc->doc_numero_registro ?? '')) . '</td>'
                . '</tr>';

            $contador++;
        }

        return $filas;
    }

    /**
     * Escapar texto para HTML
     */
    private function esc(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Para esta carta, "Grado académico" debe mostrar el tipo + titulo del diploma.
     * Formato: "DOCTORADO - dasdasd"
     */
    private function obtenerGradoAcademico($doc): string
    {
        $tipoDocumento = (string)($doc->doc_tipo ?? '');
        $docTitulo = (string)($doc->doc_titulo ?? '');
        $docGrado = (string)($doc->doc_grado ?? '');

        $tipoDescripcion = $this->obtenerTipoDocumentoDescripcion($tipoDocumento);
        $valor = '';

        if (!empty($docTitulo)) {
            $valor = $docTitulo;
        } elseif (!empty($docGrado)) {
            $valor = $docGrado;
        }

        if ($tipoDescripcion && $valor) {
            return $tipoDescripcion . ' - ' . $valor;
        } elseif ($tipoDescripcion) {
            return $tipoDescripcion;
        }

        return $valor ?: $tipoDocumento;
    }

    /**
     * Obtener la descripción legible del tipo de documento
     */
    private function obtenerTipoDocumentoDescripcion(string $tipo): string
    {
        $tipo = strtolower(trim($tipo));
        
        // Mapeo para códigos cortos
        $mapeo = [
            'db' => 'DIPLOMA DE BACHILLER',
            'da' => 'DIPLOMA ACADÉMICO',
            'tp' => 'TÍTULO PROFESIONAL',
            'dip' => 'DIPLOMADO',
            'di' => 'DIPLOMADO',
            'maestria' => 'MAESTRÍA',
            'especialidad' => 'ESPECIALIDAD',
            'doctorado' => 'DOCTORADO',
            'tpos' => 'TÍTULO POSGRADO',
        ];

        // Si encuentra un mapeo directo, usarlo
        if (isset($mapeo[$tipo])) {
            return $mapeo[$tipo];
        }

        // Si el tipo contiene palabras completas, normalizarlas
        $tipoBuscado = strtoupper($tipo);

        // Mapeo para nombres completos
        $mapeoCompleto = [
            'DIPLOMA DE BACHILLER' => 'DIPLOMA DE BACHILLER',
            'DIPLOMA ACADEMICO' => 'DIPLOMA ACADÉMICO',
            'DIPLOMA ACADÉMICO' => 'DIPLOMA ACADÉMICO',
            'TITULO PROFESIONAL' => 'TÍTULO PROFESIONAL',
            'TÍTULO PROFESIONAL' => 'TÍTULO PROFESIONAL',
            'DIPLOMADO' => 'DIPLOMADO',
            'MAESTRIA' => 'MAESTRÍA',
            'MAESTRÍA' => 'MAESTRÍA',
            'ESPECIALIDAD' => 'ESPECIALIDAD',
            'DOCTORADO' => 'DOCTORADO',
            'TITULO POSGRADO' => 'TÍTULO POSGRADO',
            'TÍTULO POSGRADO' => 'TÍTULO POSGRADO',
        ];

        return $mapeoCompleto[$tipoBuscado] ?? '';
    }

    /**
     * Ordenar documentos según el orden especificado
     */
    private function ordenarDocumentos($documentos)
    {
        // Mapeos de códigos cortos
        $ordenCodigos = [
            'db' => 1,
            'da' => 2,
            'tp' => 3,
            'dip' => 4,
            'di' => 4,
            'maestria' => 5,
            'especialidad' => 6,
            'doctorado' => 7,
            'tpos' => 6,
        ];

        // Mapeos de nombres completos
        $ordenNombres = [
            'DIPLOMA DE BACHILLER' => 1,
            'DIPLOMA ACADEMICO' => 2,
            'DIPLOMA ACADÉMICO' => 2,
            'TITULO PROFESIONAL' => 3,
            'TÍTULO PROFESIONAL' => 3,
            'DIPLOMADO' => 4,
            'MAESTRIA' => 5,
            'MAESTRÍA' => 5,
            'ESPECIALIDAD' => 6,
            'DOCTORADO' => 7,
            'TITULO POSGRADO' => 6,
            'TÍTULO POSGRADO' => 6,
        ];

        return $documentos->sortBy(function ($doc) use ($ordenCodigos, $ordenNombres) {
            $tipo = trim((string)($doc->doc_tipo ?? ''));
            $tipoLower = strtolower($tipo);

            // Primero intenta con códigos cortos
            if (isset($ordenCodigos[$tipoLower])) {
                return $ordenCodigos[$tipoLower];
            }

            // Luego intenta con nombres completos
            $tipoUpper = strtoupper($tipo);
            if (isset($ordenNombres[$tipoUpper])) {
                return $ordenNombres[$tipoUpper];
            }

            // Default para tipos desconocidos
            return 999;
        })->values();
    }

    /**
     * Asegura prefijo en la linea, evitando duplicarlo.
     */
    private function formatearLineaConPrefijo(string $valor, string $prefijo): string
    {
        $valor = trim($valor);
        if ($valor === '') {
            return $prefijo;
        }

        if (stripos($valor, $prefijo) === 0) {
            return $valor;
        }

        return $prefijo . ' ' . $valor;
    }

    /**
     * Convertir fecha a formato largo en espanol: 6 de abril de 2026
     */
    private function formatearFechaLargaEs(string $fecha): string
    {
        $fecha = trim($fecha);
        if ($fecha === '') {
            return '';
        }

        $timestamp = strtotime($fecha);
        if ($timestamp === false) {
            return $fecha;
        }

        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];

        $dia = (int)date('j', $timestamp);
        $mes = (int)date('n', $timestamp);
        $anio = date('Y', $timestamp);

        return $dia . ' de ' . ($meses[$mes] ?? date('F', $timestamp)) . ' de ' . $anio;
    }
}
