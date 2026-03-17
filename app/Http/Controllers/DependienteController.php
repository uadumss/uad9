<?php

namespace App\Http\Controllers;

use App\Models\A_cargo;
use App\Models\Diario;
use App\Models\Reporte_periodo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DependienteController extends Controller
{
    public function l_dependientes(){

        $dependientes=DB::table('a_cargos')
                ->join('users','a_cargos.id','=','users.id')
                ->select('users.*','a_cargos.ac_inicio','a_cargos.ac_fin')
                ->where('a_cargos.ac_hab','=','t')
                ->where('a_cargos.id_responsable','=',Auth::user()->id)->get();
        return view('actividad.dependiente.l_dependiente',compact('dependientes'));
    }
    public function l_tareas_dependiente($id){
        $user=User::find($id);
        $tareas=DB::table('tareas')
            ->join('designas','tareas.cod_tar','=','designas.cod_tar')
            ->join('actividads','tareas.cod_act','=','actividads.cod_act')
            ->select('tareas.*','actividads.act_nombre','designas.cod_des')
            ->where('designas.id','=',$id)->get();
        return view('actividad.dependiente.l_tareas_dependiente',compact('tareas','user'));
    }
    public function l_reporte_periodico_dependiente($id){
        $user=User::find($id);
        $reportePeriodico=Reporte_periodo::all()->where('id','=',$id);
        return view('actividad.dependiente.l_reporte_periodico_dependiente',compact('user','reportePeriodico'));
    }
    public function f_revisar_reporte_periodico($cod_rt){
        $reporte_periodico=Reporte_periodo::find($cod_rt);
        $reportes=Diario::all()->where('id','=',$reporte_periodico->id)
            ->whereBetween('dia_fech',[$reporte_periodico->rt_fech_ini,$reporte_periodico->rt_fech_fin]);
        return view('actividad.dependiente.f_revisar_reporte_periodico',compact('reporte_periodico','reportes'));
    }
}
