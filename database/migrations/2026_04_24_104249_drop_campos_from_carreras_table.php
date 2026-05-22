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
        $columnsToDrop = [];
        foreach (['car_campo_amplio', 'car_campo_especifico', 'car_campo_detallado'] as $column) {
            if (Schema::hasColumn('carreras', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if (!empty($columnsToDrop)) {
            Schema::table('carreras', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columnsToAdd = [];
        foreach (['car_campo_amplio', 'car_campo_especifico', 'car_campo_detallado'] as $column) {
            if (!Schema::hasColumn('carreras', $column)) {
                $columnsToAdd[] = $column;
            }
        }

        if (!empty($columnsToAdd)) {
            Schema::table('carreras', function (Blueprint $table) use ($columnsToAdd) {
                foreach ($columnsToAdd as $column) {
                    $table->string($column)->nullable();
                }
            });
        }
    }
};
