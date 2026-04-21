<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResolucionFieldsToCarreraAcreditacionesTable extends Migration
{
    public function up()
    {
        Schema::table('carrera_acreditaciones', function (Blueprint $table) {
            $table->date('resolucion_inicio')->nullable()->after('fecha_vencimiento');
            $table->date('resolucion_fin')->nullable()->after('resolucion_inicio');
            $table->date('resolucion_fecha_emision')->nullable()->after('resolucion_fin');
            $table->string('resolucion_numero', 20)->nullable()->after('resolucion_fecha_emision');
            $table->integer('resolucion_anio')->nullable()->after('resolucion_numero');
        });
    }

    public function down()
    {
        Schema::table('carrera_acreditaciones', function (Blueprint $table) {
            $table->dropColumn([
                'resolucion_inicio',
                'resolucion_fin',
                'resolucion_fecha_emision',
                'resolucion_numero',
                'resolucion_anio',
            ]);
        });
    }
}
