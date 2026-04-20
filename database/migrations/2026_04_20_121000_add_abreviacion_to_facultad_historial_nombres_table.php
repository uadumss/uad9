<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('facultad_historial_nombres')) {
            return;
        }

        $faltaAbreviacionAnterior = !Schema::hasColumn('facultad_historial_nombres', 'abreviacion_anterior');
        $faltaAbreviacionNueva = !Schema::hasColumn('facultad_historial_nombres', 'abreviacion_nueva');

        if (!$faltaAbreviacionAnterior && !$faltaAbreviacionNueva) {
            return;
        }

        Schema::table('facultad_historial_nombres', function (Blueprint $table) use ($faltaAbreviacionAnterior, $faltaAbreviacionNueva) {
            if ($faltaAbreviacionAnterior) {
                $table->string('abreviacion_anterior')->nullable();
            }

            if ($faltaAbreviacionNueva) {
                $table->string('abreviacion_nueva')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('facultad_historial_nombres')) {
            return;
        }

        $tieneAbreviacionAnterior = Schema::hasColumn('facultad_historial_nombres', 'abreviacion_anterior');
        $tieneAbreviacionNueva = Schema::hasColumn('facultad_historial_nombres', 'abreviacion_nueva');

        if (!$tieneAbreviacionAnterior && !$tieneAbreviacionNueva) {
            return;
        }

        Schema::table('facultad_historial_nombres', function (Blueprint $table) use ($tieneAbreviacionAnterior, $tieneAbreviacionNueva) {
            if ($tieneAbreviacionAnterior) {
                $table->dropColumn('abreviacion_anterior');
            }

            if ($tieneAbreviacionNueva) {
                $table->dropColumn('abreviacion_nueva');
            }
        });
    }
};
