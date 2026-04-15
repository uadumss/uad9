<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doc_adm.codigo_secuencial', function (Blueprint $table) {
            $table->id('cod_seq');
            $table->string('tipo', 10); // 'DOC' o 'ADM'
            $table->year('anio');
            $table->integer('ultimo_numero')->default(0);
            $table->timestamps();
            
            // Índice único por tipo y año
            $table->unique(['tipo', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_adm.codigo_secuencial');
    }
};
