<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apostilla.detalle_apostilla', function (Blueprint $table) {
            $table->string('dapo_valorado_preimpreso', 50)->nullable();
            $table->integer('dapo_valorado_gestion')->nullable();
            $table->char('dapo_verificacion_sitra', 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('apostilla.detalle_apostilla', function (Blueprint $table) {
            $table->dropColumn([
                'dapo_valorado_preimpreso',
                'dapo_valorado_gestion',
                'dapo_verificacion_sitra',
            ]);
        });
    }
};