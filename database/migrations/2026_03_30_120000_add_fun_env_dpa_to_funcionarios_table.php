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
            if (!Schema::hasColumn('doc_adm.funcionarios', 'fun_env_dpa')) {
                $table->boolean('fun_env_dpa')->default(false)->comment('Indica si el funcionario fue enviado a la DPA');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_adm.funcionarios', function (Blueprint $table) {
            if (Schema::hasColumn('doc_adm.funcionarios', 'fun_env_dpa')) {
                $table->dropColumn('fun_env_dpa');
            }
        });
    }
};
