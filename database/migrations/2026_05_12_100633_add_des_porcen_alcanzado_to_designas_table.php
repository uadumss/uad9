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
        Schema::table('designas', function (Blueprint $table) {
            $table->decimal('des_porcen_alcanzado', 5, 2)->default(0)->after('des_fech_ret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designas', function (Blueprint $table) {
            $table->dropColumn('des_porcen_alcanzado');
        });
    }
};
