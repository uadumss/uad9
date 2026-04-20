<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('carrera_acreditaciones')) {
            return;
        }

        Schema::create('carrera_acreditaciones', function (Blueprint $table) {
            $table->bigIncrements('cod_cac');
            $table->integer('cod_car')->index();
            $table->boolean('acreditada')->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('sistema', 50)->nullable();
            $table->integer('anio')->nullable();
            $table->integer('proc_sc')->nullable();
            $table->integer('proc_nc')->nullable();
            $table->integer('proc_total')->nullable();
            $table->date('fecha_acreditacion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('estado', 30)->nullable();
            $table->string('puntaje', 30)->nullable();
            $table->boolean('certificado')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_acreditaciones');
    }
};
