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
        Schema::create('carrera_campos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_car');
            $table->string('campo_amplio')->nullable();
            $table->string('campo_especifico')->nullable();
            $table->string('campo_detallado')->nullable();
            $table->timestamps();
            
            // Relación (si cod_car es bigInteger)
            // $table->foreign('cod_car')->references('cod_car')->on('carreras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_campos');
    }
};
