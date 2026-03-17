<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Designa;
use App\Models\Diario;
use App\Http\Requests\TareaCreateRequest;
use App\Models\Tarea;
use App\Models\User;
use Couchbase\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActividadTareaController extends Controller
{

    //===================TAREAS============================
    public function listar_tareas($ca){

        $tar=Tarea::all()->where('cod_act','=',$ca)
            ->sortBy('cod_tar');

        $act=Actividad::find($ca);

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
        return view('actividad.tarea.l_tarea',compact('tar','act','resp','designados','porcen','porcenAct'));

    }
    public function f_editar_tarea($cod_tar,$cod_act){
        $actividad=Actividad::find($cod_act);
        $tarea="";
        if($cod_tar!=0){
            $tarea=Tarea::find($cod_tar);
        }
        $p_acargo=DB::table('a_cargos')
            ->join('users','a_cargos.id','=','users.id')
            ->select('users.id','users.name')
            ->where('a_cargos.id_responsable','=',Auth::user()->id)
            ->where('a_cargos.ac_hab','=','t')
            ->get();
        return view('actividad.tarea.f_editarTarea',compact('tarea','cod_tar','actividad','p_acargo'));
    }

    public function guardarTarea(TareaCreateRequest $form){
        $actividad=Actividad::find($form['ca']);
        if(isset($form['ct'])){
            $act=Tarea::find($form['ct']);
            $act->tar_nombre=$form['nombre'];
            $act->tar_fi=$form['fi'];
            $act->tar_ff=$form['ff'];
            $act->tar_desc=$form['desc'];
            $act->tar_por=$form['por'];
            $act->save();
        }else{
            $cotidiano=($actividad->act_cotidiano =='t') ?"t":"f";
            $tar=Tarea::create([
                'tar_nombre'=>$form['nombre'],
                'tar_fi'=>$form['fi'],
                'tar_ff'=>$form['ff'],
                'tar_hab'=>'t',
                'tar_desc'=>$form['desc'],
                'tar_con'=>'f',
                'tar_por'=>$form['por'],
                'tar_cotidiano'=>$cotidiano,
                'cod_act'=>$form['ca'],
                'id_responsable'=> Auth::user()->id,
            ]);
            Designa::create([
                'des_fech_asig'=>'now',
                'des_hab'=>'t',
                'id_responsable'=>Auth::user()->id,
                'id'=>$form['fun'],
                'cod_tar'=>$tar->cod_tar,
            ]);
        }
        \Session::flash('exito','La Tarea se ha guardado exitosamente');
        return redirect('listar tareas/'.$form['ca']);
    }
    public function hab_Tarea($cod_tar){

        $tarea=Tarea::find($cod_tar);
        if($tarea->tar_hab=='f'){$tarea->tar_hab='t';}else{$tarea->tar_hab='f';}
        $tarea->save();
        return redirect('listar tareas/'.$tarea->cod_act);
    }
    public function f_eliminar_tarea($cod_tar){
        $tarea=Tarea::find($cod_tar);
        $actividad=Actividad::find($tarea->cod_act);
        $diarios=Diario::all()->where('cod_tar','=',$tarea->cod_tar);
        return view('actividad.tarea.f_eliminar_tarea',compact('tarea','actividad','diarios'));
    }

    public function e_tarea(Request $form){
        $tarea=Tarea::find($form['ct']);
        $diarios=Diario::all()->where('cod_tar','=',$tarea->cod_tar);
        if(sizeof($diarios)>0){
            \Session::flash('error','No se puede eliminar la tarea debido a que tiene reportes diarios');
        }else{
            DB::delete('delete from designas where cod_tar='.$tarea->cod_tar);
            $tarea->delete();
            \Session::flash('exito','Se ha eliminado con exito la tarea');
        }
        return redirect('listar tareas/'.$tarea->cod_act);
    }

    public function f_listaAsignados($cod_des){

        $usu=DB::table('designas')
            ->join('users','designas.id','=','users.id')
            ->select('designas.*','users.*')
            ->where('designas.cod_des','=',$cod_des)
            ->get();
        $id_tar=$usu[0]->cod_tar;
        $tarea=Tarea::find($id_tar);

        return view('actividad.tarea.f_asignados',compact('usu','tarea'));
    }
    public function g_funcionarioTarea(Request $request){
        Designa::create([
            'des_fech_asig'=>'now',
            'des_hab'=>'t',
            'des_con'=>'f',
            'des_bloq_con'=>'f',
            'res_id_per'=>Auth::user()->id,
            'id_per'=>$request['if'],
            'id_tar'=>$request['it'],
        ]);
        $tar=Tarea::find($request['it']);
        \Session::flash('exito','Se ha asignado un nuevo funcionario a la tarea "'.$tar['tar_nombre'].'"');
        return redirect('listar_tareas/'.$tar->id_act);
    }

    //==================END TAREAS
}
