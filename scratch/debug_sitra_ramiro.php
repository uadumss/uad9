<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SitraService;
use App\Models\Funciones;

$ci = '8040492';
$numero = '2086';
// In the image, "LEG. FOTOC. TIT. PROV. NAL. ALUM. NAL" is selected.
// Let's assume the value for that is 'tpos' (Título Provisional) or similar.
// Actually, let's try a few common ones.
$tipos = ['tp', 'tpos', 'da', 'di'];

foreach ($tipos as $tipo) {
    echo "Consultando para tipo: $tipo\n";
    $documentoSitra = Funciones::DocumentoSitra($tipo);
    $ruta = "http://sitra.umss.net/consulta/api/ci/" . $ci . "/numero/" . $numero . "/tipo/" . $documentoSitra;
    echo "URL: $ruta\n";
    try {
        $resp = file_get_contents($ruta);
        echo "Respuesta: $resp\n\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}
