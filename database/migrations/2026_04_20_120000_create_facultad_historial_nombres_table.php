<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facultad_historial_nombres', function (Blueprint $table) {
            $table->bigIncrements('cod_fhn');
            $table->integer('cod_fac')->index();
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
        Schema::dropIfExists('facultad_historial_nombres');
    }
};
