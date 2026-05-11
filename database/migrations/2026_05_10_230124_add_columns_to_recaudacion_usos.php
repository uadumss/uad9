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
        Schema::table('recaudacion_usos', function (Blueprint $table) {
            $table->string('modulo', 50)->nullable();
            $table->string('tramite', 200)->nullable();
            $table->decimal('monto', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recaudacion_usos', function (Blueprint $table) {
            $table->dropColumn(['modulo', 'tramite', 'monto']);
        });
    }
};
