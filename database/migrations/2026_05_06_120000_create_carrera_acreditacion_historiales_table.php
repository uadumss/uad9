<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('carrera_acreditacion_historiales')) {
            return;
        }

        Schema::create('carrera_acreditacion_historiales', function (Blueprint $table) {
            $table->bigIncrements('cod_cah');
            $table->integer('cod_cac')->index();
            $table->integer('cod_car')->index();
            $table->string('operacion', 20);
            $table->integer('version')->default(1);
            $table->boolean('acreditada')->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('sistema', 50)->nullable();
            $table->integer('anio')->nullable();
            $table->integer('proc_sc')->nullable();
            $table->integer('proc_nc')->nullable();
            $table->integer('proc_total')->nullable();
            $table->date('fecha_acreditacion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('resolucion_inicio')->nullable();
            $table->date('resolucion_fin')->nullable();
            $table->date('resolucion_fecha_emision')->nullable();
            $table->string('resolucion_numero', 20)->nullable();
            $table->integer('resolucion_anio')->nullable();
            $table->string('estado', 30)->nullable();
            $table->string('puntaje', 30)->nullable();
            $table->boolean('certificado')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_acreditacion_historiales');
    }
};
