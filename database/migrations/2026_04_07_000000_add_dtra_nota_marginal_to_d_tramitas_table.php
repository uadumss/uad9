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
        Schema::table('public.d_tramitas', function (Blueprint $table) {
            if (!Schema::hasColumn('public.d_tramitas', 'dtra_nota_marginal')) {
                $table->text('dtra_nota_marginal')->nullable()->comment('Nota marginal del trámite');
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
        Schema::table('public.d_tramitas', function (Blueprint $table) {
            if (Schema::hasColumn('public.d_tramitas', 'dtra_nota_marginal')) {
                $table->dropColumn('dtra_nota_marginal');
            }
        });
    }
};
