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
            if (!Schema::hasColumn('public.titulos', 'nota_marginal')) {
                $table->text('nota_marginal')->nullable()->comment('Nota marginal del título');
            }
            if (!Schema::hasColumn('public.titulos', 'fecha_nota_marginal')) {
                $table->date('fecha_nota_marginal')->nullable()->comment('Fecha de la nota marginal');
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
            if (Schema::hasColumn('public.titulos', 'nota_marginal')) {
                $table->dropColumn('nota_marginal');
            }
            if (Schema::hasColumn('public.titulos', 'fecha_nota_marginal')) {
                $table->dropColumn('fecha_nota_marginal');
            }
        });
    }
};
