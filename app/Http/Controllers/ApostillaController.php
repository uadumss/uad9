<?php

namespace App\Http\Controllers;

use App\Imports\ImportarAPO;
use App\Models\Apoderado;
use App\Models\Apostilla;
use App\Models\Detalle_apostilla;
use App\Models\Funciones;

use App\Models\Lista_doc_apostilla;
use App\Models\Objeto;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class ApostillaController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear documento apostilla - apo|editar documento apostilla - apo'], ['only' => ['fe_doc_apostilla','g_doc_apostilla']]);
        $this->middleware(['permission:habilitar documento apostilla - apo'], ['only' => ['hab_doc_apostilla']]);
        $this->middleware(['permission:eliminar documento apostilla - apo'], ['only' => ['f_eli_doc_apostilla','eli_doc_apostilla']]);

        $this->middleware(['permission:crear trámite - apo|editar trámite - apo'], ['only' => ['fe_tramite_apostilla','g_tramite_apostilla','g_apoderado_tramite_apostilla']]);
        $this->middleware(['permission:editar apoderado - apo'], ['only' => ['g_apoderado_tramite_apostilla']]);
        $this->middleware(['permission:eliminar trámite - apo'], ['only' => ['f_eli_tramite_apostilla','eli_tramite_apostilla']]);
        $this->middleware(['permission:firma trámite - apo'], ['only' => ['firmar_tramite_apostilla']]);

        $this->middleware(['permission:agregar documento - apo'],['only'=>['fe_agregar_tramite_apostilla','g_agregar_tramite_apostilla','ajax_tabla_agregar']]);
        $this->middleware(['permission:quitar doumento - apo'],['only'=>['eliminar_tramite_agregado','ajax_tabla_agregar']]);
        $this->middleware(['permission:entregar trámite - apo'],['only'=>['fe_entrega_tramite_apostilla','entrega_tramite_apostilla']]);
        $this->middleware(['permission:importar trámite - apo'],['only'=>['importar_Apostilla']]);

        $this->middleware(['permission:generar pdf - apo'],['only'=>['generar_pdf_apostilla']]);
        $this->middleware(['permission:buscar trámite - apo'],['only'=>['buscar_apostilla','fe_buscar_apostilla','ver_datos_apostilla']]);

        $this->middleware(['permission:ver reportes - apo'],['only'=>['lista_reporte_apostilla','reporte_apostilla']]);

    }
    public function l_doc_apostilla(){
        $tramites=Lista_doc_apostilla::all()->SortBy('lis_nombre');
        return view('apostilla.apostilla.l_apostilla',compact('tramites'));
    }
    public function fe_doc_apostilla($cod_lis){
        $tramite="";
        if($cod_lis!=0){
            $tramite=Lista_doc_apostilla::find($cod_lis);
        }
        return view('apostilla.apostilla.fe_tra_apostilla',compact('cod_lis','tramite'));
    }
    public function g_doc_apostilla(Request $form){
        $form->validate([
            'nombre'=>'required',
            'alias'=>'required',
            'cuenta'=>'required',
            'monto'=>'required',
        ]);

        if(isset($form['cl']) && $form['cl']!=''){
            $apostilla=Lista_doc_apostilla::find($form['cl']);
            $antiguo=json_encode($apostilla);
            $apostilla->lis_nombre=$form['nombre'];
            $apostilla->lis_cuenta=$form['cuenta'];
            $apostilla->lis_monto=$form['monto'];
            $apostilla->lis_resolucion=$form['resolucion'];
            $apostilla->lis_tipo=$form['tipo'];
            $apostilla->lis_desc=$form['desc'];
            $apostilla->lis_alias=$form['alias'];
            $apostilla->save();

            \Session::flash('exito','Se ha editado el trámite exitosamente');

            $nuevo=json_encode($apostilla);
            SessionController::write('U',$antiguo,$nuevo,'lista_doc_apostilla','4',$apostilla->cod_lis);
        }else{
            $apostilla=Lista_doc_apostilla::create([
                'lis_nombre'=>$form['nombre'],
                'lis_alias'=>$form['alias'],
                'lis_cuenta'=>$form['cuenta'],
                'lis_monto'=>$form['monto'],
                'lis_hab'=>'t',
                'lis_resolucion'=>$form['resolucion'],
                'lis_tipo'=>$form['tipo'],
                'lis_desc'=>$form['desc'],
            ]);
            \Session::flash('exito','Se ha creado el trámite exitosamente');
            $nuevo=json_encode($apostilla);
            SessionController::write('C','',$nuevo,'lista_doc_apostilla','4',$apostilla->cod_lis);
        }
        return redirect('listar documentos apostilla');
    }
    public function hab_doc_apostilla($cod_lis){
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        if($apostilla->lis_hab=='t'){
            $apostilla->lis_hab='f';
        }else{
            $apostilla->lis_hab='t';
        }
        $apostilla->save();
        \Session::flash('exito','Se ha modificado el trámite exitosamente');
        return redirect('listar documentos apostilla');
    }
    public function f_eli_doc_apostilla($cod_lis){

        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')->where('cod_lis','=',$cod_lis)->get();
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        $eliminar=true;
        if(sizeof($detalle_apostilla)>0){
            $eliminar=false;
        }
        return view('apostilla.apostilla.f_eli_apostilla',compact('apostilla','eliminar'));
    }
    public function eli_doc_apostilla(Request $form){
        $form->validate([
            'cl'=>'required'
        ]);
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')->where('cod_lis','=',$form['cl'])->get();
        if(sizeof($detalle_apostilla)>0){
            \Session::flash('error','No se puede eliminar el trámite');

        }else{
            $apostilla=Lista_doc_apostilla::find($form['cl']);
            $antiguo=json_encode($apostilla);
            SessionController::write('D',$antiguo,'','lista_doc_apostilla','4',$apostilla->cod_lis);
            $apostilla->delete();
            \Session::flash('exito','El trámite se eliminó correctamente');
        }
        return redirect('listar documentos apostilla');
    }
