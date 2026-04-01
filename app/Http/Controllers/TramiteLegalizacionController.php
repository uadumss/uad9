<?php

namespace App\Http\Controllers;

use App\Imports\ImportarLegalizacion;
use App\Imports\ImportarTema;
use App\Models\Apoderado;
use App\Models\D_confrontacion;
use App\Models\D_tramita;
use App\Models\Funciones;
use App\Models\Glosa;
use App\Models\Persona;
use App\Models\Titulo;
use App\Models\Tramita;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TramiteLegalizacionController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear traleg - srv'], ['only' => ['generar_numero']]);
        $this->middleware(['permission:eliminar traleg - srv'], ['only' => ['f_eli_tra_legalizacion']]);
        $this->middleware(['permission:eliminar traleg - srv'], ['only' => ['eli_traleg']]);
        $this->middleware(['permission:editar datos traleg - srv'], ['only' => ['g_traleg']]);
        $this->middleware(['permission:editar apoderado traleg - srv'], ['only' => ['f_apoderado']]);
        $this->middleware(['permission:editar apoderado traleg - srv'], ['only' => ['g_apoderado']]);
        $this->middleware(['permission:crear docleg - srv'], ['only' => ['g_docleg']]);
        $this->middleware(['permission:eliminar docleg - srv'], ['only' => ['f_eli_docleg','eli_docleg']]);
        $this->middleware(['permission:generar glosa docleg - srv'], ['only' => ['generar_glosa_legalizacion','elegir_modelo','legalizarTitulo']]);
        $this->middleware(['permission:imprimir legalizacion docleg - srv'], ['only' => ['conf_generar_pdf','cambiar_posicion_PDF','generarPDF']]);
        $this->middleware(['permission:entregar legalizacion docleg - srv'], ['only' => ['f_conf_entrega','g_entrega']]);
        $this->middleware(['permission:listar entregas - srv'], ['only' => ['lista_entrega_ajax','l_entrega']]);
        $this->middleware(['permission:deshacer generado glosa - srv'], ['only' => ['fe_corregir_docleg','corregir_docleg','corregir_interno']]);
    }
    public function lista_leg($fecha){
        if($fecha==''){
            $fecha=date('d/m/Y');
        }
        $tramitas=DB::table('tramitas')
            ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
            ->where('tra_fecha_solicitud','=',$fecha)
            ->select('tramitas.*','per_ci','per_nombre','per_apellido')
            ->orderBy('tra_numero')->get();
         return view('servicios.tra_legalizacion.l_traleg',compact('tramitas','fecha'));
    }
    public function lista_leg_ajax($fecha){
        $tramitas=DB::table('tramitas')
            ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
            ->where('tra_fecha_solicitud','=',$fecha)
            ->select('tramitas.*','per_ci','per_nombre','per_apellido')
            ->orderBy('tra_numero')->get();
        return view('servicios.tra_legalizacion.l_tabla_traleg',compact('tramitas','fecha'));
    }
    public function buscar_tramite($numero_tramite){
        $numero=explode('-',$numero_tramite);
        $fecha='';
        $tramitas=array();
        if(sizeof($numero)==2){
            if(is_numeric($numero[0]) && is_numeric($numero[1])){
                   $tramitas=DB::table('tramitas')
                       ->join('d_tramitas','tramitas.cod_tra','=','d_tramitas.cod_tra')
                       ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
                       ->where('d_tramitas.dtra_numero_tramite','=',$numero[0])
                       ->where('d_tramitas.dtra_gestion_tramite','=',$numero[1])
                       ->select('tramitas.*','per_ci','per_nombre','per_apellido')->get();
                   //dd($tramitas);
                   if(sizeof($tramitas)>0){
                       //$fecha=$tramitas->tra_fecha_solicitud;
                       $fecha=date('Y-m-d');
                   }else{
                       $fecha=date('Y-m-d');
                   }
            }else{
                \Session::flash('error','Número de tramite inválido');
                $fecha=date('Y-m-d');
            }
        }else{
            \Session::flash('error','Número de tramite inválido');
            $fecha=date('Y-m-d');
        }

        return view('servicios.tra_legalizacion.l_traleg',compact('tramitas','fecha'));
    }
    public function generar_numero(Request $form){
        $numero=DB::table('tramitas')->where('tra_fecha_solicitud','=',$form['fecha'])->max('tra_numero');
        $tramite=Tramita::create([
           'tra_numero'=>$numero+1,
           'tra_gestion'=>date('y'),
           'tra_fecha_solicitud'=>$form['fecha'],
           'tra_tipo_tramite'=>$form['tipo'],
        ]);
        SessionController::write('C','','','tramitas','3',$tramite->cod_tra);
        return view('servicios.tra_legalizacion.numero_legalizacion',compact('tramite'));
    }
    public function f_eli_tra_legalizacion($cod_tra){
        $tramita=DB::table('tramitas')
            ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
            ->where('cod_tra',$cod_tra)
            ->select('tramitas.*','per_nombre','per_apellido')->first();
        $docleg=D_tramita::all()->where('cod_tra',$cod_tra);
        return view('servicios.tra_legalizacion.f_eli_tramita',compact('tramita','docleg'));
    }
    public function fe_traleg($cod_tra){
        $tramite=DB::table('tramitas')
                    ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
                    ->where('cod_tra','=',$cod_tra)
                    ->select('per_apellido','per_nombre','per_ci','tramitas.*','per_pasaporte')
                    ->first();
        $tipos_titulos='';
        $lista_tramites=array();
        $tipos_array = "";
        $ptaang=array();
        if($tramite->id_per!='') {
            $ptaang=DB::select("select dt.dtra_ptaang,dt.dtra_numero,dt.dtra_gestion from d_tramitas dt, tramitas t where t.id_per=".$tramite->id_per." and t.cod_tra=dt.cod_tra and (dt.dtra_ptaang='B' or dt.dtra_ptaang='A')");
            $tipos_titulos = DB::select('select distinct(tit_tipo) from titulos where id_per=' . $tramite->id_per);
            $tipos_array = "(";
            foreach ($tipos_titulos as $tt):
                if($tt->tit_tipo=='di'){
                    $tipos_array .= "'tpos','tpos-ant',";
                    $tipos_array .= "'" . $tt->tit_tipo . "','" . $tt->tit_tipo . "-ant',";
                }else{
                    $tipos_array .= "'" . $tt->tit_tipo . "','" . $tt->tit_tipo . "-ant',";
                }
            endforeach;
            $tipos_array .= "'res')";
            $lista_tramites='';
            if($tramite->tra_tipo_tramite=='B')
            {
                    $lista_tramites=Tramite::all()->where('tre_tipo','=','B')->where('tre_hab','<>','f');
            }else{
                //esto mientras no se llene los datos de los titulos por lo menos hasta la gstion 2000
                $lista_tramites=Tramite::all()->where('tre_tipo','=',$tramite->tra_tipo_tramite)->where('tre_hab','<>','f');

                //==========IMPORTANTE======
                //Restringe la lista de tramites a los documentos que tiene el interesado

                /*$lista_tramites = DB::select("select * from tramites where tre_hab='t' and tre_tipo='"
                    . $tramite->tra_tipo_tramite . "' and tre_buscar_en in " . $tipos_array . " or tre_buscar_en='' or tre_buscar_en='res'");*/
            }

        }
        /*$lista_tramites=Tramite::all()->where('tre_hab','=','t')->sortBy('tre_nombre')
            ->where('tre_tipo','=',$tramite->tra_tipo_tramite);*/

        $documentos=DB::table('d_tramitas')
            ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_tra','=',$cod_tra)
            ->select('tre_nombre','d_tramitas.*')->orderByDesc('cod_dtra')->get();

        $confrontacion=array();
        if($tramite->tra_tipo_tramite=='F' || $tramite->tra_tipo_tramite=='B'){
            $confrontacion=DB::table('d_confrontacions')
                ->join('d_tramitas','d_confrontacions.cod_dtra','=','d_tramitas.cod_dtra')
                ->where('cod_tra','=',$cod_tra)
                ->select('d_confrontacions.*')->orderBy('cod_dcon')->get();
        }
        $apoderado=array();
        if($tramite->cod_apo!=''){
            $apoderado=Apoderado::find($tramite->cod_apo);
        }
        return view('servicios.tra_legalizacion.fe_traleg',compact('tramite','documentos','lista_tramites','confrontacion','apoderado','tipos_array','ptaang'));
    }
    public function g_traleg(Request $form){
        //return $form['ci'];
    	$form->validate([
                'ci'=>'required',
                'nombre'=>'required',
                'apellido'=>'required',
            ]);
            $persona=Persona::where('per_ci','=',$form['ci'])->first();
            $tramite=Tramita::find($form['ctra']);
            if($persona){
                $tramite->id_per=$persona->id_per;
                \Session::flash('exito','Se guardaron los datos correctamente ');
            }else{
                $persona=Persona::create([
                    'per_ci'=>$form['ci'],
                    'per_pasaporte'=>$form['pasaporte'],
                    'per_apellido'=>mb_strtoupper($form['apellido']),
                    'per_nombre'=>mb_strtoupper($form['nombre']),
                    'per_sistema'=>3,
                ]);
                $tramite->id_per=$persona['id_per'];
                \Session::flash('exito','Se guardaron los datos correctamente');
                SessionController::write('C','','persona creada','personas','3',$persona->id_per);
            }
            $tramite->save();
        return redirect('datos tramite legalizacion/'.$form['ctra']);
    }
    public function eli_traleg(Request $form){
        $tramita=Tramita::find($form['ctra']);
        $fecha=date('Y-m-d',strtotime($tramita->tra_fecha_solicitud));
        if($tramita->tra_tipo_tramite=='F') {
            $d_tramita = D_tramita::all()->where('cod_tra', '=', $tramita->cod_tra)->first();
            if ($d_tramita){
                $eli_docleg = DB::delete('delete from d_confrontacions  where cod_dtra=' . $d_tramita->cod_dtra);
                $d_tramita->delete();
            }
        }else{
            $eli_docleg=DB::delete('delete from d_tramitas where cod_tra='.$form['ctra']);
        }
        $tramita->delete();
        //[id persona]*[apoderado]*[numero]*
        $nuevo=$tramita->id_per."*".$tramita->cod_apro.'*'.$tramita['numero'].'*'.$tramita['fecha_solicitud'];
        SessionController::write('D',$nuevo,'','tramitas','3',$tramita->cod_tra);
        \Session::flash('exito','Se ha eliminado correctamente el trámite');
        return redirect('listar tramite legalizacion/'.$fecha);
    }
    public function verificarSitra($ci,$numero,$tipo){
        $documento=Funciones::DocumentoSitra($tipo);
        $ruta="http://sitra.umss.net/consulta/api/ci/".$ci."/numero/".$numero."/tipo/".$documento;
        $data=json_decode(file_get_contents($ruta));
        return $data;
    }
    public function verificacion_sitra($cod_dtra){
        $docleg=D_tramita::find($cod_dtra);
        $tramita=Tramita::find($docleg->cod_tra);
        $persona=Persona::find($tramita->id_per);
        $documento=Funciones::DocumentoSitra($docleg->dtra_buscar_en);
        $numero=$docleg->dtra_numero;
        $fuente='sitra';
        try {
            $respuesta=$this->verificarSitra($persona->per_ci,$docleg->dtra_numero,$docleg->dtra_buscar_en);
        } catch (\Throwable $e) {
            $respuesta=(object)[];
        }

        if(!is_object($respuesta)){
            $respuesta=(object)[];
        }

        $nombreSitra=trim((string)($respuesta->nombre ?? ''));
        $tipoSitra=trim((string)($respuesta->tipo ?? ''));
        $numeroSitra=trim((string)($respuesta->numero ?? ''));

        if($nombreSitra==='' && $tipoSitra==='' && $numeroSitra===''){
            $respaldoUad9=$this->buscarRespaldoInternoSitra(
                (int)$tramita->id_per,
                (string)$docleg->dtra_numero,
                (string)$docleg->dtra_buscar_en,
                trim((string)($docleg->dtra_gestion ?? ''))
            );

            if($respaldoUad9){
                $fuente='sid';
                $respuesta=(object)[
                    'nombre'=>trim((string)($persona->per_apellido.' '.$persona->per_nombre)),
                    'titulo'=>trim((string)($respaldoUad9->tit_titulo ?? '')),
                    'numero'=>(string)($respaldoUad9->tit_nro_titulo ?? $docleg->dtra_numero),
                    'gestion'=>(string)($respaldoUad9->tit_gestion ?? $docleg->dtra_gestion),
                    'tipo'=>Funciones::DocumentoSitra((string)$docleg->dtra_buscar_en),
                ];
            }else{
                $fuente='sitra_sid';
            }
        }

        return view('servicios.tra_legalizacion.verificacion_sitra', compact('respuesta','persona','docleg','documento','fuente'));
    }
    public function g_docleg(Request $form){
        $datosTramita=Tramita::find($form['ctra']);
        if($datosTramita && $datosTramita->tra_tipo_tramite=='F' && !isset($form['cdtra'])){
            return $this->guardarConfrontacionDesdeEdicion($form,$datosTramita);
        }

        /* cuadis = 'c' hace referencia a los del cuadis que no pagan valorados*/
        $cuadis='';
        if($form['cuadis']!='on'){
            if($form['control']==''){
                \Session::flash('error','El número de control del valorado es requerido');
                return redirect('datos tramite legalizacion/'.$form['ctra']);
            }
        }else{
            $cuadis='c';
        }
        if(!isset($form['cdtra'])){
            $datosTramita=Tramita::find($form['ctra']);
            if(!$datosTramita || !$datosTramita->id_per){
                \Session::flash('error','Debe registrar primero los datos personales (CI) del trámite.');
                return redirect('datos tramite legalizacion/'.$form['ctra']);
            }

            if($datosTramita->tra_tipo_tramite=='F'){
                $yaTieneDocumento=D_tramita::where('cod_tra','=',$form['ctra'])->exists();
                if($yaTieneDocumento){
                    \Session::flash('error','Confrontación permite un solo trámite por registro.');
                    return redirect('datos tramite legalizacion/'.$form['ctra']);
                }
                if(empty($form['documentos'])){
                    \Session::flash('error','Seleccione el documento de confrontación.');
                    return redirect('datos tramite legalizacion/'.$form['ctra']);
                }
            }

            $persona=Persona::find($datosTramita->id_per);
            $preimpreso=trim((string)($form['reimpresion'] ?? ''));
            $verificacionRecaudacion=['ok'=>true];

            // Validación obligatoria contra recaudaciones para valorados pagados
            if($cuadis!='c'){
                $verificacionRecaudacion=$this->validarRecaudacionLegalizacion(
                    (string)$form['control'],
                    (string)$persona->per_ci,
                    $datosTramita->tra_tipo_tramite,
                    (int)$persona->id_per,
                    $preimpreso,
                    (int)$form['ctra']
                );
                if(!$verificacionRecaudacion['ok']){
                    \Session::flash('error',$verificacionRecaudacion['message']);
                    return redirect('datos tramite legalizacion/'.$form['ctra']);
                }
            }

            $tramita=Tramite::find($form['tipo']);
            $buscarEnSitra=(string)($tramita->tre_buscar_en ?? '');
            if($datosTramita->tra_tipo_tramite=='B' && !empty($form['buscar_en'])){
                $buscarEnSitra=explode('-', (string)$form['buscar_en'])[0] ?? '';
            }
            $a=strtolower(trim((string)$buscarEnSitra));
            $respuesta="";
            $verificar_sitra=in_array($a,['db','ca','da','tp'],true) ? '2' : '';
            $numeroDoc=$form['numero'];

            if(!in_array($datosTramita->tra_tipo_tramite,['E','F'],true) && ($a=='db' || $a=='ca' || $a=='da' || $a=='tp' || $a=='re' || $a=='su')){
                try {
                    $respuesta=TramiteLegalizacionController::verificarSitra($persona->per_ci,$form['numero'],$buscarEnSitra);
                } catch (\Throwable $e) {
                    $respuesta=(object)[];
                }
                if(!is_object($respuesta)){
                    $respuesta=(object)[];
                }
                $nombre=$persona->per_apellido." ".$persona->per_nombre;
                $documento=Funciones::DocumentoSitra($buscarEnSitra);

                /*
                 * Verificar en sitra dtra_verificacion_sitra
                 * 0=Coincide los datos del sitra con los datos del trámite
                 * 1=No coincide los datos
                 * 2=No existe en el sitra
                 */
                if($form['numero']!='-'){
                    $nombreSitra = trim((string)($respuesta->nombre ?? ''));
                    $tipoSitra = strtolower(trim((string)($respuesta->tipo ?? '')));
                    $numeroSitra = trim((string)($respuesta->numero ?? ''));
                    $nombreLocal = trim((string)$nombre);
                    $tipoLocal = strtolower(trim((string)$documento));
                    $numeroLocal = trim((string)$numeroDoc);

                    $nombresCoinciden=$this->nombresCompatibles($nombreLocal,$nombreSitra);
                    if($nombresCoinciden && $tipoLocal==$tipoSitra && $numeroLocal==$numeroSitra){
                        $verificar_sitra='0';
                    }else{
                        if($nombreSitra=="" && $tipoSitra=="" && $numeroSitra==""){
                            $respaldoUad9=$this->buscarRespaldoInternoSitra(
                                (int)$datosTramita->id_per,
                                (string)$numeroDoc,
                                (string)$buscarEnSitra,
                                trim((string)($form['gestion'] ?? ''))
                            );
                            $verificar_sitra=$respaldoUad9 ? '0' : '2';
                        }else{
                            $verificar_sitra='1';
                        }
                    }
                }else{
                    $numeroDoc=0;
                }
            }

            //return $respuesta;
            if($tramita->tre_buscar_en=='' || $tramita->tre_buscar_en=='res'){
                $form->validate([
                    'tipo'=>'required','ctra'=>'required',
                ]);
            }else{
                $form->validate([
                    'tipo'=>'required','ctra'=>'required','gestion'=>'required|numeric'
                ]);
            }
            $buscar_en=array();
            if($datosTramita->tra_tipo_tramite=='B'){
                $buscar_en=explode('-',$form['buscar_en']);
            }else{
                $buscar_en=explode('-',$tramita->tre_buscar_en);
            }
            $titulo='';
            if($buscarEnSitra!='' && $form['numero']!='-'){
                if($buscar_en[0]=='tpos'){
                    $titulo=Titulo::where('tit_nro_titulo','=',$form['numero'])
                        ->where('tit_gestion','=',$form['gestion'])
			            ->whereIn('tit_tipo',['tpos','di'])
                        ->where('id_per','=',$datosTramita->id_per)->first();
                }else{
                    if($buscar_en[0]=='da'){
                        $titulo=Titulo::where('tit_nro_titulo','=',$form['numero'])
                            ->where('tit_gestion','=',$form['gestion'])
			                ->whereIn('tit_tipo',['da','ca'])
                            ->where('id_per','=',$datosTramita->id_per)->first();

                    }else{

                        $titulo=Titulo::where('tit_nro_titulo','=',$form['numero'])
                            ->where('tit_gestion','=',$form['gestion'])
                            ->where('tit_tipo','=',$buscar_en[0])
			                ->where('id_per','=',$datosTramita->id_per)->first();
                    }
                }
            }
            //dd($titulo);
            /* dtra_estado_doc
                0. verificado
                1. No hay el titulo en la base de datos
                2. Hay titulo, pero no hay el PDF
                3. Hay el titulo pero no hay su antecedente
                4. Confrontado
                5. Busqueda
            */
            $año_tramita=date('Y',strtotime($datosTramita->tra_fecha_solicitud));
            $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
            $numero_tramite+=1;

            //dd($numero_tramite);

            $aux=explode('-',$tramita->tre_buscar_en);
            $estado=1;
            if(true){
            /*if($titulo){  IMPORTANTE============Cuando este restringido por titulo

                if(sizeof($aux)==1){
                    if($titulo->tit_pdf==''){    $estado=2;  }
                }else{
                    if($titulo->tit_pdf==''){    $estado=3;  }
                }*/
                $codtit=0;
                if($titulo){
                    $codtit=$titulo->cod_tit;
                }
                $ptaang='';
                //return $form['ptaang'];
                if($form['ptaang']=='on'){
                    if($tramita->tre_buscar_en=='da'){
                        $ptaang='A';
                    }else{
                        if($tramita->tre_buscar_en=='db'){
                            $ptaang='B';
                        }
                    }
                }
                $supletorio="";
                if($form['supletorio']=='on'){
                    $supletorio="t";
                }
                $busqueda_en='';
                if($form['buscar_en']!=''){
                    $busqueda_en=$form['buscar_en'];
                }else{
                    $busqueda_en=$tramita->tre_buscar_en;
                }
                $tramite=D_tramita::create([
                    'cod_tre'=>$form['tipo'],
                    'cod_tra'=>$form['ctra'],
                    'dtra_numero'=>$numeroDoc,
                    'dtra_gestion'=>$form['gestion'],
                    // Hasta el 08-05-2023 se guardaba el preimpreso, ahora se guarda el nro. de control del valorado desde el 9 de mayo
                    //'dtra_control'=>$form['valorado'],
                    'dtra_control'=>$form['control'],
                    'dtra_valorado_reintegro'=>$form['reintegro'],
                    'dtra_valorado_busqueda'=>$form['valorado_bus'],
                    'dtra_control_reimpresion'=>$form['reimpresion'],
                    'dtra_numero_tramite'=>$numero_tramite,
                    'dtra_gestion_tramite'=>$año_tramita,
                    'dtra_costo'=>$tramita->tre_costo,
                    'dtra_tipo'=>$tramita->tre_tipo,
                    'dtra_solo_sello'=>$tramita->tre_solo_sello,
                    //'dtra_cod_tit'=>$titulo->cod_tit,
                    'dtra_cod_tit'=>$codtit,
                    'dtra_estado_doc'=>$estado,
                    'dtra_fecha_registro'=>date('d/m/Y'),
                    'dtra_interno'=>$form['tipo_tramite'],
                    'dtra_buscar_en'=>$busqueda_en,
                    'dtra_ptaang'=>$ptaang,
                    'dtra_verificacion_sitra'=>$verificar_sitra,
                    'dtra_supletorio'=>$supletorio,
                    'dtra_sin_valorado'=>$cuadis,
                ]);
                if($cuadis!='c'){
                    $errorUso='';
                    if(!$this->registrarUsoRecaudacion($verificacionRecaudacion,(int)$form['ctra'],(int)$tramite->cod_dtra,$errorUso)){
                        $tramite->delete();
                        \Session::flash('error',$errorUso);
                        return redirect('datos tramite legalizacion/'.$form['ctra']);
                    }
                }
                $nuevo=json_encode($tramite);
                SessionController::write('C','',$nuevo,'d_tramitas','3',$tramite->cod_dtra);

                \Session::flash('exito','Se ha creado exitosamente el trámite');
            }else{ // EN CASO DE QUE EL TITULO NO SE HAYA ENCONTRADO
                //return $tramita->tre_buscar_en." --";
                if($tramita->tre_buscar_en=='res'){
                    $estado=7;
                }else{
                    $estado=6;
                }
                if($tramita->tre_buscar_en=='' || $tramita->tre_buscar_en=='res'){
                    $tramite=D_tramita::create([
                        'cod_tre'=>$form['tipo'],
                        'cod_tra'=>$form['ctra'],
                        'dtra_control'=>$form['control'],
                        'dtra_control_reimpresion'=>$form['reimpresion'],
                        'dtra_valorado_busqueda'=>$form['valorado_bus'],
                        'dtra_numero_tramite'=>$numero_tramite,
                        'dtra_gestion_tramite'=>$año_tramita,
                        'dtra_costo'=>$tramita->tre_costo,
                        'dtra_tipo'=>$tramita->tre_tipo,
                        'dtra_solo_sello'=>$tramita->tre_solo_sello,
                        'dtra_fecha_registro'=>date('d/m/Y'),
                        'dtra_estado_doc'=>$estado,
                        'dtra_interno'=>$form['tipo_tramite'],
                        'dtra_buscar_en'=>$form['buscar_en'],
                        'dtra_sin_valorado'=>$cuadis,
                    ]);
                    if($cuadis!='c'){
                        $errorUso='';
                        if(!$this->registrarUsoRecaudacion($verificacionRecaudacion,(int)$form['ctra'],(int)$tramite->cod_dtra,$errorUso)){
                            $tramite->delete();
                            \Session::flash('error',$errorUso);
                            return redirect('datos tramite legalizacion/'.$form['ctra']);
                        }
                    }
                    $nuevo=json_encode($tramite);
                    SessionController::write('C','',$nuevo,'d_tramitas','3',$tramite->cod_dtra);
                    \Session::flash('exito','Se ha creado exitosamente el trámite ');
                }else{
                    \Session::flash('error','El título '.$form['numero'].'/'.$form['gestion'].' No corresponde a la persona');
                }
            }
            if($datosTramita->tra_tipo_tramite=='B' || $datosTramita->tra_tipo_tramite=='F'){
                D_confrontacion::create([
                    'dcon_doc'=>$form['documentos'],
                    'cod_dtra'=>$tramite->cod_dtra,
                ]);
            }
        }else{

        }
        return redirect('datos tramite legalizacion/'.$form['ctra']);
    }

    private function guardarConfrontacionDesdeEdicion(Request $form, Tramita $datosTramita)
    {
        if(!$datosTramita->id_per){
            \Session::flash('error','Debe registrar primero los datos personales (CI) del trámite.');
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        $yaTieneDocumento=D_tramita::where('cod_tra','=',$datosTramita->cod_tra)->exists();
        if($yaTieneDocumento){
            \Session::flash('error','Confrontación permite un solo trámite por registro.');
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        $tipoSeleccionado=$form->input('tipo',$form->input('tramite'));
        $form->merge([
            'tipo'=>$tipoSeleccionado,
        ]);

        $form->validate([
            'tipo'=>'required|integer',
            'control'=>'required|integer',
        ]);

        $persona=Persona::find($datosTramita->id_per);
        if(!$persona || !$persona->per_ci){
            \Session::flash('error','El CI no es válido para consultar.');
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        $verificacionRecaudacion=$this->validarRecaudacionLegalizacion(
            (string)$form['control'],
            (string)$persona->per_ci,
            $datosTramita->tra_tipo_tramite,
            (int)$persona->id_per,
            '',
            (int)$datosTramita->cod_tra
        );

        if(!$verificacionRecaudacion['ok']){
            \Session::flash('error',$verificacionRecaudacion['message']);
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        $documentosSeleccionados=[];
        foreach(['ci','cn','lm','ce','pa','lc'] as $doc){
            if($form[$doc]){
                $documentosSeleccionados[]=$form[$doc];
            }
        }

        if(count($documentosSeleccionados)===0){
            \Session::flash('error','Seleccione al menos un documento de confrontación.');
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        $año_tramita=date('Y',strtotime($datosTramita->tra_fecha_solicitud));
        $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
        $numero_tramite=((int)$numero_tramite)+1;

        $dTramite=D_tramita::create([
            'cod_tre'=>$form['tipo'],
            'cod_tra'=>$datosTramita->cod_tra,
            'dtra_tipo'=>'F',
            'dtra_control'=>$form['control'],
            'dtra_valorado'=>$form['control'],
            'dtra_numero_tramite'=>$numero_tramite,
            'dtra_gestion_tramite'=>$año_tramita,
            'dtra_fecha_recojo'=>date('d/m/Y H:i:s'),
            'dtra_fecha_firma'=>date('d/m/Y H:i:s'),
            'dtra_estado_doc'=>'4',
            'dtra_entregado'=>'t',
        ]);

        $errorUso='';
        if(!$this->registrarUsoRecaudacion($verificacionRecaudacion,(int)$datosTramita->cod_tra,(int)$dTramite->cod_dtra,$errorUso)){
            $dTramite->delete();
            \Session::flash('error',$errorUso);
            return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
        }

        foreach($documentosSeleccionados as $doc){
            D_confrontacion::create([
                'dcon_doc'=>$doc,
                'cod_dtra'=>$dTramite->cod_dtra,
            ]);
        }

        SessionController::write('C','',json_encode($dTramite),'d_tramitas','3',$dTramite->cod_dtra);
        \Session::flash('exito','Se ha creado exitosamente el trámite');
        return redirect('datos tramite legalizacion/'.$datosTramita->cod_tra);
    }

    public function validar_valorado_recaudaciones(Request $request, $cod_tra)
    {
        $data=$request->validate([
            'control'=>['required','integer'],
            'reimpresion'=>['nullable','string','max:30'],
        ]);

        $tramita=Tramita::find($cod_tra);
        if(!$tramita || !$tramita->id_per){
            return response()->json([
                'ok'=>false,
                'message'=>'Debe registrar primero los datos personales del trámite',
            ],422);
        }

        $persona=Persona::find($tramita->id_per);
        if(!$persona || !$persona->per_ci){
            return response()->json([
                'ok'=>false,
                'message'=>'El CI no es válido para consultar.',
            ],422);
        }

        $validacion=$this->validarRecaudacionLegalizacion(
            (string)$data['control'],
            (string)$persona->per_ci,
            $tramita->tra_tipo_tramite,
            (int)$persona->id_per,
            trim((string)($data['reimpresion'] ?? '')),
            (int)$cod_tra
        );
        if(!$validacion['ok']){
            return response()->json($validacion,422);
        }

        return response()->json($validacion);
    }

    public function validar_sitra_previa(Request $request, $cod_tra)
    {
        $data=$request->validate([
            'numero'=>['nullable','string','max:20'],
            'gestion'=>['nullable','string','max:4'],
            'tipo'=>['nullable','integer'],
            'buscar_en'=>['nullable','string','max:20'],
        ]);

        $tramita=Tramita::find($cod_tra);
        if(!$tramita || !$tramita->id_per){
            return response()->json([
                'ok'=>true,
                'aplica'=>false,
                'message'=>'SITRA: primero registre los datos personales.',
            ]);
        }

        if(in_array($tramita->tra_tipo_tramite,['E','F'],true)){
            return response()->json([
                'ok'=>true,
                'aplica'=>false,
                'message'=>'SITRA: no aplica para trámite Consejo/Confrontación.',
            ]);
        }

        $persona=Persona::find($tramita->id_per);
        if(!$persona || !$persona->per_ci){
            return response()->json([
                'ok'=>true,
                'aplica'=>false,
                'message'=>'SITRA: CI no válido para consulta.',
            ]);
        }

        $numero=trim((string)($data['numero'] ?? ''));
        $gestion=trim((string)($data['gestion'] ?? ''));
        if($numero==='' || $numero==='-'){
            return response()->json([
                'ok'=>true,
                'aplica'=>false,
                'message'=>'SITRA: pendiente.',
            ]);
        }

        $buscarEn='';
        if(!empty($data['tipo'])){
            $tipoDoc=Tramite::find((int)$data['tipo']);
            if($tipoDoc){
                $buscarEn=(string)($tipoDoc->tre_buscar_en ?? '');
            }
        }
        if($buscarEn==='' && !empty($data['buscar_en'])){
            $buscarEn=explode('-', (string)$data['buscar_en'])[0] ?? '';
        }

        if(!in_array($buscarEn,['db','ca','da','tp','re','su'],true)){
            return response()->json([
                'ok'=>true,
                'aplica'=>false,
                'message'=>'SITRA: no aplica para este tipo.',
            ]);
        }

        $sitraDisponible=true;
        try {
            $respuesta=$this->verificarSitra((string)$persona->per_ci,$numero,$buscarEn);
        } catch (\Throwable $e) {
            $sitraDisponible=false;
            $respuesta=(object)[];
        }
        if(!is_object($respuesta)){
            $respuesta=(object)[];
        }

        $nombre=trim((string)(($persona->per_apellido ?? '').' '.($persona->per_nombre ?? '')));
        $documento=Funciones::DocumentoSitra($buscarEn);
        $nombreSitra=trim((string)($respuesta->nombre ?? ''));
        $tituloSitra=trim((string)($respuesta->titulo ?? ''));
        $tipoSitra=trim((string)($respuesta->tipo ?? ''));
        $tipoSitraNormalizado=strtolower($tipoSitra);
        $numeroSitra=trim((string)($respuesta->numero ?? ''));
        $gestionSitra=trim((string)($respuesta->gestion ?? ''));
        $nombreLocal=trim((string)$nombre);
        $tipoLocal=strtolower(trim((string)$documento));
        $numeroLocal=trim((string)$numero);

        $estado='1';
        $nombresCoinciden=$this->nombresCompatibles($nombreLocal,$nombreSitra);
        if($nombresCoinciden && $tipoLocal===$tipoSitraNormalizado && $numeroLocal===$numeroSitra){
            $estado='0';
        }elseif($nombreSitra==='' && $tipoSitraNormalizado==='' && $numeroSitra===''){
            $respaldoUad9=$this->buscarRespaldoInternoSitra((int)$tramita->id_per,$numero,$buscarEn,$gestion);
            if($respaldoUad9){
                return response()->json([
                    'ok'=>true,
                    'aplica'=>true,
                    'estado'=>'0',
                    'fuente'=>'sid',
                    'nombre'=>$nombre,
                    'titulo'=>(string)($respaldoUad9->tit_titulo ?? ''),
                    'tipo'=>strtoupper($buscarEn),
                    'numero'=>(string)($respaldoUad9->tit_nro_titulo ?? $numero),
                    'gestion'=>(string)($respaldoUad9->tit_gestion ?? $gestion),
                    'message'=>'Validado con respaldo SID'.($sitraDisponible ? '' : ' (SITRA no disponible)').'.',
                ]);
            }
            $estado='2';
        }

        $fuenteRespuesta='sitra';
        if($estado==='2'){
            $fuenteRespuesta='sitra_sid';
        }

        return response()->json([
            'ok'=>true,
            'aplica'=>true,
            'estado'=>$estado,
            'fuente'=>$fuenteRespuesta,
            'nombre'=>$nombreSitra,
            'titulo'=>$tituloSitra,
            'tipo'=>$tipoSitra,
            'numero'=>$numeroSitra,
            'gestion'=>$gestionSitra,
        ]);
    }

    private function validarRecaudacionLegalizacion(
        string $control,
        string $ci,
        string $tipoTramite,
        int $idPer,
        string $preimpreso = '',
        int $codTraActual = 0
    ): array
    {
        $baseUrl = rtrim((string) config('services.recaudaciones.url'), '/');
        $token = (string) config('services.recaudaciones.token');
        $verifySsl = filter_var(config('services.recaudaciones.verify_ssl', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($verifySsl === null) {
            $verifySsl = true;
        }

        if($baseUrl==='' || $token===''){
            return $this->respuestaErrorValidacionLegalizacion(
                'SISTEMA_NO_CONFIGURADO',
                'El sistema de recaudaciones no esta configurado. Contacte al area de sistemas.'
            );
        }

        try {
            $response=Http::withToken($token)
                ->acceptJson()
                ->timeout(20)
                ->withOptions(['verify'=>$verifySsl])
                ->post($baseUrl,[
                'unidad'=>122,
                'recibo'=>(int)$control,
                'documento'=>$ci,
            ]);
        } catch (\Throwable $e) {
            return $this->respuestaErrorValidacionLegalizacion(
                'API_NO_DISPONIBLE',
                'No se pudo conectar con recaudaciones. Intente nuevamente en unos minutos.'
            );
        }

        if(!$response->successful()){
            $json=$response->json();
            $msg=(string)($json['error']['message'] ?? '');
            if(trim($msg)===''){
                $msg=(string)($json['message'] ?? '');
            }
            $errMap=$this->mapearMensajeErrorRecaudacionLegalizacion($msg,$tipoTramite);
            return $this->respuestaErrorValidacionLegalizacion($errMap['code'],$errMap['message']);
        }

        $json=$response->json();
        $lista=$this->extraerListaResultadoRecaudacion(is_array($json) ? $json : []);
        if(!is_array($lista) || sizeof($lista)==0){
            $tipoTxt=$this->etiquetaTipoTramiteLegalizacion($tipoTramite);
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_NO_EXISTE',
                'No se encontró información del recibo en recaudaciones para el CI y número de control indicados. '.
                'Para trámites de '.$tipoTxt.', confirme que el valorado exista y que el documento (CI) sea el mismo registrado en caja.'
            );
        }

        $persona=Persona::where('per_ci','=',$ci)->first();
        $nombreSistema='';
        $nombreSistemaNormalizado='';
        if($persona){
            $nombreSistemaNormalizado=$this->normalizarTexto(($persona->per_apellido ?? '').' '.($persona->per_nombre ?? ''));
        }

        $candidatos=$this->filtrarFilasRecaudacionPorPreimpreso($lista,$preimpreso);
        if(sizeof($candidatos)===0){
            $tipoTxt=$this->etiquetaTipoTramiteLegalizacion($tipoTramite);
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_NO_VALIDA',
                'El recibo existe en recaudaciones pero no coincide con el preimpreso indicado o no aplica a este trámite ('.$tipoTxt.'). Verifique el número de preimpreso o use el valorado correcto.'
            );
        }

        $usoEncontrado=null;
        $mensajeCuentaInvalida='';
        $detalleCi='';
        $detalleNombre='';

        foreach($candidatos as $fila){
            $ciFila=(string)($fila['documento'] ?? '');
            if($ciFila!==$ci){
                if($detalleCi===''){
                    $detalleCi='(Recaudación: '.$ciFila.' | Trámite: '.$ci.')';
                }
                continue;
            }

            $nombreR=trim(($fila['apellido_1'] ?? '').' '.($fila['apellido_2'] ?? '').' '.($fila['nombre_1'] ?? '').' '.($fila['nombre_2'] ?? ''));
            $nombreRecaudacionNormalizado=$this->normalizarTexto($nombreR);
            if($nombreSistemaNormalizado!=='' && $nombreSistemaNormalizado!==$nombreRecaudacionNormalizado){
                if($detalleNombre===''){
                    $detalleNombre='(Recaudación: '.$nombreR.' | Datos: '.$nombreSistemaNormalizado.')';
                }
                continue;
            }

            $preimpresoApi=$this->valorPreimpresoFila((array)$fila);
            $fechaPago=(string)($fila['fecha'] ?? '');

            $usoCombinacion=$this->buscarUsoPagoPorCombinacion(
                $nombreR,
                $ci,
                (string)$control,
                $preimpresoApi,
                $fechaPago
            );
            if($usoCombinacion){
                $usoEncontrado=$usoCombinacion;
                continue;
            }

            $codigoCuenta=(string)($fila['codigo_cuenta'] ?? '');
            $nombreCuentaRecaudaciones=strtoupper(trim((string)($fila['cuenta'] ?? '')));
            $tramiteSugerido=$this->buscarTramiteSugeridoDesdeFilaRecaudacion((array)$fila,$tipoTramite);
            $ptagAuto=$this->esCuentaVerifAutentPtag($nombreCuentaRecaudaciones,$tipoTramite,$tramiteSugerido);

            if(!$tramiteSugerido){
                $nombreCuenta=trim((string)$nombreCuentaRecaudaciones);
                if($nombreCuenta!==''){
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite. Cuenta: "'.$nombreCuenta.'".';
                }else{
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite.';
                }
                continue;
            }

            if($tramiteSugerido->tre_tipo !== $tipoTramite){
                $nombreCuenta=trim((string)$nombreCuentaRecaudaciones);
                $nombreTramiteSugerido=trim((string)$tramiteSugerido->tre_nombre);
                $sonSimilares=$this->textosCuentaMuySimilares($nombreCuenta,$nombreTramiteSugerido);

                if($nombreCuenta!=='' && !$sonSimilares){
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite. Cuenta: "'.$nombreCuenta.'". Corresponde a: "'.$nombreTramiteSugerido.'".';
                } elseif($nombreTramiteSugerido!==''){
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite. Corresponde a: "'.$nombreTramiteSugerido.'".';
                } elseif($nombreCuenta!==''){
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite. Cuenta: "'.$nombreCuenta.'".';
                } else {
                    $mensajeCuentaInvalida='La boleta no corresponde a este tipo de trámite.';
                }
                continue;
            }

            return [
                'ok'=>true,
                'control'=>$control,
                'ci'=>$ci,
                'nombre_recaudaciones'=>$nombreR,
                'identificador'=>$fila['identificador'] ?? '',
                'fecha_pago'=>$fila['fecha'] ?? '',
                'cajero'=>$fila['cajero'] ?? '',
                'codigo_cuenta'=>$codigoCuenta,
                'cuenta'=>$fila['cuenta'] ?? '',
                'monto'=>$fila['total'] ?? '',
                'preimpreso'=>$preimpresoApi,
                'tipo_legalizacion_sugerido'=>$tramiteSugerido->cod_tre,
                'nombre_tipo_legalizacion_sugerido'=>$tramiteSugerido->tre_nombre,
                'ptag_auto'=>$ptagAuto,
            ];
        }

        if($usoEncontrado){
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_YA_USADA',
                $this->mensajePagoYaUsado($usoEncontrado)
            );
        }

        if($mensajeCuentaInvalida!==''){
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_NO_CORRESPONDE_TRAMITE',
                $mensajeCuentaInvalida
            );
        }

        if($detalleCi!==''){
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_NO_PERTENECE_PERSONA',
                'La boleta no pertenece a la persona del trámite.',
                ['detalle'=>$detalleCi]
            );
        }

        if($detalleNombre!==''){
            return $this->respuestaErrorValidacionLegalizacion(
                'BOLETA_NO_PERTENECE_PERSONA',
                'La boleta no corresponde a los datos de la persona del trámite.',
                ['detalle'=>$detalleNombre]
            );
        }

        $tipoTxt=$this->etiquetaTipoTramiteLegalizacion($tipoTramite);
        return $this->respuestaErrorValidacionLegalizacion(
            'BOLETA_NO_VALIDA',
            'No se pudo validar el pago para '.$tipoTxt.'. Verifique que el valorado corresponda a ese tipo de trámite, que el CI y el nombre coincidan con recaudaciones, y que el número de control sea el correcto.'
        );
    }

    /**
     * Acepta distintas formas de respuesta de la API de recaudaciones.
     */
    private function extraerListaResultadoRecaudacion(array $json): array
    {
        $candidatos=[
            $json['data']['result'] ?? null,
            $json['result'] ?? null,
            $json['data']['data']['result'] ?? null,
        ];
        foreach($candidatos as $lista){
            if(is_array($lista) && sizeof($lista)>0){
                return $lista;
            }
        }
        $data=$json['data'] ?? null;
        if(is_array($data) && $data!==[] && array_key_exists(0, $data)){
            return $data;
        }

        return [];
    }

    private function etiquetaTipoTramiteLegalizacion(string $tipo): string
    {
        return match ($tipo) {
            'L'=>'legalización',
            'C'=>'certificación',
            'B'=>'búsqueda',
            'F'=>'confrontación',
            'E'=>'consejo universitario',
            'A'=>'no atentado',
            default=>'este módulo',
        };
    }

    private function respuestaErrorValidacionLegalizacion(string $code, string $message, array $extra=[]): array
    {
        return array_merge([
            'ok'=>false,
            'code'=>$code,
            'message'=>$message,
        ],$extra);
    }

    private function mapearMensajeErrorRecaudacionLegalizacion(string $mensajeApi, string $tipoTramite=''): array
    {
        $mensajeApi=trim($mensajeApi);
        $msgNorm=mb_strtolower($mensajeApi);
        $ctx=$tipoTramite!=='' ? ' (trámite: '.$this->etiquetaTipoTramiteLegalizacion($tipoTramite).')' : '';

        if(
            strpos($msgNorm,'configuracion')!==false ||
            strpos($msgNorm,'configuración')!==false ||
            strpos($msgNorm,'services/.env')!==false ||
            strpos($msgNorm,'sistema no configurado')!==false
        ){
            return [
                'code'=>'SISTEMA_NO_CONFIGURADO',
                'message'=>'El sistema de recaudaciones no esta configurado. Contacte al area de sistemas.',
            ];
        }

        if(
            strpos($msgNorm,'comunicacion')!==false ||
            strpos($msgNorm,'comunicación')!==false ||
            strpos($msgNorm,'error en la comunicacion con la api de recaudaciones')!==false ||
            strpos($msgNorm,'la api de recaudaciones respondio con error')!==false ||
            strpos($msgNorm,'error inesperado en recaudaciones')!==false ||
            strpos($msgNorm,'timeout')!==false
        ){
            return [
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'No se pudo conectar con recaudaciones. Intente nuevamente en unos minutos.',
            ];
        }

        if(
            $mensajeApi==='' ||
            strpos($msgNorm,'not found')!==false ||
            strpos($msgNorm,'no se encuentra')!==false ||
            strpos($msgNorm,'no encontrado')!==false ||
            strpos($msgNorm,'recibo')!==false ||
            strpos($msgNorm,'control')!==false ||
            strpos($msgNorm,'valido')!==false ||
            strpos($msgNorm,'válido')!==false
        ){
            return [
                'code'=>'BOLETA_NO_EXISTE',
                'message'=>'Recaudaciones no devolvió datos para este número de control y CI, o el comprobante no es válido.'.$ctx.' Verifique el número de control, que el CI sea el del pago y que el valorado corresponda al tipo de trámite.',
            ];
        }

        if(strpos($msgNorm,'documento')!==false || strpos($msgNorm,'ci')!==false || strpos($msgNorm,'identidad')!==false){
            return [
                'code'=>'BOLETA_NO_PERTENECE_PERSONA',
                'message'=>'La boleta no pertenece a la persona del trámite (documento/CI distinto al registrado en el pago).'.$ctx,
            ];
        }

        if(strpos($msgNorm,'cuenta')!==false || strpos($msgNorm,'tramite')!==false || strpos($msgNorm,'trámite')!==false){
            return [
                'code'=>'BOLETA_NO_CORRESPONDE_TRAMITE',
                'message'=>'La boleta no corresponde al tipo de trámite actual.'.$ctx.' Revise que el pago sea del concepto correcto (p. ej. certificación vs legalización).',
            ];
        }

        return [
            'code'=>'BOLETA_NO_VALIDA',
            'message'=>'No se pudo validar el pago en recaudaciones: '.($mensajeApi!=='' ? $mensajeApi : 'respuesta no reconocida.').$ctx,
        ];
    }

    private function registrarUsoRecaudacion(array $validacion, int $codTra, int $codDtra, string &$error): bool
    {
        $error='';
        if(!Schema::hasTable('recaudacion_usos')){
            $error='No se puede continuar: falta la tabla de bloqueo de pagos (migración pendiente).';
            Log::critical('Bloqueo de recaudación deshabilitado por migración faltante.',[
                'tabla'=>'recaudacion_usos',
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
            ]);
            return false;
        }

        $identificador=trim((string)($validacion['identificador'] ?? ''));
        if($identificador===''){
            $error='No se pudo registrar el uso del pago: identificador vacío';
            return false;
        }

        $usoCombinacion=$this->buscarUsoPagoPorCombinacion(
            (string)($validacion['nombre_recaudaciones'] ?? ''),
            (string)($validacion['ci'] ?? ''),
            (string)($validacion['control'] ?? ''),
            (string)($validacion['preimpreso'] ?? ''),
            (string)($validacion['fecha_pago'] ?? '')
        );
        if($usoCombinacion){
            $error='Este pago ya se usó (misma combinación de nombre, impreso, control y fecha).';
            return false;
        }

        try{
            DB::table('recaudacion_usos')->insert([
                'identificador'=>$identificador,
                'recibo'=>(string)($validacion['control'] ?? ''),
                'preimpreso'=>(string)($validacion['preimpreso'] ?? ''),
                'fecha_pago'=>(string)($validacion['fecha_pago'] ?? ''),
                'documento'=>(string)($validacion['ci'] ?? ''),
                'nombre_persona'=>(string)($validacion['nombre_recaudaciones'] ?? ''),
                'cajero'=>(string)($validacion['cajero'] ?? ''),
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
                'usuario_registro'=>Auth::check() ? Auth::user()->name : 'sistema',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }catch(\Throwable $e){
            $error='No se guardó el bloqueo. Intente de nuevo.';
            Log::error('Error al registrar uso de recaudación.',[
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
                'identificador'=>$identificador,
                'error'=>$e->getMessage(),
            ]);
            return false;
        }

        return true;
    }

    private function buscarUsoPagoPorCombinacion(string $nombrePersona, string $documento, string $recibo, string $preimpreso, string $fechaPago)
    {
        if(!Schema::hasTable('recaudacion_usos')){
            return null;
        }

        if(trim($recibo)==='' || trim($fechaPago)===''){
            return null;
        }

        $query=DB::table('recaudacion_usos')
            ->where('recibo','=',trim($recibo))
            ->where('fecha_pago','=',trim($fechaPago));

        $documento=trim($documento);
        if($documento!==''){
            $query->where('documento','=',$documento);
        }

        $preimpreso=trim($preimpreso);
        if($preimpreso!==''){
            $query->where('preimpreso','=',$preimpreso);
        }

        $usos=$query->get();
        if($usos->isEmpty()){
            return null;
        }

        $nombreNormalizado=$this->normalizarTexto($nombrePersona);
        foreach($usos as $uso){
            $nombreGuardado=$this->normalizarTexto((string)($uso->nombre_persona ?? ''));
            if($nombreNormalizado!=='' && $nombreGuardado!==$nombreNormalizado){
                continue;
            }
            return $uso;
        }

        return null;
    }

    private function filtrarFilasRecaudacionPorPreimpreso(array $lista, string $preimpreso): array
    {
        if(sizeof($lista)===0){
            return [];
        }

        if($preimpreso===''){
            return array_map(function($fila){
                return (array)$fila;
            }, $lista);
        }

        $resultado=[];
        $preimpresoNormalizado=$this->normalizarNumero($preimpreso);
        foreach($lista as $fila){
            $filaArr=(array)$fila;
            $valorFila=$this->normalizarNumero($this->valorPreimpresoFila($filaArr));
            if($valorFila!=='' && $valorFila===$preimpresoNormalizado){
                $resultado[]=$filaArr;
            }
        }

        return $resultado;
    }

    private function mensajePagoYaUsado(object $usoPago): string
    {
        $nombrePersona=trim((string)($usoPago->nombre_persona ?? ''));
        $ciPersona=trim((string)($usoPago->documento ?? ''));
        $fechaUso=trim((string)($usoPago->created_at ?? ''));

        if($fechaUso!==''){
            $timestamp=strtotime($fechaUso);
            if($timestamp!==false){
                $fechaUso=date('d/m/Y H:i', $timestamp);
            }
        }

        $mensaje='Este pago ya fue utilizado';
        
        if($nombrePersona!=='' || $ciPersona!==''){
            $mensaje.=' a nombre de '.$nombrePersona;
            if($ciPersona!==''){
                $mensaje.=' (CI '.$ciPersona.')';
            }
        }
        
        if($fechaUso!==''){
            $mensaje.=' el '.$fechaUso;
        }
        
        $mensaje.='. No se puede usar nuevamente.';

        return $mensaje;
    }

    private function seleccionarFilaRecaudacion(array $lista, string $preimpreso): ?array
    {
        if(sizeof($lista)===0){
            return null;
        }

        if($preimpreso===''){
            return $lista[0];
        }

        $preimpresoNormalizado=$this->normalizarNumero($preimpreso);
        foreach($lista as $fila){
            $valorFila=$this->normalizarNumero($this->valorPreimpresoFila((array)$fila));
            if($valorFila!=='' && $valorFila===$preimpresoNormalizado){
                return (array)$fila;
            }
        }

        return null;
    }

    private function valorPreimpresoFila(array $fila): string
    {
        $keys=['preimpreso','nro_preimpreso','numero_preimpreso','pre_impreso'];
        foreach($keys as $key){
            if(isset($fila[$key]) && (string)$fila[$key]!==''){
                return (string)$fila[$key];
            }
        }
        return '';
    }

    private function normalizarNumero(string $valor): string
    {
        return preg_replace('/\D+/', '', trim($valor)) ?? '';
    }

    private function normalizarTexto(string $valor): string
    {
        $valor=mb_strtoupper(trim($valor));
        $valor=str_replace(['Á','É','Í','Ó','Ú'],['A','E','I','O','U'],$valor);
        $valor=preg_replace('/\s+/', ' ', $valor);
        return (string)$valor;
    }

    private function textosCuentaMuySimilares(string $a, string $b): bool
    {
        $normalizarComparacion=function(string $texto): string {
            $t=$this->normalizarTexto($texto);
            $t=preg_replace('/[^A-Z0-9 ]+/', ' ', $t) ?? '';
            $t=preg_replace('/\s+/', ' ', trim($t)) ?? '';
            return (string)$t;
        };

        $singularizar=function(string $texto): string {
            if($texto===''){
                return '';
            }
            $palabras=explode(' ', $texto);
            foreach($palabras as &$palabra){
                if(strlen($palabra)>3){
                    $palabra=preg_replace('/(ES|S)$/', '', $palabra) ?? $palabra;
                }
            }
            return implode(' ', $palabras);
        };

        $x=$normalizarComparacion($a);
        $y=$normalizarComparacion($b);
        if($x==='' || $y===''){
            return false;
        }

        if($x===$y){
            return true;
        }

        if(strpos($x,$y)!==false || strpos($y,$x)!==false){
            return true;
        }

        $xs=$singularizar($x);
        $ys=$singularizar($y);
        if($xs!=='' && $ys!=='' && ($xs===$ys || strpos($xs,$ys)!==false || strpos($ys,$xs)!==false)){
            return true;
        }

        similar_text($xs!=='' ? $xs : $x, $ys!=='' ? $ys : $y, $porcentaje);
        return $porcentaje>=92;
    }

    private function buscarTramiteSugeridoDesdeFilaRecaudacion(array $fila, string $tipoTramite): ?Tramite
    {
        $codigoCuenta=trim((string)($fila['codigo_cuenta'] ?? ''));
        $nombreCuenta=trim((string)($fila['cuenta'] ?? ''));

        if($codigoCuenta!==''){
            $porCuenta=Tramite::where('tre_hab','=','t')
                ->where('tre_numero_cuenta','=',$codigoCuenta)
                ->get();

            $porTipo=$porCuenta->where('tre_tipo','=',$tipoTramite)->values();
            if($porTipo->count()===1){
                return $porTipo->first();
            }
            if($porTipo->count()>1){
                $mejor=null;
                $mejorScore=-1;
                foreach($porTipo as $tramiteCand){
                    $score=$this->puntajeSimilitudCuentaTramite($nombreCuenta,(string)$tramiteCand->tre_nombre);
                    if($score>$mejorScore){
                        $mejorScore=$score;
                        $mejor=$tramiteCand;
                    }
                }
                if($mejor){
                    return $mejor;
                }
            }

            if($porCuenta->count()===1){
                return $porCuenta->first();
            }
            if($porCuenta->count()>1){
                $mejor=null;
                $mejorScore=-1;
                foreach($porCuenta as $tramiteCand){
                    $score=$this->puntajeSimilitudCuentaTramite($nombreCuenta,(string)$tramiteCand->tre_nombre);
                    if($score>$mejorScore){
                        $mejorScore=$score;
                        $mejor=$tramiteCand;
                    }
                }
                if($mejor){
                    return $mejor;
                }
            }
        }

        if($nombreCuenta===''){
            return null;
        }

        $candidatos=Tramite::where('tre_hab','=','t')
            ->where('tre_tipo','=',$tipoTramite)
            ->get();

        $mejor=null;
        $mejorScore=-1;
        foreach($candidatos as $tramiteCand){
            $score=$this->puntajeSimilitudCuentaTramite($nombreCuenta,(string)$tramiteCand->tre_nombre);
            if($score>$mejorScore){
                $mejorScore=$score;
                $mejor=$tramiteCand;
            }
        }

        $umbralSimilitud=86;
        if($mejor && $mejorScore>=$umbralSimilitud){
            return $mejor;
        }

        return null;
    }

    private function puntajeSimilitudCuentaTramite(string $nombreCuenta, string $nombreTramite): float
    {
        $cuentaComp=$this->normalizarTextoComparacionCuenta($nombreCuenta);
        $tramiteComp=$this->normalizarTextoComparacionCuenta($nombreTramite);

        if($cuentaComp==='' || $tramiteComp===''){
            return 0.0;
        }

        if($cuentaComp===$tramiteComp){
            return 100.0;
        }

        similar_text($cuentaComp,$tramiteComp,$porcentaje);
        return (float)$porcentaje;
    }

    private function normalizarTextoComparacionCuenta(string $valor): string
    {
        $v=$this->normalizarTexto($valor);
        $v=preg_replace('/[^A-Z0-9 ]+/', ' ', $v) ?? '';

        $reemplazos=[
            '/\bVERIFICACION\b/' => ' ',
            '/\bVERIF\b/' => ' ',
            '/\bAUTENTICACION\b/' => ' ',
            '/\bAUTENT\b/' => ' ',
            '/\bLEGALIZACION\b/' => ' ',
            '/\bLEG\b/' => ' ',
            '/\bCERTIFICACION\b/' => ' ',
            '/\bCERTIFICACIÓN\b/u' => ' ',
            '/\bCERT\b/' => ' ',
            '/\bDE\b/' => ' ',
            '/\bDEL\b/' => ' ',
            '/\bLA\b/' => ' ',
            '/\bEL\b/' => ' ',
            '/\bY\b/' => ' ',
        ];

        foreach($reemplazos as $patron=>$reemplazo){
            $v=preg_replace($patron,$reemplazo,$v) ?? $v;
        }

        $v=preg_replace('/\s+/', ' ', trim($v)) ?? '';
        return (string)$v;
    }

    private function esCuentaVerifAutentPtag(string $nombreCuenta, string $tipoTramite, ?Tramite $tramiteSugerido=null): bool
    {
        if($tipoTramite!=='L'){
            return false;
        }

        if(!$tramiteSugerido){
            return false;
        }

        $buscarEnSugerido=strtolower(trim((string)($tramiteSugerido->tre_buscar_en ?? '')));
        if(!in_array($buscarEnSugerido,['da','db'],true)){
            return false;
        }

        $texto=$this->normalizarTexto($nombreCuenta);
        if($texto===''){
            return false;
        }

        $tieneVerif=(strpos($texto,'VERIF')!==false || strpos($texto,'VERIFICACION')!==false);
        $tieneAutent=(strpos($texto,'AUTENT')!==false || strpos($texto,'AUTENTICACION')!==false);
        if(!($tieneVerif && $tieneAutent)){
            return false;
        }

        $esAcademico=(strpos($texto,'ACADEM')!==false);
        $esBachiller=(strpos($texto,'BACHILLER')!==false);

        if($buscarEnSugerido==='da'){
            return $esAcademico;
        }

        if($buscarEnSugerido==='db'){
            return $esBachiller;
        }

        return false;
    }

    private function nombresCompatibles(string $nombreLocal, string $nombreSitra): bool
    {
        $local=$this->normalizarTexto($nombreLocal);
        $sitra=$this->normalizarTexto($nombreSitra);

        if($local==='' || $sitra===''){
            return false;
        }

        if($local===$sitra){
            return true;
        }

        return strpos($local,$sitra)!==false || strpos($sitra,$local)!==false;
    }

    private function buscarRespaldoInternoSitra(int $idPer, string $numero, string $buscarEn, string $gestion=''): ?Titulo
    {
        if($idPer<=0 || trim($numero)==='' || trim($buscarEn)===''){
            return null;
        }

        $query=Titulo::where('id_per','=',$idPer)
            ->where('tit_nro_titulo','=',trim($numero));

        if(trim($gestion)!==''){
            $query->where('tit_gestion','=',trim($gestion));
        }

        if($buscarEn==='da'){
            $query->whereIn('tit_tipo',['da','ca']);
        }elseif($buscarEn==='tpos'){
            $query->whereIn('tit_tipo',['tpos','di']);
        }else{
            $query->where('tit_tipo','=',$buscarEn);
        }

        return $query->first();
    }
    public function obs_docleg($cod_dtra){
        $docleg=DB::table('d_tramitas')->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_dtra',$cod_dtra)
            ->select('tre_nombre','d_tramitas.*')->first();
        return view ('servicios.tra_legalizacion.obs_docleg',compact('docleg'));
    }
    public function fe_corregir_docleg($cod_dtra){
        $docleg=D_tramita::find($cod_dtra);
        $tramita=Tramita::find($docleg->cod_tra);
        $tramite=Tramite::find($docleg->cod_tre);
        $persona=Persona::find($tramita->id_per);
        return view('servicios.tra_legalizacion.fe_corregir_docleg',compact('docleg','persona','tramita','tramite'));
    }
    public function corregir_docleg(Request $form){

        $docleg=D_tramita::find($form['cdtra']);
        $antiguo=$docleg->toArray();
        $docleg->dtra_generado='';
        $docleg->save();
        SessionController::write('U',json_encode($antiguo),json_encode($docleg->toArray()),'d_tramitas','3',$docleg->cod_dtra);
        return redirect('datos tramite legalizacion/'.$docleg->cod_tra);
    }
    public function g_obs_docleg(Request $form){
        $docleg=D_tramita::find($form['cdtra']);
        $tramita=Tramita::find($docleg->cod_tra);
        $docleg->dtra_obs=$form['obs'];
        if($form['falso']=='on') {
            $docleg->dtra_falso = 't';
            $tramita->tra_anulado='t';
            $tramita->save();
        }
        if($form['obs']!=''){
            $tramita->tra_obs='t';
            $tramita->save();
        }
        $nuevo=json_encode($docleg);
        SessionController::write('C','','OBSERVACION:'.$docleg->dtra_obs." - ".$docleg->dtra_falso,'d_tramitas','3',$docleg->cod_dtra);
        $docleg->save();
        return redirect('datos tramite legalizacion/'.$form['ctra']);
    }
    public function f_eli_docleg($cod_dtra){
        $docleg=DB::table('d_tramitas')->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_dtra',$cod_dtra)
            ->select('tre_nombre','d_tramitas.*')->first();
        $persona=DB::table('personas')
            ->join('tramitas','personas.id_per','=','tramitas.id_per')
            ->select('personas.*')
            ->where('tramitas.cod_tra','=',$docleg->cod_tra)->first();
        return view ('servicios.tra_legalizacion.f_eli_docleg',compact('docleg','persona'));
    }
    public function eli_docleg(Request $form){
        $docleg=D_tramita::find($form['cdtra']);
        if($docleg->dtra_falso!='t') {
            DB::delete('delete from d_confrontacions where cod_dtra='.$docleg->cod_dtra);
            $docleg->delete();
            $antiguo=json_encode($docleg);
            SessionController::write('D',$antiguo,'','d_tramitas','3',$docleg->cod_dtra);

            \Session::flash('exito','Sa ha eliminado satisfactoriamente el documento de legalización');
        }else{
            \Session::flash('error','No se puede eliminar el documento');
        }
        return redirect('datos tramite legalizacion/'.$form['ctra']);
    }
    public function generar_glosa_legalizacion($cod_dtra)
    {
        $docleg = D_tramita::find($cod_dtra);
        $tramite = Tramite::find($docleg->cod_tre);
        $glosas = DB::table('glosas')->where('cod_tre','=',$tramite->cod_tre)->get();
        $tramita = Tramita::find($docleg->cod_tra);
        $persona = Persona::find($tramita->id_per);
            $titulo = Titulo::find($docleg->dtra_cod_tit);
            $tipo = $titulo ? $titulo->tit_tipo : $tramite->tre_buscar_en;
            $unidadAcademica = '';
            if ($titulo){
                if ($tipo == 'ca' || $tipo == 'da' || $tipo == 'tp') {
                    $unidadAcademica = DB::table('diploma_academicos')->join('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                        ->join('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                        ->select('carreras.cod_car', 'carreras.cod_fac', 'car_nombre', 'fac_nombre')
                        ->where('cod_tit', '=', $titulo->cod_tit)->first();
                }
            }

            $apoderado="";
            if($tramita->cod_apo!=''){
                $apoderado=Apoderado::find($tramita->cod_apo);
            }

            $mes=Funciones::mes(date('n'));
            $numero="<span style='font-weight:bold'>".$docleg->dtra_numero."/".substr($docleg->dtra_gestion,-2)."</span>";
            if($docleg->dtra_cod_glosa==''){
                //return "glosa vacia";
                $docleg->dtra_cod_glosa=$glosas[0]->cod_glo;
                if($docleg->dtra_tipo=='E'){
                    $docleg->dtra_glosa=Funciones::glosa_consejo($tramite,$glosas[0],$docleg,$persona);
                }else{
                    $docleg->dtra_glosa=Funciones::glosa_tarmites($tramite,$glosas[0],$docleg,$persona,$titulo,$unidadAcademica);
                }
            }else{
                $glosa=Glosa::find($docleg->dtra_cod_glosa);
                /*if($glosa){
                    //return "glosa no vacia";
                    $docleg->dtra_glosa=Funciones::glosa_tarmites($tramite,$glosa,$docleg,$persona,$titulo,$unidadAcademica);
                }else{
                    \Session::flash('error','La glosa anteriormente seleccionada fue eliminada, se procesdio a elegir otra glosa');
                    $docleg->dtra_glosa=Funciones::glosa_tarmites($tramite,$glosas[0],$docleg,$persona,$titulo,$unidadAcademica);
                }*/
            }
            $qr=$this->valorQR(date('d'),date('m'),date('Y'));
            $qr_generado='http://http://www.archivos.umss.edu.bo/verificar_tramite/index.php?q='.$qr;

            if($docleg->dtra_interno=='t'){$docleg->dtra_titulo=$tramite->tre_titulo_interno;}
            else{$docleg->dtra_titulo=$tramite->tre_titulo;}

            $docleg->dtra_qr=$qr;
            if($docleg->dtra_fecha_literal=='') {
                $docleg->dtra_fecha_literal = "Cochabamba, " . date('j') . " de " . $mes . " del " . date('Y');
            }
            $docleg->save();

            SessionController::write('U','','Generó glosa->modelo : '.$docleg->dtra_cod_glosa,'d_tramitas','3',$docleg->cod_dtra);

            $fecha=date('Y-m-d', strtotime($tramita->tra_fecha_solicitud));
            return view('servicios.tra_legalizacion.glosa_legalizacion',compact('persona','fecha','glosas','apoderado','tramita','tramite','mes','docleg','qr_generado','numero'));
    }
    public function elegir_modelo($cod_glo,$cod_dtra){
        //return $cod_dtra;
        $docleg=D_tramita::find($cod_dtra);
        $docleg->dtra_cod_glosa=$cod_glo;
        $tramite = Tramite::find($docleg->cod_tre);
        $glosa=Glosa::find($cod_glo);
        $tramita = Tramita::find($docleg->cod_tra);
        $persona = Persona::find($tramita->id_per);
        $titulo = Titulo::find($docleg->dtra_cod_tit);
        $tipo = $titulo ? $titulo->tit_tipo : $tramite->tre_buscar_en;
        $unidadAcademica = '';
        if ($titulo){
            if ($tipo == 'ca' || $tipo == 'da' || $tipo == 'tp') {
                $unidadAcademica = DB::table('diploma_academicos')->join('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                    ->join('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                    ->select('carreras.cod_car', 'carreras.cod_fac', 'car_nombre', 'fac_nombre')
                    ->where('cod_tit', '=', $titulo->cod_tit)->first();
            }
        }
        $docleg->dtra_glosa=Funciones::glosa_tarmites($tramite,$glosa,$docleg,$persona,$titulo,$unidadAcademica);
        $docleg->save();
        return redirect('generar glosa_leg/'.$docleg->cod_dtra);
    }
    public function legalizarTitulo(Request $form){
        $docleg=D_tramita::find($form['cdtra']);
        $docleg->dtra_glosa=$form['glosa'];
        $docleg->dtra_generado='t';
        $docleg->dtra_glosa_posicion=$form['posicion'];
        $docleg->dtra_fecha_firma=date('d/m/Y');
        $docleg->save();
        SessionController::write('U','','Genera legalización','d_tramitas','3',$docleg->cod_dtra);
        return redirect('datos tramite legalizacion/'.$form['ctra']);
    }
    public function conf_generar_pdf($cod_dtra){
        $docleg=D_tramita::find($cod_dtra);
        return view('servicios.tra_legalizacion.conf_imprimir_glosa',compact('docleg'));
    }
    public function cambiar_posicion_PDF($cdtra,$posicion){
        $docleg=D_tramita::find($cdtra);
        $docleg->dtra_glosa_posicion=$posicion;
        $docleg->save();
        return redirect('generar pdf/'.$docleg->cod_dtra);
    }
    public function generarPDF($cdtra){
        $docleg=D_tramita::find($cdtra);
        SessionController::write('U','','Imprime pdf','d_tramitas','3',$docleg->cod_dtra);
        if($docleg->dtra_falso!='t'){
            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('legal');
            $pdf->loadView('servicios.tra_legalizacion.pdf_legalizacion',compact('docleg'));
            return $pdf->stream('legalizado.pdf');
        }else{
            $pdf = app('dompdf.wrapper');
            $pdf->loadHtml("<span style='color: #DD0000'>Archivo bloqueado</span>");
            return $pdf->stream('legalizado.pdf');
        }
    }
    public function ver_documento($cod_dtra){

        $docleg=D_tramita::find($cod_dtra);
        if($docleg->dtra_cod_tit!=''){
            $cod_tit=$docleg->dtra_cod_tit;
            $diploma_academico=array();
            $revalida=array();

            $titulo=DB::table('titulos')
                ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                ->join('tomos','titulos.cod_tom','=','tomos.cod_tom')
                ->join('personas','titulos.id_per','=','personas.id_per')
                ->leftJoin('nacionalidads','personas.cod_nac','=','nacionalidads.cod_nac')
                ->where('cod_tit','=',$cod_tit)
                ->select('tom_numero','tom_gestion','tom_tipo','tit_nro_folio','tit_ref','tit_titulo','tit_pdf','tit_antecedentes',
                    'titulos.*','per_nombre','per_apellido','per_ci','per_sexo','per_pasaporte',
                    'nac_nombre','mod_nombre')->get();

            if($titulo[0]->tom_tipo=='da' || $titulo[0]->tom_tipo=='ca' || $titulo[0]->tom_tipo=='tp'){
                $diploma_academico=DB::table('diploma_academicos')
                    ->join('carreras','diploma_academicos.cod_car','=','carreras.cod_car')
                    ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
                    ->where('diploma_academicos.cod_tit','=',$cod_tit)
                    ->select('car_nombre','fac_nombre')
                    ->get();
            }

            if($titulo[0]->tit_revalida=='t' || $titulo[0]->tom_tipo=='re'){
                $revalida=DB::table('revalidas')
                    ->join('nacionalidads','revalidas.cod_nac','=','nacionalidads.cod_nac')
                    ->where('revalidas.cod_tit','=',$cod_tit)
                    ->select('re_fecha','re_universidad','nac_nombre')
                    ->get();
            }
            return view('servicios.tra_legalizacion.detalleTituloLegalizacion',compact('titulo','revalida','diploma_academico','docleg'));
        }else{
            return view('servicios.tra_legalizacion.detalleTituloLegalizacion',compact('docleg'));
        }

    }
    public function f_apoderado($cod_tra){
        $tramita=Tramita::find($cod_tra);
        $apoderado=array();
        $persona=array();
        if($tramita->cod_apo!=''){
            $apoderado=Apoderado::find($tramita->cod_apo);
        }
        if($tramita->id_per!=''){
            $persona=Persona::find($tramita->id_per);
        }
        return view('servicios.tra_legalizacion.apoderado',compact('tramita','apoderado','persona'));
    }
    public function g_apoderado(Request $form){
        $tramita=Tramita::find($form['ctra']);
        if($tramita->cod_apo==''){
            $apoderado=Apoderado::where('apo_ci','=',$form['ci'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci'],
                    'apo_apellido'=>mb_strtoupper($form['apellido']),
                    'apo_nombre'=>mb_strtoupper($form['nombre']),
                    'apo_sistema'=>3,
                ]);
            }
            $tramita->cod_apo=$apoderado->cod_apo;
            $tramita->tra_tipo_apoderado=$form['tipo'];
            $tramita->save();
            $nuevo=json_encode($apoderado);
            SessionController::write('U','',$nuevo,'apoderados','3',$apoderado->cod_apo);
        }else{
            $apoderado=Apoderado::find($tramita->cod_apo);
            $apoderado->apo_apellido=$form['apellido'];
            $apoderado->apo_nombre=$form['nombre'];
            $tramita->tra_tipo_apoderado=$form['tipo'];
            $tramita->save();
            $apoderado->save();
            $antiguo=json_encode($apoderado);
            SessionController::write('U',$antiguo,'','apoderados','3',$apoderado->cod_apo);
        }
        \Session::flash('exito','Se ha guardado exitosamente los datos del apoderado');
        //return redirect('datos apoderado/'.$tramita->cod_tra);
        if($form['pan']=='ent'){
            return redirect('panel entrega legalizacion/'.$tramita->cod_tra);
        }else{
            return redirect('datos tramite legalizacion/'.$tramita->cod_tra);
        }
    }
    public function l_entrega(){

        $l_entregas=DB::table('personas')
            ->join('tramitas','personas.id_per','=','tramitas.id_per')
            ->join('d_tramitas','tramitas.cod_tra','=','d_tramitas.cod_tra')
            ->where('d_tramitas.dtra_entregado',NULL)
            ->where('dtra_generado','=','t')
            ->select('dtra_fecha_recojo','cod_dtra','tramitas.cod_tra',
                'personas.id_per','per_apellido','per_nombre','per_ci',
            'tra_numero','tra_fecha_solicitud',
            'dtra_numero','dtra_tipo','dtra_numero_tramite','dtra_gestion_tramite','tra_tipo_apoderado','dtra_fecha_firma','dtra_fecha_recojo')
            ->get();

        $noatentado=DB::table('d_tramitas')
            ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('dtra_tipo','=','A')
            ->where('dtra_generado','=','t')
            ->where('d_tramitas.dtra_entregado',NULL)
            ->select('d_tramitas.*','tramites.tre_nombre')->orderBy('dtra_numero_tramite','DESC')->get();

            return view('servicios.entrega.l_entregas',compact('l_entregas','noatentado'));

    }
    public function lista_entrega_ajax(){
        $l_entregas=DB::table('personas')
            ->join('tramitas','personas.id_per','=','tramitas.id_per')
            ->join('d_tramitas','tramitas.cod_tra','=','d_tramitas.cod_tra')
            ->where('d_tramitas.dtra_entregado',NULL)
            ->where('dtra_generado','=','t')
            ->select('dtra_fecha_recojo','cod_dtra','tramitas.cod_tra',
                'personas.id_per','per_apellido','per_nombre','per_ci',
                'tra_numero','tra_fecha_solicitud',
                'dtra_numero','dtra_tipo','dtra_numero_tramite','dtra_gestion_tramite','tra_tipo_apoderado','dtra_fecha_firma','dtra_fecha_recojo')
            ->get();


        return view('servicios.entrega.l_entregas_ajax',compact('l_entregas'));
    }
    public function f_entrega($cod_tra){

        $tramita=Tramita::find($cod_tra);
        $documentos=array();
        if($tramita->tra_tipo_tramite=='B'){
            $documentos=DB::table('d_tramitas')
                ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->join('d_confrontacions','d_tramitas.cod_dtra','=','d_confrontacions.cod_dtra')
                ->where('cod_tra','=',$cod_tra)->where('dtra_generado','=','t')
                ->select('tre_nombre','d_tramitas.*','d_confrontacions.dcon_doc')->orderByDesc('cod_dtra')->get();
        }else{
            $documentos=DB::table('d_tramitas')
                ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->where('cod_tra','=',$cod_tra)->where('dtra_generado','=','t')
                ->select('tre_nombre','d_tramitas.*')->orderByDesc('cod_dtra')->get();
        }


        $apoderado=array();
        $persona=array();
        if($tramita->cod_apo!=''){
            $apoderado=Apoderado::find($tramita->cod_apo);
        }
        if($tramita->id_per!=''){
            $persona=Persona::find($tramita->id_per);
        }
        return view('servicios.tra_legalizacion.f_entrega',compact('tramita','documentos','persona','apoderado'));
    }
    public function f_conf_entrega($varios,$cod_dtra){
        if($varios=='1'){
            $docleg=DB::table('d_tramitas')
                ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->where('cod_dtra','=',$cod_dtra)->where('dtra_generado','=','t')
                ->select('tre_nombre','d_tramitas.*')->first();

            $tramita=Tramita::find($docleg->cod_tra);
            $apoderado=array();
            $persona=array();
            if($tramita->cod_apo!=''){
                $apoderado=Apoderado::find($tramita->cod_apo);
            }
            if($tramita->id_per!=''){
                $persona=Persona::find($tramita->id_per);
            }
        }else{
            if($varios=='2'){
                //cod_dtra hace referencia a cod_tra o a la llave primaria de tramita
                $tramita=Tramita::find($cod_dtra);
                $docleg=DB::table('d_tramitas')
                    ->Join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                    ->where('cod_tra','=',$cod_dtra)->where('dtra_generado','=','t')
                    ->select('tre_nombre','d_tramitas.*')->get();
                $apoderado=array();
                $persona=array();
                if($tramita->cod_apo!=''){
                    $apoderado=Apoderado::find($tramita->cod_apo);
                }
                if($tramita->id_per!=''){
                    $persona=Persona::find($tramita->id_per);
                }
            }
        }

        return view('servicios.tra_legalizacion.f_conf_entrega',compact('docleg','tramita','apoderado','persona','varios'));

    }
    public function g_entrega(Request $form){
        if(isset($form['todo']) && $form['todo']=='t'){
            $tramita=Tramita::find($form['ctra']);
            //$docleg=DB::select('select * from d_tramitas where cod_tra='.$tramita->cod_tra." and (dtra_entregado='' or dtra_entregado is NULL)");
            $docleg=D_tramita::where('cod_tra','=',$tramita->cod_tra)->get();
            foreach ($docleg as $d){
                if($d->dtra_entregado==''){
                    $d->dtra_entregado=$form['tipo'];
                    $d->dtra_fecha_recojo=date('d/m/Y H:i:s');
                    $d->save();
                    SessionController::write('U','','Entrega legalizacion','d_tramitas','3',$d->cod_dtra);
                    \Session::flash('exito','Sa ha registrado la entrega exitosamente');
                }
            }

            return redirect('panel entrega legalizacion/'.$tramita->cod_tra);
        }else{
            $docleg=D_tramita::find($form['cdtra']);
            $tramita=Tramita::find($docleg->cod_tra);
            $docleg->dtra_entregado=$form['tipo'];
            $docleg->dtra_fecha_recojo=date('d/m/Y H:i:s');
            $docleg->save();
            SessionController::write('U','','Entrega legalizacion','d_tramitas','3',$docleg->cod_dtra);
            \Session::flash('exito','Sa ha registrado la entrega exitosamente');
            return redirect('panel entrega legalizacion/'.$docleg->cod_tra);
        }
    }
    public function corregir_interno($cod_dtra){
        $docleg=D_tramita::find($cod_dtra);
        if($docleg->dtra_interno=='t'){
            $docleg->dtra_interno='f';
        }else{
            $docleg->dtra_interno='t';
        }
        $docleg->save();
        return redirect('datos tramite legalizacion/'.$docleg->cod_tra);
    }
    public function f_tipo_tramite($cod_tra){
        $tramita=Tramita::find($cod_tra);
        $docleg=D_tramita::all()->where('cod_tra','=',$cod_tra)->first();
        $persona="";
        $datos_per=1;
        if($tramita->id_per!=''){
            $persona=Persona::find($tramita->id_per);
        }else{
            $datos_per=0;
        }
        return view('servicios.tra_legalizacion.f_cambiar_tramite',compact('tramita','docleg','persona','datos_per','cod_tra'));

    }
    public function e_tipo_tramite(Request $form){
        $form->validate([
            'ctra'=>'required',
            'tramite'=>'required',
        ]);
        $tramita=Tramita::find($form['ctra']);
        $docleg=D_tramita::all()->where('cod_tra','=',$form['ctra'])->first();
        if($docleg){
            \Session::flash('error','No se puede modificar el tipo de trámite debido a que tiene documentos asociados');
        }else{
            $tramita->tra_tipo_tramite=$form['tramite'];
            if($form['borrar_datos']=='on'){
                $tramita->id_per=null;
            }
            $tramita->save();
            \Session::flash('exito','Sa ha modificado el tipo de trámite exitosamente');
        }

        return redirect('listar tramite legalizacion/'.$tramita->tra_fecha_solicitud);

    }
    //=====================IMPORTAR LEGALIZCION
    public function fe_importar_legalizacion(){
        return view('servicios.importar.fe_importar_legalizacion');
    }
    public function verificar_importacion_legalizacion(Request $form){
        try {
            if ($form->hasFile('archivo')){
                //$array = Excel::toArray(new importarDB(), $form->file('archivo'));
                $banderaIden = 0;
                $ruta = 'alma/ser/imp/';
                $extension = $form->file('archivo')->getClientOriginalExtension();
                $nombre = '';
                while ($banderaIden == 0) {
                    $nombre = Auth::user()->id . '-' . rand(0, 9999) .'-'.date('d_m_Y'). '- Legalizacion' . $extension;
                    if (!Storage::exists($ruta. $nombre)) {
                        $banderaIden = 1;
                    }
                }
                //\Session::put('cod_tem', $form['ct']);
                $importado = Excel::import(new ImportarLegalizacion(), $form->file('archivo'));
                Storage::putFileAs($ruta, $form->file('archivo'), $nombre);

                \Session::flash('exito', 'Se ha importado con exito el archivo');
                return redirect('listar tramite legalizacion/'.date('Y-m-d'));
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            dd($fallas);
            return "<div class='alert-danger border border-danger rounded p-2'>Error al importar los datos</div>";
        }

    }
    //==================================

    public function valorQR($dia,$mes,$año) {
        $key = '';
        $longitud=22;
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz_-';
        $max = strlen($pattern)-1;
            for($i=0;$i < $longitud;$i++) $key .= $pattern[mt_rand(0,$max)];
        return $dia.$mes.$año.$key;
    }
    public static function estado($codigo){
        $texto="";
        switch ($codigo){
            case 0: $texto="Verificado"; break;
            case 1: $texto="No existe el título"; break;
            case 2: $texto="No existe el PDF del título"; break;
            case 3: $texto="No existe el ANTECEDENTE del título"; break;
            case 4: $texto="Confrontado"; break;
            case 5: $texto="Búsqueda"; break;
            case 6: $texto="Extranjero"; break;
            case 7: $texto="Resolución"; break;
        }
        return $texto;
    }
}
