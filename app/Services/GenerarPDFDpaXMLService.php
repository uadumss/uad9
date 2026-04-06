<?php

namespace App\Services;

use App\Models\Documento;
use App\Models\Funcionario;
use Exception;
use ZipArchive;
use DOMDocument;
use DOMXPath;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerarPDFDpaXMLService
{
    /**
     * Generar PDF usando edición XML del DOCX
     * 
     * @param Funcionario $funcionario
     * @param array $codsDocumentos Array de códigos de documentos seleccionados
     * @param string $tipoFuncionario 'D' para docente o 'A' para administrativo
     * @return string Ruta del archivo PDF generado
     */
    public function generarPDF(Funcionario $funcionario, array $codsDocumentos, string $tipoFuncionario = 'D')
    {
        try {
            $tipoTemplate = ($tipoFuncionario === 'D') ? 'docente' : 'administrativo';
            $rutaTemplate = storage_path("app/templates/dpa/{$tipoTemplate}.docx");

            \Log::info("GenerarPDFDpaXMLService: Usando plantilla: {$rutaTemplate}");

            if (!file_exists($rutaTemplate)) {
                throw new Exception("Plantilla no encontrada: {$rutaTemplate}");
            }

            // Obtener documentos
            $documentos = Documento::whereIn('cod_doc', $codsDocumentos)
                ->where('cod_fun', $funcionario->cod_fun)
                ->get();

            \Log::info("GenerarPDFDpaXMLService: Documentos encontrados: " . $documentos->count());

            if ($documentos->isEmpty()) {
                throw new Exception('No se encontraron documentos');
            }

            // Crear directorio temporal
            $tempDir = storage_path("app/temp/dpa-" . uniqid());
            mkdir($tempDir, 0755, true);

            // Descomprimir DOCX
            $this->descomprimirDocx($rutaTemplate, $tempDir);

            // Editar document.xml para agregar tabla
            $documentXmlPath = "{$tempDir}/word/document.xml";
            $xml = file_get_contents($documentXmlPath);

            // Agregar tabla ANTES de "Sin otro particular"
            $tablaXml = $this->generarTablaXML($documentos);
            
            // Buscar el párrafo que contiene "Sin otro particular" y insertar tabla antes de él
            $xml = $this->insertarTablaEnPosicionCorrecta($xml, $tablaXml);

            file_put_contents($documentXmlPath, $xml);

            // Recomprimir DOCX
            $nombreArchivo = 'dpa-' . $funcionario->cod_fun . '-' . date('Y-m-d_H-i-s');
            $rutaDocxFinal = storage_path("app/temp/{$nombreArchivo}.docx");
            $this->comprimirDocx($tempDir, $rutaDocxFinal);

            // Limpiar temporales
            $this->eliminarDirectorio($tempDir);

            \Log::info("GenerarPDFDpaXMLService: DOCX generado en: {$rutaDocxFinal}");
            return $rutaDocxFinal;

        } catch (Exception $e) {
            \Log::error("GenerarPDFDpaXMLService ERROR: " . $e->getMessage());
            throw new Exception('Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generar XML de la tabla con documentos
     */
    private function generarTablaXML($documentos): string
    {
        $xml = <<<'XML'

<w:p>
    <w:pPr>
        <w:spacing w:line="240" w:lineRule="auto" w:before="120" w:after="120"/>
    </w:pPr>
</w:p>

<w:tbl>
    <w:tblPr>
        <w:tblW w:w="5000" w:type="dxa"/>
        <w:tblBorders>
            <w:top w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:left w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:bottom w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:right w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:insideH w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:insideV w:val="single" w:sz="12" w:space="0" w:color="000000"/>
        </w:tblBorders>
    </w:tblPr>
XML;

        // Fila de encabezados
        $encabezados = ['Nro.', 'Nombre', 'Grado académico', 'Universidad', 'Fecha de emisión', 'N° de registro'];
        $xml .= '<w:tr>';
        foreach ($encabezados as $encabezado) {
            $xml .= <<<XML
<w:tc>
    <w:tcPr>
        <w:shd w:fill="CCCCCC"/>
        <w:tcBorders>
            <w:top w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:left w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:bottom w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:right w:val="single" w:sz="12" w:space="0" w:color="000000"/>
        </w:tcBorders>
    </w:tcPr>
    <w:p>
        <w:pPr>
            <w:jc w:val="center"/>
        </w:pPr>
        <w:r>
            <w:rPr>
                <w:b/>
                <w:sz w:val="20"/>
            </w:rPr>
            <w:t>{$encabezado}</w:t>
        </w:r>
    </w:p>
</w:tc>
XML;
        }
        $xml .= '</w:tr>';

        // Filas de datos
        $contador = 1;
        foreach ($documentos as $doc) {
            $xml .= '<w:tr>';
            
            // Nro.
            $xml .= $this->generarCeldaXML((string)$contador, true);
            
            // Nombre
            $xml .= $this->generarCeldaXML($doc->doc_titulo ?? '');
            
            // Grado
            $xml .= $this->generarCeldaXML($doc->doc_grado ?? '');
            
            // Universidad
            $xml .= $this->generarCeldaXML($doc->doc_universidad ?? '');
            
            // Fecha
            $fecha = $doc->doc_fecha_emision ? date('d/m/Y', strtotime($doc->doc_fecha_emision)) : '';
            $xml .= $this->generarCeldaXML($fecha, true);
            
            // Registro
            $xml .= $this->generarCeldaXML($doc->doc_numero_registro ?? '');
            
            $xml .= '</w:tr>';
            $contador++;
        }

        $xml .= '</w:tbl>';
        return $xml;
    }

    /**
     * Generar celda XML
     */
    private function generarCeldaXML(string $texto, bool $centrado = false): string
    {
        $jc = $centrado ? '<w:jc w:val="center"/>' : '';
        $texto = htmlspecialchars($texto);
        
        return <<<XML
<w:tc>
    <w:tcPr>
        <w:tcBorders>
            <w:top w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:left w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:bottom w:val="single" w:sz="12" w:space="0" w:color="000000"/>
            <w:right w:val="single" w:sz="12" w:space="0" w:color="000000"/>
        </w:tcBorders>
    </w:tcPr>
    <w:p>
        <w:pPr>
            {$jc}
        </w:pPr>
        <w:r>
            <w:rPr>
                <w:sz w:val="20"/>
            </w:rPr>
            <w:t>{$texto}</w:t>
        </w:r>
    </w:p>
</w:tc>
XML;
    }

    /**
     * Insertar tabla en la posición correcta (antes de "Sin otro particular")
     */
    private function insertarTablaEnPosicionCorrecta(string $xml, string $tablaXml): string
    {
        // Primero intenta con la búsqueda exacta
        $pattern = '/<w:p>.*?<w:t[^>]*>Sin otro particular.*?<\/w:p>/s';
        
        if (preg_match($pattern, $xml, $matches)) {
            \Log::info("Encontró párrafo con 'Sin otro particular'");
            return str_replace($matches[0], $tablaXml . $matches[0], $xml);
        }
        
        // Si no funciona, intenta con búsqueda más flexible
        // Busca "Sin otro" en múltiples palabras
        $pattern = '/<w:p[^>]*>.*?<w:t[^>]*>.*?Sin otro.*?<\/w:p>/s';
        if (preg_match($pattern, $xml, $matches)) {
            \Log::info("Encontró párrafo con 'Sin otro' (flexible)");
            return str_replace($matches[0], $tablaXml . $matches[0], $xml);
        }
        
        // Último intento: buscar cualquier párrafo después de la mitad del documento
        // que probablemente contenga "Sin otro particular"
        $parrafos = preg_split('/<w:p[^>]*>/', $xml);
        if (count($parrafos) > 5) {
            // Busca en los últimos párrafos
            for ($i = count($parrafos) - 1; $i >= max(1, count($parrafos) - 5); $i--) {
                if (strpos($parrafos[$i], 'Sin otro') !== false) {
                    \Log::info("Encontró 'Sin otro' en párrafo {$i}");
                    // Reconstruir el párrafo y reemplazar
                    $parrafo = '<w:p' . explode('</w:p>', $parrafos[$i])[0] . '</w:p>';
                    return str_replace($parrafo, $tablaXml . $parrafo, $xml);
                }
            }
        }
        
        \Log::warning("No se encontró 'Sin otro particular', insertando al final");
        // Si no encuentra, insertar antes del cierre de body
        return str_replace('</w:body>', $tablaXml . '</w:body>', $xml);
    }

    /**
     * Descomprimir DOCX
     */
    private function descomprimirDocx(string $docxPath, string $destDir): void
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath) !== true) {
            throw new Exception("No se puede abrir DOCX: {$docxPath}");
        }
        $zip->extractTo($destDir);
        $zip->close();
    }

    /**
     * Comprimir DOCX
     */
    private function comprimirDocx(string $sourceDir, string $docxPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath, ZipArchive::CREATE) !== true) {
            throw new Exception("No se puede crear ZIP");
        }

        $this->agregarArchivosAZip($zip, $sourceDir, '');
        $zip->close();
    }

    /**
     * Agregar archivos a ZIP recursivamente
     */
    private function agregarArchivosAZip(ZipArchive $zip, string $dir, string $baseDir): void
    {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $path = "{$dir}/{$item}";
            $zipPath = $baseDir ? "{$baseDir}/{$item}" : $item;
            
            if (is_dir($path)) {
                $zip->addEmptyDir($zipPath);
                $this->agregarArchivosAZip($zip, $path, $zipPath);
            } else {
                $zip->addFile($path, $zipPath);
            }
        }
    }

    /**
     * Eliminar directorio recursivamente
     */
    private function eliminarDirectorio(string $dir): void
    {
        if (!is_dir($dir)) return;
        
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $path = "{$dir}/{$item}";
            if (is_dir($path)) {
                $this->eliminarDirectorio($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
