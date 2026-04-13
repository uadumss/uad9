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
        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            $table->string('doc_tesis_titulo')->nullable()->default(null)->comment('Título de la tesis');
            $table->char('doc_tesis', 1)->nullable()->default('f')->comment('Si es tesis: t (true) o f (false)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            $table->dropColumn('doc_tesis_titulo');
            $table->dropColumn('doc_tesis');
        });
    }
};
