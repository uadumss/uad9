<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas_cuadis', function (Blueprint $table) {
            // Extension 1:1 de personas: una fila CUADIS por persona.
            $table->integer('id_per');
            $table->boolean('pcu_hab')->default(true);
            $table->string('pcu_respaldo', 180)->nullable();
            $table->text('pcu_observacion')->nullable();
            $table->timestamps();

            $table->primary('id_per');
            $table->index('pcu_hab');

            $table->foreign('id_per')
                ->references('id_per')
                ->on('personas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas_cuadis');
    }
};
