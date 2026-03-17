<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\Funciones;
use App\Http\Requests\Reporte_periodoCreateRequest;
use App\Models\Reporte_periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Reporte_periodoController extends Controller
{
    public function l_informePeriodo(){
        $informe=Reporte_periodo::all()
            ->where('id',Auth::user()->id)
            ->sortByDesc('rt_num');
        return view('actividad.informe.l_informe',compact('informe'));
    }
    public function f_editar_reporteFinal($cod_rt){
        $reporte_final=Reporte_periodo::find($cod_rt);

        return view('actividad.informe.f_editar_reporte',compact('reporte_final','cod_rt'));

    }
    public function o_reporteDiario(Request $request){
        $diario=Diario::all()
            ->where('id',Auth::user()->id)
            ->whereBetween('dia_fech',[$request['fi'],$request['ff']])
            ->sortBy('dia_fech');
        $funciones=new Funciones();
        return view('actividad.informe.t_infDiario',compact('diario','funciones'));
    }
    public function g_informe(Reporte_periodoCreateRequest $request){

        if(isset($request['cr'])){
                $rep=Reporte_periodo::find($request['cr']);
                $rep->rt_fech_ini=$request['fi'];
                $rep->rt_fech_fin=$request['ff'];
                $rep->rt_bandera_obs='f';
                $rep->rt_bandera_corregido='t';
                $rep->rt_desc=$request['desc'];
                $rep->save();

        }else{
            Reporte_periodo::create([
                'rt_desc'=>$request['desc'],
                'rt_fech_ini'=>$request['fi'],
                'rt_fech_fin'=>$request['ff'],
                'rt_apr'=>'f',
                'rt_cal'=>0,
                'id'=>Auth::user()->id,
            ]);
        }
        \Session::flash('exito',"El informe se ha guardado exitosamente");
        return redirect('listar informePeriodo');
    }
    public function g_observacion_periodico(Request $form){
        $reporte_peridico=Reporte_periodo::find($form['cr']);
        if($form['obs']=='1'){
            $reporte_peridico->rt_obs=$form['observacion'];
            $reporte_peridico->rt_bandera_obs='t';
            $reporte_peridico->rt_bandera_corregido='f';
            $reporte_peridico->rt_fech_rev=date('d/m/Y');

        }else{
            $reporte_peridico->rt_cal=$form['calificacion'];
            $reporte_peridico->rt_bandera_obs='f';
            $reporte_peridico->rt_bandera_corregido='f';
            $reporte_peridico->rt_fech_rev=date('d/m/Y');
            $reporte_peridico->rt_apr='t';
        }
        $reporte_peridico->save();
        return redirect('l_reporte_periodico_dependiente/'.$reporte_peridico->id);
    }
    public function f_eliminar_reporte_periodico($cod_rt){
        $reporte_periodico=Reporte_periodo::find($cod_rt);
        return view('actividad.informe.f_eliminar_reporte_periodo',compact('reporte_periodico'));
    }
    public function e_reporte_periodo(Request $request){
        $rep=Reporte_periodo::findOrFail($request['cr']);
        if($rep['rt_apr']!='t') {
            $rep->delete();
            \Session::flash('exito',"El informe periodico Nº ".$rep->rt_num." se ha eliminado correctamente");
        }else{
            \Session::flash('error',"No se puede eliminar el reporte");
        }
        return redirect('listar informePeriodo');
    }

}
