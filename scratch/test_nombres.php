<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SitraService;

$service = new SitraService();

$local = "ZELADA OROPEZA JUAN CARLOS";
$sitra = "ZELADA OROPEZA JUAN  CARLOS";

echo "Caso 1: Juan Carlos\n";
echo "Local: $local\n";
echo "Sitra: $sitra\n";
$res = $service->nombresCompatibles($local, $sitra);
echo "Compatibles: " . ($res ? "SI" : "NO") . "\n\n";

$local2 = "RAMIRO ROY ORDOÑEZ TAPIA";
$sitra2 = ""; // or null

echo "Caso 2: Ramiro Roy (Sitra vacio)\n";
echo "Local: $local2\n";
echo "Sitra: (vacio)\n";
$res2 = $service->nombresCompatibles($local2, $sitra2);
echo "Compatibles: " . ($res2 ? "SI" : "NO") . "\n\n";
