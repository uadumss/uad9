<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SitraService
{
    public function obtenerDatos($ci, $nroTitulo, $serieInput)
    {
        \Log::info('ENTRO AL SERVICE');
        $serieNumerica = preg_replace('/[^0-9]/', '', $serieInput);
        $nroTitulo = ltrim($nroTitulo, '0');
        \Log::info('SITRA INPUT', [
            'ci' => $ci,
            'nroTitulo' => $nroTitulo,
            'serieInput' => $serieInput,
            'serieNumerica' => $serieNumerica,
        ]);
        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Origin' => 'http://sitra.umss.net',
            'Referer' => 'http://sitra.umss.net/',
            'User-Agent' => env('SITRA_USER_AGENT', 'Mozilla/5.0'),
            'X-Requested-With' => 'XMLHttpRequest',
            'Cookie' => env('SITRA_COOKIE'),
        ])->asForm()->post(
            'http://sitra.umss.net/principal_dev.php/tramites/index',
            [
                'query'  => '',
                'query1' => $ci,
                'query2' => '',
                'query3' => $nroTitulo,
                'query4' => $serieNumerica,
                'query5' => '',
            ]
        );
        \Log::info('HTML SITRA', [
            'body' => substr($response->body(), 0, 2000)
        ]);
        if (!$response->successful()) {
            return null;
        }

        $rows = $this->parseRows($response->body());
        \Log::info('SITRA ROWS', $rows);
        return collect($rows)->first(function ($row) use ($nroTitulo, $serieNumerica) {
            return
                $row['nro_titulo'] == $nroTitulo &&
                $row['serie'] == $serieNumerica;
        });
    }

    private function parseRows($html)
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'ISO-8859-1, UTF-8');
        $dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);
        $trs = $xpath->query("//table[contains(@class,'jobs')]//tbody/tr");

        $data = [];

        foreach ($trs as $tr) {
            $tds = $xpath->query('./td', $tr);

            if ($tds->length < 6) continue;

            $nroTramite = trim($tds->item(0)->textContent);


            if ($nroTramite === '' || $nroTramite === 'Nro Tramite') continue;

            $personaText = preg_replace('/\s+/u', ' ', trim($tds->item(1)->textContent));

            $ci = null;
            $nombre = null;

            if (preg_match('/^(\d+)\s+(.+)$/u', $personaText, $m)) {
                $ci = trim($m[1]);
                $nombre = trim($m[2]);
            }

            $titulo = trim(preg_replace('/\s+/u', ' ', $tds->item(4)->textContent));

            $docHtml = $this->innerHtml($tds->item(5));
            $parts = preg_split('/<br\s*\/?>/i', $docHtml);

            $docLinea = trim(strip_tags($parts[0] ?? ''));
            $fecha = trim(strip_tags($parts[1] ?? ''));

            $nroTitulo = null;
            $serie = null;

            if ($docLinea && str_contains($docLinea, '-')) {
                [$nro, $ser] = explode('-', $docLinea);
                $nroTitulo = ltrim(trim($nro), '0');
                $serie = trim($ser);
            }

            $data[] = [
                'ci' => $ci,
                'nombre' => $nombre,
                'titulo' => $titulo ?: null,
                'nro_titulo' => $nroTitulo,
                'serie' => $serie,
                'fecha_emision' => $fecha ?: null,
            ];
        }

        return $data;
    }

    private function innerHtml($node)
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }
        return $html;
    }

}