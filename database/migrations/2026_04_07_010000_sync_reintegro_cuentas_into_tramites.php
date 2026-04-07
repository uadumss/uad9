<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('tramites')){
            return;
        }

        $defaultCuentas=[
            [
                'codigo_cuenta'=>'151025.001',
                'descripcion'=>'REINTEGRO TIT. PROVISION NAL-PROFESIONAL',
                'habilitada'=>true,
            ],
            [
                'codigo_cuenta'=>'151035.001',
                'descripcion'=>'REINTEGRO DIPL. ACADEMICO',
                'habilitada'=>true,
            ],
            [
                'codigo_cuenta'=>'151035.002',
                'descripcion'=>'REINTEGRO DIPL. BACHILLER',
                'habilitada'=>true,
            ],
            [
                'codigo_cuenta'=>'151039.077',
                'descripcion'=>'REINTEGROS VARIOS',
                'habilitada'=>true,
            ],
        ];

        $cuentas=$defaultCuentas;

        foreach($cuentas as $cuenta){
            $codigo=trim((string)$cuenta['codigo_cuenta']);
            if($codigo===''){
                continue;
            }

            $descripcion=trim((string)($cuenta['descripcion'] ?? ''));
            if($descripcion===''){
                $descripcion='CUENTA REINTEGRO';
            }

            $habilitada=(bool)($cuenta['habilitada'] ?? true);
            $valorHab=$habilitada ? 't' : 'f';

            $existente=DB::table('tramites')
                ->where('tre_tipo','=','R')
                ->where('tre_numero_cuenta','=',$codigo)
                ->first(['cod_tre']);

            if($existente){
                DB::table('tramites')
                    ->where('cod_tre','=',$existente->cod_tre)
                    ->update([
                        'tre_nombre'=>$descripcion,
                        'tre_hab'=>$valorHab,
                        'tre_desc'=>'CUENTAS REINTEGRO (CATALOGO)',
                        'updated_at'=>now(),
                    ]);
                continue;
            }

            DB::table('tramites')->insert([
                'tre_nombre'=>$descripcion,
                'tre_titulo'=>'',
                'tre_titulo_interno'=>'',
                'tre_glosa'=>'',
                'tre_hab'=>$valorHab,
                'tre_duracion'=>'0',
                'tre_desc'=>'CUENTAS REINTEGRO (CATALOGO)',
                'tre_costo'=>0,
                'tre_tipo'=>'R',
                'tre_buscar_en'=>'',
                'tre_numero_cuenta'=>$codigo,
                'tre_solo_sello'=>'',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }
    }

    public function down(): void
    {
        if(!Schema::hasTable('tramites')){
            return;
        }

        DB::table('tramites')
            ->where('tre_tipo','=','R')
            ->where('tre_desc','=','CUENTAS REINTEGRO (CATALOGO)')
            ->whereIn('tre_numero_cuenta',[
                '151025.001',
                '151035.001',
                '151035.002',
                '151039.077',
            ])
            ->delete();
    }
};
