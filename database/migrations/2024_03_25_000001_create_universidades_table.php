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
        Schema::connection('pgsql')->create('doc_adm.universidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('sigla')->unique();
            $table->enum('tipo', ['Pública', 'Privada', 'Extranjera', 'Otro'])->default('Extranjera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql')->dropIfExists('doc_adm.universidades');
    }
};
