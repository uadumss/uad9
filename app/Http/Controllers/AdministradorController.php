<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Designa;
use App\Models\Diario;
use App\Http\Requests\ActividadCreateRequest;
use App\Http\Requests\TareaCreateRequest;
use App\Models\Obs_diario;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministradorController extends Controller
{
    public function a_o_listaAct($id){

        $act=Actividad::all()
            ->where('id',$id)
            ->sortBy('cod_act');

        $por=DB::table('actividads')
            ->join('tareas','actividads.cod_act','=','tareas.cod_act')
            ->select('actividads.cod_act', DB::raw('SUM(tareas.tar_por) as por'))
            ->where('actividads.id',$id)
            ->groupBy('actividads.cod_act')
            ->get();

        $porcen=DB::select("select a.cod_act,sum(a.suma) as suma
                                    from (select a.cod_act,a.act_nombre,(sum(d.dia_porcen)*t.tar_por)/100 as suma from actividads a, tareas t, diarios d
                                    WHERE a.cod_act=t.cod_act and t.cod_tar=d.cod_tar and a.id=$id group by a.cod_act,t.tar_por) as a
                                    group by a.cod_act;");
        //dd($porcen);
        //return $por;
        return view('session.administracion.act.listar_actividad_responsable',compact('act','por','porcen','id'));
    }
    public function hab_actividad($cod_act){
        //return "hola";
        $act=Actividad::find($cod_act);
        if($act->act_hab=='t'){
            $act->act_hab='f';
        }else{
            $act->act_hab='t';
        }
        $act->save();
        return redirect('listar actividades adm/'.$act->id);
    }
    public function a_o_actividad($ia,$id_per){
        $act=Actividad::find($ia);
        return view('session.administracion.act.f_editarActividad',compact('act','id_per'));
    }
    public function a_guardarActividad(ActividadCreateRequest $request){
        if(isset($request['ia'])){
            $act=Actividad::find($request['ia']);
            $act->act_nombre=$request['nombre'];
            $act->act_inicio=$request['fi'];
            $act->act_fin=$request['ff'];
            $act->act_desc=$request['desc'];
            $act->save();
        }else{
            $cot=($request['cot'] =='on') ?"t":"f";
            Actividad::create([
                'act_nombre'=>$request['nombre'],
                'act_fi'=>$request['fi'],
                'act_ff'=>$request['ff'],
                'act_hab'=>'t',
                'act_cot'=>$cot,
                'act_desc'=>$request['desc'],
                'id_per'=> $request['id'],
            ]);
        }
        \Session::flash('exito','La actividad se ha guardado exitosamente');
        return redirect('listar actividades adm/'.$request['id']);
    }
    public function f_eliminar_actividad($cod_act){
        $a=Actividad::find($cod_act);
        $tareas=Tarea::all()->where('cod_act','=',$cod_act);
        return view('session.administracion.act.f_eliminar_actividad_adm',compact('a','tareas'));
    }
    public function e_actividad(Request $form){
        $tareas=Tarea::all()->where('cod_act','=',$form['ca']);
        if(sizeof($tareas)>0){
            \Session::flash('error','No se puede eliminar la actividad, debido a que tiene tareas programadas');
        }else{
            \Session::flash('exito','Se ha eliminado correctamente la Actividad');
            $act=Actividad::find($form['ca']);
            $act->delete();
        }

        return redirect('listar actividades adm/'.$form['id']);
    }
    //===================================TAREAS DE ACTIVIDADES=================================

    public function l_tareas($ca){

        $tar=Tarea::all()->where('cod_act','=',$ca)
            ->sortBy('cod_tar');

        $act=Actividad::find($ca);
        $id_per=$act['id'];
        $resp=User::all()->where('bloqueado','=','f');

        $designados=DB::table('designas')
            ->join('users','designas.id','=','users.id')
            ->join('tareas','designas.cod_tar','=','tareas.cod_tar')
            ->select('designas.cod_tar','designas.cod_des','users.name','users.foto','users.id','users.sexo')
            ->where('tareas.cod_act','=',$ca)
            ->get();

        //return $designados;
        $porcen=DB::select("select t.cod_tar,t.tar_nombre,sum(d.dia_porcen) as suma from tareas t, diarios d
                                    WHERE t.cod_act=$ca and t.cod_tar=d.cod_tar group by t.cod_tar");

        $porcenAct=DB::select("select a.cod_act,sum(a.suma) as suma
                                    from (select a.cod_act,a.act_nombre,(sum(d.dia_porcen)*t.tar_por)/100 as suma from actividads a, tareas t, diarios d
                                    WHERE a.cod_act=$ca and a.cod_act=t.cod_act and t.cod_tar=d.cod_tar group by a.cod_act,t.tar_por) as a
                                    group by a.cod_act;");
        return view('session.administracion.act.listar_tarea',compact('tar','act','resp','designados','porcen','porcenAct','id_per'));
    }
    public function a_hab_tarea($cod_tar){
        $tarea=Tarea::find($cod_tar);
        if($tarea->tar_hab=='f'){
            $tarea->tar_hab='t';
        }else{
            $tarea->tar_hab='f';
        }
        $tarea->save();
        return redirect('listar tareas actividad adm/'.$tarea->cod_act);
    }
    public function a_o_tarea($ct){
        $tarea=Tarea::find($ct);
        return view('session.administracion.act.f_editarTarea',compact('tarea'));
    }
    public function a_guardarTarea(TareaCreateRequest $request){

        if(isset($request['ct'])){
            $tarea=Tarea::find($request['ct']);
            $tarea->tar_nombre=$request['nombre'];
            $tarea->tar_fi=$request['fi'];
            $tarea->tar_ff=$request['ff'];
            $tarea->tar_desc=$request['desc'];
            $tarea->tar_por=$request['por'];
            $tarea->save();
        }else{
            $tarea=Tarea::create([
                'tar_nombre'=>$request['nombre'],
                'tar_fi'=>$request['fi'],
                'tar_ff'=>$request['ff'],
                'tar_hab'=>'t',
                'tar_desc'=>$request['desc'],
                'tar_con'=>'f',
                'tar_por'=>$request['por'],
                'id_act'=>$request['ia'],
                'id_per'=> Auth::user()->id,
            ]);
            Designa::create([
                'des_fech_asig'=>'now',
                'des_hab'=>'t',
                'res_id_per'=>Auth::user()->id,
                'id_per'=>$request['fun'],
                'id_tar'=>$tarea->cod_tar,
            ]);
        }
        \Session::flash('exito','La Tarea se ha guardado exitosamente');
        return redirect('listar tareas actividad adm/'.$tarea->cod_act);
    }
    public function f_eliminar_tarea($cod_tar){
        $tarea=Tarea::find($cod_tar);
        $diarios=Diario::all()->where('cod_tar','=',$cod_tar);
        return view('session.administracion.act.f_eliminar_tarea_adm',compact('tarea','diarios'));
    }
    public function eliminar_tarea(Request $form){
        $tarea=Tarea::find($form['ct']);
        $cod_act=$tarea->cod_act;
        DB::delete('delete from designas where cod_tar='.$form['ct']);
        $tarea->delete();
        \Session::flash('exito','Se ha eliminado con exito la tarea');
        return redirect('listar tareas actividad adm/'.$tarea->cod_act);
    }
    public function l_reporte_diario($cod_tar){
        $tarea=Tarea::find($cod_tar);
        $actividad=Actividad::find($tarea->cod_act);
        $designa=DB::table('designas')->where('cod_tar','=',$cod_tar)->first();
        $diario=Diario::all()->where('cod_tar','=',$cod_tar);
        return view('session.administracion.act.listar_diario_adm',compact('tarea','diario','designa','actividad'));
    }
    public function revision_diario($cod_dia){
        $diario=Diario::find($cod_dia);
        $observaciones=Obs_diario::all()->where('cod_dia','=',$cod_dia)->sortByDesc('cod_od');
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where cod_tar=".$diario['cod_tar'];
        $totalPorcen=DB::select($sql);
        return view('session.administracion.act.l_observacionTarea_adm',compact('diario','observaciones','totalPorcen'));
    }
    public function f_eliminar_diario($cod_dia){
        $diario=Diario::find($cod_dia);
        $usuario=User::find($diario->id);
        $tarea=Tarea::find($diario->cod_tar);
        return view('session.administracion.act.f_eliminar_diario_adm',compact('diario','usuario','tarea'));
    }
    public function eliminar_diario(Request $form){
        $diario=Diario::find($form['cd']);
        DB::delete('delete from obs_diarios where cod_dia='.$diario->cod_dia);
        $diario->delete();
        return redirect('listar reporte diario adm/'.$diario->cod_tar);
    }
    public function listar_tareas($id)
    {
        $tarea=DB::table('designas')
            ->join('tareas','designas.cod_tar','=','tareas.cod_tar')
            ->join('actividads','tareas.cod_act','=','actividads.cod_act')
            ->select('tareas.tar_nombre','tareas.tar_fi','tareas.tar_ff','tareas.cod_tar','tareas.tar_avance',
                'designas.id','designas.des_con','designas.cod_des','act_nombre','tar_concluido','tar_inf_final','tar_cotidiano')
            ->where('designas.id','=',$id)
            ->get();

        $porcen=DB::select("select t.cod_tar,sum(d.dia_porcen) as suma from tareas t, diarios d
                                    WHERE d.id=$id and t.cod_tar=d.cod_tar group by t.cod_tar");
        return view('session.administracion.tar.lista_tarea_adm',compact('tarea','porcen'));
    }
//============================================VER REPORTES POR FECHAS================
    public function listar_reportes_fecha(){

        $usuarios=User::all()->where('bloqueado','<>','t')->sortBy('name');
        $reportes=Diario::all()->where('dia_fech','=',date('Y-m-d'));
        $fecha=date('Y-m-d');
        //return sizeof($usuarios)."- ".sizeof($reportes);
        return view('session.administracion.reporte.l_reporte_fecha',compact('usuarios','reportes','fecha'));

    }
    public function listar_reportes_fecha1($fecha){

        $usuarios=User::all()->where('bloqueado','<>','t')->sortBy('name');
        $reportes=Diario::all()->where('dia_fech','=',$fecha);
        //return sizeof($usuarios)."- ".sizeof($reportes);
        return view('session.administracion.reporte.l_reporte_fecha',compact('usuarios','reportes','fecha'));

    }
}
