<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class TestSitraScraper extends Command
{
    protected $signature = 'sitra:test-scrape
                            {--ci= : CI para filtrar}
                            {--nombre= : Nombre para filtrar}
                            {--tipo=AC : Tipo de documento (AC, SU, PN, etc.)}
                            {--nro=}
                            {--serie=}';

    protected $description = 'Prueba temporal de scraping a SITRA y parseo del HTML';

    public function handle()
    {
        $ci = trim((string) $this->option('ci'));
        $nroTitulo = trim((string) $this->option('nro'));
        $serie = trim((string) $this->option('serie'));
        $nombre = trim((string) $this->option('nombre'));
        $tipo = trim((string) $this->option('tipo'));
        $serieInput = trim((string) $this->option('serie'));
        $serieNumerica = preg_replace('/[^0-9]/', '', $serieInput);
        
        $cookie = env('SITRA_COOKIE');
        if (!$cookie) {
            $this->error('Falta SITRA_COOKIE en el archivo .env');
            return self::FAILURE;
        }

        $url = 'http://sitra.umss.net/principal_dev.php/tramites/index';

        $payload = [
            'query'  => '',
            'query1' => $ci,
            'query2' => '',
            'query3' => $nroTitulo,
            'query4' => $serieNumerica,
            'query5' => '',
        ];

        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Origin' => 'http://sitra.umss.net',
            'Referer' => 'http://sitra.umss.net/',
            'User-Agent' => env('SITRA_USER_AGENT', 'Mozilla/5.0'),
            'X-Requested-With' => 'XMLHttpRequest',
            'Cookie' => $cookie,
        ])->asForm()->post($url, $payload);

        $this->info('HTTP status: ' . $response->status());
        Log::info('SITRA RESPONSE STATUS', ['status' => $response->status()]);

        if (!$response->successful()) {
            $this->error('La request falló.');
            Log::error('SITRA REQUEST FAILED', ['body' => $response->body()]);
            return self::FAILURE;
        }

        $html = $response->body();
        $rows = $this->parseRows($html);
        $tipoBuscado = strtoupper(trim((string) $this->option('tipo')));
        $rowsFiltrados = array_filter($rows, function ($row) use ($nroTitulo, $serieNumerica) {
            return
                $row['nro_titulo'] == ltrim($nroTitulo, '0') &&
                $row['serie'] == $serieNumerica;
        });
        if (empty($rows)) {
            $this->warn('No se encontraron filas parseables.');
            Log::warning('SITRA SCRAPE: sin filas parseables');
            return self::SUCCESS;
        }

        foreach ($rowsFiltrados as $row) {
            $this->line(json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            Log::info('SITRA ROW', $row);
        }

        return self::SUCCESS;
    }

    private function parseRows(string $html): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'ISO-8859-1, UTF-8');
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);
        $trs = $xpath->query("//tr");

        $data = [];

        foreach ($trs as $tr) {
            $tds = $xpath->query('./td', $tr);
            if ($tds->length < 6) {
                continue;
            }

            $nroTramite = trim($tds->item(0)->textContent);
            if ($nroTramite === '' || $nroTramite === 'Nro Tramite') {
            continue;
            }

            $personaText = preg_replace('/\s+/u', ' ', trim($tds->item(1)->textContent));
            $ci = null;
            $nombre = null;

            if (preg_match('/^(\d+)\s+(.+)$/u', $personaText, $m)) {
                $ci = trim($m[1]);
                $nombre = trim($m[2]);
            } else {
                $nombre = $personaText;
            }

            $fechaSolicitud = trim(preg_replace('/\s+/u', ' ', $tds->item(2)->textContent));
            $tipo = trim($tds->item(3)->textContent);
            $titulo = trim(preg_replace('/\s+/u', ' ', $tds->item(4)->textContent));

            $docHtml = $this->innerHtml($tds->item(5));
            $docHtmlParts = preg_split('/<br\s*\/?>/i', $docHtml);

            $docLinea1 = trim(strip_tags($docHtmlParts[0] ?? ''));
            $fechaEmision = trim(strip_tags($docHtmlParts[1] ?? ''));

            $numeroTitulo = null;
            $serie = null;

            if ($docLinea1 !== '' && str_contains($docLinea1, '-')) {
                [$numeroTituloRaw, $serieRaw] = array_pad(explode('-', $docLinea1, 2), 2, '');
                $numeroTitulo = ltrim(trim($numeroTituloRaw), '0');
                $serie = trim($serieRaw);
            }

            $data[] = [
                'nro_tramite'   => $nroTramite,
                'ci'            => $ci,
                'nombre'        => $nombre,
                'fecha_tramite' => $fechaSolicitud,
                'tipo'          => $tipo,
                'titulo'        => $titulo !== '' ? $titulo : null,
                'nro_titulo'    => $numeroTitulo,
                'serie'         => $serie,
                'fecha_emision' => $fechaEmision !== '' ? $fechaEmision : null,
            ];
        }

        return $data;
    }

    private function innerHtml($node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }
        return $html;
    }
}