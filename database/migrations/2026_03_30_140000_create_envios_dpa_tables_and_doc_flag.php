<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('doc_adm.envio_dpas')) {
            Schema::create('doc_adm.envio_dpas', function (Blueprint $table) {
                $table->bigIncrements('cod_env_dpa');
                $table->unsignedBigInteger('cod_fun');
                $table->string('env_pdf_control', 255);
                $table->timestamp('env_fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('doc_adm.envio_dpa_detalles')) {
            Schema::create('doc_adm.envio_dpa_detalles', function (Blueprint $table) {
                $table->bigIncrements('cod_env_det');
                $table->unsignedBigInteger('cod_env_dpa');
                $table->unsignedBigInteger('cod_doc');
                $table->timestamps();
                $table->unique(['cod_env_dpa', 'cod_doc']);
            });
        }

        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_adm.documentos', 'doc_enviado_dpa')) {
                $table->boolean('doc_enviado_dpa')->default(false)->comment('Indica si el titulo/diploma ya fue enviado al menos una vez a la DPA');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_adm.documentos', function (Blueprint $table) {
            if (Schema::hasColumn('doc_adm.documentos', 'doc_enviado_dpa')) {
                $table->dropColumn('doc_enviado_dpa');
            }
        });

        if (Schema::hasTable('doc_adm.envio_dpa_detalles')) {
            Schema::drop('doc_adm.envio_dpa_detalles');
        }

        if (Schema::hasTable('doc_adm.envio_dpas')) {
            Schema::drop('doc_adm.envio_dpas');
        }
    }
};