//====================================================================== TRAMITE DE APOSTILLA
    public function l_tramite_apostilla($fecha){
        $array_fecha=explode('-',$fecha);
        $valido=checkdate($array_fecha[1],$array_fecha[2],$array_fecha[0]);
        if($valido){
            $tramites=DB::table('apostilla.apostilla')
                ->join('public.personas','apostilla.apostilla.id_per','=','public.personas.id_per')
                ->select('personas.*','apostilla.*')
                ->where('apos_fecha_ingreso','=',$fecha)->orderByDesc('apostilla.apos_fecha_ingreso')
                ->orderByDesc('apos_numero')->get();
            return view('apostilla.tramite.l_tramite_apostilla',compact('tramites','fecha','valido'));
        }else{
            \Session::flhas('error','La fecha no es correcta');
            return view('apostilla.tramite.l_tramite_apostilla','fecha','valido');
        }

    }
    public function l_tramite_apostilla_tabla($fecha){
        $tramites=DB::table('apostilla.apostilla')
            ->join('public.personas','apostilla.apostilla.id_per','=','public.personas.id_per')
            ->select('personas.*','apostilla.*')
            ->where('apos_fecha_ingreso','=',$fecha)->orderByDesc('apostilla.apos_fecha_ingreso')
            ->orderByDesc('apos_numero')->get();
        return view('apostilla.tramite.l_tramite_apostilla_tabla',compact('tramites','fecha'));
    }
    public function fe_tramite_apostilla($cod_apos){
        $persona=array();
        $apoderado=array();
        $apostilla=array();
        $detalle_apostilla=array();

        if($cod_apos==0){
            $tramite_apostilla=array();
            return view('apostilla.tramite.fe_tramite_apostilla',compact('tramite_apostilla','apostilla','cod_apos','persona','apoderado','detalle_apostilla'));
        }else{
            $apostilla=Lista_doc_apostilla::where('lis_hab','=','t')->orderBy('lis_nombre')->get();
            $tramite_apostilla=Apostilla::find($cod_apos);
            if($tramite_apostilla){
                $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
                    ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
                    ->where('cod_apos','=',$tramite_apostilla->cod_apos)
                    ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();
                //dd($detalle_apostilla);
                $persona=Persona::find($tramite_apostilla->id_per);
                $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
                return view('apostilla.tramite.fe_tramite_apostilla',compact('apostilla','tramite_apostilla','cod_apos','persona','apoderado','detalle_apostilla'));
            }else{
                \Session::flash('error','Hubo un error en los datos proveidos');
            }
        }

    }
    public function g_tramite_apostilla(Request $form){

        $persona=array();
        $apoderado=array();
        $nuevo="";
        $form->validate([
            'ci'=>'required',
            'nombre'=>'required',
            'apellido'=>'required',
        ]);
        if($form['ci_apoderado']!='' || $form['apellido_apoderado']!='' || $form['nombre_apoderado']!=''){
            $form->validate([
                'ci_apoderado'=>'required',
                'nombre_apoderado'=>'required',
                'apellido_apoderado'=>'required',
                'tipo'=>'required',
            ]);
            $apoderado=Apoderado::where('apo_ci','=',$form['ci_apoderado'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci_apoderado'],
                    'apo_nombre'=>mb_strtoupper($form['nombre_apoderado']),
                    'apo_apellido'=>mb_strtoupper($form['apellido_apoderado']),
                    'apo_sistema'=>4,
                ]);

            }
        }
        $nuevo=$apoderado;
        $persona=Persona::where('per_ci','=',$form['ci'])->first();
        if(!$persona){
            $persona=Persona::create([
                'per_ci'=>substr($form['ci'],0,12),
                'per_nombre'=>mb_strtoupper($form['nombre']),
                'per_apellido'=>mb_strtoupper($form['apellido']),
                'per_celular'=>substr($form['celular'], 0, 8),
                'per_sistema'=>4,
            ]);
        }else{
            $persona->per_celular=$form['celular'];
            $persona->save();
        }
        $nuevo=$persona->toArray();
        if($apoderado){
            $nuevo=(object) array_merge($nuevo,$apoderado->toArray());
        }
        $maximo=DB::select('select max(apos_numero) as max from apostilla.apostilla where apos_gestion='.date('Y'));
        $numero=500000;
       // return $maximo[0];
        if($maximo[0]->max){
            $numero=((int)$maximo[0]->max+1);
        }
        $uuid=(String)Str::uuid();
        $clave=Funciones::alfanumerico(10);

        if(!$apoderado){
            $form['tipo']='';
        }
        $tramite_apostilla=Apostilla::create([
            'cod_apos'=>$uuid,
            'id_per'=>$persona->id_per,
            'apos_numero'=>$numero,
            'apos_clave'=>$clave,
            'apos_fecha_ingreso'=>date('d/m/Y H:i:s'),
            'apos_estado'=>'0',
            'apos_hab'=>'t',
            'apos_apoderado'=>$form['tipo'],
            'apos_gestion'=>date('Y'),
        ]);
        $nuevo=(object) array_merge((Array)$nuevo,$tramite_apostilla->toArray());
        $nuevo=json_encode($nuevo);
        SessionController::write('C','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);

        if($apoderado){
            $tramite_apostilla->cod_apo=$apoderado->cod_apo;
            $tramite_apostilla->save();
        }
        return redirect('editar tramite apostilla/'.$tramite_apostilla->cod_apos);
    }
    public function g_apoderado_tramite_apostilla(Request $form){

            $form->validate([
                'ci_apoderado'=>'required',
                'nombre_apoderado'=>'required',
                'apellido_apoderado'=>'required',
                'tipo'=>'required',
                'ca'=>'required',
            ]);
            $nuevo="";
            $antiguo="";
            $apoderado=Apoderado::where('apo_ci','=',$form['ci_apoderado'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci_apoderado'],
                    'apo_nombre'=>mb_strtoupper($form['nombre_apoderado']),
                    'apo_apellido'=>mb_strtoupper($form['apellido_apoderado']),
                    'apo_sistema'=>4,
                ]);
                $nuevo=$apoderado;
            }
            $tramite_apostilla=Apostilla::find($form['ca']);
            $antiguo=json_encode($tramite_apostilla);

            $tramite_apostilla->cod_apo=$apoderado->cod_apo;
            $tramite_apostilla->apos_apoderado=$form['tipo'];
            $tramite_apostilla->save();
            $nuevo=(Object)array_merge($nuevo->toArray(),$tramite_apostilla->toArray());
            $nuevo=json_encode($nuevo);
            SessionController::write('C',$antiguo,$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);

            return redirect('editar tramite apostilla/'.$tramite_apostilla->cod_apos);
    }
    public function fe_agregar_tramite_apostilla($cod_lis,$cod_apos){
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        $lista_apostilla=Lista_doc_apostilla::where('lis_hab','=','t')->orderBy('lis_nombre')->get();
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=array();
        if($tramite_apostilla->cod_apo!=''){
            $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
        };
        return view('apostilla.tramite.fe_agregar_tramite_apostilla',compact('apostilla','lista_apostilla','tramite_apostilla','apoderado','persona','cod_lis','cod_apos'));
    }
    public function g_agregar_tramite_apostilla(Request $form){
        /*
         * Estado del tramite [apos_estado]
         * 0 -> Creado
         * 1 -> Registrado
         * 2 -> Firmado
         * 3 -> Entregado
         */
        $form->validate([
            'cl'=>'nullable|integer',
            'ca'=>'required',
            'nro_control'=>'required',
            'gestion_valorado'=>'nullable|digits:4',
        ]);
        $codApos=(string)$form['ca'];
        if(!Str::isUuid($codApos)){
            return $this->responderAgregarTramiteApostilla($form,false,'Debe guardar primero el trámite de apostilla antes de agregar documentos.',$codApos);
        }
        $codLisIngresado=(int)($form['cl'] ?? 0);
        $tramite_apostilla=Apostilla::find($form['ca']);
        if(!$tramite_apostilla){
            return $this->responderAgregarTramiteApostilla($form,false,'No se encontró el trámite de apostilla.',$codApos);
        }
        $persona=Persona::find($tramite_apostilla->id_per);
        if(!$persona || trim((string)$persona->per_ci)===''){
            return $this->responderAgregarTramiteApostilla($form,false,'Debe registrar correctamente el CI de la persona del trámite.',$codApos);
        }

        $verificacionRecaudacion=$this->validarRecaudacionApostilla(
            (string)$form['nro_control'],
            (string)$persona->per_ci,
            (int)$tramite_apostilla->id_per,
            0
        );
        if(!$verificacionRecaudacion['ok']){
            return $this->responderAgregarTramiteApostilla($form,false,(string)$verificacionRecaudacion['message'],$codApos);
        }

        $codLisFinal=(int)($verificacionRecaudacion['cod_lis_sugerido'] ?? 0);

        if($codLisFinal<=0){
            return $this->responderAgregarTramiteApostilla($form,false,'Boleta inválida para apostilla: no se pudo detectar automáticamente un trámite habilitado.',$codApos);
        }

        if($codLisIngresado>0 && $codLisIngresado!==$codLisFinal){
            return $this->responderAgregarTramiteApostilla($form,false,'Se detectó una inconsistencia en el trámite enviado. Vuelva a validar el N° de control.',$codApos);
        }

        $apostilla=Lista_doc_apostilla::find($codLisFinal);
        if(!$apostilla){
            return $this->responderAgregarTramiteApostilla($form,false,'No se encontró el tipo de trámite seleccionado.',$codApos);
        }

        $controlIngresado=trim((string)$form['nro_control']);
        if($controlIngresado===''){
            return $this->responderAgregarTramiteApostilla($form,false,'Debe ingresar el número de control del pago.',$codApos);
        }

        $gestion_valorado=$this->extraerGestionDesdeFechaPagoApostilla((string)($verificacionRecaudacion['fecha_pago'] ?? ''));
        if($gestion_valorado===''){
            $gestion_valorado=(string)$form['gestion_valorado'];
        }
        if(trim($gestion_valorado)===''){
            return $this->responderAgregarTramiteApostilla($form,false,'No se pudo determinar la gestión del valorado desde la API de recaudaciones.',$codApos);
        }

        if($tramite_apostilla->apos_estado<=1){
            if($tramite_apostilla->apos_estado==0){
                $tramite_apostilla->apos_estado=1;
                $tramite_apostilla->save();
            }
            $uuid=(String)Str::uuid();
            $maximo=DB::select('select max(dapo_numero) as max from apostilla.detalle_apostilla');
            $numero=1;
            if($maximo[0]->max){
                $numero=((int)$maximo[0]->max+1);
            }

            $documento=Detalle_apostilla::create([
                'cod_dapo'=>$uuid,
                'cod_apos'=>$form['ca'],
                'cod_lis'=>$codLisFinal,
                'dapo_fecha_ingreso'=>date('d/m/Y'),
                'dapo_hab'=>'t',
                'dapo_numero'=>$numero,
            ]);

            if(isset($form['numero'])){
                $documento->dapo_numero_documento=$form['numero'];
            }
            if(isset($form['gestion'])){
                $documento->dapo_gestion_documento=$form['gestion'];
            }

            $documento->dapo_valorado_preimpreso=$controlIngresado;
            $documento->dapo_valorado_gestion=$gestion_valorado;

            // lis_tipo ya contiene el valor buscar_en (da, db, tpos, etc.) configurado al crear el tipo de apostilla
            $buscarEnSitra=trim((string)($apostilla->lis_tipo ?? ''));
            $documento->dapo_buscar_en=$buscarEnSitra;

            // Verificar en SITRA si corresponde (igual que en servicios)
            $verificarSitra='';
            $numeroDocumento=trim((string)($form['numero'] ?? ''));
            $debeVerificarSitra=($buscarEnSitra!=='' && $buscarEnSitra!=='sid' && $buscarEnSitra!=='res' && $numeroDocumento!=='' && $numeroDocumento!=='-');
            if($debeVerificarSitra){
                $verificarSitra='2'; // por defecto: no existe
                try{
                    $respuestaSitra=$this->verificarSitraApostilla((string)$persona->per_ci,$numeroDocumento,$buscarEnSitra);
                    $nombreSitra=trim((string)($respuestaSitra->nombre ?? ''));
                    $tipoSitra=strtolower(trim((string)($respuestaSitra->tipo ?? '')));
                    $numeroSitra=trim((string)($respuestaSitra->numero ?? ''));
                    $nombreLocal=mb_strtoupper(trim((string)(($persona->per_apellido ?? '').' '.($persona->per_nombre ?? ''))));
                    $tipoLocal=strtolower(trim((string)Funciones::DocumentoSitra($buscarEnSitra)));
                    $numeroLocal=trim((string)$numeroDocumento);
                    if($nombreSitra!=='' || $tipoSitra!=='' || $numeroSitra!==''){
                        $coincideNombre=(bool)preg_match('/'.preg_quote(explode(' ',$nombreSitra)[0] ?? '','/').'/i',$nombreLocal);
                        if($coincideNombre && $tipoLocal===$tipoSitra && $numeroLocal===$numeroSitra){
                            $verificarSitra='0'; // coincide
                        }else{
                            $verificarSitra='1'; // no coincide
                        }
                    }
                }catch(\Throwable $e){
                    $verificarSitra='2';
                }
            }
            $documento->dapo_verificacion_sitra=$verificarSitra;
            $documento->save();

            $errorUso='';
            if(!$this->registrarUsoRecaudacionApostilla($verificacionRecaudacion,(int)$tramite_apostilla->apos_numero,(int)$documento->dapo_numero,$errorUso)){
                $documento->delete();
                $detallesRestantes=Detalle_apostilla::where('cod_apos','=',$tramite_apostilla->cod_apos)->first();
                if(!$detallesRestantes){
                    $tramite_apostilla->apos_estado=0;
                    $tramite_apostilla->save();
                }
                return $this->responderAgregarTramiteApostilla($form,false,$errorUso,$codApos);
            }

            $nuevo=json_encode($documento);
            SessionController::write('C','',$nuevo,'detalle_apostilla','4',$documento->cod_dapo);

            return $this->responderAgregarTramiteApostilla($form,true,'Se ha agregado el trámite correctamente',$codApos);
        }else{
            return $this->responderAgregarTramiteApostilla($form,false,'No se puede agregar más documentos',$codApos);
        }
    }

    public function verificacion_sitra_apostilla(string $cod_dapo)
    {
        $docleg=Detalle_apostilla::find($cod_dapo);
        if(!$docleg){
            return response()->json(['error'=>'Documento no encontrado'],404);
        }
        $tramiteApostilla=Apostilla::find($docleg->cod_apos);
        $persona=$tramiteApostilla ? Persona::find($tramiteApostilla->id_per) : null;
        $apostilla=Lista_doc_apostilla::find($docleg->cod_lis);

        $buscarEn=trim((string)($docleg->dapo_buscar_en ?? ''));
        $buscarEnNombre=Funciones::nombre_titulo($buscarEn);
        $respuesta=(object)[];
        $fuente='sitra';

        if($buscarEn!=='' && $buscarEn!=='sid' && $buscarEn!=='res'){
            try{
                $respuesta=$this->verificarSitraApostilla(
                    (string)($persona->per_ci ?? ''),
                    (string)($docleg->dapo_numero_documento ?? ''),
                    $buscarEn
                );
            }catch(\Throwable $e){
                $respuesta=(object)[];
            }
            if(!is_object($respuesta)){
                $respuesta=(object)[];
            }
        }

        return view('apostilla.tramite.verificacion_sitra_apostilla',compact('docleg','persona','apostilla','respuesta','fuente','buscarEnNombre'));
    }

    private function verificarSitraApostilla(string $ci, string $numero, string $buscarEn): object
    {
        $documento=Funciones::DocumentoSitra($buscarEn);
        $ruta='http://sitra.umss.net/consulta/api/ci/'.$ci.'/numero/'.$numero.'/tipo/'.$documento;
        $data=json_decode(file_get_contents($ruta));
        return is_object($data) ? $data : (object)[];
    }

    private function responderAgregarTramiteApostilla(Request $request, bool $ok, string $message, string $codApos)
    {
        if($request->ajax() || $request->expectsJson()){
            return response()->json([
                'ok'=>$ok,
                'message'=>$message,
                'cod_apos'=>$codApos,
            ],$ok ? 200 : 422);
        }

        if($ok){
            \Session::flash('exitoagregar',$message);
        }else{
            \Session::flash('erroragregar',$message);
        }

        return redirect('ajax tabla agregar/'.$codApos);
    }

    public function validar_valorado_recaudaciones_apostilla(Request $request, $cod_apos)
    {
        $data=$request->validate([
            'nro_control'=>['required','integer'],
            'ca'=>['nullable','string'],
        ]);

        $codApos=trim((string)($data['ca'] ?? ''));
        if($codApos===''){
            $codApos=trim((string)$cod_apos);
        }

        if(!Str::isUuid($codApos)){
            return response()->json([
                'ok'=>false,
                'message'=>'Debe guardar primero el trámite de apostilla antes de validar el pago.',
            ],422);
        }

        $tramiteApostilla=Apostilla::find($codApos);
        if(!$tramiteApostilla || !$tramiteApostilla->id_per){
            return response()->json([
                'ok'=>false,
                'message'=>'Debe registrar primero los datos personales del trámite.',
            ],422);
        }

        $persona=Persona::find($tramiteApostilla->id_per);
        if(!$persona || trim((string)$persona->per_ci)===''){
            return response()->json([
                'ok'=>false,
                'message'=>'El CI no es válido para consultar.',
            ],422);
        }

        $validacion=$this->validarRecaudacionApostilla(
            (string)$data['nro_control'],
            (string)$persona->per_ci,
            (int)$tramiteApostilla->id_per,
            0
        );

        if(!$validacion['ok']){
            return response()->json($validacion,422);
        }
        $respuesta=$validacion;
        unset($respuesta['ci']);
        return response()->json($respuesta);
    }

    public function ajax_tabla_agregar($cod_apos){
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$cod_apos)->orderBy('lis_nombre')
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();
        $tramite_apostilla=Apostilla::find($cod_apos);
        $fecha=date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso));
        return view('apostilla.tramite.fe_tramite_apostilla_tabla',compact('detalle_apostilla','cod_apos','fecha','tramite_apostilla'));
    }
    public function eliminar_tramite_agregado($cod_dapo){
        $detalle_apostilla=Detalle_apostilla::find($cod_dapo);
        $cod_apos=0;
        if($detalle_apostilla){
            $cod_apos=$detalle_apostilla->cod_apos;

            $antiguo=json_encode($detalle_apostilla);
            SessionController::write('D',$antiguo,'','detalle_apostilla','4',$detalle_apostilla->cod_dapo);

            $detalle_apostilla->delete();
            $detalles=Detalle_apostilla::where('cod_apos','=',$cod_apos)->first();
            if(!$detalles){
                $tramite_Apostilla=Apostilla::find($cod_apos);
                $tramite_Apostilla->apos_estado=0;
                $tramite_Apostilla->save();
            }
            \Session::flash('exito_agregar','Se ha eliminado correctamente el tramite');
        }else{
            \Session::flash('error_agregar','No se puede eliminar el documento seleccionado');
        }
        return redirect('ajax tabla agregar/'.$cod_apos);
    }
    public function generar_pdf_apostilla($cod_apos){

        $tramite_apostilla=Apostilla::find($cod_apos);
        if($tramite_apostilla->apos_qr==''){
            $tramite_apostilla->apos_qr=Funciones::valorQR(date('d'),date('m'),date('Y'),5);
            //$qr_generado='http://www.archivos.umss.edu.bo/verificar_apostilla/index.php?q='.$qr;
            $tramite_apostilla->save();
        }
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=array();
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$tramite_apostilla->cod_apos)->orderBy('lis_nombre')
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();

        if($tramite_apostilla->cod_apos!=''){
            $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('letter');
        $pdf->loadView('apostilla.tramite.tramites_vista_PDF',compact('tramite_apostilla','persona','apoderado','detalle_apostilla'));
        //return "entro";
        return $pdf->download('Tramite '.$tramite_apostilla->apos_numero.'.pdf');

    }
    public function f_eli_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $detalle_apostilla=Detalle_apostilla::where('cod_apos','=',$cod_apos)->first();
        $eliminar=true;
        if($detalle_apostilla){
            $eliminar=false;
        }
        return view('apostilla.tramite.f_eli_tramite_apostilla',compact('tramite_apostilla','persona','eliminar'));
    }
    public function eli_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required'
        ]);
        $tramite_apostilla=Apostilla::find($form['ca']);
        $detalle_apostilla=Detalle_apostilla::where('cod_apos','=',$form['ca'])->first();
        $fecha=date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso));
        if($detalle_apostilla){
            \Session::flash('error','No se puede eliminar el trámite de apostilla');
        }else{
            \Session::flash('exito','Se ha eliminado correctamente el trámite');
            $antiguo=json_encode($tramite_apostilla);
            SessionController::write('D',$antiguo,'','apostilla','4',$tramite_apostilla->cod_apos);
            $tramite_apostilla->delete();
        }
        return redirect('listar tramite apostilla/'.$fecha);
    }
    public function firmar_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $tramite_apostilla->apos_estado=2;
        $tramite_apostilla->apos_fecha_firma=date('d/m/Y');
        $tramite_apostilla->save();
        $nuevo=json_encode($tramite_apostilla);
        SessionController::write('U','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);
        return redirect('listar tramite apostilla/'.$tramite_apostilla->apos_fecha_ingreso);
    }
    public function fe_entrega_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=Apoderado::find($tramite_apostilla->cod_apo);

        return view('apostilla.tramite.fe_entrega_tramite_apostilla',compact('tramite_apostilla','persona','apoderado'));
    }
    public function entrega_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required',
        ]);
        /*
         * apos_entregaddo-> a quien entrega
         * A -> Apoderado
         * T -> Titular
         */
        $tramite_apostilla=Apostilla::find($form['ca']);
        if(isset($form['apo'])){
            $tramite_apostilla->apos_fecha_recojo=date('d/m/Y');
            $tramite_apostilla->apos_estado=3;
            if($form['apo']=='A'){
                $tramite_apostilla->apos_entregado='A';
            }else{
                $tramite_apostilla->apos_entregado='T';
            }
            $tramite_apostilla->save();
            $nuevo=json_encode($tramite_apostilla);
            SessionController::write('U','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);
        }else{

        }
        return redirect('listar tramite apostilla/'.date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)));
    }
    public function importar_apostilla(Request $form){
        try {
            if ($form->hasFile('archivo')) {
                //$array = Excel::toArray(new importarDB(), $form->file('archivo'));
                $importado = Excel::import(new ImportarAPO(), $form->file('archivo'));
                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                return redirect('listar tramite apostilla/'.date('Y-m-d'));
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            return view('importacion.resultado_importacion', compact('fallas'));
        }
    }
    public function fe_buscar_apostilla(){
        return view('apostilla.buscar.fe_busqueda');
    }
    public function buscar_apostilla(Request $form)
    {
        $parametro = 0;
        $resultado=array();
        $consulta = "select * from apostilla.apostilla a join personas p on p.id_per=a.id_per where ";

        if ($form['numero'] != '') {
            $parametro = 1;
            if ($form['gestion'] != '') {
                $consulta .= " (a.apos_numero=" . $form['numero'] . " and a.apos_gestion=" . $form['gestion'] . ")";
            } else {
                $consulta .= " a.apos_numero=" . $form['numero'];
            }
        }
        if ($form['ci'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_ci='" . $form['ci'] . "'";
            } else {
                $parametro = 1;

                $consulta .= " p.per_ci='" . $form['ci'] . "'";
            }
        }
        if ($form['nombre'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_nombre like'%" . mb_strtoupper($form['nombre']) . "%'";
            } else {
                $parametro = 1;
                $consulta .= " p.per_nombre like'%" . mb_strtoupper($form['nombre']) . "%'";
            }
        }
        if ($form['apellido'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_apellido like '%" . mb_strtoupper($form['apellido']) . "%'";
            } else {
                $parametro = 1;
                $consulta .= " p.per_apellido like '%" . mb_strtoupper($form['apellido']) . "%'";
            }
        }

            $consulta .= " order by a.apos_fecha_ingreso ";

        if ($parametro == 1)
        {
            $resultado = DB::select($consulta);
        }
        $busqueda=$consulta;
        SessionController::write('B','',$busqueda,'apostilla','4','');
        return view('apostilla.buscar.resultado_busqueda',compact('resultado'));
    }
    public function ver_datos_apostilla($cod_apos){
        $apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($apostilla->id_per);
        $apoderado=array();
        if($apostilla->cod_apo!=''){
            $apoderado=Apoderado::find($apostilla->cod_apo);
        }
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$cod_apos)
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();

        return view('apostilla.buscar.detalle_busqueda_apostilla',compact('apostilla','persona','apoderado','detalle_apostilla'));
    }
    public function mostrar_observacion_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        return view('apostilla.tramite.fe_observacion',compact('tramite_apostilla','persona'));
    }
    public function g_observacion_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required',
        ]);
        $tramite_apostilla=Apostilla::find($form['ca']);
        $tramite_apostilla->apos_obs=$form['observacion'];
        $tramite_apostilla->save();
        return $tramite_apostilla->apos_obs;
    }
    //============================REPORTES
    public function lista_reporte_apostilla(){
        $lista=Lista_doc_apostilla::where('lis_hab','=','t')->get();
        $consulta="select lis_alias as nombre, count(cod_dapo) as cantidad from apostilla.lista_doc_apostilla l join apostilla.detalle_apostilla d on l.cod_lis=d.cod_lis group by nombre;";
        $resultado=DB::select($consulta);
        return view('apostilla.reporte.reporte_apostilla',compact('resultado','lista'));
    }
    public function reporte_apostilla(Request $form){

        $mensaje="";
        $fecha=$this->construirFecha($form['dia'],$form['mes'],$form['gestion'],0);
        $fecha_final=$this->construirFecha($form['dia_final'],$form['mes_final'],$form['gestion_final'],1);
        $documento=$form['documento'];
        $resultado=array();
        $consulta="";

        if($fecha==1 || $fecha_final==1){
            $mensaje="Error en las fechas";
        }else{

            if($fecha==0){
                if($form['documento']==''){
                    $consulta="select lis_nombre as nombre, count(cod_dapo) as cantidad
                                from apostilla.lista_doc_apostilla l join apostilla.detalle_apostilla d on l.cod_lis=d.cod_lis
                                group by lis_nombre;";
                }else{
                    if($form['documento']=='tramites'){
                        $consulta="select apos_gestion as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a group by apos_gestion
                                    order by apos_gestion";
                    }else{
                        $consulta="select EXTRACT(YEAR FROM a.dapo_fecha_ingreso) as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  a.cod_lis=".$form['documento']."
                                    group by nombre order by nombre";
                    }
                }

            }else{
                if($fecha_final==0){
                    if($form['documento']=='' || $form['documento']!='tramites'){
                        $aux_condicion=($form['documento']!='')?" a.cod_lis=".$form['documento']." and ":"";

                        if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                            $consulta="select lis_alias as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso='".$fecha."'
                                    group by nombre order by cantidad;";
                        }else{

                            if($form['mes']!='' && $form['gestion']!=''){

                                $month     = $form['gestion'].'-'.$form['mes'];
                                $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));

                                $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                $consulta="select dapo_fecha_ingreso as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre;";

                            }else{

                                $consulta="select to_char(dapo_fecha_ingreso, 'Month') as nombre,EXTRACT(MONTH from dapo_fecha_ingreso) as alias, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='01/01/".$form['gestion']."' and dapo_fecha_ingreso<='31/12/".$form['gestion']."'
                                    group by alias,nombre order by alias;";
                            }
                        }

                    }else{

                        if($form['documento']=='tramites'){
                            if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){

                                $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso='".$fecha."'
                                    group by nombre;";
                            }else{

                                if($form['mes']!='' && $form['gestion']!=''){
                                    $month     = $form['gestion'].'-'.$form['mes'];
                                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                    $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                    $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre";

                                }else{
                                    if($form['gestion']!=''){
                                        $consulta="select extract(MONTH from apos_fecha_ingreso) as mes, to_char(apos_fecha_ingreso,'Month') as nombre, count(cod_apos) as cantidad
                                                   from apostilla.apostilla a
                                                    where  apos_gestion='".$form['gestion']."'
                                                    group by mes, nombre order by mes";
                                    }
                                }
                            }
                        }
                    }

                }else{
                    if($form['documento']=='' || $form['documento']!='tramites'){
                        $aux_condicion=($form['documento']!='')?" a.cod_lis=".$form['documento']." and ":"";

                        if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                            if($form['dia_final']!='' && $form['mes_final']!='' && $form['gestion_final']!=''){
                                    $fechafinal=$form['dia_final'].'/'.$form['mes_final'].'/'.$form['gestion_final'];
                                    $consulta="select dapo_fecha_ingreso as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre;";
                            }else{
                                $mensaje='Error en la fecha final';
                            }
                        }else{
                            if($form['mes']!='' && $form['gestion']!=''){
                                if($form['mes_final']!='' && $form['gestion_final']!=''){

                                    $month     = $form['gestion_final'].'-'.$form['mes_final'];
                                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                    $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                    $consulta="select (EXTRACT(YEAR from dapo_fecha_ingreso) ||'-'|| to_char(dapo_fecha_ingreso, 'Month')) as nombre,
                                                        EXTRACT(MONTH from dapo_fecha_ingreso) as mes,EXTRACT(YEAR from dapo_fecha_ingreso) as gestion,
                                                            count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by gestion,mes,nombre order by gestion,mes,nombre;";
                                }else{
                                    $mensaje='Error en la fecha final';
                                }
                            }else{
                                if($form['gestion']!=''){
                                    if($form['gestion_final']){
                                        $consulta="select EXTRACT(YEAR from dapo_fecha_ingreso) as nombre, count(cod_dapo) as cantidad
                                        from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                        where  ".$aux_condicion." dapo_fecha_ingreso>='01/01/".$form['gestion']."' and dapo_fecha_ingreso<='31/12/".$form['gestion_final']."'
                                        group by nombre order by nombre;";
                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }
                                }else{

                                }
                            }
                        }

                    }else{

                        if($form['documento']=='tramites'){

                            if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                                if($form['dia_final']!='' && $form['mes_final']!='' && $form['gestion_final']!=''){
                                    $fechafinal=$form['dia_final'].'/'.$form['mes_final'].'/'.$form['gestion_final'];
                                    $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre";
                                }else{
                                    $mensaje='Error en la fecha final';
                                }

                            }else{
                                if($form['mes']!='' && $form['gestion']!=''){
                                    if($form['mes_final']!='' && $form['gestion_final']!=''){
                                        $month     = $form['gestion_final'].'-'.$form['mes_final'];
                                        $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                        $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                        $consulta="select (apos_gestion ||'-'||to_char(apos_fecha_ingreso, 'Month'))  as nombre, apos_gestion as gestion,
                                                            EXTRACT(MONTH from apos_fecha_ingreso) as mes,count(cod_apos) as cantidad
                                                    from apostilla.apostilla a
                                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                                    group by gestion, mes,nombre  order by gestion,mes";

                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }

                                }else{
                                    if($form['gestion']!=''){
                                        if($form['gestion_final']!=''){
                                            $consulta="select apos_gestion as nombre,count(cod_apos) as cantidad
                                                    from apostilla.apostilla a
                                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='31/12/".$form['gestion_final']."'
                                                    group by nombre  order by nombre";
                                        }else{
                                            $mensaje='Error en la fecha final';
                                        }
                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if($consulta!=''){
            $resultado=DB::select($consulta);
        }else{
            \Session::flash('error',$mensaje.", No se puede generar el reporte");
        }
        if($form['pdf']=='on'){

            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('letter');
            $pdf->loadView('apostilla.reporte.reporte_apostilla_PDF',compact('resultado','fecha','fecha_final','documento','mensaje','form'));
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf ->get_canvas();
            $canvas->page_text(495, 96, 'Página {PAGE_NUM} de {PAGE_COUNT}', null, 8, array(0, 0, 0));
            return $pdf->download('Reporte.pdf');

        }else{
            return view('apostilla.reporte.panel_estadistico_apostilla',compact('resultado','fecha','fecha_final','documento','mensaje','form'));
        }

    }

    private function validarRecaudacionApostilla(
        string $nroControl,
        string $ci,
        int $idPer,
        int $codLis
    ): array
    {
        $ciSistemaRaw=trim($ci);
        $ciConsulta=$ciSistemaRaw;

        $consultaRecaudacion=$this->consultarRecaudacionDesdeEndpointExistente((int)$nroControl,$ciConsulta);
        if(!$consultaRecaudacion['ok']){
            return $consultaRecaudacion;
        }
        $lista=$consultaRecaudacion['lista'];

        $persona = Persona::find($idPer);
        $nombreSistemaNormalizado='';
        if($persona){
            $nombreSistemaNormalizado=$this->normalizarTexto(($persona->per_apellido ?? '').' '.($persona->per_nombre ?? ''));
        }

        $tramiteSeleccionado = Lista_doc_apostilla::find($codLis);
        $usoEncontrado=null;
        $mensajeCuentaInvalida='';
        $detalleCi='';
        $detalleNombre='';
        $hayDatosPersonaRecaudacion=false;
        $hayNombreRecaudacion=false;

        foreach($lista as $fila){
            $ciFila=trim((string)($fila['documento'] ?? ''));
            $nombreR=trim(($fila['apellido_1'] ?? '').' '.($fila['apellido_2'] ?? '').' '.($fila['nombre_1'] ?? '').' '.($fila['nombre_2'] ?? ''));

            if($ciFila!=='' || $nombreR!==''){
                $hayDatosPersonaRecaudacion=true;
            }
            if($nombreR!==''){
                $hayNombreRecaudacion=true;
            }

            if($ciFila===''){
                continue;
            }

            if($ciFila!==$ciSistemaRaw){
                if($detalleCi===''){
                    $detalleCi='(Recaudación: '.$ciFila.' | Trámite: '.$ciSistemaRaw.')';
                }
                continue;
            }

            $nombreRecaudacionNormalizado=$this->normalizarTexto($nombreR);
            if($nombreR!=='' && $nombreSistemaNormalizado!=='' && $nombreSistemaNormalizado!==$nombreRecaudacionNormalizado){
                if($detalleNombre===''){
                    $detalleNombre='(Recaudación: '.$nombreR.' | Datos: '.$nombreSistemaNormalizado.')';
                }
                continue;
            }

            $codigoCuenta=(string)($fila['codigo_cuenta'] ?? '');
            $nombreCuentaRecaudaciones = (string)($fila['cuenta'] ?? '');
            
            $tramiteSugerido=$this->buscarTramiteApostillaPorCuenta($codigoCuenta, $nombreCuentaRecaudaciones);
            if(!$tramiteSugerido){
                $mensajeCuentaInvalida='La cuenta del valorado no corresponde al tipo de trámite actual.';
                continue;
            }

            if($tramiteSeleccionado && (int)$tramiteSeleccionado->cod_lis!==(int)$tramiteSugerido->cod_lis){
                $mensajeCuentaInvalida='El pago corresponde al trámite "'.trim((string)$tramiteSugerido->lis_alias).'" y no al trámite seleccionado.';
                continue;
            }

            $preimpresoApi=$this->valorPreimpresoFila((array)$fila);
            $fechaPago=(string)($fila['fecha'] ?? '');

            $usoCombinacion=$this->buscarUsoPagoPorCombinacionApostilla(
                $nombreR,
                $ciSistemaRaw,
                (string)$nroControl,
                (string)$preimpresoApi,
                $fechaPago
            );
            if($usoCombinacion){
                $usoEncontrado=$usoCombinacion;
                continue;
            }

            return [
                'ok' => true,
                'nro_control' => $nroControl,
                'ci' => $ciSistemaRaw,
                'nombre_recaudaciones' => $nombreR,
                'identificador' => $fila['identificador'] ?? '',
                'fecha_pago' => $fechaPago,
                'cajero' => $fila['cajero'] ?? '',
                'codigo_cuenta' => $codigoCuenta,
                'cuenta' => $fila['cuenta'] ?? '',
                'monto' => $fila['total'] ?? '',
                'control' => (string)$nroControl,
                'preimpreso' => (string)$preimpresoApi,
                'cod_lis_sugerido'=>(int)$tramiteSugerido->cod_lis,
                'lis_nombre_sugerido'=>(string)($tramiteSugerido->lis_nombre ?? ''),
                'lis_alias_sugerido'=>(string)($tramiteSugerido->lis_alias ?? ''),
                'lis_tipo_sugerido'=>(string)($tramiteSugerido->lis_tipo ?? ''),
                'documento_label_sugerido'=>$this->etiquetaDocumentoApostilla($tramiteSugerido),
            ];
        }

        if($usoEncontrado){
            return $this->respuestaErrorValidacionApostilla(
                'BOLETA_YA_USADA',
                $this->mensajePagoYaUsadoApostilla($usoEncontrado)
            );
        }
        if($mensajeCuentaInvalida!==''){
            return $this->respuestaErrorValidacionApostilla(
                'BOLETA_NO_CORRESPONDE_TRAMITE',
                $mensajeCuentaInvalida
            );
        }
        if($detalleNombre!=='' && $hayNombreRecaudacion){
            return $this->respuestaErrorValidacionApostilla(
                'BOLETA_NO_PERTENECE_PERSONA',
                'La boleta no corresponde a los datos de la persona del tramite.',
                ['detalle'=>$detalleNombre]
            );
        }
        if($detalleCi!=='' && $hayDatosPersonaRecaudacion){
            return $this->respuestaErrorValidacionApostilla(
                'BOLETA_NO_PERTENECE_PERSONA',
                'La boleta no pertenece a la persona del tramite.',
                ['detalle'=>$detalleCi]
            );
        }

        return $this->respuestaErrorValidacionApostilla(
            'BOLETA_NO_VALIDA',
            'Ingrese un numero de control valido.'
        );
    }

    private function consultarRecaudacionDesdeEndpointExistente(int $recibo, string $documento): array
    {
        try{
            $request=Request::create('/api/recaudaciones/buscar-control-documento','POST',[
                'unidad'=>122,
                'recibo'=>$recibo,
                'documento'=>$documento,
            ]);

            $response=app(RecaudacionesController::class)->buscarPorControlYDocumento($request);
        }catch(\Throwable $e){
            return $this->respuestaErrorValidacionApostilla(
                'API_NO_DISPONIBLE',
                'No se pudo conectar con recaudaciones. Intente nuevamente en unos minutos.'
            );
        }

        if(!($response instanceof \Illuminate\Http\JsonResponse)){
            return $this->respuestaErrorValidacionApostilla(
                'API_RESPUESTA_INVALIDA',
                'No se pudo validar la boleta en recaudaciones. Intente nuevamente.'
            );
        }

        $status=(int)$response->getStatusCode();
        $json=$response->getData(true);
        if(!is_array($json) || !($json['ok'] ?? false)){
            $msg='';
            $statusApi=0;
            if(is_array($json)){
                $msg=(string)($json['error']['message'] ?? '');
                if(trim($msg)===''){
                    $msg=(string)($json['message'] ?? '');
                }
                $statusApi=(int)($json['status'] ?? 0);
            }
            $statusFinal=$statusApi>0 ? $statusApi : $status;
            $errMap=$this->mapearMensajeErrorRecaudacionApostilla($msg,$statusFinal);
            return $this->respuestaErrorValidacionApostilla($errMap['code'],$errMap['message']);
        }

        $data=(array)($json['data'] ?? []);
        $lista=$data['data']['result'] ?? [];
        if(sizeof($lista)===0){
            $lista=$data['result'] ?? [];
        }

        if(!is_array($lista) || sizeof($lista)==0){
            return $this->respuestaErrorValidacionApostilla(
                'BOLETA_NO_EXISTE',
                'Boleta no valida o no existe. Revise el numero de control.'
            );
        }

        return [
            'ok'=>true,
            'lista'=>$lista,
        ];
    }

    private function respuestaErrorValidacionApostilla(string $code, string $message, array $extra=[]): array
    {
        return array_merge([
            'ok'=>false,
            'code'=>$code,
            'message'=>$message,
        ],$extra);
    }

    private function mapearMensajeErrorRecaudacionApostilla(string $mensajeApi,int $status=0): array
    {
        $mensajeApi=trim($mensajeApi);
        $msgNorm=mb_strtolower($mensajeApi);

        if($status===429 || strpos($msgNorm,'too many')!==false || strpos($msgNorm,'demasiadas solicitudes')!==false || strpos($msgNorm,'rate limit')!==false){
            return [
                'code'=>'RATE_LIMIT',
                'message'=>'Demasiadas solicitudes a recaudaciones. Intente nuevamente en unos segundos.',
            ];
        }

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
            $status===404 ||
            strpos($msgNorm,'not found')!==false ||
            strpos($msgNorm,'no se encuentra')!==false ||
            strpos($msgNorm,'no encontrado')!==false ||
            strpos($msgNorm,'control')!==false ||
            strpos($msgNorm,'recibo')!==false ||
            strpos($msgNorm,'valido')!==false ||
            strpos($msgNorm,'válido')!==false
        ){
            return [
                'code'=>'BOLETA_NO_EXISTE',
                'message'=>'Ingrese un numero de control valido.',
            ];
        }

        if($status>0 && $status<500){
            return [
                'code'=>'API_RECAUDACIONES_ERROR',
                'message'=>'No se pudo validar el control en recaudaciones. Verifique los datos e intente nuevamente.',
            ];
        }

        if(
            strpos($msgNorm,'comunicacion')!==false ||
            strpos($msgNorm,'comunicación')!==false ||
            strpos($msgNorm,'error en la comunicacion con la api de recaudaciones')!==false ||
            strpos($msgNorm,'error inesperado en recaudaciones')!==false ||
            strpos($msgNorm,'sin conexion')!==false ||
            strpos($msgNorm,'sin conexión')!==false ||
            strpos($msgNorm,'timeout')!==false
        ){
            return [
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'No se pudo conectar con recaudaciones. Intente nuevamente en unos minutos.',
            ];
        }

        if(strpos($msgNorm,'documento')!==false || strpos($msgNorm,'ci')!==false || strpos($msgNorm,'identidad')!==false){
            return [
                'code'=>'BOLETA_NO_PERTENECE_PERSONA',
                'message'=>'La boleta no pertenece a la persona del tramite.',
            ];
        }

        if(strpos($msgNorm,'cuenta')!==false || strpos($msgNorm,'tramite')!==false || strpos($msgNorm,'trámite')!==false){
            return [
                'code'=>'BOLETA_NO_CORRESPONDE_TRAMITE',
                'message'=>'La boleta no corresponde al tramite de apostilla seleccionado.',
            ];
        }

        return [
            'code'=>'BOLETA_NO_VALIDA',
            'message'=>'Boleta no valida. Verifique los datos e intente nuevamente.',
        ];
    }

    private function buscarTramiteApostillaPorCuenta(string $codigoCuenta, string $nombreCuenta = ''): ?Lista_doc_apostilla
    {
        $cuentaPago=$this->normalizarNumero($codigoCuenta);
        if($cuentaPago===''){
            return null;
        }

        $lista=Lista_doc_apostilla::where('lis_hab','=','t')->get();
        $coincidencias = [];
        
        foreach($lista as $item){
            $cuentaItem=$this->normalizarNumero((string)($item->lis_cuenta ?? ''));
            if($cuentaItem!=='' && $cuentaItem===$cuentaPago){
                $coincidencias[] = $item;
            }
        }

        if(count($coincidencias) === 1){
            return $coincidencias[0];
        } elseif(count($coincidencias) > 1 && $nombreCuenta !== '') {
            $nombreCuenta = strtoupper(trim($nombreCuenta));
            $mejorCoincidencia = null;
            $mayorSimilitud = -1;

            foreach($coincidencias as $item){
                $nombreTramite = strtoupper(trim($item->lis_nombre));
                similar_text($nombreCuenta, $nombreTramite, $porcentaje);

                if($porcentaje > $mayorSimilitud){
                    $mayorSimilitud = $porcentaje;
                    $mejorCoincidencia = $item;
                }
            }
            return $mejorCoincidencia;
        }

        return null;
    }

    private function etiquetaDocumentoApostilla(Lista_doc_apostilla $tramite): string
    {
        $tipo=strtolower(trim((string)($tramite->lis_tipo ?? '')));
        if($tipo==='sid'){
            return 'N° trámite';
        }

        if(trim((string)($tramite->lis_resolucion ?? ''))!==''){
            return 'N° resolución';
        }

        return 'N° título';
    }

    private function registrarUsoRecaudacionApostilla(array $validacion, int $codTra, int $codDtra, string &$error): bool
    {
        $error='';
        if(!Schema::hasTable('recaudacion_usos')){
            $error='No se puede continuar: falta la tabla de bloqueo de pagos (migración pendiente).';
            Log::critical('Bloqueo de recaudación deshabilitado por migración faltante en apostilla.',[
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

        $usoCombinacion=$this->buscarUsoPagoPorCombinacionApostilla(
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
            Log::error('Error al registrar uso de recaudación en apostilla.',[
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
                'identificador'=>$identificador,
                'error'=>$e->getMessage(),
            ]);
            return false;
        }

        return true;
    }

    private function buscarUsoPagoPorCombinacionApostilla(string $nombrePersona, string $documento, string $recibo, string $preimpreso, string $fechaPago)
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

    private function mensajePagoYaUsadoApostilla(object $usoPago): string
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

        return $mensaje;
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

    private function extraerGestionDesdeFechaPagoApostilla(string $fechaPago): string
    {
        $fechaPago=trim($fechaPago);
        if($fechaPago===''){
            return '';
        }

        if(preg_match('/(19|20)\d{2}/',$fechaPago,$m)){
            return (string)$m[0];
        }

        return '';
    }

    public function construirFecha($dia,$mes,$gestion,$final){
        /*
         * return 0 -> sin fecha
         *        1 -> error en la fecha
         */
        $fecha='';
        if($final==0){
            if($dia!='' && $mes!='' && $gestion!=''){
                $fecha=$dia."/".$mes."/".$gestion;
            }else{
                if($mes!='' && $gestion!=''){
                    $dia=1;
                    $fecha='01/'.$mes.'/'.$gestion;

                }else{
                    if($gestion!=''){

                        $dia=1;
                        $mes=1;
                        $fecha='01/01/'.$gestion;

                    }else{
                        return "0";
                    }
                }
            }
        }else{
            if($dia!='' && $mes!='' && $gestion!=''){
                $fecha=$dia."/".$mes."/".$gestion;
            }else{
                if($mes!='' && $gestion!=''){
                    $month     = $gestion.'-'.$mes;
                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                    $fecha = date('d/m/Y', strtotime("{$aux} - 1 day"));
                    $mes=date('m',strtotime($fecha));
                    $dia=date('j',strtotime($fecha));
                }else{
                    if($gestion!=''){
                        $dia=31;
                        $mes=12;
                        $fecha='31/12/'.$gestion;
                    }else{
                        return "0";
                    }
                }
            }
        }
        if(checkdate($mes,$dia,$gestion)){
            return $fecha;
        }else{
            return 1;
        }
    }
}
