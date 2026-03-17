<?php

namespace App\Imports;

use App\Models\Autoridad;
use App\Models\Firma;
use App\Models\Plan_archivo;
use App\Models\Resolucion;
use App\Models\Codigo_archivo;
use App\Models\Archivado;
use App\Models\Tomo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};


class importarRES implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        $tituloRegisrado=DB::table('resolucions')->join('tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->where('res_numero','=',$row['numero'])
            ->where('res_tipo','=',$row['tipo'])
            ->where('tom_tipo','=','res')
            ->where('tom_gestion','=',$row['ano'])
            ->first();
        if($tituloRegisrado){
            $resolucion=Resolucion::find($tituloRegisrado->cod_res);
            $resolucion->res_tema.="; ".$row['descriptor'];
            $resolucion->res_objeto.="; ".$row['nombre'];
            $resolucion->res_desc.="; ".$row['referencia'];
            $resolucion->save();

            $codigos=DB::table('archivados')
                ->join('codigo_archivos','archivados.cod_carch','=','codigo_archivos.cod_carch')
                ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
                ->where('cod_res','=',$resolucion->cod_res)
                ->select('carch_numero','plan_numero','codigo_archivos.cod_carch')
                ->get();
            $existe=0;
            foreach ($codigos as $c):
                if($row['codigo']==($c->plan_numero."/".$c->carch_numero)){
                    $existe=1;
                }
            endforeach;
            if($existe==0){
                $codigo=explode('/',$row['codigo']);
                $cod_carch='';
                $codigoArchivado=array();
                if(sizeof($codigo)>1) {
                    $cod_carch = '';
                    $cod_plan = '';
                    $plan = Plan_archivo::all()->where('plan_numero', '=', $codigo[0])->first();
                    if (!$plan) {
                        $plan = Plan_archivo::create([
                            'plan_numero' => $codigo[0],
                        ]);
                        $cod_plan = $plan->cod_plan;
                    } else {
                        $cod_plan = $plan->cod_plan;
                    }

                    $codigoArchivado = Codigo_archivo::all()->where('carch_numero', '=', $codigo[1])
                        ->where('cod_plan', '=', $cod_plan)->first();

                    if (!$codigoArchivado) {
                        $codigoArchivado = Codigo_archivo::create([
                            'carch_numero' => $codigo[1],
                            'cod_plan' => $cod_plan,
                        ]);
                        $cod_carch = $codigoArchivado->cod_carch;
                    } else {
                        $cod_carch = $codigoArchivado['cod_carch'];
                    }
                    $archivado = Archivado::create([
                        'cod_carch' => $cod_carch,
                        'cod_res' => $resolucion['cod_res'],
                    ]);
                }
            }

            return null;
        }else{
            $tomo=Tomo::where('tom_numero','=',$row['tomo'])
                ->where('tom_tipo','=','res')
                ->where('tom_gestion','=',$row['ano'])->get();
            $cod_tomo='';
            if(sizeof($tomo)<1){
                $tomo=Tomo::create([
                    'tom_numero'=>$row['tomo'],
                    'tom_gestion'=>$row['ano'],
                    'tom_tipo'=>'res',
                ]);
                $cod_tomo=$tomo->cod_tom;
            }else{

                $cod_tomo=$tomo[0]->cod_tom;
            }
            $resolucion =Resolucion::create([
                'res_numero'=>$row['numero'],
                'res_fecha'=>$row['fecha'],
                'res_desc'=>$row['referencia'],
                'res_tema'=>$row['descriptor'],
                'res_objeto'=>$row['nombre'],
                'res_tipo'=>$row['tipo'],
                'res_vistos'=>$row['vistos'],
                'res_gestion'=>$row['ano'],
                'res_considerando'=>$row['considerando'],
                'res_resuelve'=>$row['resuelve'],
                'res_obs'=>$row['observacion'],
                'cod_tom'=>$cod_tomo,
                'res_importacion'=>\Session::get('id_import'),
            ]);
            if($row['autoridad']!=''){
                   $aut=Autoridad::find($row['autoridad']);
                   if($aut){
                       $fir=Firma::create([
                           'cod_res'=>$resolucion->cod_res,
                            'cod_aut'=>$row['autoridad'],
                           'cod_aut2'=>$row['autoridad2'],
                       ]);
                   }
            }
            $codigo=explode('/',$row['codigo']);
            $cod_carch='';
            $codigoArchivado=array();
            if(sizeof($codigo)>1) {
                $cod_carch = '';
                $cod_plan = '';
                $plan = Plan_archivo::all()->where('plan_numero', '=', $codigo[0])->first();
                if (!$plan) {
                    $plan = Plan_archivo::create([
                        'plan_numero' => $codigo[0],
                    ]);
                    $cod_plan = $plan->cod_plan;
                } else {
                    $cod_plan = $plan->cod_plan;
                }

                $codigoArchivado = Codigo_archivo::all()->where('carch_numero', '=', $codigo[1])
                    ->where('cod_plan', '=', $cod_plan)->first();

                if (!$codigoArchivado) {
                    $codigoArchivado = Codigo_archivo::create([
                        'carch_numero' => $codigo[1],
                        'cod_plan' => $cod_plan,
                    ]);
                    $cod_carch = $codigoArchivado->cod_carch;
                } else {
                    $cod_carch = $codigoArchivado['cod_carch'];
                }
                $archivado = Archivado::create([
                    'cod_carch' => $cod_carch,
                    'cod_res' => $resolucion['cod_res'],
                ]);
            }
//===================OTRO CODIGO====================
                if($row['otro_codigo']!=''){
                    $codigo=explode('/',$row['otro_codigo']);
                    $cod_carch='';
                    $codigoArchivado=array();
                    if(sizeof($codigo)>1){
                        $cod_carch='';
                        $cod_plan='';
                        $plan=Plan_archivo::all()->where('plan_numero','=',$codigo[0])->first();
                        if(!$plan){
                            $plan=Plan_archivo::create([
                                'plan_numero'=>$codigo[0],
                            ]);
                            $cod_plan=$plan->cod_plan;
                        }else{
                            $cod_plan=$plan->cod_plan;
                        }

                        $codigoArchivado=Codigo_archivo::all()->where('carch_numero','=',$codigo[1])
                            ->where('cod_plan','=',$cod_plan)->first();

                        if(!$codigoArchivado){
                            $codigoArchivado=Codigo_archivo::create([
                                'carch_numero'=>$codigo[1],
                                'cod_plan'=>$cod_plan,
                            ]);
                            $cod_carch=$codigoArchivado->cod_carch;
                        }else{
                            $cod_carch=$codigoArchivado['cod_carch'];
                        }

                        $archivado=Archivado::create([
                            'cod_carch'=>$cod_carch,
                            'cod_res'=>$resolucion['cod_res'],
                        ]);
                }

            }
//===================FIN OTRO CODIGO====================
            return $resolucion;
        }
    }
    public function rules(): array
    {
        return [
            'fecha' => 'required|date',
            'numero' => 'required',
            'tomo'=>'required',
            'ano'=>'required',
            'tipo'=>['required', Rule::in(['rr','rcu','rs','rvr','rcf','rcc'])],
        ];
    }
}
