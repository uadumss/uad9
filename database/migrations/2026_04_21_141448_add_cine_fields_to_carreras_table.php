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
    public function up()
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->string('car_campo_amplio')->nullable()->after('car_abreviacion');
            $table->string('car_campo_especifico')->nullable()->after('car_campo_amplio');
            $table->string('car_campo_detallado')->nullable()->after('car_campo_especifico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn(['car_campo_amplio', 'car_campo_especifico', 'car_campo_detallado']);
        });
    }
};
