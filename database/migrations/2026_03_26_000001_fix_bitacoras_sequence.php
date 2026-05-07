<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixBitacorasSequence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Resetear la secuencia de cod_bit en la tabla bitacoras
        DB::statement("SELECT setval(pg_get_serial_sequence('seguridad.bitacoras', 'cod_bit'), 
            (SELECT COALESCE(MAX(cod_bit), 0) FROM seguridad.bitacoras) + 1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revertir cambios de secuencia
    }
}
