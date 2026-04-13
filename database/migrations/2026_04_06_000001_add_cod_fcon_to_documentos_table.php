<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('doc_adm.documentos', function (Blueprint $table) {
            if (!Schema::connection('pgsql')->hasColumn('doc_adm.documentos', 'cod_fcon')) {
                $table->unsignedBigInteger('cod_fcon')->nullable()->after('cod_fun');
                $table->foreign('cod_fcon', 'fk_documentos_formulario_conformidad')
                    ->references('cod_fcon')
                    ->on('doc_adm.formularios_conformidad')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('doc_adm.documentos', function (Blueprint $table) {
            if (Schema::connection('pgsql')->hasColumn('doc_adm.documentos', 'cod_fcon')) {
                $table->dropForeign('fk_documentos_formulario_conformidad');
                $table->dropColumn('cod_fcon');
            }
        });
    }
};