<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Designa;
use App\Models\Diario;
use App\Models\Obs_diario;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TareaController extends Controller
{
   public function a_l_tareas($id_per){

       $tarea=DB::table('designas')
           ->join('tareas','designas.id_tar','=','tareas.id_tar')
           ->join('actividads','tareas.id_act','=','actividads.id_act')
           ->select('tareas.tar_nombre','tareas.tar_fi','tareas.tar_ff','tareas.id_tar','tareas.tar_avance',
               'designas.id_per','designas.des_con','designas.id_des','act_nombre','tar_con','tar_inf_final')
           ->where('designas.id_per','=',$id_per)
           ->where('tareas.deleted_at')
           ->get();

       $porcen=DB::select("select t.id_tar,sum(d.dia_porcen) as suma from tareas t, diarios d
                                    WHERE d.id_per=$id_per and t.id_tar=d.id_tar group by t.id_tar");
       return view('adm.tar.l_tareaFun',compact('tarea','porcen'));
   }
    public function a_m_tareaRegistro($id_des){
        $des=Designa::find($id_des);
        $tarea=Tarea::find($des['id_tar']);
        $id_per=$des->id_per;
        $actividad=Actividad::find($tarea->id_act);
        $diario=DB::table('diarios')
            ->join('tareas','diarios.id_tar','=','tareas.id_tar')
            ->join('designas','diarios.id_des','=','designas.id_des')
            ->select('diarios.*')
            ->where('designas.id_per','=',$id_per)
            ->where('diarios.deleted_at')
            ->where('diarios.id_des',$id_des)
            ->orderByDesc('diarios.dia_fech')
            ->get();

        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where id_tar=".$tarea->id_tar;
        $totalPorcen=DB::select($sql);

        return view('adm.tar.registro_diario',compact('tarea','diario','des','actividad','totalPorcen'));
    }
    public function a_mostrar_observacionesTarea($id_dia){
        $diario=Diario::find($id_dia);
        $observaciones=Obs_diario::all()->where('id_dia','=',$id_dia)->sortByDesc('id_od');
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where id_tar=".$diario['id_tar'];
        $totalPorcen=DB::select($sql);

        return view('adm.tar.l_observacionFun',compact('diario','observaciones','totalPorcen'));
    }
}
