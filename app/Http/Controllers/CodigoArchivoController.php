<?php

namespace App\Http\Controllers;

use App\Models\Archivado;
use App\Models\Archivados1;
use App\Models\Codigo_archivo;
use App\Models\Detalle_codigo;
use App\Models\Plan_archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodigoArchivoController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear plan - rr|editar plan - rr'],['only'=>['g_plan']]);
        $this->middleware(['permission:crear codigo - rr|editar codigo - rr'],['only'=>['g_codigo']]);
        $this->middleware(['permission:editar codigo - rr'],['only'=>['fe_codigo']]);
        $this->middleware(['permission:eliminar codigo - rr'],['only'=>['f_eli_codigo']]);
        $this->middleware(['permission:eliminar codigo - rr'],['only'=>['e_codigo']]);

        $this->middleware(['permission:editar plan - rr'],['only'=>['fe_plan']]);
        $this->middleware(['permission:eliminar plan - rr'],['only'=>['f_eli_plan']]);
        $this->middleware(['permission:eliminar plan - rr'],['only'=>['e_plan']]);


    }
    public function l_codigos($cod_plan){
        $plan=Plan_archivo::all()->sortBy('plan_numero');
        $plan1=Plan_archivo::find($cod_plan);
        if(sizeof($plan)>0 && $cod_plan==0){
            $plan1=$plan->first();
            $cod_plan=$plan1->cod_plan;
        }
        $codigos=Codigo_archivo::all()->where('cod_plan','=',$cod_plan)->sortBy('carch_numero');
        return view('resoluciones/codigo/l_codigo',compact('codigos','plan','plan1'));
    }
    public function g_codigo(Request $form){
        $form->validate([
            'codigo'=>'required',
            'titulo'=>'required',
        ]);
        if(isset($form['cc'])){
            $codigo=Codigo_archivo::find($form['cc']);
            $antiguo=json_encode($codigo);
            $codigo->carch_numero=$form['codigo'];
            $codigo->carch_titulo=$form['titulo'];
            $codigo->carch_desc=$form['desc'];
            $codigo->save();
            $form['cp']=$codigo->cod_plan;

            $nuevo=json_encode($codigo);
            SessionController::write('UPDATE',$antiguo,$nuevo,'codigo_archivos','2',$codigo->cod_carch);
            \Session::flash('exito','Se ha modificado el código exitosamente');
        }else{
            $codigo=Codigo_archivo::create([
                'carch_numero'=>$form['codigo'],
                'carch_titulo'=>$form['titulo'],
                'carch_desc'=>$form['desc'],
                'cod_plan'=>$form['cp'],
            ]);
            $nuevo=json_encode($codigo);
            SessionController::write('CREATE','',$nuevo,'codigo_archivos','2',$codigo->cod_carch);
            \Session::flash('exito','Se ha creado el código exitosamente');
        }
        return redirect('lista codigos/'.$form['cp']);
    }
    public function l_plan(){

        $plan=Plan_archivo::all()->sortBy('plan_numero');
        return view('resoluciones.codigo.l_plan',compact('plan'));
    }
    public function fe_codigo($cod_carch){
        $codigo=Codigo_archivo::find($cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        return view('resoluciones.codigo.fe_codigo',compact('codigo','plan'));
    }
    public function f_eli_codigo($cod_carch){
        $codigo=Codigo_archivo::find($cod_carch);
        return view('resoluciones.codigo.feli_codigo',compact('codigo'));
    }
    public function e_codigo(Request $form){
        $codigo=Codigo_archivo::find($form['cc']);
        $res=Archivado::where('cod_carch','=',$form['cc'])->get();
        $detalle=Detalle_codigo::where('cod_carch','=',$form['cc'])->get();
        if(sizeof($res)>0 || sizeof($detalle)>0){
            \Session::flash('error','El código de archivado no se puede eliminar, tiene temas registrados');
        }else{
            $codigo->delete();
            $antiguo=json_encode($codigo);
            SessionController::write('DELETE',$antiguo,'','codigo_archivos','2',$codigo->cod_carch);
            \Session::flash('exito','Se ha eliminado correctamente el código');
        }
        return redirect('lista codigos/'.$codigo->cod_plan);
    }
    public function g_plan(Request $form){

        $form->validate([
            'numero'=>'required',
            'titulo'=>'required',
        ]);
        if(isset($form['cp'])){
            $plan=Plan_archivo::find($form['cp']);
            $antiguo=json_encode($plan);
            $plan->plan_numero=$form['numero'];
            $plan->plan_titulo=$form['titulo'];
            $plan->save();

            $nuevo=json_encode($plan);
            SessionController::write('UPDATE',$antiguo,$nuevo,'plan_archivos','2',$plan->cod_plan);

            \Session::flash('exito','Se ha modificado exitosamente el plan ');
        }else{
            $plan=Plan_archivo::create([
                'plan_numero'=>$form['numero'],
                'plan_titulo'=>$form['titulo'],
                'plan_tipo'=>'RESOLUCION',
            ]);
            $nuevo=json_encode($plan);
            SessionController::write('CREATE','',$nuevo,'plan_archivos','2',$plan->cod_plan);
            \Session::flash('exito','Se ha creado  exitosamente el plan '.$plan->plan_numero);
        }
        return redirect('lista codigos/0');
    }
    public function fe_plan($cod_plan)
    {
        $plan=Plan_archivo::find($cod_plan);
        return view ('resoluciones.codigo.fe_plan',compact('plan'));
    }
    public function f_eli_plan($cod_plan){
        $plan=Plan_archivo::find($cod_plan);
        return view ('resoluciones.codigo.feli_plan',compact('plan'));
    }
    public function e_plan(Request $form){
        $plan=Plan_archivo::find($form['cp']);
        $codigos=Codigo_archivo::all()->where('cod_plan','=',$form['cp']);
        if(sizeof($codigos)>0){
            \Session::flash('error','El plan número '.$plan->plan_numero.' tiene códigos de archivado, no se puede eliminar');
        }else{
            $plan->delete();
            $antiguo=json_encode($plan);
            SessionController::write('DELETE',$antiguo,'','plan_archivos','2',$plan->cod_plan);
            \Session::flash('exito','Se ha eliminado correctamente el plan '.$plan['plan_numero']);
        }
        return redirect('lista codigos/0');
    }
    //==================DETALLE CODIGO==================
    public function fe_detalle_codigo($cod_carch){
        $codigo=Codigo_archivo::find($cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        $detalle=Detalle_codigo::where('cod_carch','=',$cod_carch)->get();
        return view('resoluciones.codigo.detalle.fe_detalle_codigo', compact('cod_carch','codigo','plan','detalle'));
    }
    public function tema_codigo_tabla($cod_carch){
        $detalle=Detalle_codigo::where('cod_carch','=',$cod_carch)->get();
        return view('resoluciones.codigo.detalle.fe_detalle_codigo_tabla', compact('cod_carch','detalle'));
    }

    public function g_detalle_codigo(Request $form){
        $form->validate([
            'cc'=>'required',
            'tema'=>'required',
        ]);
        if(isset($form['cd']) && $form['cd']!=''){
            $detalle=Detalle_codigo::find($form['cd']);
            $detalle->det_nombre=$form['tema'];
            $detalle->save();
            \Session::flash('exito','Se ha actualizado con éxito el tema');
        }else{
            $detalle=Detalle_codigo::create([
                'det_nombre'=>$form['tema'],
                'cod_carch'=>$form['cc']
            ]);
            \Session::flash('exito','Se ha creado con éxito el tema');
        }
        return redirect('tema codigo tabla/'.$form['cc']);
    }
    public function fe_tema_codigo($cod_det){
        $detalle=Detalle_codigo::find($cod_det);
        $codigo=Codigo_archivo::find($detalle->cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        return view('resoluciones.codigo.detalle.fe_tema_codigo',compact('detalle','codigo','plan'));
    }
    public function f_eli_tema_codigo($cod_det){
        $detalle=Detalle_codigo::find($cod_det);
        $codigo=Codigo_archivo::find($detalle->cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        $eliminar=1;

        $archivados=DB::table('archivados')->where('cod_det','=',$cod_det)->first();
        if($archivados){
            $eliminar=0;
        }
        return view('resoluciones.codigo.detalle.f_eli_tema_codigo',compact('detalle','codigo','plan','eliminar'));
    }
    public function eli_tema_codigo(Request $form){
        $form->validate([
            'cd'=>'required',
        ]);
        $cod_det=$form['cd'];
        $detalle=Detalle_codigo::find($cod_det);
        $cod_carch=$detalle->cod_carch;
        $detalle->delete();
        \Session::flash('exito','Se ha eliminado con exito el tema');

        return redirect('tema codigo tabla/'.$cod_carch);
    }
    //============================TEMA A RESOLUCION============
    public function l_tema_resolucion($cod_res,$cod_carch){
        $detalle=Detalle_codigo::where('cod_carch','=',$cod_carch)->get();
        $codigo=Codigo_archivo::find($cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        //dd($detalle);
        return view('resoluciones.resolucion.l_tema_resolucion',compact('detalle','plan','codigo','cod_res'));
    }
    public function g_tema_resolucion($cod_res,$cod_det){
        $detalle=Detalle_codigo::find($cod_det);
        if($cod_res!='0'){
            $tema=Archivado::create([
                'cod_res'=>$cod_res,
                'cod_det'=>$cod_det,
                'cod_carch'=>$detalle->cod_carch,
            ]);
        }
        return $this->actualizar_tema_resolucion($cod_res,$cod_det);

    }
    public function actualizar_tema_resolucion($cod_res,$cod_det){
        $detalle=Detalle_codigo::find($cod_det);
        $codigo=Codigo_archivo::find($detalle->cod_carch);
        $plan=Plan_archivo::find($codigo->cod_plan);
        $archivado=DB::table('archivados')
            ->join('detalle_codigo','archivados.cod_det','=','detalle_codigo.cod_det')
            ->join('codigo_archivos','detalle_codigo.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('detalle_codigo.cod_det','detalle_codigo.cod_carch','det_nombre','plan_numero','carch_numero','cod_arc')
            ->where('cod_res','=',$cod_res)
            ->get();
        //dd($archivado);
        return view('resoluciones.resolucion.panel_tema',compact('detalle','cod_res','archivado','codigo','plan'));
    }
    public function lista_resoluciones_corregir_temas(){
        $resultado=array();
        $form=array();
        $criterio="";
        $plan=DB::table('detalle_codigo')
            ->join('codigo_archivos','detalle_codigo.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('codigo_archivos.cod_carch','carch_numero','plan_numero','carch_titulo','det_nombre','cod_det')
            ->orderBy('plan_numero','ASC')->orderBy('carch_numero','ASC')->orderBy('det_nombre','ASC')
            ->get();
        return view('resoluciones.codigo.l_corregir_tema',compact('plan','resultado','form','criterio'));
    }
    public function lista_resoluciones_corregir(Request $form){
        $form->validate([
            'criterio'=>'required',
        ]);
        $resultado=array();
        if($form['criterio']!='z'){
            $consulta="select res_tema as tema, count(cod_res) as cantidad from resolucions where res_tema ilike '%".$form['criterio']."%' group by res_tema";
            $resultado=DB::select($consulta);
        }
        $criterio=$form['criterio'];
        $plan=DB::table('detalle_codigo')
            ->join('codigo_archivos','detalle_codigo.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('codigo_archivos.cod_carch','carch_numero','plan_numero','carch_titulo','det_nombre','cod_det')
            ->orderBy('plan_numero','ASC')->orderBy('carch_numero','ASC')->orderBy('det_nombre','ASC')
            ->get();

        return view('resoluciones.codigo.l_corregir_tema',compact('form','resultado','plan','criterio'));
    }
    public function mostrar_resolucion_tema($criterio){
        $consulta="select * from resolucions where res_tema='".$criterio."'";
        $resultado=DB::select($consulta);
        return view('resoluciones.codigo.mostrar_resolucion_tema',compact('resultado'));
    }
    public function asignar_tema_reslocion_corregido(Request $form){
        $form->validate([
            'tema'=>'required',
            'criterio'=>'required',
        ]);
        $detalle_archivado=Detalle_codigo::find($form['tema']);
        $consulta="select * from resolucions where res_tema='".$form['criterio']."' and
                    cod_res not in (select cod_res from archivados1 where cod_det=".$form['tema'].")
                    order by res_gestion,res_numero";
        $resultado=DB::select($consulta);
        foreach ($resultado as $r):
            Archivados1::create([
              'cod_res'=>$r->cod_res,
              'cod_det'=>$form['tema'],
              'cod_carch'=>$detalle_archivado->cod_carch
            ]);
        endforeach;
        $respuesta="<div class='border-danger rounded p-2 text-danger font-italic font-weight-bold' > * Se ha asignado el tema</div>";
        return $respuesta;
    }
}
