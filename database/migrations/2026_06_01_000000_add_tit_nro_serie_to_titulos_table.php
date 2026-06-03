<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('public.titulos', function (Blueprint $table) {
            if (!Schema::hasColumn('public.titulos', 'tit_nro_serie')) {
                $table->string('tit_nro_serie', 15)->nullable()->comment('Número de serie del título (Formato: Letra-Número)');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('public.titulos', function (Blueprint $table) {
            if (Schema::hasColumn('public.titulos', 'tit_nro_serie')) {
                $table->dropColumn('tit_nro_serie');
            }
        });
    }
};
