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
            if (!Schema::hasColumn('doc_adm.funcionarios', 'fun_pdf_env_dpa')) {
                $table->string('fun_pdf_env_dpa', 255)->nullable()->comment('Archivo PDF de control de envio a la DPA');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_adm.funcionarios', function (Blueprint $table) {
            if (Schema::hasColumn('doc_adm.funcionarios', 'fun_pdf_env_dpa')) {
                $table->dropColumn('fun_pdf_env_dpa');
            }
        });
    }
};
