<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('carrera_historial_nombres')) {
            return;
        }

        Schema::create('carrera_historial_nombres', function (Blueprint $table) {
            $table->bigIncrements('cod_chn');
            $table->integer('cod_car')->index();
            $table->string('nombre_anterior');
            $table->string('nombre_nuevo');
            $table->string('abreviacion_anterior')->nullable();
            $table->string('abreviacion_nueva')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_historial_nombres');
    }
};
