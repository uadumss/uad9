<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recaudacion_usos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identificador', 100)->unique();
            $table->string('recibo', 30)->nullable();
            $table->string('preimpreso', 30)->nullable();
            $table->string('fecha_pago', 40)->nullable();
            $table->string('documento', 30)->nullable();
            $table->string('nombre_persona', 200)->nullable();
            $table->string('cajero', 120)->nullable();
            $table->unsignedBigInteger('cod_tra')->nullable();
            $table->unsignedBigInteger('cod_dtra')->nullable();
            $table->string('usuario_registro', 120)->nullable();
            $table->timestamps();

            $table->index(['recibo', 'fecha_pago', 'preimpreso']);
            $table->index('cod_tra');
            $table->index('cod_dtra');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recaudacion_usos');
    }
};
