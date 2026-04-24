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
            $table->dropColumn([
                'car_campo_amplio',
                'car_campo_especifico',
                'car_campo_detallado'
            ]);
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
            $table->string('car_campo_amplio')->nullable();
            $table->string('car_campo_especifico')->nullable();
            $table->string('car_campo_detallado')->nullable();
        });
    }
};
