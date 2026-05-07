<?php

// Script para resetear la secuencia de bitácoras

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Limpiar la tabla y resetear la secuencia
    DB::statement("TRUNCATE TABLE seguridad.bitacoras RESTART IDENTITY CASCADE");
    echo "✓ Tabla bitácoras limpiada y secuencia reseteada exitosamente\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
