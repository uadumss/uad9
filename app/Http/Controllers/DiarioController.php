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
use Illuminate\Auth\Authenticatable;

class DiarioController extends Controller
{
    public function l_tareaRegistro(){
        $id=Auth::user()->id;
        $tarea=DB::table('designas')
                        ->join('tareas','designas.cod_tar','=','tareas.cod_tar')
                        ->join('actividads','tareas.cod_act','=','actividads.cod_act')
                        ->select('tareas.tar_nombre','tareas.tar_fi','tareas.tar_ff','tareas.cod_tar','tareas.tar_avance',
                            'designas.id','designas.des_con','designas.cod_des','act_nombre','tar_concluido','tar_inf_final','tar_cotidiano')
                        ->where('designas.id','=',$id)
                        ->get();

        $porcen=DB::select("select t.cod_tar,sum(d.dia_porcen) as suma from tareas t, diarios d
                                    WHERE d.id=$id and t.cod_tar=d.cod_tar group by t.cod_tar");
        //dd($porcen);
        return view('actividad.registro.l_tarea_funcionario',compact('tarea','porcen'));

    }
    public function l_reporteDiario($cod_des){
        $des=Designa::find($cod_des);
        $tarea=Tarea::find($des['cod_tar']);
        $actividad=Actividad::find($tarea->cod_act);
        $diario=Diario::all()->where('id','=',Auth::user()->id)->where('cod_des',$cod_des)
            ->sortByDesc('diarios.dia_fech');
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where cod_tar=".$tarea->cod_tar;
        $totalPorcen=DB::select($sql);

        return view('actividad.reporte.registro_diario',compact('tarea','diario','des','actividad','totalPorcen'));
    }
    public function f_editar_diario($cod_dia,$cod_tar){
        $diario=Diario::find($cod_dia);
        $tarea=Tarea::find($cod_tar);

        return view('actividad.reporte.f_editar_reporte',compact('diario','tarea','cod_dia'));
    }
    public function g_diario(Request $form)
    {
        $fecha='';
        $tarea=Tarea::find($form['ct']);
        $des=Designa::all()->where('cod_tar','=',$form['ct'])->where('id','=',Auth::user()->id)->first();
        if($tarea->concluido!='t') { //puede crear o editar solo cuando no esta concluido
            if (isset($form['cd'])) {
                $dia=Diario::find($form['cd']);
                $dia->dia_fech=$form['fecha'];
                $dia->dia_reporte=$form['desc'];
                $dia->dia_fech_mod=date('d/m/Y');
                $dia->dia_corregir='c';
                $dia->save();
                $fecha=$dia->dia_fech;
            } else {

                    $final='';
                    if(isset($form['final']) && $form['final']=='t'){
                        $final='t';
                        $tarea=Tarea::find($form['ct']);
                        $tarea->tar_inf_final='t';
                        $tarea->save();
                    }
                    Diario::create([
                        'dia_fech' => $form['fecha'],
                        'dia_reporte' => $form['desc'],
                        'dia_fech_reportado' =>date('d/m/Y'),
                        'cod_tar' => $form['ct'],
                        'cod_des' => $des['cod_des'],
                        'id' => Auth::user()->id,
                        'dia_corregir'=>'f',
                        'dia_aceptado'=>'f',
                        'dia_final'=>$final,
                    ]);
            }
            $fecha=$form['fecha'];
            \Session::flash('exito',"El reporte de fecha ".date('d/m/Y', strtotime($fecha))." se ha guardado exitosamente");
        }
        return redirect('listar reportes diarios/'.$des['cod_des']);
    }
    public function f_eliminar_diario($cod_dia){
        $diario=Diario::find($cod_dia);
        $tarea=Tarea::find($diario->cod_tar);
        return view('actividad.reporte.f_eliminar_diario',compact('diario','tarea'));
    }

    public function eliminar_diario(Request $diario){
        $d=Diario::findOrFail($diario['cd']);
        $d->delete();
        \Session::flash('exito',"Se ha eliminado la fecha del reporte correctamente");
        return redirect('listar reportes diarios/'.$d['cod_des']);
    }
    // ================== SE REDIRECCIONO A N_DIARIO
    /*public function g_conclusion(Request $request){
        $des = Designa::find($request['id_des']);
        if($des['des_bloq_con']!='t') {                 //solo puede editar cuando no esta bloqueado por el responsable
            $des->des_rep_con = $request['desc'];
            $des->des_con = 't';
            $des->des_bloq_con='f';
            $des->save();
            \Session::flash('exito',"El reporte de conclusion se ha guardado exitosamente");
        }
        return redirect('m_funtarea/'.$des['id_des']);

    }*/
    //=====================ADMINISTRACION
    public function l_reporte($cod_tar,$redireccion){
        $tarea=Tarea::find($cod_tar);
        $diario=Diario::all()->where('cod_tar','=',$cod_tar)->sortByDesc('dia_fech');
        $actividad=Actividad::find($tarea->cod_act);
        $designa=DB::table('users')
            ->join('designas','users.id','=','designas.id')
            ->select('users.*')
            ->where('designas.cod_tar','=',$cod_tar)->first();
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where cod_tar=".$tarea->cod_tar;
        $totalPorcen=DB::select($sql);
        return view('actividad.reporte.l_reporte_diario',compact('tarea','diario','actividad','totalPorcen','designa','redireccion'));

    }
    public function revision_diario($cod_dia,$redireccion){
        $diario=Diario::find($cod_dia);
        $tarea=Tarea::find($diario->cod_tar);
        $observaciones=Obs_diario::all()->where('cod_dia','=',$cod_dia)->sortByDesc('cod_od');
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where cod_tar=".$diario['cod_tar'];
        $totalPorcen=DB::select($sql);
        return view('actividad.registro.l_observacionTarea',compact('diario','observaciones','totalPorcen','redireccion','tarea'));
    }

    public function l_observacionesTarea($cod_dia){
        $diario=Diario::find($cod_dia);
        $tarea=Tarea::find($diario->cod_tar);
        $observaciones=Obs_diario::all()->where('cod_dia','=',$cod_dia)->sortByDesc('cod_od');
        //return sizeof($observaciones);
        return view('actividad.registro.l_observacionFun',compact('diario','observaciones','tarea'));
    }
    public function g_observacion(Request $form){
        $tarea=Tarea::find($form['ct']);
        if($form['obs']!=''){
            $diario=Diario::find($form['cd']);
            if($diario->dia_corregir!='t'){
                $obs=Obs_diario::create([
                    'od_rep'=>$diario->dia_reporte,
                    'od_obs'=>$form['obs'],
                    'od_fech'=>'now()',
                    'od_fech_mod'=>$diario->dia_fech_mod,
                    'id_responsable'=>Auth::user()->id,
                    'cod_dia'=>$form['cd'],
                ]);
            }
            $diario->dia_obs=$form['obs'];
            $diario->dia_corregir='t';
            $diario->dia_fech_revision='now()';
            $diario->save();
        }
        return redirect('listar reportes/'.$tarea['cod_tar'].'/'.$form['redireccion']);
    }
    public function aceptarReporteDiario(Request $form){
        $diario=Diario::find($form['cd']);
        $diario->dia_calificacion=$form['cal'];
        $diario->dia_porcen=$form['por'];
        $diario->dia_aceptado='t';
        $diario->dia_corregir='f';
        $diario->dia_obs='';
        $diario->dia_fech_revision=date('d-m-Y');
        $diario->save();
        if($diario->dia_final=='t'){
            $tarea=Tarea::find($diario->cod_tar);
            $tarea->tar_concluido='t';
            $tarea->save();
        }
        $sql="select sum(diarios.dia_porcen) as porcentaje from diarios  where cod_tar=".$diario->cod_tar;
        $totalPorcen=DB::select($sql);

        if($totalPorcen[0]->porcentaje>=100){
            $tarea=Tarea::find($diario->cod_tar);
            $tarea->tar_concluido='t';
            $tarea->save();
        }
        return redirect('listar reportes/'.$diario['cod_tar'].'/'.$form['redireccion']);
    }
    public function f_reporte_concluido($cod_tar){
        $tarea=Tarea::find($cod_tar);
        $des=Designa::all()->where('cod_tar','=',$cod_tar)->where('id','=',Auth::user()->id)->first();
        $diario=Diario::all()->where('cod_tar','=',$cod_tar);
        return view('actividad.reporte.f_reporte_conclusion',compact('tarea','diario','des'));
    }
}
