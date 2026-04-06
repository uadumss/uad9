<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroRegistroToDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            $table->string('doc_numero_registro')->nullable()->comment('Número de registro del documento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            $table->dropColumn('doc_numero_registro');
        });
    }
}
