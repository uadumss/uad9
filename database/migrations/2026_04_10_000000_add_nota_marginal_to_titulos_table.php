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
        });
    }
};
