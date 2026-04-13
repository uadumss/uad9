<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('doc_adm.formularios_conformidad', function (Blueprint $table) {
            if (!Schema::connection('pgsql')->hasColumn('doc_adm.formularios_conformidad', 'lugar_trabajo')) {
                $table->string('lugar_trabajo')->nullable()->after('codigo');
            }
            if (!Schema::connection('pgsql')->hasColumn('doc_adm.formularios_conformidad', 'carrera')) {
                $table->string('carrera')->nullable()->after('lugar_trabajo');
            }
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('doc_adm.formularios_conformidad', function (Blueprint $table) {
            if (Schema::connection('pgsql')->hasColumn('doc_adm.formularios_conformidad', 'carrera')) {
                $table->dropColumn('carrera');
            }
            if (Schema::connection('pgsql')->hasColumn('doc_adm.formularios_conformidad', 'lugar_trabajo')) {
                $table->dropColumn('lugar_trabajo');
            }
        });
    }
};