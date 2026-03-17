<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Designa;
use App\Http\Requests\ActividadCreateRequest;
use App\Http\Requests\TareaCreateRequest;
use App\Models\Responsable;
use App\Models\Tarea;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ActividadController extends Controller
{
    public function f_nueActividad(){
        return view('actividad.f_nueActividad');
    }
    public function l_actividad(){
        $id_per=Auth::user()->id;
        $act=Actividad::all()
            ->where('id',Auth::user()->id)
            ->sortBy('cod_act');

        $por=DB::table('actividads')
            ->join('tareas','actividads.cod_act','=','tareas.cod_act')
            ->select('actividads.cod_act', DB::raw('SUM(tareas.tar_por) as por'))
            ->where('actividads.id',Auth::user()->id)
            ->groupBy('actividads.cod_act')
            ->get();
        $porcen=DB::select("select a.cod_act,sum(a.suma) as suma
                                    from (select a.cod_act,a.act_nombre,(sum(d.dia_porcen)*t.tar_por)/100 as suma from actividads a, tareas t, diarios d
                                    WHERE a.cod_act=t.cod_act and t.cod_tar=d.cod_tar and a.id=$id_per group by a.cod_act,t.tar_por) as a
                                    group by a.cod_act;");
        //return $por;
        return view('actividad.l_actividad',compact('act','por','porcen'));
    }
    public function guardarActividad(ActividadCreateRequest $request){
        if(isset($request['ca'])){
            $act=Actividad::find($request['ca']);
            $act->act_nombre=$request['nombre'];
            $act->act_inicio=$request['fi'];
            $act->act_fin=$request['ff'];
            $act->act_desc=$request['desc'];
            $act->save();
        }else{
            $cotidiano=($request['cotidiano'] =='on') ?"t":"f";
            Actividad::create([
                'act_nombre'=>$request['nombre'],
                'act_inicio'=>$request['fi'],
                'act_fin'=>$request['ff'],
                'act_hab'=>'t',
                'act_cotidiano'=>$cotidiano,
                'act_desc'=>$request['desc'],
                'id'=> Auth::user()->id,
            ]);
        }
        \Session::flash('exito','La actividad se ha guardado exitosamente');

        return redirect('listar actividades');
    }
    public function f_obtener_actividad($cod_act){
        $a=Actividad::find($cod_act);
        return view('actividad.f_editarActividad',compact('a','cod_act'));
    }
    public function f_eliminar_actividad($cod_act){
        $a=Actividad::find($cod_act);
        $tareas=Tarea::all()->where('cod_act','=',$cod_act);
        return view('actividad.f_eliminarActividad',compact('a','tareas'));
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
        return redirect('listar actividades');
    }

    public function hab_actividad($id){
        $act=Actividad::find($id);
        if($act->act_hab=='t'){
            $act->act_hab='f';
        }else{
            $act->act_hab='t';
        }
        $act->save();
        return redirect('listar actividades');
    }


}
