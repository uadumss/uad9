<?php

namespace App\Http\Controllers;

use App\Models\T_observacion;
use App\Models\Titulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObservacionController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear observacion titulo - dyt|registrar solucion titulo - dyt'], ['only' => ['g_obs']]);
        $this->middleware(['permission:eliminar observacion titulo - dyt'], ['only' => ['e_obs']]);


    }
    public function l_obs($cod_tit){
        $titulo=DB::table('titulos')
            ->join('personas','titulos.id_per','=','personas.id_per')
            ->join('tomos','titulos.cod_tom','=','tomos.cod_tom')
            ->where('cod_tit','=',$cod_tit)
            ->select('tit_nro_titulo','cod_tit','per_apellido','tit_fecha_emision','tom_tipo','tom_gestion','per_nombre')
            ->get();
        $observaciones=T_observacion::all()->where('cod_tit','=',$cod_tit)->sortByDesc('obs_fecha');
        //return dd($titulo);
        return view('diplomas.titulo.l_observacion',compact('titulo','observaciones'));
    }
    public function g_obs(Request $form){
        if(isset($form['co'])){
            $obs=T_observacion::find($form['co']);
            $obs->obs_solucion=$form['obs'];
            $obs->obs_fecha_solucion=date('d/m/Y');
            $obs->save();
            \Session::flash('exito','Se ha guardado exitosamente la correción');
        }else{
            if($form['obs']!=''){
                $obs=T_observacion::create([
                    'cod_tit'=>$form['ct'],
                    'obs_observacion'=>$form['obs'],
                    'obs_fecha'=>date('d/m/Y'),
                ]);
                $titulo=Titulo::find($form['ct']);
                $titulo->tit_obs=1;
                $titulo->save();
                \Session::flash('exito','Se ha guardado exitosamente la observacion');
            }else{
                \Session::flash('error','Debe ingresar una observación válida');
            }
        }

        return redirect('ver obs/'.$form['ct']);
    }
    public function e_obs(Request $form){
        if(isset($form['co'])){
            $obs=T_observacion::find($form['co']);
            $obs->delete();
            $cantObs=T_observacion::all()->where('cod_tit','=',$obs->cod_tit);
            if(sizeof($cantObs)<1){
                $titulo=Titulo::find($obs->cod_tit);
                $titulo->tit_obs=0;
                $titulo->save();
            }

            \Session::flash('exito','Se ha eliminado exitosamente la observacion '.sizeof($cantObs));
        }else{
            \Session::flash('error','No se puedo eliminar la observación');
        }
        return redirect('ver obs/'.$obs->cod_tit);
    }
}
