<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql')->table('doc_adm.documentos', function (Blueprint $table) {
            $table->string('doc_pdf')->nullable()->comment('Ruta del archivo PDF del documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql')->table('doc_adm.documentos', function (Blueprint $table) {
            $table->dropColumn('doc_pdf');
        });
    }
};
