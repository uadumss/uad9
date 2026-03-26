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
        Schema::table('doc_adm.funcionarios', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_adm.funcionarios', 'fun_fecha_importacion')) {
                $table->timestamp('fun_fecha_importacion')->nullable()->comment('Fecha de importación del funcionario');
            }
            if (!Schema::hasColumn('doc_adm.funcionarios', 'fun_habilitado')) {
                $table->boolean('fun_habilitado')->nullable()->comment('Estado de habilitación del funcionario: true=habilitado, false=inhabilitado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_adm.funcionarios', function (Blueprint $table) {
            $table->dropColumn('fun_fecha_importacion');
            $table->dropColumn('fun_habilitado');
        });
    }
};
