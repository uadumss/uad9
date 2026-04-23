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
        Schema::table('apostilla.detalle_apostilla', function (Blueprint $table) {
            if (!Schema::hasColumn('apostilla.detalle_apostilla', 'dapo_verificacion_sitra')) {
                $table->string('dapo_verificacion_sitra', 1)->nullable()->default('0')->comment('0=ok, 1=no coincide, 2=no encontrado');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apostilla.detalle_apostilla', function (Blueprint $table) {
            if (Schema::hasColumn('apostilla.detalle_apostilla', 'dapo_verificacion_sitra')) {
                $table->dropColumn('dapo_verificacion_sitra');
            }
        });
    }
};
