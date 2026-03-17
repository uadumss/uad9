<?php

namespace App\Http\Controllers\Noatentado;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TramiteLegalizacionController;
use App\Models\Apoderado;
use App\Models\D_tramita;
use App\Models\Funciones;
use App\Models\Glosa;
use App\Models\Noatentado\Cargo_convocatoria;
use App\Models\Noatentado\Convocatoria;
use App\Models\Noatentado\Noatentado;
use App\Models\Persona;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteNoAtentadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:crear tramite - noa|editar tramite - noa'], ['only' => ['fe_noatentado_convocatoria','g_tramite_convocatoria','fe_candidato',
            'g_candidato','fe_eli_candidato','eli_candidato','fe_agregar_excel','g_excel_noatentado']]);
        $this->middleware(['permission:eliminar tramite - noa'], ['only' => ['f_eli_tramite','eli_tramite']]);
        $this->middleware(['permission:generar glosa - noa'], ['only' => ['fe_glosa','generarPDF','generar_documento']]);
        $this->middleware(['permission:entregar tramite - noa'], ['only' => ['fe_entrega','g_apoderado','g_entrega','actualizar_lista_entrega']]);
        $this->middleware(['permission:rehacer tramite - noa'], ['only' => ['f_corregir_tramite_noa','corregir_tramite_noa']]);


    }
    //=================== TRAMITE POR CONVOCATORIA
    public function l_tramite_convocatoria($cod_con){
        $convocatoria=Convocatoria::find($cod_con);
        $tramites=DB::table('d_tramitas')
            ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_con','=',$cod_con)->where('dtra_tipo','=','A')
            ->select('d_tramitas.*','tramites.tre_nombre')->orderBy('dtra_numero_tramite','DESC')->get();
        return view('servicios.no_atentado.tramite.l_tramite_convocatoria',compact('convocatoria','tramites'));
    }
    public function tabla_tramite_convocatoria($cod_con){
        $convocatoria=Convocatoria::find($cod_con);
        $tramites=DB::table('d_tramitas')
            ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_con','=',$cod_con)->where('dtra_tipo','=','A')
            ->select('d_tramitas.*','tramites.tre_nombre')->orderBy('dtra_numero_tramite','DESC')->get();
        return view('servicios.no_atentado.tramite.l_tramite_convocatoria_tabla',compact('convocatoria','tramites','cod_con'));
    }
    public function fe_noatentado_convocatoria($cod_con,$cod_dtra){
        $convocatoria=Convocatoria::find($cod_con);
        $tramites=Tramite::where('tre_tipo','=','A')->get();
        $tramite_noatentado=array();
        $noatentados=array();
        if($cod_dtra!=0){
            $tramite_noatentado=DB::table('d_tramitas')
                ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->where('cod_dtra','=',$cod_dtra)
                ->select('d_tramitas.*','tramites.*')->first();
            $noatentados=DB::table('noatentado.noatentado')
                ->join('personas','noatentado.id_per','=','personas.id_per')
                ->leftJoin('claustros.cargo_convocatoria','noatentado.noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
                ->where('cod_dtra','=',$cod_dtra)
                ->select('personas.*','noatentado.*','cargo_convocatoria.*')->get();
        }
        //dd($tramite_noatentado);
        return view('servicios.no_atentado.tramite.fe_noatentado_convocatoria',compact('convocatoria','tramites','tramite_noatentado','noatentados','cod_con'));
    }
    public function g_tramite_convocatoria(Request $form){
        $form->validate([
            'cc'=>'required',
            'control'=>'required',
        ]);
        $tramite_noatentado=array();
        if(isset($form['cd']) && $form['cd']!=''){
            $tramite_noatentado=D_tramita::find($form['cd']);
            $antiguo=json_encode($tramite_noatentado);
            $tramite_noatentado->dtra_interno=$form['tipo_tramite'];
            $tramite_noatentado->dtra_control=$form['control'];
            $tramite_noatentado->dtra_valorado_reintegro=$form['reintegro'];
            $tramite_noatentado->save();
            $nuevo=json_encode($tramite_noatentado);
            SessionController::write('U',$antiguo,$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);
            \Session::flash('exitoModal','Se ha editado satisfactoriamente el tramite');
        }else{
            $form->validate(['tramite'=>'required',]);
            $año_tramita=date('Y');
            $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
            $numero_tramite+=1;

            $tramite_noatentado=D_tramita::create([
                'cod_con'=>$form['cc'],
                'cod_tre'=>$form['tramite'],
                'dtra_interno'=>$form['tipo_tramite'],
                'dtra_control'=>$form['control'],
                'dtra_valorado_reintegro'=>$form['reintegro'],
                'dtra_numero_tramite'=>$numero_tramite,
                'dtra_gestion_tramite'=>$año_tramita,
                'dtra_posicion'=>1,
                'dtra_tipo'=>'A',
                'dtra_fecha_registro'=>date('d/m/Y'),
                'dtra_gestion'=>$año_tramita,
            ]);
            $nuevo=json_encode($tramite_noatentado);
            SessionController::write('C','',$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);
            \Session::flash('exitoModal','Se ha creado satisfactoriamente el tramite');
        }
        return redirect("editar tramite convocatoria/".$form['cc']."/".$tramite_noatentado->cod_dtra);
    }
    //=================== CANDIDATOS
    public function fe_candidato($cod_dtra,$cod_noa){
        $candidato=array();
        $tramite=D_tramita::find($cod_dtra);
        //dd($tramite);

        $cargos=Cargo_convocatoria::where('cod_con','=',$tramite->cod_con)->get();
        if($cod_noa!=0){
            $candidato=DB::table('noatentado.noatentado')
                ->join('personas','noatentado.id_per','=','personas.id_per')
                ->join('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
                ->where('cod_noa','=',$cod_noa)->first();
        }
        return view('servicios.no_atentado.tramite.fe_candidato',compact('candidato','cod_dtra','tramite','cargos'));
    }
    public function g_candidato(Request $form){
        $form->validate([
            'cd'=>'required',
            'ci'=>'required',
            'nombre'=>'required',
            'apellido'=>'required',
        ]);
        $tramite=D_tramita::find($form['cd']);
        if(isset($form['cn']) && $form['cn']!=''){

        }else{
            $persona=Persona::where('per_ci','=',$form['ci'])->first();
            $id_per=0;
            if(!$persona){
                $persona=Persona::create([
                    'per_ci'=>mb_strtoupper($form['ci']),
                    'per_nombre'=>mb_strtoupper($form['nombre']),
                    'per_apellido'=>mb_strtoupper($form['apellido']),
                    'per_cod_sis'=>$form['cod_sis'],
                    'per_sistema'=>8,
                ]);
                $nuevo=json_encode($persona);
                SessionController::write('C','',$nuevo,'personas','8',$persona->id_per);
                $id_per=$persona->id_per;
            }else{
                $id_per=$persona->id_per;
            }
            $noatentado=Noatentado::create([
                'cod_dtra'=>$form['cd'],
                'id_per'=>$id_per,
                'noa_unidad'=>$form['unidad'],
            ]);
            $nuevo=json_encode($noatentado);
            SessionController::write('C','',$nuevo,'noatentado.noatentado','8',$noatentado->cod_noa);
            if($form['cargo']!=''){
                $cargo=DB::table('claustros.cargo_convocatoria')->where('carg_nombre','=',$form['cargo'])->first();
                if($cargo){
                    $noatentado->cod_carg=$cargo->cod_carg;
                }else{
                    //dd($form);
                    $cargo=Cargo_convocatoria::create([
                        'carg_nombre'=>mb_strtoupper($form['cargo']),
                        'cod_con'=>$tramite->cod_con,
                    ]);
                    $nuevo=json_encode($cargo);
                    SessionController::write('C','',$nuevo,'calustros.cargo_convocatoria','8',$cargo->cod_carg);
                    $noatentado->cod_carg=$cargo->cod_carg;
                }
            }else{
                if(isset($form['cargo_convocatoria']) && $form['cargo_convocatoria']){
                  $noatentado->cod_carg=$form['cargo_convocatoria'];
                }
            }
            $noatentado->save();
        }
        return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
    }

    public function fe_eli_candidato($cod_noa){
        $candidato=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
            ->where('cod_noa','=',$cod_noa)->first();
        //dd($candidato);
        return view('servicios.no_atentado.tramite.fe_eli_candidato',compact('candidato'));
    }
    public function eli_candidato(Request $form){
        $form->validate(['cn'=>'required']);
        $candidato=Noatentado::find($form['cn']);
        $cod_dtra=$candidato->cod_dtra;
        $tramite=D_tramita::find($candidato->cod_dtra);
        $candidato->delete();
        $antiguo=json_encode($candidato);
        SessionController::write('D',$antiguo,'','noatentado.noatentado','8',$candidato->cod_noa);
        \Session::flash('exitoModal','Se ha eliminado correctamente el candidato');
        return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
    }
    public function fe_agregar_excel($cod_dtra){
        $tramite_noatentado=D_tramita::find($cod_dtra);
        return view('servicios.no_atentado.tramite.fe_agregar_excel',compact('cod_dtra','tramite_noatentado'));
    }

    public function fe_glosa($cod_dtra){
        $tramite_noatentado=D_tramita::find($cod_dtra);
        $tramite=Tramite::find($tramite_noatentado->cod_tre);
        if($tramite_noatentado->dtra_interno=='t'){
            $tramite_noatentado->dtra_titulo=$tramite->tre_titulo_interno;
        }else{
            $tramite_noatentado->dtra_titulo=$tramite->tre_titulo;
        }
        $tramite_noatentado->dtra_glosa_posicion=0;
        $convocatoria=Convocatoria::find($tramite_noatentado->cod_con);
        $modelo_glosa = Glosa::where('cod_tre','=',$tramite->cod_tre)->first();
        $candidatos=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
            ->select('personas.*','cargo_convocatoria.*','noatentado.*')
            ->where('cod_dtra','=',$cod_dtra)->orderBy('cod_noa','ASC')->get();
        if(sizeof($candidatos)>0){
            if($tramite_noatentado->dtra_cod_glosa==''){
                $tramite_noatentado->dtra_cod_glosa=$modelo_glosa->cod_glo;
                $tramite_noatentado->dtra_glosa=Funciones::glosa_noatentado($tramite,$modelo_glosa,$tramite_noatentado,$convocatoria,$candidatos);
            }else{
                $modelo_glosa=Glosa::find($tramite_noatentado->dtra_cod_glosa);
            }
            $legalizacion=new TramiteLegalizacionController();
            $qr=$legalizacion->valorQR(date('d'),date('m'),date('Y'));
            $qr_generado='http://www.archivos.umss.edu.bo/verificar_tramite/index.php?q='.$qr;
            $tramite_noatentado->dtra_qr=$qr;
            $mes=Funciones::mes(date('n'));
            if($tramite_noatentado->dtra_fecha_literal=='') {
                $tramite_noatentado->dtra_fecha_literal = "Cochabamba, " . date('j') . " de " . $mes . " del " . date('Y');
            }
            $fecha=date('Y-m-d', strtotime($tramite_noatentado->dtra_fecha_registro));
            if($tramite_noatentado->dtra_glosa!='0'){
                $tramite_noatentado->save();
            }
            return view('servicios.no_atentado.tramite.fe_glosa',compact('tramite_noatentado','candidatos','convocatoria','tramite','modelo_glosa'));
        }else{
            return view('servicios.no_atentado.tramite.fe_glosa',compact('tramite_noatentado','candidatos','convocatoria','tramite','modelo_glosa'));
        }
    }
    public function generar_documento(Request $form){
        //dd($form);

        $tramite_noatentado=D_tramita::find($form['cd']);
        $antiguo=json_encode($tramite_noatentado);
        if($tramite_noatentado->dtra_qr!=''){
            $tramite_noatentado->dtra_glosa=$form['glosa'];
            $tramite_noatentado->dtra_generado='t';
            $tramite_noatentado->dtra_glosa_posicion=$form['posicion'];
            $tramite_noatentado->dtra_fecha_firma=date('d/m/Y');
            $tramite_noatentado->save();
            $nuevo=json_encode($tramite_noatentado);
            SessionController::write('U',$antiguo,$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);
        }
        return redirect('actualizar lista tramite convocatoria/'.$tramite_noatentado->cod_con);
    }
    public function generarPDF($cod_dtra){

        $tramite_noatentado=D_tramita::find($cod_dtra);
        SessionController::write('U','','Imprime pdf','d_tramitas','8',$tramite_noatentado->cod_dtra);
        if($tramite_noatentado->dtra_falso!='t'){
            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('letter');
            $pdf->loadView('servicios.no_atentado.tramite.pdf_noatentado',compact('tramite_noatentado'));
            return $pdf->stream('No-atentado.pdf');
        }else{
            $pdf = app('dompdf.wrapper');
            $pdf->loadHtml("<span style='color: #DD0000'>Archivo bloqueado</span>");
            return $pdf->stream('No-atentado.pdf');
        }
    }
    public function f_eli_tramite($cod_dtra){
        $documento_tramite=D_tramita::find($cod_dtra);
        $tramite=Tramite::find($documento_tramite->cod_tre);
        $noatentado=Noatentado::where('cod_dtra','=',$cod_dtra)->first();
        $eliminar=1;
        if($noatentado){
            $eliminar=0;
        }
        return view('servicios.no_atentado.tramite.f_eli_tramite',compact('tramite','documento_tramite','eliminar'));
    }

    public function eli_tramite(Request $form){
        $form->validate([
            'cd'=>'required',
        ]);
        $noatentado=Noatentado::where('cod_dtra','=',$form['cd'])->first();
        if($noatentado){
            \Session::flash('error','No se puede eliminar el tramite');
        }else{
            $tramite=D_tramita::find($form['cd']);
            $cod_con=$tramite->cod_con;
            $antiguo=json_encode($tramite);
            SessionController::write('D',$antiguo,'','d_tramita','8',$tramite->cod_noa);
            $tramite->delete();
            \Session::flash('exito','Se ha eliminado con exito el trámite');
        }
        return redirect('listar tramite convocatoria/'.$cod_con);
    }
    public static function listaCandidatos($cod_dtra){
        $candidato=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
            ->where('cod_dtra','=',$cod_dtra)
            ->select('per_nombre','per_apellido','per_ci','carg_nombre')
            ->OrderBy('cod_noa','ASC')->get();
        $html="";
        foreach ($candidato as $c){
            $html.=$c->per_apellido." ".$c->per_nombre."<br/>";
        }
        return $html;
    }
    //=================ENTREGA
    public function fe_entrega($cod_dtra){
            $tramite_noatentado=DB::table('d_tramitas')
                ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->where('cod_dtra','=',$cod_dtra)
                ->select('d_tramitas.*','tramites.*')->first();
            //dd($tramite_noatentado);

            $noatentados=DB::table('noatentado.noatentado')
                ->join('personas','noatentado.id_per','=','personas.id_per')
                ->leftJoin('claustros.cargo_convocatoria','noatentado.noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
                ->where('cod_dtra','=',$cod_dtra)
                ->select('personas.*','noatentado.*','cargo_convocatoria.*')->get();
        $convocatoria=Convocatoria::find($tramite_noatentado->cod_con);
        $apoderado=array();
        if($tramite_noatentado->cod_apo!=''){
            $apoderado=Apoderado::find($tramite_noatentado->cod_apo);
        }

        return view('servicios.no_atentado.entrega.fe_entrega_noa',compact('tramite_noatentado','convocatoria','noatentados','apoderado'));
    }
    public function g_apoderado(Request $form){
        $tramita=D_tramita::find($form['cdtra']);
        $antiguo=json_encode($tramita);
        if($tramita->cod_apo==''){
            $apoderado=Apoderado::where('apo_ci','=',$form['ci'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci'],
                    'apo_apellido'=>mb_strtoupper($form['apellido']),
                    'apo_nombre'=>mb_strtoupper($form['nombre']),
                    'apo_sistema'=>8,
                ]);
            }
            $tramita->cod_apo=$apoderado->cod_apo;
            $tramita->dtra_tipo_apoderado=$form['tipo'];
            $tramita->save();
            $nuevo=json_encode($apoderado);
            SessionController::write('C','',$nuevo,'apoderados','8',$apoderado->cod_apo);
        }else{
            $apoderado=Apoderado::find($tramita->cod_apo);
            $apoderado->apo_apellido=$form['apellido'];
            $apoderado->apo_nombre=$form['nombre'];
            $tramita->dtra_tipo_apoderado=$form['tipo'];
            $tramita->save();
            $apoderado->save();
            $nuevo=json_encode($apoderado);
            SessionController::write('U',$antiguo,$nuevo,'d_tramita','8',$apoderado->cod_apo);
        }
        \Session::flash('exito','Se ha guardado exitosamente los datos del apoderado');
        //return redirect('datos apoderado/'.$tramita->cod_tra);

        return redirect('formulario entrega tramite noatentado/'.$tramita->cod_dtra);
    }
    public function g_entrega(Request $form){
        $form->validate(['cdtra'=>'required']);
            $tramite_noatentado=D_tramita::find($form['cdtra']);
            if($form['tipo']=='a'){
                $tramite_noatentado->dtra_entregado=$form['tipo'];
            }else{
                $tramite_noatentado->dtra_entregado='t';
                $tramite_noatentado->dtra_entregado_persona=$form['tipo'];
            }
            $tramite_noatentado->dtra_fecha_recojo=date('d/m/Y H:i:s');
            $tramite_noatentado->save();
            SessionController::write('U','','Entrega noatentado','d_tramitas','8',$tramite_noatentado->cod_dtra);
            \Session::flash('exito','Sa ha registrado la entrega exitosamente');
            return redirect('formulario entrega tramite noatentado/'.$tramite_noatentado->cod_dtra);
    }
    public function actualizar_lista_entrega(){
        $noatentado=DB::table('d_tramitas')
            ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('dtra_tipo','=','A')
            ->where('dtra_generado','=','t')
            ->where('d_tramitas.dtra_entregado',NULL)
            ->select('d_tramitas.*','tramites.tre_nombre')->orderBy('dtra_numero_tramite','DESC')->get();


        return view('servicios.no_atentado.entrega.l_entrega_noa_ajax',compact('noatentado'));
    }
    public function f_corregir_tramite_noa($cod_dtra){

        $tramite_noatentado=D_tramita::find($cod_dtra);
        $tramite=Tramite::find($tramite_noatentado->cod_tre);
        $noatentado=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->where('cod_dtra','=',$tramite_noatentado->cod_dtra)->get();
        return view('servicios.no_atentado.tramite.f_corregir_tramite_noa',compact('tramite_noatentado','noatentado','tramite'));
    }
    public function corregir_tramite_noa(Request $form){
        $form->validate([
            'cd'=>'required'
        ]);
        $tramite_noatentado=D_tramita::find($form['cd']);
        $tramite_noatentado->dtra_entregado=null;
        $tramite_noatentado->dtra_fecha_recojo=null;
        $tramite_noatentado->dtra_cod_glosa=null;
        $tramite_noatentado->dtra_generado=null;
        $tramite_noatentado->save();
        SessionController::write('U','','Editar noatentado','d_tramitas','8',$tramite_noatentado->cod_dtra);
        \Session::flash('exito','Ahora puede editar el trámite '.$tramite_noatentado->dtra_numero_tramite." / ".$tramite_noatentado->dtra_gestion_tramite);
        return redirect('listar tramite convocatoria/'.$tramite_noatentado->cod_con);
    }
    public function f_conf_entrega_noa($cod_dtra){
        $tramite_noatentado=DB::table('d_tramitas')
            ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_dtra','=',$cod_dtra)->where('dtra_generado','=','t')
            ->select('tre_nombre','d_tramitas.*')->first();
        $noatentado=DB::table('noatentado.noatentado')
                    ->join('personas','noatentado.id_per','=','personas.id_per')
                    ->where('cod_dtra','=',$tramite_noatentado->cod_dtra)->get();


        $apoderado=array();
        if($tramite_noatentado->cod_apo!=''){
            $apoderado=Apoderado::find($tramite_noatentado->cod_apo);
        }
        return view('servicios.no_atentado.entrega.f_conf_entrega_noa',compact('tramite_noatentado','noatentado','apoderado'));
    }
}
