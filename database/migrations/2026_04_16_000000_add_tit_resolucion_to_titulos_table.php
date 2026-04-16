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
            if (!Schema::hasColumn('public.titulos', 'tit_resolucion')) {
                $table->string('tit_resolucion')->nullable()->comment('Número de resolución del título');
            }
            if (!Schema::hasColumn('public.titulos', 'tit_fecha_resolucion')) {
                $table->date('tit_fecha_resolucion')->nullable()->comment('Fecha de la resolución del título');
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
            if (Schema::hasColumn('public.titulos', 'tit_resolucion')) {
                $table->dropColumn('tit_resolucion');
            }
            if (Schema::hasColumn('public.titulos', 'tit_fecha_resolucion')) {
                $table->dropColumn('tit_fecha_resolucion');
            }
        });
    }
};
