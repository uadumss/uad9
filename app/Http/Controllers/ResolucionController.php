<?php

namespace App\Http\Controllers;

use App\Models\Archivado;
use App\Models\Autoridad;
use App\Models\Firma;
use App\Models\Persona;
use App\Models\Resolucion;
use App\Models\Tomo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ResolucionController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear resolucion - rr|editar resolucion - rr'], ['only' => ['g_resolucion']]);
        $this->middleware(['permission:editar resolucion - rr'], ['only' => ['fe_resolucion']]);
        $this->middleware(['permission:eliminar resolucion - rr'], ['only' => ['f_eli_resolucion']]);
        $this->middleware(['permission:eliminar resolucion - rr'], ['only' => ['eli_resolucion']]);
        $this->middleware(['permission:mostrar antecedentes - rr'], ['only' => ['pdf_a']]);
        $this->middleware(['permission:listar resoluciones - rr'], ['only' => ['listar_gestion']]);
        $this->middleware(['permission:cambiar resolucion a tomo - rr'], ['only' => ['f_cambiarTomo','o_tomos','cambiar_tomo_resolucion']]);

    }
    public function l_resolucion($tipo_res,$cod_tom)
    {

        $resoluciones="";
        if($tipo_res=='todos'){
            $resoluciones=Resolucion::where('cod_tom','=',$cod_tom)->orderBy('res_numero')->get();
        }else{
            if($tipo_res=='rr' || $tipo_res=='rcu' || $tipo_res=='rs' || $tipo_res=='rvr') {
                $resoluciones = Resolucion::where('cod_tom','=', $cod_tom)->where('res_tipo', '=', $tipo_res)->orderBy('res_numero')->get();
            }
        }
	//dd($resoluciones);
        $tomo=Tomo::find($cod_tom);
        $gestion=$tomo->tom_gestion;
//	return $tipo_res."-".$cod_tom;
        $plan=DB::table('codigo_archivos')
                        ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
                        ->select('cod_carch','carch_numero','plan_numero','carch_titulo')->get();

        $autoridad=Autoridad::all()->where('aut_hab','=','t')
                        ->where('aut_inicio','<=',$gestion)
                        ->where('aut_fin','>=',$gestion);
        //echo sizeof($plan);
        $listaTomo=Tomo::where('tom_gestion','=',$gestion)->where('tom_tipo','=','res')->orderBy('tom_numero')->get();

        return view('resoluciones.resolucion.l_resolucion',compact('resoluciones','tomo','listaTomo','gestion','plan','autoridad','tipo_res'));
    }
    public function g_resolucion(Request $form){

        $tomo=Tomo::find($form['ct']);
        $gestion = $tomo->tom_gestion;

        if($tomo->tom_cerrado!='t') {
            $tipo = $form['tipo'];
            $resoluciones =array();
            if (isset($form['cr']) && $form['tipo']!='rcc') {
                $resoluciones= DB::select("select cod_res from resolucions where cod_tom=" . $form['ct'] . " and res_numero='" . $form['numero'] .
                    "' and cod_res<>" . $form['cr']." and res_tipo='".$form['tipo']."'");
            } else {
                $resoluciones = DB::select("select cod_res from resolucions where cod_tom=" . $form['ct'] . " and res_numero='" . $form['numero'] . "'"." and res_tipo='".$form['tipo']."'");
            }
            if (sizeof($resoluciones) < 1) {
                if(!isset($form['cr'])){
                    $ruta = 'alma/res/' . $gestion . '/' . $tomo->tom_numero;
                        $resolucion = Resolucion::create([
                            'res_numero' => $form['numero'],
                            'res_fecha' => $form['fecha'],
                            'res_tipo' => $form['tipo'],
                            'res_tema' => $form['tema'],//mb_strtoupper($form['tema']),
                            'res_objeto' => $form['objeto'],//mb_strtoupper($form['objeto']),
                            'res_desc' => $form['desc'],//mb_strtoupper($form['desc']),
                            'cod_tom' => $form['ct'],
                            'res_vistos' => $form['visto'],
                            'res_considerando' => $form['considerando'],
                            'res_resuelve' => $form['resuelve'],
                            'res_obs'=>$form['obs'],
                            'res_gestion'=>date('Y', strtotime($form['fecha'])),
                        ]);
                        if ($form->hasFile('pdf')) {
                            $nombreArch = ResolucionController::nombreArchivo($ruta);
                            Storage::putFileAs($ruta.'/', $form->file('pdf'), $nombreArch);
                            $resolucion->res_pdf = $nombreArch;
                            $resolucion->save();
                        }
                        if ($form->hasFile('pdf_ant')) {
                            $nombreArch = ResolucionController::nombreArchivo($ruta);
                            Storage::putFileAs($ruta.'/', $form->file('pdf_ant'), $nombreArch);
                            $resolucion->res_ant = $nombreArch;
                            $resolucion->save();
                        }
                        $objetoCompleto=$resolucion;
                        $archivado=array();

                        if(isset($form['temas']) && $form['temas']!=''){
                            $archivado=Archivado::create([
                                'cod_carch'=>$form['plan'],
                                'cod_res'=>$resolucion->cod_res,
                                'cod_det'=>$form['temas']
                            ]);
                            $objetoCompleto=(object) array_merge($objetoCompleto->toArray(),$archivado->toArray());
                        }


                        $firmas=array();
                        if(isset($form['firma1']) && $form['firma1']!=''){
                            $firmas=Firma::create([
                                'cod_aut'=>$form['firma1'],
                                'cod_aut2'=>$form['firma2'],
                                'cod_res'=>$resolucion->cod_res,
                            ]);
                            $objetoCompleto=(object) array_merge((array)$objetoCompleto,$firmas->toArray());
                        }
                    $nuevo=json_encode($objetoCompleto);
                   SessionController::write('C','',$nuevo,'Resolucion','2',$resolucion->cod_res);
                    \Session::flash('exito', 'La resolucion se ha creado exitosamente');
                    if($form['tipo']=='rcc'){
                        return redirect('lista resoluciones - rcc/'.$gestion);
                    }else{
                        return redirect('listar resoluciones/'.$form['tipo'].'/'. $form['ct']);
                    }
                }else{
                    $cod_res=$form['cr'];
                    $resolucion=Resolucion::find($cod_res);
                    $antiguo=$resolucion->toArray();

                    $resolucion->res_numero=$form['numero'];
                    $resolucion->res_fecha=$form['fecha'];
                    $resolucion->res_tipo=$form['tipo'];
                    $resolucion->res_tema=$form['tema'];
                    $resolucion->res_objeto=$form['objeto'];
                    $resolucion->res_desc=$form['desc'];
                    $resolucion->res_vistos=$form['visto'];
                    $resolucion->res_considerando=$form['considerando'];
                    $resolucion->res_resuelve=$form['resuelve'];
                    $resolucion->res_obs=$form['obs'];
                    $resolucion->save();

                    $archivoEliminado=array();
                    $ruta = 'alma/res/' . $gestion . '/' . $tomo->tom_numero;
                    $nombreArch = ResolucionController::nombreArchivo($ruta);
                    if ($form->hasFile('pdf')) {
                        if($resolucion->res_pdf!=''){
                            $valor= rand(0,999999999999);
                            if(Storage::exists($ruta.'/'.$resolucion->res_pdf)) {
                                Storage::move($ruta . '/' . $resolucion->res_pdf, 'trash/res/' . $valor . '-' . $resolucion->res_pdf);
                            }
                            Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                            $archivoEliminado['pdf']=$valor.'-'.$resolucion->res_pdf;
                            $resolucion->res_pdf=$nombreArch;
                            $resolucion->save();
                        }else{
                            Storage::putFileAs($ruta.'/', $form->file('pdf'), $nombreArch);
                            $resolucion->res_pdf = $nombreArch;
                            $resolucion->save();
                        }

                    }
                    $nombreArch = 'A-'.ResolucionController::nombreArchivo($ruta);
                    if ($form->hasFile('pdf_ant')) {
                        if($resolucion->res_ant!=''){
                            $valor= rand(0,999999999999);
                            if(Storage::exists($ruta.'/'.$resolucion->res_ant)) {
                                Storage::move($ruta.'/'.$resolucion->res_ant, 'trash/res/'.$valor.'-'.$resolucion->res_ant);
                            }
                            Storage::putFileAs($ruta, $form->file('pdf_ant'),$nombreArch);
                            $archivoEliminado['pdf_ant']=$valor.'-'.$resolucion->res_ant;
                            $resolucion->res_ant=$nombreArch;
                            $resolucion->save();
                        }else{
                            Storage::putFileAs($ruta.'/', $form->file('pdf_ant'), $nombreArch);
                            $resolucion->res_ant = $nombreArch;
                            $resolucion->save();
                        }
                    }
//====================PARA LOS ENLACES=================
                    $enlaces=Resolucion::all()->where('res_enlace','=',$resolucion->cod_res);
                    foreach ($enlaces as $en):
                        $en->res_numero=$form['numero'];
                        $en->res_fecha=$form['fecha'];
                        $en->res_tipo=$form['tipo'];
                        $en->res_tema=$form['tema'];
                        $en->res_objeto=$form['objeto'];
                        $en->res_desc=$form['desc'];
                        $en->res_obs=$form['obs'];
                        $en->res_pdf=$resolucion->res_pdf;
                        $en->res_ant=$resolucion->res_ant;
                        $en->save();
                    endforeach;
//====================PARA LOS ENLACES=================
                    $nuevo=$resolucion->toArray();
                    if(isset($form['plan']) && $form['plan']!=''){
                        $archivado=Archivado::all()->where('cod_res','=',$resolucion->cod_res);
                        $ban=false;
                        foreach ($archivado as $a){
                            if($a->cod_carch==$form['plan']){
                                $ban=true;
                            }
                        }

                        if(!$ban){
                            $nuevoArchivado=Archivado::create([
                                'cod_res'=>$resolucion->cod_res,
                                'cod_carch'=>$form['plan'],

                            ]);

                            $antiguo=(object) array_merge((array)$antiguo,$archivado->toArray());
                            $archivado->add($nuevoArchivado);
                            $archivado=$archivado->toArray();
                            $nuevo=(object) array_merge((array)$nuevo,(array)$archivado);
                        }
                    }
                    $firmas=Firma::all()->where('cod_res','=',$resolucion->cod_res)->first();
                    if($firmas){
                        $antiguo=(object) array_merge((array)$antiguo,$firmas->toArray());
                        $firmas->cod_aut=$form['firma1'];
                        $firmas->cod_aut2=$form['firma2'];
                        $firmas->save();
                        $nuevo=(object) array_merge((array)$nuevo,$firmas->toArray());
                    }else{
                        if(isset($form['firma1']) && $form['firma1']!=''){
                            $firmas=Firma::create([
                                'cod_aut'=>$form['firma1'],
                                'cod_aut2'=>$form['firma2'],
                                'cod_res'=>$resolucion->cod_res,
                            ]);
                            $nuevo=(object) array_merge((array)$nuevo,$firmas->toArray());
                        }
                    }
                    $nuevo=json_encode($nuevo);
                    $antiguo=(object) array_merge((array)$antiguo,$archivoEliminado);
                    $antiguo=json_encode($antiguo);
                    SessionController::write('U',$antiguo,$nuevo,'Resolucion','2',$resolucion->cod_res);

                    \Session::flash('exito','Se ha editado exitosamente la resolución');
                    return redirect('fe_resolucion/'.$form['cr'].'/'. $form['ct']);
                }
            } else {
                //return "hola";
                \Session::flash('error','No se ha podido guardar debido a que ya existe una resolución con el número '.$form['nro']);
                if(!isset($form['cr'])) {
                    return redirect('listar resoluciones/'.$form['tipo'].'/'. $form['ct']);
                }else{
                    return redirect('fe_resolucion/' . $form['cr'].'/'. $form['ct']);
                }
            }
        }else{
            \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar la resolución');
            return redirect('listar resoluciones/'.$form['tipo'].'/'.$tomo['cod_tom']);
        }
    }
    public function fe_resolucion($cod_res,$cod_tom){
        $tomo=Tomo::find($cod_tom);
        $plan=DB::table('codigo_archivos')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('cod_carch','carch_numero','plan_numero','carch_titulo')
            ->get();

        $autoridad=Autoridad::all()->where('aut_hab','=','t')
            ->where('aut_inicio','<=',$tomo->tom_gestion)
            ->where('aut_fin','>=',$tomo->tom_gestion);

        if($cod_res!=0){
            $resolucion=Resolucion::find($cod_res);
            //$fir=DB::select('select * from firmas where cod_res='.$cod_res);
            $fir=Firma::where('cod_res',$cod_res)->first();
            $archivado=DB::table('archivados')
                ->join('detalle_codigo','archivados.cod_det','=','detalle_codigo.cod_det')
                ->join('codigo_archivos','detalle_codigo.cod_carch','=','codigo_archivos.cod_carch')
                ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
                ->select('cod_arc','carch_numero','plan_numero','det_nombre')
                ->where('archivados.cod_res','=',$cod_res)
                ->get();
            return view('resoluciones.resolucion.fe_resolucion',compact('resolucion','plan','autoridad','fir','archivado','cod_res','tomo'));
        }else{
            return view('resoluciones.resolucion.fe_resolucion',compact('cod_res','cod_tom','tomo','plan','autoridad'));
        }

    }
    public function l_res_sinTomo($gestion,$cod_tomo_actual){
        $tomo=Tomo::where('tom_gestion','=',$gestion)
            ->where('tom_tipo','=','res')
            ->where('tom_numero','=',0)->first();
        $cod_tom=0;
        if($tomo){
            $cod_tom=$tomo->cod_tom;
        }
        $resoluciones=Resolucion::where('cod_tom',$cod_tom)->orderBy('res_numero')->get();
        return view('resoluciones.resolucion.l_res_sintomo',compact('resoluciones','cod_tom','cod_tomo_actual'));
    }
    public function asignar_tomo(Request $form){
        $resolucion=Resolucion::find($form['cod_res']);
        $tomo_actual=Tomo::find($form['cod_tomo_actual']);
        $tomo_antiguo=Tomo::find($form['cod_tomo_antiguo']);
        $mensaje='';
        $resoluciones=Resolucion::all()->where('res_numero','=',$resolucion->res_numero)
            ->where('res_tipo','=',$resolucion->res_tipo)
            ->where('cod_tom','=',$form['cod_tomo_actual'])->first();

        if(!$resoluciones) {
            $ruta1 = 'alma/res/' . $tomo_actual['tom_gestion'] . '/' . $tomo_actual['tom_numero'] . '/';
            $ruta2 = 'alma/res/' . $tomo_antiguo['tom_gestion'] . '/' . $tomo_antiguo['tom_numero'] . '/';

            if ($resolucion->res_pdf != '') {
                if (Storage::exists($ruta2.$resolucion->res_pdf)){
                    if(Storage::exists($ruta1.$resolucion->res_pdf)){
                        $nombreArch = ResolucionController::nombreArchivo($ruta1);
                        Storage::move($ruta2 . $resolucion->res_pdf, $ruta1 . $nombreArch);
                        $resolucion->res_pdf=$nombreArch;
                        $resolucion->save();
                    }else{
                        Storage::move($ruta2 . $resolucion->res_pdf, $ruta1 . $resolucion->res_pdf);
                    }
                }else{
                    $resolucion->res_pdf='';
                    $resolucion->save();
                }
            }
            if ($resolucion->res_ant != '') {
                if (Storage::exists($ruta2.$resolucion->res_ant)){
                    if(Storage::exists($ruta1.$resolucion->res_and)){
                        $nombreArch = ResolucionController::nombreArchivo($ruta1);
                        Storage::move($ruta2 . $resolucion->res_ant, $ruta1 . $nombreArch);
                        $resolucion->res_ant=$nombreArch;
                        $resolucion->save();
                    }else{
                        Storage::move($ruta2 . $resolucion->res_ant, $ruta1 . $resolucion->res_ant);
                    }

                }else{
                    $resolucion->res_ant='';
                    $resolucion->save();
                }
            }
            $resolucion->cod_tom = $form['cod_tomo_actual'];
            $resolucion->save();
        }else{
            $mensaje='Ya existe una resolucion de este tipo con el mismo número';
        }
        return $mensaje;
    }
    public function f_eli_resolucion($cod_res){
        $resolucion=Resolucion::find($cod_res);
        $enlace=DB::select('select count(cod_res) as enlace from resolucions where res_enlace='.$cod_res);
        return view('resoluciones.resolucion.f_eli_resolucion',compact('resolucion','enlace'));
    }
    public function eli_resolucion(Request $form){
        if(isset($form['cr'])){
            $resolucion=Resolucion::find($form['cr']);

                DB::delete('delete from firmas where cod_res='.$form['cr']);
                DB::delete('delete from archivados where cod_res='.$form['cr']);
            $pdf['pdf']='';
            $pdf['pdf_ant']='';
            $tomo=Tomo::find($resolucion['cod_tom']);
            if($resolucion->res_enlace==''){
                if($resolucion['res_pdf']!=''){
                    $ruta='alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$resolucion['res_pdf'];
                    if(Storage::exists($ruta)){
                        $valor= 'E'.rand(0,999999999999);
                        Storage::move($ruta, 'trash/res/'.$valor.'-'.$resolucion->res_pdf);
                        //Storage::delete($ruta);
                        $pdf['pdf']=$valor.'-'.$resolucion->res_pdf;
                    }
                }
                if($resolucion['res_ant']!=''){
                    $ruta='alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$resolucion['res_ant'];
                    if(Storage::exists($ruta)){
                        $valor= 'EA'.rand(0,999999999999);
                        Storage::move($ruta, 'trash/res/'.$valor.'-'.$resolucion->res_ant);
                        $pdf['pdf_ant']=$valor.'-'.$resolucion->res_ant;
                    }
                }
            }
            $resolucion->delete();
            $objeto=(object) array_merge($resolucion->toArray(),$pdf);
            $antiguo=json_encode($objeto);
            SessionController::write('D',$antiguo,'','Resolucion','2',$resolucion->cod_res);

            \Session::flash('exito','Se ha eliminado exitosamente la resolucion '.$resolucion['res_numero']);
            return redirect('listar resoluciones/todos/'.$form['ct']);
        }
    }
    public function datos_resolucion($cod_res){
        $resolucion=Resolucion::find($cod_res);
        $tomo=Tomo::find($resolucion->cod_tom);
        $fir=Firma::where('cod_res',$cod_res)->first();
        $archivado=DB::table('archivados')
            ->join('codigo_archivos','archivados.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('cod_arc','carch_numero','plan_numero')
            ->where('archivados.cod_res','=',$cod_res)
            ->get();
        $autoridad=Autoridad::all();
        return view('resoluciones.resolucion.detalle_resolucion',compact('resolucion','fir','archivado','autoridad','tomo'));
    }
    public function listar_gestion($gestion,$tipo){
        $resoluciones=DB::table('resolucions')
            ->join('tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->where('tom_gestion','=',$gestion)
            ->where('res_tipo','=',$tipo)
            ->orderBy('res_numero')
            ->get();
        $tipoCompleto=ResolucionController::nombreTipo($tipo);
        return view('resoluciones.resolucion.l_resolucion_gestion',compact('resoluciones','gestion','tipo','tipoCompleto'));
    }
    //==================== CAMBIAR DE TOMO f_cambiarTomo,o_tomos,cambiar_tomo_resolucion
    public function f_cambiarTomo($cod_res){
        $resolucion=Resolucion::find($cod_res);
        $tomo=Tomo::find($resolucion->cod_tom);
        $tomos=Tomo::all()
            ->where('tom_gestion','=',$tomo->tom_gestion)
            ->where('tom_tipo','=','res')->sortBy('tom_numero');
        return view('resoluciones.resolucion.f_cambiarTomo',compact('tomo','resolucion','tomos'));
    }
    public function o_tomos($gestion,$tipo){
        $tomos=Tomo::all()
            ->where('tom_gestion','=',$gestion)
            ->where('tom_tipo','=',$tipo)->sortBy('tom_numero');;
        return view('diplomas.titulo.l_tomos',compact('tomos'));
    }
    public function cambiar_tomo_resolucion(Request $form){
        $form->validate([
            'resolucion'=>'required',
            'tomo'=>'required',
        ]);

        $resolucion=Resolucion::find($form['resolucion']);
        $tomoAntiguo=Tomo::find($resolucion->cod_tom);
        $tomoNuevo=Tomo::find($form['tomo']);
        if($form['tomo']!=$tomoAntiguo->cod_tom){
            $resoluciones=DB::table('resolucions')->where('cod_tom','=',$tomoNuevo->cod_tom)
                ->where('res_numero','=',$resolucion->res_numero)
                ->where('res_tipo','=',$resolucion->res_tipo)->first();
            //return "hola";
            if($resoluciones) {
                \Session::flash('error', 'Ya existe una resolución '.strtoupper($resoluciones->res_tipo). " Número ". $resolucion->res_numero . ' en el Tomo ' . $tomoNuevo->tom_numero);

            }else{
                $rutaAntigua='alma/res/'.$tomoAntiguo->tom_gestion.'/'.$tomoAntiguo->tom_numero.'/';
                $rutaNueva='alma/res/'.$tomoNuevo->tom_gestion.'/'.$tomoNuevo->tom_numero.'/';
                try {
                    if($resolucion->res_pdf!='') {
                        if (Storage::exists($rutaAntigua . $resolucion->res_pdf)) {
                            if(Storage::exists($rutaNueva . $resolucion->res_pdf)){
                                $nombreArchivo=$this->nombreArchivo($rutaNueva);
                                Storage::move($rutaAntigua . $resolucion->res_pdf, $rutaNueva . $nombreArchivo);
                                $resolucion->res_pdf=$nombreArchivo;
                            }else{
                                Storage::move($rutaAntigua . $resolucion->res_pdf, $rutaNueva . $resolucion->res_pdf);
                            }

                        }
                    }
                    if($resolucion->res_ant!='') {
                        if (Storage::exists($rutaAntigua . $resolucion->res_ant)) {
                            if(Storage::exists($rutaNueva . $resolucion->res_ant)){
                                $nombreArchivo=$this->nombreArchivo($rutaNueva);
                                Storage::move($rutaAntigua . $resolucion->res_ant, $rutaNueva . $nombreArchivo);
                                $resolucion->res_ant=$nombreArchivo;
                            }else{
                                Storage::move($rutaAntigua . $resolucion->res_ant, $rutaNueva . $resolucion->res_ant);
                            }
                        }
                    }
                }catch (Exception $e){
                    return $e;
                }

                $resolucion->cod_tom=$form['tomo'];
                $resolucion->res_gestion=$tomoNuevo->tom_gestion;
                $resolucion->save();

                \Session::flash('exito','Se realizado exitosamente el cambio de la resolución '.$resolucion->res_numero.' al tomo '.$tomoNuevo->tom_numero);

            }    //return "hola";
        }else{
            \Session::flash('error','No puede realizar el cambio al mismo tomo');
        }
        return redirect('listar resoluciones/todos/'.$tomoAntiguo->cod_tom);
    }
    //===================== CODIGO DE ARCHIVADO
    public static function l_codigo($cod_res){
        $archivados=DB::table('archivados')
            ->join('codigo_archivos','archivados.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->where('cod_res',$cod_res)
            ->select('cod_arc','carch_numero','plan_numero')
            ->get();
        $resp='';
        foreach ($archivados as $a):
            $resp.=$a->plan_numero."/".$a->carch_numero."<br/>";
        endforeach;

        return $resp;
    }
    public function eli_plan_resolucion($cod_arc){
        $archivado=Archivado::find($cod_arc);
        //dd($archivado);
        $cod_det=$archivado->cod_det;
        $cod_res=$archivado->cod_res;
        //return $cod_det." - ".$cod_res;
        $archivado->delete();
        return redirect('actualizar temas resolucion/'.$cod_res.'/'.$cod_det);
    }
    //============================ENLAZAR RESOLUCIONES
    public function f_enlazar_resolucion($cod_tom){
        return view('resoluciones.resolucion.enlace.f_enlazar_resolucion',compact('cod_tom'));
    }
    public function listar_resoluciones_enlace(Request $form){
        $resoluciones=DB::table('resolucions')
            ->join('tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->where('res_numero','=',$form['numero'])
            ->where('res_tipo','=',$form['tipo'])
            ->where('res_gestion','=',$form['gestion'])
            ->where('tom_numero','<>','0')
            ->where('res_enlace')
            ->select('res_numero','res_gestion','res_tipo','res_fecha','cod_res','res_enlace')->get();
        $cod_tom=$form['ct'];
        return view('resoluciones.resolucion.enlace.lista_resoluciones_enlace',compact('resoluciones','cod_tom'));

    }
    public function datos_resolucion_enlace($cod_tom,$cod_res){
        $resolucion=Resolucion::find($cod_res);
        $tomo=Tomo::find($resolucion->cod_tom);
        $fir=Firma::where('cod_res',$cod_res)->first();
        $archivado=DB::table('archivados')
            ->join('codigo_archivos','archivados.cod_carch','=','codigo_archivos.cod_carch')
            ->join('plan_archivos','codigo_archivos.cod_plan','=','plan_archivos.cod_plan')
            ->select('cod_arc','carch_numero','plan_numero')
            ->where('archivados.cod_res','=',$cod_res)
            ->get();
        $autoridad=Autoridad::all();
        return view('resoluciones.resolucion.enlace.detalle_resolucion_enlace',compact('resolucion','fir','archivado','autoridad','tomo','cod_tom'));
    }
    public function enlazar_resolucion(Request $form){
        $resolucion=Resolucion::find($form['cr']);
        $tomo=Tomo::find($form['ct']);
        $nueva_resolucion = Resolucion::create([
            'res_numero' => $resolucion->res_numero,
            'res_fecha' => $resolucion->res_fecha,
            'res_tipo' => $resolucion->res_tipo,
            'res_tema' => $resolucion->res_tema,//mb_strtoupper($form['tema']),
            'res_objeto' => $resolucion->res_objeto,//mb_strtoupper($form['objeto']),
            'res_desc' => $resolucion->res_desc,//mb_strtoupper($form['desc']),
            'cod_tom' => $form['ct'],
            'res_enlace'=>$resolucion->cod_res,
            'res_pdf'=>$resolucion->res_pdf,
            'res_ant'=>$resolucion->res_ant,
            'res_gestion'=>$tomo->tom_gestion,
        ]);
        return redirect('listar resoluciones/'.$resolucion->res_tipo.'/'. $form['ct']);
    }
    //================================== PDF
    public function pdf($id){
        $resolucion=Resolucion::find($id);
        if($resolucion->res_enlace!=''){
            $resolucion=Resolucion::find($resolucion->res_enlace);
        }
        $tomo=Tomo::find($resolucion['cod_tom']);
        if($resolucion->res_pdf!='') {
            $ruta = 'alma/res/' . $tomo->tom_gestion . '/' . $tomo->tom_numero . '/' . $resolucion->res_pdf;
            if(Storage::exists($ruta)){
                return Storage::response($ruta);
            }else{
                $var="<div class='alert alert-danger alert-dismissible'>No existe el archivo</div>";
                return $var;
            }
        }else{
            $var="<div class='alert alert-danger alert-dismissible'>No existe el archivo</div>";
            return $var;
        }
    }
    public function pdf_a($id){
        $resolucion=Resolucion::find($id);
        if($resolucion->res_enlace!=''){
            $resolucion=Resolucion::find($resolucion->res_enlace);
        }
        $tomo=Tomo::find($resolucion['cod_tom']);
        if($resolucion->res_ant!='') {
            $ruta = 'alma/res/' . $tomo->tom_gestion . '/' . $tomo->tom_numero . '/' . $resolucion->res_ant;
            if(Storage::exists($ruta)){
                return Storage::response($ruta);
            }else{
                $var="<div class='alert alert-danger alert-dismissible'>No existe el archivo</div>";
                return $var;
            }
        }else{
            $var="<div class='alert alert-danger alert-dismissible'>No existe el archivo</div>";
            return $var;
        }
    }
    public function complementar_pdf(Request $form){
        $resoluciones=Resolucion::all()
            ->where('res_gestion','=',$form['gestion'])
            ->whereIn('res_tipo',['rr','rcu'])->sortBy('res_numero');
        $no_cambiados="";
        foreach ($resoluciones as $r):
            if($r->res_pdf==''){
                $tomo=Tomo::find($r->cod_tom);
                $ruta_origen="alma/prueba/".$form['gestion']."/";
                $ruta_origen1="alma/prueba/".$form['gestion']."/";
                if($r->res_tipo=='rcu'){

                    $ruta_origen.="RCU/RCU-".$r->res_numero.".pdf";
                    $ruta_origen1.="RCU/rcu-".$r->res_numero.".pdf";
                }else{
                    $ruta_origen.="RR/RR-".$r->res_numero.".pdf";
                    $ruta_origen1.="RR/rr-".$r->res_numero.".pdf";
                }
                $destino = "alma/res/".$form['gestion']."/".$tomo->tom_numero."/";
                if (Storage::exists($ruta_origen)){
                    $nombreArch = ResolucionController::nombreArchivo($destino);
                    if(Storage::move($ruta_origen, $destino . $nombreArch)){
                        $r->res_pdf=$nombreArch;
                        $r->save();
                        $no_cambiados.=$r->res_numero.":".$r->res_pdf."<br/>";
                    }
                }else{
                    if (Storage::exists($ruta_origen1)){
                        $nombreArch = ResolucionController::nombreArchivo($destino);
                        if(Storage::move($ruta_origen1, $destino . $nombreArch)){
                            $r->res_pdf=$nombreArch;
                            $r->save();
                            $no_cambiados.=$r->res_numero.":".$r->res_pdf."<br/>";
                        }
                    }
                }
            }else{

            }
        endforeach;
        return $no_cambiados." res";
    }
    public static function nombreTipo($tipo){
        $tipoCompleto='';
        switch ($tipo){
            case 'rcu':$tipoCompleto='RESOLUCIONES DE CONSEJO UNIVERSITARIO';break;
            case 'rr':$tipoCompleto='RESOLUCIONES RECTORALES';break;
            case 'rvr':$tipoCompleto='RESOLUCIONES VICERRECTORALES';break;
            case 'rs':$tipoCompleto='RESOLUCIONES SECRETARIALES';break;
            case 'rc':$tipoCompleto='RESOLUCIONES CONGRESALES';break;
            case 'rcf':$tipoCompleto='RESOLUCIONES DE CONSEJO FACULTATIVO';break;
            case 'rcc':$tipoCompleto='RESOLUCIONES DE CONSEJO DE CARRERA';break;
        }
        return $tipoCompleto;
    }
    public function nombreArchivo($ruta) {
        $key ='';
        $longitud=20;
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $bandera=true;
        $max = strlen($pattern)-1;
        while($bandera){
            for($i=0;$i < $longitud;$i++) $key .= $pattern[mt_rand(0,$max)];
            if(!Storage::exists($ruta.$key.'.pdf'));
            {
                $bandera=false;
            }
        }
        return $key.'.pdf';
    }
}
