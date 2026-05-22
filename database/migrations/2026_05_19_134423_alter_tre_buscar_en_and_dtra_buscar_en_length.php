<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Se utiliza RAW query para evitar dependencias de doctrine/dbal que a veces causan conflictos.
        DB::statement('ALTER TABLE tramites ALTER COLUMN tre_buscar_en TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE d_tramitas ALTER COLUMN dtra_buscar_en TYPE VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tramites ALTER COLUMN tre_buscar_en TYPE VARCHAR(8)');
        DB::statement('ALTER TABLE d_tramitas ALTER COLUMN dtra_buscar_en TYPE VARCHAR(20)');
    }
};
