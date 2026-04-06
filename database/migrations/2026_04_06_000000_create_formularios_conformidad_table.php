<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->create('doc_adm.formularios_conformidad', function (Blueprint $table) {
            $table->bigIncrements('cod_fcon');
            $table->unsignedBigInteger('cod_fun');
            $table->string('codigo')->unique();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('cod_fun', 'fk_formularios_conformidad_funcionario')
                ->references('cod_fun')
                ->on('doc_adm.funcionarios')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->dropIfExists('doc_adm.formularios_conformidad');
    }
};