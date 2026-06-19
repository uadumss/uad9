<?php

namespace App\Http\Controllers\Noatentado;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RecaudacionesController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TramiteLegalizacionController;
use App\Models\Apoderado;
use App\Models\D_tramita;
use App\Models\Funciones;
use App\Models\Glosa;
use App\Models\Noatentado\Cargo_convocatoria;
use App\Models\Noatentado\Convocatoria;
use App\Models\Noatentado\EscalaCandidato;
use App\Models\Noatentado\Noatentado;
use App\Models\Persona;
use App\Models\Tramite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TramiteNoAtentadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:crear tramite - noa|editar tramite - noa'], ['only' => ['fe_noatentado_convocatoria','g_tramite_convocatoria','fe_candidato',
            'g_candidato','fe_eli_candidato','eli_candidato','fe_agregar_excel','g_excel_noatentado','validar_pago_noatentado','importar_excel_temporal_noatentado']]);
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
        if((int)$cod_dtra===0){
            if(!Gate::allows('crear tramite - noa')){
                abort(403,'No tiene permisos para crear trámites de No Atentado.');
            }
        }else{
            if(!Gate::allows('editar tramite - noa')){
                abort(403,'No tiene permisos para editar trámites de No Atentado.');
            }
        }

        $convocatoria=Convocatoria::find($cod_con);
        $tramites=Tramite::where('tre_tipo','=','A')->get();
        $cargos=Cargo_convocatoria::where('cod_con','=',$cod_con)->orderBy('carg_nombre','ASC')->get();
        $escalaCandidatosNoa=$this->escalaCandidatosMontoNoAtentado();
        $codTramitePlanchaNoa=$this->obtenerCodTramitePlanchaNoAtentado();
        $tramite_noatentado=array();
        $noatentados=array();
        if($cod_dtra!=0){
            $tramite_noatentado=DB::table('d_tramitas')
                ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                ->where('cod_dtra','=',$cod_dtra)
                ->where('d_tramitas.cod_con','=',$cod_con)
                ->where('d_tramitas.dtra_tipo','=','A')
                ->select('d_tramitas.*','tramites.*')->first();

            if(!$tramite_noatentado){
                abort(404,'No se encontró el trámite solicitado.');
            }

            $noatentados=DB::table('noatentado.noatentado')
                ->join('personas','noatentado.id_per','=','personas.id_per')
                ->leftJoin('claustros.cargo_convocatoria','noatentado.noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
                ->where('cod_dtra','=',$cod_dtra)
                ->select('personas.*','noatentado.*','cargo_convocatoria.*')->get();
        }
        //dd($tramite_noatentado);
        return view('servicios.no_atentado.tramite.fe_noatentado_convocatoria',compact('convocatoria','tramites','tramite_noatentado','noatentados','cod_con','cargos','escalaCandidatosNoa','codTramitePlanchaNoa'));
    }

    public function l_escala_precios_noatentado()
    {
        $escalaCandidatosNoa=$this->escalaCandidatosMontoNoAtentado();
        return view('servicios.no_atentado.tramite.l_escala_precios_noatentado',compact('escalaCandidatosNoa'));
    }

    public function validar_pago_noatentado(Request $request,$cod_con){
        $convocatoria=Convocatoria::where('cod_con','=',$cod_con)
            ->where('con_hab','=','t')
            ->first();
        if(!$convocatoria){
            return response()->json([
                'ok'=>false,
                'code'=>'CONVOCATORIA_NO_DISPONIBLE',
                'message'=>'La convocatoria no está disponible para validar pagos.',
            ],404);
        }

        $request->validate([
            'tramite'=>'nullable|integer',
            'control'=>'required',
            'reintegro'=>'nullable|string',
            'preconsulta_control'=>'nullable',
            'documento_pago'=>'nullable|string',
            'preimpreso_pago'=>'nullable|string',
            'cantidad_candidatos'=>'nullable|integer|min:0',
            'ci_candidato_unico'=>'nullable|string',
            'ci_candidatos'=>'nullable',
        ]);

        $control=trim((string)$request['control']);
        // El tipo de trámite se resuelve al validar el pago (automático o manual según reglas de reintegro/monto).
        $codTre=0;
        $filtros=$this->construirFiltrosPagoNoAtentado([
            'documento'=>$request['documento_pago'] ?? '',
            'preimpreso'=>$request['preimpreso_pago'] ?? '',
            'cantidad_candidatos'=>$request['cantidad_candidatos'] ?? 0,
            'ci_candidato_unico'=>$request['ci_candidato_unico'] ?? '',
            'ci_candidatos'=>$request['ci_candidatos'] ?? [],
        ]);
        $preimpresoIngresado=trim((string)($request['preimpreso_pago'] ?? ''));
        $preconsultaControl=filter_var($request['preconsulta_control'] ?? false,FILTER_VALIDATE_BOOLEAN);
        $esMultiSinPreimpreso=(int)($filtros['cantidad_candidatos'] ?? 0)>1 && $preimpresoIngresado==='';

        $validacionPrincipal=$this->validarControlPagoNoAtentado($control,$codTre,0,$filtros,[
            'requerir_preimpreso_multi'=>!$preconsultaControl,
        ]);

        if($preconsultaControl && $esMultiSinPreimpreso){
            if((bool)($validacionPrincipal['ok'] ?? false)){
                $validacionPrincipal=[
                    'ok'=>false,
                    'code'=>'PREIMPRESO_REQUERIDO_MULTI_CANDIDATO',
                    'message'=>'Control encontrado. Ingrese preimpreso para seleccionar el valorado correcto.',
                    'preconsulta_control'=>true,
                    'coincidencias'=>1,
                ];
            }elseif(trim((string)($validacionPrincipal['code'] ?? ''))==='PAGO_AMBIGUO'){
                $validacionPrincipal['preconsulta_control']=true;
            }
        }

        if((bool)($validacionPrincipal['ok'] ?? false) && !$this->documentoPagoPerteneceACandidatosNoAtentado(
            $validacionPrincipal,
            $filtros['documentos_candidatos'] ?? [],
            (int)($filtros['cantidad_candidatos'] ?? 0),
            (bool)($filtros['forzar_documento_candidato'] ?? false)
        )){
            $validacionPrincipal=[
                'ok'=>false,
                'code'=>'CARNET_CANDIDATO_NO_COINCIDE',
                'message'=>'El pago no corresponde a ninguno de los carnets de candidatos del trámite.',
            ];
        }

        $validacionReintegro=$this->estadoReintegroPendienteNoAtentado((string)($request['reintegro'] ?? ''));
        if((bool)($validacionPrincipal['ok'] ?? false)){
            $validacionReintegro=$this->validarControlReintegroPagoNoAtentado(
                (string)($request['reintegro'] ?? ''),
                (string)($validacionPrincipal['documento'] ?? ''),
                $control,
                0
            );
        }

        $montos=$this->adjuntarMontosValidacionPagoNoAtentado([], $validacionPrincipal, $validacionReintegro);
        $resolucionTipos=$this->resolverTiposTramitePagoNoAtentado($validacionPrincipal,$validacionReintegro,$montos);
        if(!(bool)($resolucionTipos['ok'] ?? false)){
            $validacionPrincipal['ok']=false;
            $validacionPrincipal['code']=trim((string)($resolucionTipos['code'] ?? 'TIPO_TRAMITE_NO_RESUELTO'));
            $validacionPrincipal['message']=trim((string)($resolucionTipos['message'] ?? 'No se pudo resolver el tipo de trámite para el pago validado.'));
            $validacionPrincipal['tipo_noatentado_sugerido']=0;
            $validacionPrincipal['nombre_tipo_noatentado_sugerido']='';
            $validacionPrincipal['tipos_noatentado_permitidos']=[];
            $validacionPrincipal['requiere_seleccion_manual']=false;
        }else{
            $validacionPrincipal['tipo_noatentado_sugerido']=(int)($resolucionTipos['tipo_noatentado_sugerido'] ?? 0);
            $validacionPrincipal['nombre_tipo_noatentado_sugerido']=trim((string)($resolucionTipos['nombre_tipo_noatentado_sugerido'] ?? ''));
            $validacionPrincipal['tipos_noatentado_permitidos']=$resolucionTipos['tipos_noatentado_permitidos'] ?? [];
            $validacionPrincipal['requiere_seleccion_manual']=(bool)($resolucionTipos['requiere_seleccion_manual'] ?? false);

            $mensajeResolucion=trim((string)($resolucionTipos['message'] ?? ''));
            if($mensajeResolucion!==''){
                $validacionPrincipal['message']=$mensajeResolucion;
            }
        }

        $respuesta=$this->respuestaPublicaValidacionPagoNoAtentado($validacionPrincipal,$validacionReintegro,$montos);
        return response()->json($respuesta);
    }

    public function importar_excel_temporal_noatentado(Request $request,$cod_con){
        $request->validate([
            'lista'=>'required|file|mimes:xlsx,xls',
        ]);

        $convocatoria=Convocatoria::find($cod_con);
        if(!$convocatoria){
            return response()->json([
                'ok'=>false,
                'message'=>'No se encontró la convocatoria para importar candidatos.',
            ],404);
        }

        try{
            $excel=IOFactory::load($request->file('lista')->getRealPath());
            $hoja=$excel->getActiveSheet();
            $filas=$hoja->toArray('',true,true,false);
        }catch(\Throwable $e){
            return response()->json([
                'ok'=>false,
                'message'=>'No se pudo leer el archivo Excel seleccionado.',
            ],422);
        }

        if(!is_array($filas) || sizeof($filas)<2){
            return response()->json([
                'ok'=>false,
                'message'=>'El archivo no contiene filas válidas para importar.',
            ],422);
        }

        $cabecera=$filas[0] ?? [];
        $indices=[];
        foreach($cabecera as $idx=>$nombre){
            $clave=$this->normalizarCabeceraExcelNoAtentado((string)$nombre);
            if($clave!==''){
                $indices[$clave]=$idx;
            }
        }

        if(!array_key_exists('apellido',$indices) || !array_key_exists('nombre',$indices) || !array_key_exists('ci',$indices)){
            return response()->json([
                'ok'=>false,
                'message'=>'El Excel debe contener como mínimo las columnas: apellido, nombre y ci.',
            ],422);
        }

        $candidatos=[];
        $ciProcesados=[];
        $errores=[];

        for($i=1;$i<sizeof($filas);$i++){
            $fila=$filas[$i] ?? [];
            $apellido=mb_strtoupper(trim((string)($fila[$indices['apellido']] ?? '')));
            $nombre=mb_strtoupper(trim((string)($fila[$indices['nombre']] ?? '')));
            $ci=mb_strtoupper(trim((string)($fila[$indices['ci']] ?? '')));

            $sis='';
            if(array_key_exists('sis',$indices)){
                $sis=trim((string)($fila[$indices['sis']] ?? ''));
            }

            $cargo='';
            if(array_key_exists('cargo',$indices)){
                $cargo=$this->normalizarTextoCargoNoAtentado((string)($fila[$indices['cargo']] ?? ''));
            }

            $unidad='';
            if(array_key_exists('unidad',$indices)){
                $unidad=mb_strtoupper(trim((string)($fila[$indices['unidad']] ?? '')));
            }

            if($apellido==='' && $nombre==='' && $ci==='' && $sis==='' && $cargo==='' && $unidad===''){
                continue;
            }

            if($apellido==='' || $nombre==='' || $ci===''){
                $errores[]='Fila '.($i+1).': faltan datos obligatorios (apellido, nombre o ci).';
                continue;
            }

            if(array_key_exists($ci,$ciProcesados)){
                continue;
            }
            $ciProcesados[$ci]=true;

            $cargoConvocatoria='';
            if($cargo!==''){
                $cargoDb=Cargo_convocatoria::where('cod_con','=',$cod_con)
                    ->where('carg_nombre','=',$cargo)
                    ->first();
                if($cargoDb){
                    $cargoConvocatoria=(string)$cargoDb->cod_carg;
                }
            }

            $candidatos[]=[
                'ci'=>$ci,
                'nombre'=>$nombre,
                'apellido'=>$apellido,
                'cod_sis'=>$sis,
                'cargo'=>$cargo,
                'cargo_convocatoria'=>$cargoConvocatoria,
                'cargo_nombre'=>$cargo,
                'unidad'=>$unidad,
            ];
        }

        if(sizeof($candidatos)===0){
            return response()->json([
                'ok'=>false,
                'message'=>sizeof($errores)>0 ? implode(' ',$errores) : 'No se detectaron candidatos válidos en el archivo.',
            ],422);
        }

        return response()->json([
            'ok'=>true,
            'message'=>'Se importaron '.sizeof($candidatos).' candidato(s) desde Excel.',
            'candidatos'=>$candidatos,
            'errores'=>$errores,
        ]);
    }

    private function normalizarCabeceraExcelNoAtentado(string $texto): string
    {
        $valor=trim(mb_strtolower($texto));
        if($valor===''){
            return '';
        }

        $valor=strtr($valor,[
            'á'=>'a',
            'é'=>'e',
            'í'=>'i',
            'ó'=>'o',
            'ú'=>'u',
            'ñ'=>'n',
        ]);
        $valor=preg_replace('/\s+/',' ',$valor);

        return match ($valor) {
            'apellido','apellidos' => 'apellido',
            'nombre','nombres' => 'nombre',
            'ci','c.i.','cedula','cedula de identidad' => 'ci',
            'cargo' => 'cargo',
            'unidad' => 'unidad',
            'sis','cod sis','codigo sis','codigo_sis' => 'sis',
            default => '',
        };
    }

    public function g_tramite_convocatoria(Request $form){
        $form->validate([
            'cc'=>'required|integer',
            'control'=>'required',
            'tipo_tramite'=>'required|in:t,f',
            'reintegro'=>'nullable|string',
            'cd'=>'nullable|integer',
        ]);

        $codDtra=(int)($form['cd'] ?? 0);
        $esEdicion=$codDtra>0;

        if($esEdicion){
            if(!Gate::allows('editar tramite - noa')){
                abort(403,'No tiene permisos para editar trámites de No Atentado.');
            }
        }else{
            if(!Gate::allows('crear tramite - noa')){
                abort(403,'No tiene permisos para crear trámites de No Atentado.');
            }
        }

        $convocatoria=Convocatoria::where('cod_con','=',(int)$form['cc'])
            ->where('con_hab','=','t')
            ->first();

        $esPeticionAjax=(bool)($form->ajax() || $form->expectsJson());
        $responderError=function(string $mensaje,string $ruta,int $status=422) use ($esPeticionAjax){
            if($esPeticionAjax){
                return response()->json([
                    'ok'=>false,
                    'message'=>$mensaje,
                ],$status);
            }

            \Session::flash('errorModal',$mensaje);
            return redirect($ruta);
        };

        if(!$convocatoria){
            return $responderError('La convocatoria no está disponible para registrar trámites.','lista convocatoria noatentado/'.date('Y'),404);
        }

        $tramite_noatentado=array();
        if($esEdicion){
            $tramite_noatentado=D_tramita::where('cod_dtra','=',$form['cd'])
                ->where('cod_con','=',(int)$form['cc'])
                ->where('dtra_tipo','=','A')
                ->first();

            if(!$tramite_noatentado){
                return $responderError('No se encontró el trámite a editar.','listar tramite convocatoria/'.$form['cc'],404);
            }

            if($this->tramiteNoAtentadoFueGenerado($tramite_noatentado)){
                return $responderError(
                    'El trámite ya fue generado y no permite edición directa.',
                    'listar tramite convocatoria/'.$form['cc'],
                    422
                );
            }

            $controlActual=trim((string)($tramite_noatentado->dtra_control ?? ''));
            $controlFormulario=trim((string)($form['control'] ?? ''));
            $reintegroActual=trim((string)($tramite_noatentado->dtra_valorado_reintegro ?? ''));
            $reintegroFormulario=trim((string)($form['reintegro'] ?? ''));

            if($controlFormulario!==$controlActual || $reintegroFormulario!==$reintegroActual){
                return $responderError(
                    'No se permite modificar el pago en un trámite ya creado.',
                    'editar tramite convocatoria/'.$form['cc'].'/'.$tramite_noatentado->cod_dtra,
                    422
                );
            }

            DB::beginTransaction();
            try{
                $antiguo=json_encode($tramite_noatentado);
                $tramite_noatentado->dtra_interno=$form['tipo_tramite'];
                $tramite_noatentado->save();

                $nuevo=json_encode($tramite_noatentado);
                SessionController::write('U',$antiguo,$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);

                DB::commit();
                \Session::flash('exitoModal','Se ha editado satisfactoriamente el tramite');
            }catch(\Throwable $e){
                DB::rollBack();
                Log::error('Error al editar trámite no atentado.',[
                    'cod_dtra'=>$tramite_noatentado->cod_dtra,
                    'error'=>$e->getMessage(),
                ]);
                return $responderError(
                    'No se pudo guardar la edición del trámite.',
                    'editar tramite convocatoria/'.$form['cc'].'/'.$tramite_noatentado->cod_dtra,
                    500
                );
            }
        }else{
            $form->validate([
                'tramite'=>'nullable',
                'candidatos_json'=>'required',
            ]);

            $candidatos=json_decode((string)$form['candidatos_json'],true);
            if(!is_array($candidatos) || sizeof($candidatos)===0){
                return $responderError(
                    'Debe registrar al menos un candidato antes de guardar el trámite.',
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $resumenCandidatos=$this->resumenCandidatosPagoNoAtentado($candidatos);
            // El tipo de trámite se resuelve por validación de pago y puede requerir selección manual.
            $codTreFormulario=0;
            $filtrosPago=$this->construirFiltrosPagoNoAtentado([
                'documento'=>$form['documento_pago'] ?? '',
                'preimpreso'=>$form['preimpreso_pago'] ?? '',
                'cantidad_candidatos'=>$resumenCandidatos['cantidad'] ?? 0,
                'ci_candidato_unico'=>$resumenCandidatos['documento_unico'] ?? '',
                'ci_candidatos'=>$resumenCandidatos['documentos'] ?? [],
            ]);
            $validacionPago=$this->validarControlPagoNoAtentado((string)$form['control'],$codTreFormulario,0,$filtrosPago);
            if(!(bool)($validacionPago['ok'] ?? false)){
                return $responderError(
                    (string)($validacionPago['message'] ?? 'No se pudo validar el pago del trámite.'),
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            if(!$this->documentoPagoPerteneceACandidatosNoAtentado(
                $validacionPago,
                $resumenCandidatos['documentos'] ?? [],
                (int)($resumenCandidatos['cantidad'] ?? 0),
                false
            )){
                return $responderError(
                    'El CI del pago validado no pertenece a la lista de candidatos registrada.',
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $validacionReintegro=$this->validarControlReintegroPagoNoAtentado(
                (string)($form['reintegro'] ?? ''),
                (string)($validacionPago['documento'] ?? ''),
                (string)$form['control'],
                0
            );
            if(!(bool)($validacionReintegro['ok'] ?? false)){
                return $responderError(
                    (string)($validacionReintegro['message'] ?? 'No se pudo validar el control de reintegro.'),
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $montosValidados=$this->adjuntarMontosValidacionPagoNoAtentado([], $validacionPago, $validacionReintegro);
            $montoTotalValidado=(float)($montosValidados['monto_total_validado'] ?? 0);

            $resolucionTipos=$this->resolverTiposTramitePagoNoAtentado($validacionPago,$validacionReintegro,$montosValidados);
            if(!(bool)($resolucionTipos['ok'] ?? false)){
                return $responderError(
                    (string)($resolucionTipos['message'] ?? 'No se pudo resolver el tipo de trámite para el pago validado.'),
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $codTreFormulario=0;
            $errorCodTre='';
            $codTreFormulario=$this->resolverCodTramiteGuardarNoAtentado($resolucionTipos,(string)($form['tramite'] ?? ''),$errorCodTre);
            if($codTreFormulario<=0){
                return $responderError(
                    $errorCodTre!=='' ? $errorCodTre : 'No se pudo determinar el tipo de trámite para guardar.',
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $controlCantidad=$this->validarCantidadCandidatosPorTipoNoAtentado(
                $codTreFormulario,
                (int)($resumenCandidatos['cantidad'] ?? 0),
                $montoTotalValidado
            );
            if(!(bool)($controlCantidad['ok'] ?? false)){
                return $responderError(
                    (string)($controlCantidad['message'] ?? 'La cantidad de candidatos no corresponde al tipo de trámite seleccionado.'),
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $controlReintegroGuardar=trim((string)($validacionReintegro['control'] ?? ($form['reintegro'] ?? '')));
            $controlReintegroNormalizado=$this->normalizarNumeroNoAtentado($controlReintegroGuardar);
            $controlReintegroGuardarValor=$controlReintegroNormalizado!=='' ? $controlReintegroNormalizado : null;

            $dtraControlGuardar=$this->normalizarNumeroNoAtentado((string)($validacionPago['control'] ?? $form['control']));
            if($dtraControlGuardar===''){
                return $responderError(
                    'No se pudo normalizar el número de control validado.',
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }

            $año_tramita=date('Y');
            $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
            $numero_tramite+=1;

            DB::beginTransaction();
            try{
                $tramite_noatentado=D_tramita::create([
                    'cod_con'=>$form['cc'],
                    'cod_tre'=>$codTreFormulario,
                    'dtra_interno'=>$form['tipo_tramite'],
                    'dtra_control'=>$dtraControlGuardar,
                    'dtra_valorado_reintegro'=>$controlReintegroGuardarValor,
                    'dtra_numero_tramite'=>$numero_tramite,
                    'dtra_gestion_tramite'=>$año_tramita,
                    'dtra_posicion'=>1,
                    'dtra_tipo'=>'A',
                    'dtra_fecha_registro'=>date('d/m/Y'),
                    'dtra_gestion'=>$año_tramita,
                ]);
                $nuevo=json_encode($tramite_noatentado);
                SessionController::write('C','',$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);

                $cantidadCandidatos=$this->guardarCandidatosDesdeJson($candidatos,$tramite_noatentado);
                if($cantidadCandidatos===0){
                    throw new \RuntimeException('No se registraron candidatos válidos para el trámite.');
                }

                $errorUso='';
                if(!$this->registrarUsoRecaudacionNoAtentado($validacionPago,0,(int)$tramite_noatentado->cod_dtra,$errorUso)){
                    throw new \RuntimeException($errorUso!=='' ? $errorUso : 'No se pudo registrar el bloqueo del pago.');
                }

                if((bool)($validacionReintegro['aplica'] ?? false)){
                    $errorUso='';
                    if(!$this->registrarUsoRecaudacionNoAtentado($validacionReintegro,0,(int)$tramite_noatentado->cod_dtra,$errorUso)){
                        throw new \RuntimeException($errorUso!=='' ? $errorUso : 'No se pudo registrar el bloqueo del reintegro.');
                    }
                }

                DB::commit();
                \Session::flash('exitoModal','Se ha creado satisfactoriamente el tramite');
            }catch(\Throwable $e){
                DB::rollBack();
                Log::error('Error al registrar trámite no atentado.',[
                    'cod_con'=>$form['cc'],
                    'error'=>$e->getMessage(),
                ]);
                $mensajeError='No se pudo guardar el trámite. Intente nuevamente.';
                if($e instanceof \RuntimeException){
                    $detalle=trim((string)$e->getMessage());
                    if($detalle!==''){
                        $mensajeError=$detalle;
                    }
                }

                return $responderError($mensajeError,'editar tramite convocatoria/'.$form['cc'].'/0',($e instanceof \RuntimeException) ? 422 : 500);
            }
        }
        $rutaRedireccion=$esEdicion
            ? "editar tramite convocatoria/".$form['cc']."/".$tramite_noatentado->cod_dtra
            : "editar tramite convocatoria/".$form['cc']."/0";
        if($esPeticionAjax){
            return response()->json([
                'ok'=>true,
                'redirect'=>url($rutaRedireccion),
                'cod_dtra'=>$tramite_noatentado->cod_dtra,
                'accion'=>$esEdicion ? 'editado' : 'creado',
                'cerrar_modal'=>!$esEdicion,
                'refresh_url'=>!$esEdicion ? url('actualizar lista tramite convocatoria/'.$form['cc']) : '',
            ]);
        }

        return redirect($rutaRedireccion);
    }

    private function construirFiltrosPagoNoAtentado(array $datos): array
    {
        $documento=$this->normalizarDocumentoNoAtentado((string)($datos['documento'] ?? ''));
        $preimpreso=$this->normalizarNumeroNoAtentado((string)($datos['preimpreso'] ?? ''));
        $documentosCandidatos=$this->normalizarDocumentosCandidatosNoAtentado($datos['ci_candidatos'] ?? []);
        $cantidad=max(0,(int)($datos['cantidad_candidatos'] ?? sizeof($documentosCandidatos)));
        $documentoCandidatoUnico=$this->normalizarDocumentoNoAtentado((string)($datos['ci_candidato_unico'] ?? ''));

        if($documentoCandidatoUnico!=='' && !in_array($documentoCandidatoUnico,$documentosCandidatos,true)){
            $documentosCandidatos[]=$documentoCandidatoUnico;
        }

        if($cantidad===0){
            $cantidad=sizeof($documentosCandidatos);
        }

        if($documentoCandidatoUnico==='' && sizeof($documentosCandidatos)===1){
            $documentoCandidatoUnico=(string)$documentosCandidatos[0];
        }

        $forzarDocumentoCandidato=$cantidad===1 && $documentoCandidatoUnico!=='';

        if($forzarDocumentoCandidato){
            $documento=$documentoCandidatoUnico;
        }

        return [
            'documento'=>$documento,
            'preimpreso'=>$preimpreso,
            'cantidad_candidatos'=>$cantidad,
            'documento_candidato_unico'=>$documentoCandidatoUnico,
            'documentos_candidatos'=>$documentosCandidatos,
            'forzar_documento_candidato'=>$forzarDocumentoCandidato,
        ];
    }

    private function resumenCandidatosPagoNoAtentado(array $candidatos): array
    {
        $documentos=[];
        foreach($candidatos as $item){
            if(!is_array($item)){
                continue;
            }
            $documento=$this->normalizarDocumentoNoAtentado((string)($item['ci'] ?? ''));
            if($documento===''){
                continue;
            }
            $documentos[$documento]=true;
        }

        $lista=array_keys($documentos);
        return [
            'cantidad'=>sizeof($lista),
            'documento_unico'=>sizeof($lista)===1 ? (string)$lista[0] : '',
            'documentos'=>$lista,
        ];
    }

    private function escalaCandidatosMontoNoAtentado(): array
    {
        \Log::info('DEBUG escala NOA - inicio');
        \Log::info('DEBUG escala NOA - existe tabla', [
            'tabla' => 'noatentado.escala_candidatos',
            'hasTable' => Schema::hasTable('noatentado.escala_candidatos'),
        ]);
        
        try {
            $reglas=EscalaCandidato::query()
                ->where('habilitado','=',true)
                ->orderBy('monto_total','ASC')
                ->orderBy('cantidad_max','ASC')
                ->orderBy('orden','ASC')
                ->get(['cantidad_min','cantidad_max','costo','aporte_umss','monto_total'])
                ->map(function($fila){
                    return [
                        'cantidad_min'=>(int)($fila->cantidad_min ?? 0),
                        'cantidad_max'=>(int)($fila->cantidad_max ?? 0),
                        'costo'=>(float)($fila->costo ?? 0),
                        'aporte_umss'=>(float)($fila->aporte_umss ?? 0),
                        'monto_total'=>(float)($fila->monto_total ?? 0),
                    ];
                })
                ->toArray();
            \Log::info('DEBUG escala NOA - reglas desde BD', [
                'cantidad' => count($reglas),
                'reglas' => $reglas,
            ]);
        } catch (\Throwable $e) {
            \Log::error('ERROR escala NOA - consulta falló', [
                'mensaje' => $e->getMessage(),
            ]);

            return [];
        }
        if(!is_array($reglas)){
        return [];
        }   
        $normalizadas=[];
        foreach($reglas as $fila){
            if(!is_array($fila)){
                continue;
            }

            $cantidadMin=max(1,(int)($fila['cantidad_min'] ?? 0));
            $cantidadMax=max($cantidadMin,(int)($fila['cantidad_max'] ?? 0));
            $costo=$this->normalizarMontoRecaudacionNoAtentado($fila['costo'] ?? 0);
            $aporte=$this->normalizarMontoRecaudacionNoAtentado($fila['aporte_umss'] ?? 0);
            $montoTotal=$this->normalizarMontoRecaudacionNoAtentado($fila['monto_total'] ?? 0);

            if($montoTotal<=0){
                $montoTotal=round($costo+$aporte,2);
            }

            if($montoTotal<=0){
                continue;
            }

            $normalizadas[]=[
                'cantidad_min'=>$cantidadMin,
                'cantidad_max'=>$cantidadMax,
                'costo'=>$costo,
                'aporte_umss'=>$aporte,
                'monto_total'=>$montoTotal,
            ];
            \Log::info('DEBUG escala NOA - procesando fila', [
                'fila' => $fila,
                'cantidadMin' => $cantidadMin,
                'cantidadMax' => $cantidadMax,
                'costo' => $costo,
                'aporte' => $aporte,
                'montoTotal' => $montoTotal,
            ]);
        }

        usort($normalizadas,function(array $a,array $b){
            $cmpMonto=(float)$a['monto_total'] <=> (float)$b['monto_total'];
            if($cmpMonto!==0){
                return $cmpMonto;
            }
            return (int)$a['cantidad_max'] <=> (int)$b['cantidad_max'];
        });
        \Log::info('DEBUG escala NOA - normalizadas final', [
            'cantidad' => count($normalizadas),
            'normalizadas' => $normalizadas,
        ]);
        return $normalizadas;
    }

    private function resolverCupoCandidatosPorMontoNoAtentado(float $montoTotalValidado): array
    {
        $escala=$this->escalaCandidatosMontoNoAtentado();
        if(sizeof($escala)===0){
            return [
                'ok'=>false,
                'code'=>'ESCALA_CANDIDATOS_NO_CONFIGURADA',
                'message'=>'No existe una escala de candidatos configurada para validar el monto.',
                'max_permitidos'=>0,
            ];
        }

        $montoTotalValidado=$this->normalizarMontoRecaudacionNoAtentado($montoTotalValidado);
        if($montoTotalValidado<=0){
            return [
                'ok'=>false,
                'code'=>'MONTO_VALIDADO_NO_DISPONIBLE',
                'message'=>'No se pudo determinar el monto total validado para controlar la cantidad de candidatos.',
                'max_permitidos'=>0,
            ];
        }

        $tolerancia=$this->normalizarMontoRecaudacionNoAtentado(config('noatentado.tolerancia_monto',0.01));
        if($tolerancia<0){
            $tolerancia=0.0;
        }

        $reglaSeleccionada=null;
        foreach($escala as $regla){
            if($montoTotalValidado+$tolerancia>=(float)$regla['monto_total']){
                $reglaSeleccionada=$regla;
                continue;
            }
            break;
        }

        if(!$reglaSeleccionada){
            $primeraRegla=$escala[0];
            return [
                'ok'=>false,
                'code'=>'MONTO_INSUFICIENTE_PARA_ESCALA',
                'message'=>'El monto validado (Bs '.number_format($montoTotalValidado,2,'.','').') es menor al mínimo de la escala (Bs '.number_format((float)$primeraRegla['monto_total'],2,'.','').').',
                'max_permitidos'=>0,
            ];
        }

        return [
            'ok'=>true,
            'code'=>'',
            'message'=>'Monto validado dentro de escala para candidatos.',
            'max_permitidos'=>(int)$reglaSeleccionada['cantidad_max'],
            'regla'=>$reglaSeleccionada,
        ];
    }

    private function validarCantidadCandidatosPorMontoNoAtentado(int $cantidadCandidatos,float $montoTotalValidado): array
    {
        $cantidadCandidatos=max(0,$cantidadCandidatos);
        if($cantidadCandidatos===0){
            return [
                'ok'=>false,
                'code'=>'SIN_CANDIDATOS',
                'message'=>'Debe registrar al menos un candidato.',
                'max_permitidos'=>0,
            ];
        }

        $cupo=$this->resolverCupoCandidatosPorMontoNoAtentado($montoTotalValidado);
        if(!(bool)($cupo['ok'] ?? false)){
            return $cupo;
        }

        $maxPermitidos=(int)($cupo['max_permitidos'] ?? 0);
        if($maxPermitidos>0 && $cantidadCandidatos>$maxPermitidos){
            return [
                'ok'=>false,
                'code'=>'CANTIDAD_CANDIDATOS_SUPERA_MONTO',
                'message'=>'Con el monto validado (Bs '.number_format($this->normalizarMontoRecaudacionNoAtentado($montoTotalValidado),2,'.','').') solo se permiten hasta '.$maxPermitidos.' candidato(s). Registró '.$cantidadCandidatos.'.',
                'max_permitidos'=>$maxPermitidos,
            ];
        }

        return [
            'ok'=>true,
            'code'=>'',
            'message'=>'Cantidad de candidatos válida para el monto pagado.',
            'max_permitidos'=>$maxPermitidos,
        ];
    }

    private function validarCantidadCandidatosPorTipoNoAtentado(int $codTre,int $cantidadCandidatos,float $montoTotalValidado): array
    {
        $cantidadCandidatos=max(0,$cantidadCandidatos);
        if($cantidadCandidatos===0){
            return [
                'ok'=>false,
                'code'=>'SIN_CANDIDATOS',
                'message'=>'Debe registrar al menos un candidato.',
                'max_permitidos'=>0,
            ];
        }

        if($this->esTramitePlanchaEstudiantesNoAtentado($codTre)){
            return $this->validarCantidadCandidatosPorMontoNoAtentado($cantidadCandidatos,$montoTotalValidado);
        }

        if($cantidadCandidatos>1){
            return [
                'ok'=>false,
                'code'=>'CANTIDAD_CANDIDATOS_SOLO_UNO',
                'message'=>'Para este tipo de trámite solo se permite registrar un candidato por cuenta/pago.',
                'max_permitidos'=>1,
            ];
        }

        return [
            'ok'=>true,
            'code'=>'',
            'message'=>'Cantidad de candidatos válida para el tipo de trámite seleccionado.',
            'max_permitidos'=>1,
        ];
    }

    private function resolverTiposTramitePagoNoAtentado(array $validacionPrincipal,array $validacionReintegro,array $montos): array
    {
        if(!(bool)($validacionPrincipal['ok'] ?? false)){
            return [
                'ok'=>false,
                'code'=>'PAGO_PRINCIPAL_NO_VALIDO',
                'message'=>trim((string)($validacionPrincipal['message'] ?? 'No se pudo validar el pago principal.')),
            ];
        }

        $tiposPermitidos=$this->normalizarTiposPermitidosNoAtentado($validacionPrincipal['tipos_noatentado_permitidos'] ?? []);
        $tipoSugerido=(int)($validacionPrincipal['tipo_noatentado_sugerido'] ?? 0);
        $nombreTipoSugerido=trim((string)($validacionPrincipal['nombre_tipo_noatentado_sugerido'] ?? ''));
        $requiereSeleccionManual=(bool)($validacionPrincipal['requiere_seleccion_manual'] ?? false);

        $reintegroOk=(bool)($validacionReintegro['ok'] ?? false);
        $reintegroAplica=(bool)($validacionReintegro['aplica'] ?? false);
        $montoReintegro=$this->normalizarMontoRecaudacionNoAtentado($montos['monto_reintegro_validado'] ?? 0);
        $usarMontoPorReintegro=$reintegroOk && $reintegroAplica && $montoReintegro>0;

        if($tipoSugerido>0){
            $existeSugerido=false;
            foreach($tiposPermitidos as $tipoItem){
                if((int)($tipoItem['cod_tre'] ?? 0)===$tipoSugerido){
                    $existeSugerido=true;
                    if($nombreTipoSugerido===''){
                        $nombreTipoSugerido=trim((string)($tipoItem['tre_nombre'] ?? ''));
                    }
                    break;
                }
            }

            if(!$existeSugerido){
                $tramite=Tramite::query()->where('cod_tre','=',$tipoSugerido)->where('tre_tipo','=','A')->first();
                if($tramite){
                    $tiposPermitidos[]=[
                        'cod_tre'=>(int)$tramite->cod_tre,
                        'tre_nombre'=>trim((string)$tramite->tre_nombre),
                    ];
                    if($nombreTipoSugerido===''){
                        $nombreTipoSugerido=trim((string)$tramite->tre_nombre);
                    }
                }
            }
        }

        if($usarMontoPorReintegro){
            $montoTotal=$this->normalizarMontoRecaudacionNoAtentado($montos['monto_total_validado'] ?? 0);
            if($montoTotal>0){
                $tiposPorMonto=$this->buscarTiposNoAtentadoPorMontoTotal($montoTotal);
                if(sizeof($tiposPorMonto)>0 && sizeof($tiposPermitidos)>0){
                    $permitidosMap=[];
                    foreach($tiposPermitidos as $tipoItem){
                        $cod=(int)($tipoItem['cod_tre'] ?? 0);
                        if($cod>0){
                            $permitidosMap[$cod]=true;
                        }
                    }

                    if(sizeof($permitidosMap)>0){
                        $tiposPorMonto=array_values(array_filter($tiposPorMonto,function($item) use ($permitidosMap){
                            $cod=(int)($item['cod_tre'] ?? 0);
                            return $cod>0 && array_key_exists($cod,$permitidosMap);
                        }));
                    }
                }
                if(sizeof($tiposPorMonto)>0){
                    if(sizeof($tiposPorMonto)===1){
                        $tipoUnico=$tiposPorMonto[0];
                        return [
                            'ok'=>true,
                            'tipo_noatentado_sugerido'=>(int)($tipoUnico['cod_tre'] ?? 0),
                            'nombre_tipo_noatentado_sugerido'=>trim((string)($tipoUnico['tre_nombre'] ?? '')),
                            'tipos_noatentado_permitidos'=>$tiposPorMonto,
                            'requiere_seleccion_manual'=>false,
                            'message'=>'Pago validado. El tipo de trámite se resolvió por monto total.',
                        ];
                    }

                    $planchaId = $this->obtenerCodTramitePlanchaNoAtentado();
                    $esPlancha = false;
                    $nombrePlancha = '';
                    foreach($tiposPorMonto as $t) {
                        if ((int)($t['cod_tre'] ?? 0) === $planchaId) {
                            $esPlancha = true;
                            $nombrePlancha = trim((string)($t['tre_nombre'] ?? ''));
                            break;
                        }
                    }

                    if ($esPlancha) {
                        return [
                            'ok'=>true,
                            'tipo_noatentado_sugerido'=>$planchaId,
                            'nombre_tipo_noatentado_sugerido'=>$nombrePlancha,
                            'tipos_noatentado_permitidos'=>$tiposPorMonto,
                            'requiere_seleccion_manual'=>false,
                            'message'=>'Pago validado. Priorizado trámite de plancha por monto total.',
                        ];
                    }

                    return [
                        'ok'=>true,
                        'tipo_noatentado_sugerido'=>0,
                        'nombre_tipo_noatentado_sugerido'=>'',
                        'tipos_noatentado_permitidos'=>$tiposPorMonto,
                        'requiere_seleccion_manual'=>true,
                        'message'=>'Pago validado. Existen múltiples tipos de trámite con el mismo monto total; seleccione uno manualmente.',
                    ];
                }
            }

            return [
                'ok'=>false,
                'code'=>'MONTO_TOTAL_NO_CORRESPONDE',
                'message'=>'Pago validado, pero el monto total no coincide con ningún trámite No Atentado.',
            ];
        }

        if($tipoSugerido<=0 && sizeof($tiposPermitidos)===1){
            $tipoSugerido=(int)($tiposPermitidos[0]['cod_tre'] ?? 0);
            $nombreTipoSugerido=trim((string)($tiposPermitidos[0]['tre_nombre'] ?? ''));
        }

        return [
            'ok'=>true,
            'tipo_noatentado_sugerido'=>$tipoSugerido,
            'nombre_tipo_noatentado_sugerido'=>$nombreTipoSugerido,
            'tipos_noatentado_permitidos'=>$tiposPermitidos,
            'requiere_seleccion_manual'=>$requiereSeleccionManual,
            'message'=>'',
        ];
    }

    private function resolverCodTramiteGuardarNoAtentado(array $resolucionTipos,string $codTreFormularioRaw,string &$error=''): int
    {
        $error='';
        $tiposPermitidos=$this->normalizarTiposPermitidosNoAtentado($resolucionTipos['tipos_noatentado_permitidos'] ?? []);
        $permitidos=[];
        foreach($tiposPermitidos as $tipoItem){
            $cod=(int)($tipoItem['cod_tre'] ?? 0);
            if($cod>0){
                $permitidos[]=$cod;
            }
        }

        $codTreFormulario=(int)$codTreFormularioRaw;
        $requiereManual=(bool)($resolucionTipos['requiere_seleccion_manual'] ?? false);
        if($requiereManual){
            if($codTreFormulario<=0){
                $error='Debe seleccionar manualmente el tipo de trámite para el monto validado.';
                return 0;
            }

            if(sizeof($permitidos)>0 && !in_array($codTreFormulario,$permitidos,true)){
                $error='El tipo de trámite seleccionado no está permitido para el monto validado.';
                return 0;
            }

            return $codTreFormulario;
        }

        if($codTreFormulario>0){
            if(sizeof($permitidos)>0 && !in_array($codTreFormulario,$permitidos,true)){
                $error='El tipo de trámite seleccionado no coincide con la validación del pago.';
                return 0;
            }

            return $codTreFormulario;
        }

        $codTreSugerido=(int)($resolucionTipos['tipo_noatentado_sugerido'] ?? 0);
        if($codTreSugerido>0){
            return $codTreSugerido;
        }

        if(sizeof($permitidos)===1){
            return (int)$permitidos[0];
        }

        if(sizeof($permitidos)>1){
            $error='Debe seleccionar manualmente uno de los tipos de trámite permitidos para el monto validado.';
            return 0;
        }

        $error='No se pudo determinar un tipo de trámite válido para guardar.';
        return 0;
    }

    private function normalizarTiposPermitidosNoAtentado($tipos): array
    {
        if(!is_array($tipos)){
            return [];
        }

        $normalizados=[];
        foreach($tipos as $item){
            if(!is_array($item)){
                continue;
            }

            $codTre=(int)($item['cod_tre'] ?? 0);
            if($codTre<=0){
                continue;
            }

            $normalizados[$codTre]=[
                'cod_tre'=>$codTre,
                'tre_nombre'=>trim((string)($item['tre_nombre'] ?? '')),
            ];
        }

        return array_values($normalizados);
    }

    private function buscarTiposNoAtentadoPorMontoTotal(float $montoTotal): array
    {
        $montoTotal=$this->normalizarMontoRecaudacionNoAtentado($montoTotal);
        if($montoTotal<=0){
            return [];
        }

        $tolerancia=$this->normalizarMontoRecaudacionNoAtentado(config('noatentado.tolerancia_monto',0.01));
        if($tolerancia<0.01){
            $tolerancia=0.01;
        }

        $coincidentes=[];
        $tramites=Tramite::query()
            ->where('tre_tipo','=','A')
            ->orderBy('cod_tre','ASC')
            ->get(['cod_tre','tre_nombre','tre_costo']);

        foreach($tramites as $tramite){
            $costo=$this->normalizarMontoRecaudacionNoAtentado($tramite->tre_costo ?? 0);
            if($costo<=0){
                continue;
            }

            if(abs($costo-$montoTotal)>$tolerancia){
                continue;
            }

            $coincidentes[]=[
                'cod_tre'=>(int)$tramite->cod_tre,
                'tre_nombre'=>trim((string)$tramite->tre_nombre),
            ];
        }

        return $coincidentes;
    }

    private function esNombreTramitePlanchaEstudiantesNoAtentado(string $nombreTramite): bool
    {
        $nombre=$this->normalizarTextoComparacionNoAtentado($nombreTramite);
        if($nombre===''){
            return false;
        }

        return strpos($nombre,'PLANCHA')!==false && strpos($nombre,'ESTUDIANT')!==false;
    }

    private function esTramiteAcreditativoDiplomaAcademico(?Tramite $tramite): bool
    {
        if(!$tramite){
            return false;
        }

        $campos=[
            (string)($tramite->tre_nombre ?? ''),
            (string)($tramite->tre_titulo ?? ''),
            (string)($tramite->tre_titulo_interno ?? ''),
        ];

        foreach($campos as $campo){
            $normalizado=$this->normalizarTextoComparacionNoAtentado($campo);
            if($normalizado===''){
                continue;
            }

            $tieneCert=strpos($normalizado,'CERT')!==false;
            $tieneAcreditat=strpos($normalizado,'ACREDITAT')!==false;
            $tieneDipl=strpos($normalizado,'DIPL')!==false;
            $tieneAcadem=strpos($normalizado,'ACADEM')!==false;
            if($tieneCert && $tieneAcreditat && $tieneDipl && $tieneAcadem){
                return true;
            }
        }

        return false;
    }

    private function obtenerCodTramitePlanchaNoAtentado(): int
    {
        $tramites=Tramite::query()
            ->where('tre_tipo','=','A')
            ->get(['cod_tre','tre_nombre']);

        foreach($tramites as $tramite){
            if($this->esNombreTramitePlanchaEstudiantesNoAtentado((string)($tramite->tre_nombre ?? ''))){
                return (int)($tramite->cod_tre ?? 0);
            }
        }

        return 0;
    }

    private function esTramitePlanchaEstudiantesNoAtentado(int $codTre): bool
    {
        if($codTre<=0){
            return false;
        }

        $tramite=Tramite::query()
            ->where('cod_tre','=',$codTre)
            ->where('tre_tipo','=','A')
            ->first(['cod_tre','tre_nombre']);

        if(!$tramite){
            return false;
        }

        return $this->esNombreTramitePlanchaEstudiantesNoAtentado((string)($tramite->tre_nombre ?? ''));
    }

    private function resumenCandidatosTramiteNoAtentado(int $codDtra): array
    {
        if($codDtra<=0){
            return [
                'cantidad'=>0,
                'documento_unico'=>'',
            ];
        }

        $filas=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->where('noatentado.cod_dtra','=',$codDtra)
            ->select('personas.per_ci')
            ->get();

        $documentos=[];
        foreach($filas as $fila){
            $documento=$this->normalizarDocumentoNoAtentado((string)($fila->per_ci ?? ''));
            if($documento===''){
                continue;
            }
            $documentos[$documento]=true;
        }

        $lista=array_keys($documentos);
        return [
            'cantidad'=>sizeof($lista),
            'documento_unico'=>sizeof($lista)===1 ? (string)$lista[0] : '',
            'documentos'=>$lista,
        ];
    }

    private function validarControlPagoNoAtentado(string $control,int $codTre=0,int $codDtra=0,array $filtros=[],array $opciones=[]): array
    {
        $control=trim($control);
        if($control===''){
            return [
                'ok'=>false,
                'code'=>'CONTROL_REQUERIDO',
                'message'=>'Debe ingresar el numero de control.',
            ];
        }

        if(!$this->esControlNumericoNoAtentado($control)){
            return [
                'ok'=>false,
                'code'=>'CONTROL_INVALIDO',
                'message'=>'El numero de control debe contener solo numeros.',
            ];
        }

        $control=$this->normalizarNumeroNoAtentado($control);

        if(!Schema::hasTable('recaudacion_usos')){
            return [
                'ok'=>false,
                'code'=>'TABLA_BLOQUEO_INEXISTENTE',
                'message'=>'No se puede validar el pago porque falta la tabla de bloqueo de usos.',
            ];
        }

        $tramiteSeleccionado=null;
        if($codTre>0){
            $tramiteSeleccionado=Tramite::where('cod_tre','=',$codTre)->where('tre_tipo','=','A')->first();
            if(!$tramiteSeleccionado){
                return [
                    'ok'=>false,
                    'code'=>'TRAMITE_INVALIDO',
                    'message'=>'El trámite seleccionado no corresponde a No Atentado.',
                ];
            }

            $cuentaSeleccionadaNormalizada=$this->normalizarNumeroNoAtentado((string)($tramiteSeleccionado->tre_numero_cuenta ?? ''));
            if($cuentaSeleccionadaNormalizada===''){
                return [
                    'ok'=>false,
                    'code'=>'CUENTA_TRAMITE_NO_CONFIGURADA',
                    'message'=>'El trámite seleccionado no tiene número de cuenta configurado.',
                ];
            }
        }

        $tramitesNoAtentado=Tramite::where('tre_tipo','=','A')->get();
        $tramitesPorCuenta=[];
        foreach($tramitesNoAtentado as $itemTramite){
            $cuentaItemNormalizada=$this->normalizarNumeroNoAtentado((string)($itemTramite->tre_numero_cuenta ?? ''));
            if($cuentaItemNormalizada===''){
                continue;
            }
            if(!array_key_exists($cuentaItemNormalizada,$tramitesPorCuenta)){
                $tramitesPorCuenta[$cuentaItemNormalizada]=[];
            }
            $tramitesPorCuenta[$cuentaItemNormalizada][]=$itemTramite;
        }

        if(sizeof($tramitesPorCuenta)===0){
            return [
                'ok'=>false,
                'code'=>'NO_HAY_CUENTAS_NOATENTADO',
                'message'=>'No hay trámites No Atentado con cuentas configuradas para validar el pago.',
            ];
        }

        $filtrosAplicados=array_key_exists('forzar_documento_candidato',$filtros)
            ? $filtros
            : $this->construirFiltrosPagoNoAtentado($filtros);
        $documentoFiltro=(string)($filtrosAplicados['documento'] ?? '');
        $preimpresoFiltro=(string)($filtrosAplicados['preimpreso'] ?? '');
        $documentosCandidatos=$this->normalizarDocumentosCandidatosNoAtentado($filtrosAplicados['documentos_candidatos'] ?? []);
        $forzarDocumentoCandidato=(bool)($filtrosAplicados['forzar_documento_candidato'] ?? false);
        $requerirContextoCandidatos=(bool)($opciones['requerir_contexto_candidatos'] ?? true);
        $requerirPreimpresoMulti=(bool)($opciones['requerir_preimpreso_multi'] ?? true);
        $requerirDocumentoCandidato=(bool)($opciones['requerir_documento_candidato'] ?? true);
        $requiereCoincidenciaDocumentoCandidato=$requerirDocumentoCandidato && ($forzarDocumentoCandidato || sizeof($documentosCandidatos)===1);

        if($requerirContextoCandidatos && sizeof($documentosCandidatos)===0){
            return [
                'ok'=>false,
                'code'=>'CONTEXTO_CANDIDATOS_REQUERIDO',
                'message'=>'Debe registrar candidatos y validar con carnet para consultar el pago.',
            ];
        }

        if($requerirPreimpresoMulti && sizeof($documentosCandidatos)>1 && $preimpresoFiltro===''){
            return [
                'ok'=>false,
                'code'=>'PREIMPRESO_REQUERIDO_MULTI_CANDIDATO',
                'message'=>'Con varios candidatos debe ingresar el preimpreso para validar el pago con seguridad.',
            ];
        }

        $consultaPorControlDocumentoPrincipal=$requiereCoincidenciaDocumentoCandidato && $documentoFiltro!=='';
        $consulta=$consultaPorControlDocumentoPrincipal
            ? $this->consultarControlPrincipalDocumentoRecaudacionesNoAtentado($control,$documentoFiltro)
            : $this->consultarControlRecaudacionesNoAtentado($control);

        if(!(bool)($consulta['ok'] ?? false)){
            return $consulta;
        }

        $filas=$consulta['resultados'] ?? [];
        if(sizeof($filas)===0){
            if($consultaPorControlDocumentoPrincipal){
                return [
                    'ok'=>false,
                    'code'=>'CONTROL_DOCUMENTO_NO_ENCONTRADO',
                    'message'=>'No se encontró información del número de control y carnet en recaudaciones.',
                ];
            }

            return [
                'ok'=>false,
                'code'=>'CONTROL_NO_ENCONTRADO',
                'message'=>'No se encontro informacion del numero de control en recaudaciones.',
            ];
        }

        $usoEncontrado=null;
        $cuentaDetectada='';
        $coincidioConCandidatos=false;
        $coincidioConFiltros=false;
        $pagosValidos=[];

        foreach($filas as $filaItem){
            $fila=(array)$filaItem;
            $codigoCuenta=(string)($fila['codigo_cuenta'] ?? '');
            $codigoCuentaNormalizado=$this->normalizarNumeroNoAtentado($codigoCuenta);

            if($codigoCuentaNormalizado===''){
                continue;
            }

            if($cuentaDetectada===''){
                $cuentaDetectada=(string)($fila['cuenta'] ?? $codigoCuenta);
            }

            $tiposPermitidosRaw=[];
            if($tramiteSeleccionado){
                $cuentaTramiteSeleccionado=$this->normalizarNumeroNoAtentado((string)($tramiteSeleccionado->tre_numero_cuenta ?? ''));
                if($cuentaTramiteSeleccionado==='' || $cuentaTramiteSeleccionado!==$codigoCuentaNormalizado){
                    continue;
                }
                $tiposPermitidosRaw[]=$tramiteSeleccionado;
            }else{
                if(!array_key_exists($codigoCuentaNormalizado,$tramitesPorCuenta)){
                    continue;
                }
                $tiposPermitidosRaw=$tramitesPorCuenta[$codigoCuentaNormalizado];
            }

            $documentoFila=$this->normalizarDocumentoNoAtentado((string)($fila['documento'] ?? ''));
            if($documentoFila===''){
                continue;
            }

            $esDocumentoCandidato=in_array($documentoFila,$documentosCandidatos,true);
            if($esDocumentoCandidato){
                $coincidioConCandidatos=true;
            }

            if($requiereCoincidenciaDocumentoCandidato && !$esDocumentoCandidato){
                continue;
            }

            if($documentoFiltro!=='' && $documentoFila!==$documentoFiltro){
                continue;
            }

            $preimpresoFilaNormalizado=$this->normalizarPreimpresoFilaNoAtentado($fila);
            if($preimpresoFiltro!=='' && $preimpresoFilaNormalizado!==$preimpresoFiltro){
                continue;
            }

            $coincidioConFiltros=true;

            $usoExistente=$this->usoRecaudacionExistenteNoAtentado($fila,$control,$codDtra);
            if($usoExistente){
                if(!$usoEncontrado){
                    $usoEncontrado=$usoExistente;
                }
                continue;
            }

            $nombrePersona=$this->nombrePersonaFilaRecaudacionNoAtentado($fila);
            $preimpreso=(string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? '')));

            $tiposPermitidos=[];
            foreach($tiposPermitidosRaw as $tipoPermitido){
                $tiposPermitidos[]=[
                    'cod_tre'=>(int)$tipoPermitido->cod_tre,
                    'tre_nombre'=>(string)$tipoPermitido->tre_nombre,
                ];
            }

            $tipoSugerido=0;
            $nombreTipoSugerido='';
            $requiereSeleccionManual=false;
            $tramiteSugerido=$this->seleccionarTramiteSugeridoPagoNoAtentado($tiposPermitidosRaw,(string)($fila['cuenta'] ?? ''));
            if($tramiteSugerido){
                $tipoSugerido=(int)$tramiteSugerido->cod_tre;
                $nombreTipoSugerido=(string)$tramiteSugerido->tre_nombre;
            }

            $mensaje='Pago validado correctamente para No Atentado.';
            if($tipoSugerido>0){
                $mensaje='Pago validado. Tipo de trámite definido automáticamente según la cuenta del pago.';
            }

            $pagosValidos[]=[
                'ok'=>true,
                'message'=>$mensaje,
                'control'=>$control,
                'identificador'=>(string)($fila['identificador'] ?? ''),
                'total'=>(string)($fila['total'] ?? ''),
                'monto_total'=>$this->normalizarMontoRecaudacionNoAtentado($fila['total'] ?? 0),
                'codigo_cuenta'=>$codigoCuenta,
                'cuenta'=>(string)($fila['cuenta'] ?? ''),
                'fecha_pago'=>(string)($fila['fecha'] ?? ''),
                'cajero'=>(string)($fila['cajero'] ?? ''),
                'documento'=>(string)($fila['documento'] ?? ''),
                'nombre_persona'=>$nombrePersona,
                'preimpreso'=>$preimpreso,
                'tipo_noatentado_sugerido'=>$tipoSugerido,
                'nombre_tipo_noatentado_sugerido'=>$nombreTipoSugerido,
                'tipos_noatentado_permitidos'=>$tiposPermitidos,
                'requiere_seleccion_manual'=>$requiereSeleccionManual,
            ];
        }

        if(sizeof($pagosValidos)===1){
            return $pagosValidos[0];
        }

        if(sizeof($pagosValidos)>1){
            return [
                'ok'=>false,
                'code'=>'PAGO_AMBIGUO',
                'message'=>'Se encontraron varios pagos válidos para este control. Ingrese preimpreso para identificar el pago correcto.',
                'coincidencias'=>sizeof($pagosValidos),
            ];
        }

        if($requiereCoincidenciaDocumentoCandidato && !$coincidioConCandidatos){
            if($forzarDocumentoCandidato || sizeof($documentosCandidatos)===1){
                return [
                    'ok'=>false,
                    'code'=>'CI_CANDIDATO_NO_COINCIDE',
                    'message'=>'El CI del candidato no coincide con el pago consultado.',
                ];
            }

            return [
                'ok'=>false,
                'code'=>'CARNET_CANDIDATO_NO_COINCIDE',
                'message'=>'El pago no corresponde a ninguno de los carnets de candidatos del trámite.',
            ];
        }

        if(($documentoFiltro!=='' || $preimpresoFiltro!=='') && !$coincidioConFiltros){
            if($forzarDocumentoCandidato){
                return [
                    'ok'=>false,
                    'code'=>'CI_CANDIDATO_NO_COINCIDE',
                    'message'=>'El CI del candidato único no coincide con el pago consultado.',
                ];
            }

            if($documentoFiltro!=='' && $preimpresoFiltro!==''){
                return [
                    'ok'=>false,
                    'code'=>'FILTRO_PAGO_SIN_COINCIDENCIA',
                    'message'=>'No se encontró pago que coincida con CI y preimpreso para este número de control.',
                ];
            }

            if($documentoFiltro!==''){
                return [
                    'ok'=>false,
                    'code'=>'DOCUMENTO_PAGO_NO_COINCIDE',
                    'message'=>'No se encontró pago con ese CI para este número de control.',
                ];
            }

            return [
                'ok'=>false,
                'code'=>'PREIMPRESO_PAGO_NO_COINCIDE',
                'message'=>'No se encontró pago con ese preimpreso para este número de control.',
            ];
        }

        if($usoEncontrado){
            return [
                'ok'=>false,
                'code'=>'PAGO_YA_USADO',
                'message'=>$this->mensajePagoUsadoNoAtentado($usoEncontrado),
            ];
        }

        if($tramiteSeleccionado){
            return [
                'ok'=>false,
                'code'=>'CUENTA_NO_CORRESPONDE',
                'message'=>'La boleta no corresponde al tipo de trámite No Atentado seleccionado.',
            ];
        }

        if($cuentaDetectada!==''){
            return [
                'ok'=>false,
                'code'=>'CUENTA_SIN_TRAMITE_HABILITADO',
                'message'=>'La cuenta del pago no tiene un trámite No Atentado habilitado en el sistema: '.$cuentaDetectada.'.',
            ];
        }

        return [
            'ok'=>false,
            'code'=>'CUENTA_NO_IDENTIFICADA',
            'message'=>'No se pudo identificar una cuenta válida en la boleta para No Atentado.',
        ];
    }

    private function estadoReintegroPendienteNoAtentado(string $controlReintegro): array
    {
        $controlReintegro=trim($controlReintegro);
        if($controlReintegro===''){
            return [
                'ok'=>true,
                'aplica'=>false,
                'control'=>'',
                'monto_total'=>0.0,
                'message'=>'Sin reintegro.',
            ];
        }

        return [
            'ok'=>true,
            'aplica'=>false,
            'pendiente'=>true,
            'control'=>$controlReintegro,
            'monto_total'=>0.0,
            'message'=>'Reintegro pendiente: primero confirme control principal y preimpreso.',
        ];
    }

    private function adjuntarMontosValidacionPagoNoAtentado(array $respuesta,array $validacionPrincipal,array $validacionReintegro): array
    {
        $montoPrincipal=(bool)($validacionPrincipal['ok'] ?? false)
            ? $this->normalizarMontoRecaudacionNoAtentado($validacionPrincipal['monto_total'] ?? 0)
            : 0.0;

        $montoReintegro=((bool)($validacionReintegro['ok'] ?? false) && (bool)($validacionReintegro['aplica'] ?? false))
            ? $this->normalizarMontoRecaudacionNoAtentado($validacionReintegro['monto_total'] ?? 0)
            : 0.0;

        $respuesta['monto_principal_validado']=$montoPrincipal;
        $respuesta['monto_reintegro_validado']=$montoReintegro;
        $respuesta['monto_total_validado']=round($montoPrincipal+$montoReintegro,2);
        return $respuesta;
    }

    private function respuestaPublicaValidacionPagoNoAtentado(array $validacionPrincipal,array $validacionReintegro,array $montos): array
    {
        $principal=$this->bloquePublicoValidacionPagoNoAtentado($validacionPrincipal,'principal');
        $reintegro=$this->bloquePublicoValidacionPagoNoAtentado($validacionReintegro,'reintegro');

        $principalOk=(bool)($principal['ok'] ?? false);
        $reintegroOk=(bool)($reintegro['ok'] ?? false);
        $ok=$principalOk && $reintegroOk;

        $code='';
        $message='';
        if(!$principalOk){
            $code=trim((string)($principal['code'] ?? 'PAGO_NO_VALIDO'));
            $message=trim((string)($principal['message'] ?? 'No se pudo validar el pago principal.'));
        }elseif(!$reintegroOk){
            $code=trim((string)($reintegro['code'] ?? 'REINTEGRO_NO_VALIDO'));
            $message=trim((string)($reintegro['message'] ?? 'No se pudo validar el control de reintegro.'));
        }else{
            $message=trim((string)($principal['message'] ?? 'Pago validado correctamente.'));
        }

        return [
            'ok'=>$ok,
            'code'=>$code,
            'message'=>$message,
            'validacion_principal'=>$principal,
            'validacion_reintegro'=>$reintegro,
            'tipo_noatentado_sugerido'=>$principal['tipo_noatentado_sugerido'] ?? null,
            'nombre_tipo_noatentado_sugerido'=>$principal['nombre_tipo_noatentado_sugerido'] ?? '',
            'tipos_noatentado_permitidos'=>$principal['tipos_noatentado_permitidos'] ?? [],
            'requiere_seleccion_manual'=>(bool)($principal['requiere_seleccion_manual'] ?? false),
            'monto_principal_validado'=>$this->normalizarMontoRecaudacionNoAtentado($montos['monto_principal_validado'] ?? 0),
            'monto_reintegro_validado'=>$this->normalizarMontoRecaudacionNoAtentado($montos['monto_reintegro_validado'] ?? 0),
            'monto_total_validado'=>$this->normalizarMontoRecaudacionNoAtentado($montos['monto_total_validado'] ?? 0),
        ];
    }

    private function bloquePublicoValidacionPagoNoAtentado(array $bloque,string $tipo): array
    {
        $permitidos=$tipo==='reintegro'
            ? ['ok','code','message','aplica']
            : ['ok','code','message','tipo_noatentado_sugerido','nombre_tipo_noatentado_sugerido','tipos_noatentado_permitidos','requiere_seleccion_manual'];

        $publico=[];
        foreach($permitidos as $campo){
            if(array_key_exists($campo,$bloque)){
                $publico[$campo]=$bloque[$campo];
            }
        }

        $publico['ok']=(bool)($publico['ok'] ?? false);
        $publico['code']=trim((string)($publico['code'] ?? ''));
        $publico['message']=trim((string)($publico['message'] ?? ''));

        if($tipo==='reintegro'){
            $publico['aplica']=(bool)($publico['aplica'] ?? false);
            return $publico;
        }

        $publico['tipo_noatentado_sugerido']=(int)($publico['tipo_noatentado_sugerido'] ?? 0);
        $publico['nombre_tipo_noatentado_sugerido']=trim((string)($publico['nombre_tipo_noatentado_sugerido'] ?? ''));
        $publico['requiere_seleccion_manual']=(bool)($publico['requiere_seleccion_manual'] ?? false);

        $tipos=$publico['tipos_noatentado_permitidos'] ?? [];
        if(!is_array($tipos)){
            $tipos=[];
        }

        $tiposPublicos=[];
        foreach($tipos as $item){
            if(!is_array($item)){
                continue;
            }

            $codTre=(int)($item['cod_tre'] ?? 0);
            if($codTre<=0){
                continue;
            }

            $tiposPublicos[]=[
                'cod_tre'=>$codTre,
                'tre_nombre'=>trim((string)($item['tre_nombre'] ?? '')),
            ];
        }

        $publico['tipos_noatentado_permitidos']=$tiposPublicos;

        return $publico;
    }

    private function validarControlReintegroPagoNoAtentado(string $controlReintegro,string $documentoTitularPrincipal='',string $controlPrincipal='',int $codDtra=0): array
    {
        $controlReintegro=trim($controlReintegro);
        if($controlReintegro===''){
            return [
                'ok'=>true,
                'aplica'=>false,
                'control'=>'',
                'message'=>'Sin reintegro.',
            ];
        }

        $documentoTitularPrincipal=$this->normalizarDocumentoNoAtentado($documentoTitularPrincipal);
        $metaReintegro=$this->metaSolicitudReintegroNoAtentado($controlReintegro,$documentoTitularPrincipal);

        if(!$this->esControlNumericoNoAtentado($controlReintegro)){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_CONTROL_INVALIDO',
                'message'=>'El número de control de reintegro debe contener solo números.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        $controlReintegro=$this->normalizarNumeroNoAtentado($controlReintegro);

        if($documentoTitularPrincipal===''){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_DOCUMENTO_PRINCIPAL_REQUERIDO',
                'message'=>'Control de reintegro: primero valide el pago principal para obtener el CI del pagador.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        $controlPrincipal=trim($controlPrincipal);
        if($controlPrincipal!=='' && $controlPrincipal===$controlReintegro){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_IGUAL_CONTROL',
                'message'=>'El número de control de reintegro no puede ser igual al control principal.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        $consultaDocumento=$this->consultarControlDocumentoRecaudacionesNoAtentado($controlReintegro,$documentoTitularPrincipal);
        if(!(bool)($consultaDocumento['ok'] ?? false)){
            return $this->mapearErrorValidacionReintegroPagoNoAtentado($consultaDocumento,$controlReintegro,$metaReintegro);
        }

        $filasDocumento=$consultaDocumento['resultados'] ?? [];
        if(!is_array($filasDocumento) || sizeof($filasDocumento)===0){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_CONTROL_DOCUMENTO_NO_COINCIDE',
                'message'=>'Control de reintegro: no se pudo validar la boleta con control y CI del pagador principal en recaudaciones.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        $usoEncontrado=null;
        $pagosValidos=[];
        $pagosProcesados=[];
        foreach($filasDocumento as $filaItem){
            $fila=(array)$filaItem;
            $documentoFila=$this->normalizarDocumentoNoAtentado((string)($fila['documento'] ?? ''));
            if($documentoFila==='' || $documentoFila!==$documentoTitularPrincipal){
                continue;
            }

            $clavePago=$this->clavePagoRecaudacionNoAtentado($fila,$controlReintegro);
            if(array_key_exists($clavePago,$pagosProcesados)){
                continue;
            }
            $pagosProcesados[$clavePago]=true;

            $usoExistente=$this->usoRecaudacionExistenteNoAtentado($fila,$controlReintegro,$codDtra);
            if($usoExistente){
                if(!$usoEncontrado){
                    $usoEncontrado=$usoExistente;
                }
                continue;
            }

            $pagosValidos[]=[
                'ok'=>true,
                'aplica'=>true,
                'control'=>$controlReintegro,
                'identificador'=>(string)($fila['identificador'] ?? ''),
                'total'=>(string)($fila['total'] ?? ''),
                'monto_total'=>$this->normalizarMontoRecaudacionNoAtentado($fila['total'] ?? 0),
                'codigo_cuenta'=>(string)($fila['codigo_cuenta'] ?? ''),
                'cuenta'=>(string)($fila['cuenta'] ?? ''),
                'fecha_pago'=>(string)($fila['fecha'] ?? ''),
                'cajero'=>(string)($fila['cajero'] ?? ''),
                'documento'=>(string)($fila['documento'] ?? ''),
                'nombre_persona'=>$this->nombrePersonaFilaRecaudacionNoAtentado($fila),
                'preimpreso'=>(string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? ''))),
                'message'=>'Control de reintegro validado en recaudaciones con control y CI del pagador principal.',
                ...$metaReintegro,
            ];
        }

        if(sizeof($pagosValidos)===1){
            return $pagosValidos[0];
        }

        if($usoEncontrado){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_PAGO_YA_USADO',
                'message'=>'Control de reintegro: '.$this->mensajePagoUsadoNoAtentado($usoEncontrado),
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        if(sizeof($pagosValidos)>1){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_PAGO_AMBIGUO',
                'message'=>'Control de reintegro: se encontraron múltiples pagos para ese control y CI del pagador principal.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

        return [
            'ok'=>false,
            'code'=>'REINTEGRO_CONTROL_DOCUMENTO_NO_COINCIDE',
            'message'=>'Control de reintegro: no se pudo validar la boleta con control y CI del pagador principal en recaudaciones.',
            'aplica'=>true,
            'control'=>$controlReintegro,
            ...$metaReintegro,
        ];
    }

    private function metaSolicitudReintegroNoAtentado(string $control,string $documento): array
    {
        $controlNormalizado=$this->normalizarNumeroNoAtentado($control);
        $documentoNormalizado=$this->normalizarDocumentoNoAtentado($documento);

        return [
            'documento_principal_usado'=>$documentoNormalizado,
            'payload_consulta_reintegro'=>[
                'unidad'=>122,
                'recibo'=>$controlNormalizado!=='' ? (int)$controlNormalizado : 0,
                'documento'=>$documentoNormalizado,
            ],
        ];
    }

    private function clavePagoRecaudacionNoAtentado(array $fila,string $control): string
    {
        $fecha=trim((string)($fila['fecha'] ?? ''));
        $documento=$this->normalizarDocumentoNoAtentado((string)($fila['documento'] ?? ''));
        $preimpreso=$this->normalizarPreimpresoFilaNoAtentado($fila);
        if($fecha!=='' || $documento!=='' || $preimpreso!==''){
            return 'cmp:'.$control.'|'.$fecha.'|'.$documento.'|'.$preimpreso;
        }

        $identificador=trim((string)($fila['identificador'] ?? ''));
        if($identificador!==''){
            return 'id:'.$identificador;
        }

        return 'alt:'.$control.'|'.$fecha.'|'.$documento.'|'.$preimpreso;
    }

    private function consultarControlPrincipalDocumentoRecaudacionesNoAtentado(string $control,string $documento): array
    {
        try{
            $response=app(\App\Services\RecaudacionesService::class)->buscarPorControlYDocumento(122,(int)$control,$documento);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones por control+documento para pago principal No Atentado.',[
                'control'=>$control,
                'documento'=>$documento,
                'error'=>$e->getMessage(),
            ]);
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($e->getMessage(),0);
            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        if(!is_array($response)){
            return [
                'ok'=>false,
                'code'=>'API_RESPUESTA_INVALIDA',
                'message'=>'No se pudo validar el control y carnet en recaudaciones. Intente nuevamente.',
            ];
        }

        if(!($response['ok'] ?? false)){
            $mensaje=trim((string)($response['message'] ?? ''));
            $status=(int)($response['status'] ?? 0);
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($mensaje,$status);

            if($errorMap['code']==='CONTROL_NO_ENCONTRADO'){
                $errorMap['code']='CONTROL_DOCUMENTO_NO_ENCONTRADO';
                $errorMap['message']='No se encontró información del número de control y carnet en recaudaciones.';
            }

            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($response['data'] ?? []));

        return [
            'ok'=>true,
            'resultados'=>$resultado,
        ];
    }

    private function consultarControlDocumentoRecaudacionesNoAtentado(string $control,string $documento): array
    {
        try{
            $response=app(\App\Services\RecaudacionesService::class)->buscarPorControlYDocumento(122,(int)$control,$documento);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones por control+documento para reintegro No Atentado.',[
                'control'=>$control,
                'documento'=>$documento,
                'error'=>$e->getMessage(),
            ]);
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($e->getMessage(),0);
            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        if(!is_array($response)){
            return [
                'ok'=>false,
                'code'=>'API_RESPUESTA_INVALIDA',
                'message'=>'No se pudo validar el control de reintegro en recaudaciones. Intente nuevamente.',
            ];
        }

        if(!($response['ok'] ?? false)){
            $mensaje=trim((string)($response['message'] ?? ''));
            $status=(int)($response['status'] ?? 0);
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($mensaje,$status);

            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($response['data'] ?? []));

        return [
            'ok'=>true,
            'resultados'=>$resultado,
        ];
    }

    private function mapearErrorValidacionReintegroPagoNoAtentado(array $resultado,string $controlReintegro,array $metaReintegro=[]): array
    {
        $codigo=trim((string)($resultado['code'] ?? ''));
        if($codigo===''){
            $codigo='REINTEGRO_INVALIDO';
        }elseif(strpos($codigo,'REINTEGRO_')!==0){
            $codigo='REINTEGRO_'.$codigo;
        }

        $mensaje=trim((string)($resultado['message'] ?? 'No se pudo validar el control de reintegro en recaudaciones.'));
        if($mensaje===''){
            $mensaje='No se pudo validar el control de reintegro en recaudaciones.';
        }

        return [
            'ok'=>false,
            'code'=>$codigo,
            'message'=>'Control de reintegro: '.$mensaje,
            'aplica'=>true,
            'control'=>trim($controlReintegro),
            ...$metaReintegro,
        ];
    }

    private function consultarControlRecaudacionesNoAtentado(string $control): array
    {
        try{
            $response=app(\App\Services\RecaudacionesService::class)->buscarPorControl(122,(int)$control);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones para No Atentado.',[
                'control'=>$control,
                'error'=>$e->getMessage(),
            ]);
            // Usar mapeo centralizado para convertir excepción en error normalizado
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($e->getMessage(),0);
            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        if(!is_array($response)){
            return [
                'ok'=>false,
                'code'=>'API_RESPUESTA_INVALIDA',
                'message'=>'No se pudo validar el control en recaudaciones. Intente nuevamente.',
            ];
        }

        if(!(bool)($response['ok'] ?? false)){
            $mensaje=trim((string)($response['message'] ?? ''));
            $status=(int)($response['status'] ?? 0);
            // Usar mapeo centralizado en lugar de reimplementar
            $errorMap=app(\App\Services\RecaudacionesService::class)->mapearMensajeErrorComun($mensaje,$status);

            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($response['data'] ?? []));

        return [
            'ok'=>true,
            'resultados'=>$resultado,
        ];
    }

    private function extraerResultadoRecaudacionNoAtentado(array $json): array
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
        if(is_array($data) && $data!==[] && array_key_exists(0,$data)){
            return $data;
        }

        return [];
    }

    private function usoRecaudacionExistenteNoAtentado(array $fila,string $control,int $codDtra=0)
    {
        if(!Schema::hasTable('recaudacion_usos')){
            return null;
        }

        $nombrePersona=$this->nombrePersonaFilaRecaudacionNoAtentado($fila);
        $documento=trim((string)($fila['documento'] ?? ''));
        $fechaPago=trim((string)($fila['fecha'] ?? ''));
        $preimpreso=trim((string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? ''))));

        $usoCombinacion=$this->buscarUsoPagoPorCombinacionNoAtentado(
            $nombrePersona,
            $documento,
            $control,
            $preimpreso,
            $fechaPago,
            $codDtra
        );
        if($usoCombinacion){
            return $usoCombinacion;
        }

        return null;
    }

    private function buscarUsoPagoPorCombinacionNoAtentado(
        string $nombrePersona,
        string $documento,
        string $recibo,
        string $preimpreso,
        string $fechaPago,
        int $codDtra=0
    )
    {
        if(!Schema::hasTable('recaudacion_usos')){
            return null;
        }

        $recibo=trim($recibo);
        if($recibo===''){
            return null;
        }

        $query=DB::table('recaudacion_usos')
            ->where('recibo','=',$recibo);

        $fechaPago=$this->normalizarFechaPagoUsoNoAtentado($fechaPago);
        if($fechaPago!==''){
            $query->where('fecha_pago','=',$fechaPago);
        }

        $documento=trim($documento);
        if($documento!==''){
            $query->where('documento','=',$documento);
        }

        $preimpreso=trim($preimpreso);
        if($preimpreso!==''){
            $query->where('preimpreso','=',$preimpreso);
        }

        if($codDtra>0){
            $query->where('cod_dtra','<>',$codDtra);
        }

        $usos=$query->orderBy('created_at','DESC')->get();
        if($usos->isEmpty()){
            return null;
        }

        $nombreNormalizado=$this->normalizarTextoComparacionNoAtentado($nombrePersona);
        foreach($usos as $uso){
            $nombreGuardado=$this->normalizarTextoComparacionNoAtentado((string)($uso->nombre_persona ?? ''));
            if($nombreNormalizado!=='' && $nombreGuardado!==$nombreNormalizado){
                continue;
            }
            return $uso;
        }

        return null;
    }

    private function normalizarFechaPagoUsoNoAtentado(string $fechaPago): string
    {
        $valor=trim($fechaPago);
        if($valor===''){
            return '';
        }

        $formatos=[
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d\TH:i:s',
            'Y-m-d\TH:i',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
        ];

        foreach($formatos as $formato){
            $dt=\DateTime::createFromFormat($formato,$valor);
            if($dt instanceof \DateTime){
                return $dt->format('Y-m-d H:i:s');
            }
        }

        $timestamp=strtotime($valor);
        if($timestamp!==false){
            return date('Y-m-d H:i:s',$timestamp);
        }

        return $valor;
    }

    private function resolverIdentificadorBloqueoNoAtentado(
        string $identificadorOriginal,
        string $recibo,
        string $preimpreso,
        string $documento,
        string $fechaPago
    ): string
    {
        $reciboNorm=$this->normalizarNumeroNoAtentado($recibo);
        $preimpresoNorm=$this->normalizarNumeroNoAtentado($preimpreso);
        $documentoNorm=$this->normalizarDocumentoNoAtentado($documento);
        $fechaNorm=$this->normalizarFechaPagoUsoNoAtentado($fechaPago);

        $huella=substr(sha1($reciboNorm.'|'.$preimpresoNorm.'|'.$documentoNorm.'|'.$fechaNorm),0,20);

        $base=trim($identificadorOriginal);
        if($base===''){
            return 'NOA-'.$huella;
        }

        $base=mb_substr($base,0,75);
        return $base.'-'.$huella;
    }

    private function esViolacionUnicaIdentificadorNoAtentado(\Throwable $e): bool
    {
        $mensaje=strtolower((string)$e->getMessage());
        return strpos($mensaje,'recaudacion_usos_identificador_unique')!==false || strpos($mensaje,'duplicate key value violates unique constraint')!==false;
    }

    private function registrarUsoRecaudacionNoAtentado(array $validacion,int $codTra,int $codDtra,string &$error): bool
    {
        $error='';
        if(!Schema::hasTable('recaudacion_usos')){
            $error='No existe la tabla de bloqueo de pagos.';
            return false;
        }

        $identificadorOriginal=trim((string)($validacion['identificador'] ?? ''));
        $nombrePersona=trim((string)($validacion['nombre_persona'] ?? ''));
        $documento=trim((string)($validacion['documento'] ?? ''));
        $recibo=trim((string)($validacion['control'] ?? ''));
        $fechaPago=$this->normalizarFechaPagoUsoNoAtentado((string)($validacion['fecha_pago'] ?? ''));
        $preimpreso=trim((string)($validacion['preimpreso'] ?? ''));
        $identificador=$this->resolverIdentificadorBloqueoNoAtentado(
            $identificadorOriginal,
            $recibo,
            $preimpreso,
            $documento,
            $fechaPago
        );

        if($recibo===''){
            $error='No se pudo registrar el bloqueo del pago.';
            return false;
        }

        $usoCombinacion=$this->buscarUsoPagoPorCombinacionNoAtentado(
            $nombrePersona,
            $documento,
            $recibo,
            $preimpreso,
            $fechaPago,
            $codDtra
        );
        if($usoCombinacion){
            $error='Este pago ya fue utilizado en otro trámite.';
            return false;
        }

        try{
            DB::table('recaudacion_usos')->insert([
                'identificador'=>$identificador,
                'recibo'=>$recibo,
                'preimpreso'=>$preimpreso,
                'fecha_pago'=>$fechaPago,
                'documento'=>$documento,
                'nombre_persona'=>$nombrePersona,
                'cajero'=>(string)($validacion['cajero'] ?? ''),
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
                'modulo'=>'no_atentado',
                'tramite'=>(string)($validacion['cuenta'] ?? ''),
                'monto'=>(float)($validacion['total'] ?? ($validacion['monto'] ?? 0)),
                'usuario_registro'=>Auth::check() ? Auth::user()->name : 'sistema',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }catch(\Throwable $e){
            $usoCombinacion=$this->buscarUsoPagoPorCombinacionNoAtentado(
                $nombrePersona,
                $documento,
                $recibo,
                $preimpreso,
                $fechaPago,
                $codDtra
            );
            if($usoCombinacion || $this->esViolacionUnicaIdentificadorNoAtentado($e)){
                $error='Este pago ya fue utilizado en otro trámite.';
                return false;
            }

            Log::error('Error al registrar uso de recaudacion para No Atentado.',[
                'cod_dtra'=>$codDtra,
                'identificador'=>$identificador,
                'identificador_original'=>$identificadorOriginal,
                'error'=>$e->getMessage(),
            ]);
            $error='No se pudo registrar el bloqueo del pago.';
            return false;
        }

        return true;
    }

    private function eliminarUsosRecaudacionPorTramite(int $codDtra): void
    {
        if($codDtra<=0 || !Schema::hasTable('recaudacion_usos')){
            return;
        }

        try{
            DB::table('recaudacion_usos')->where('cod_dtra','=',$codDtra)->delete();
        }catch(\Throwable $e){
            Log::warning('No se pudo liberar el pago de recaudaciones en No Atentado.',[
                'cod_dtra'=>$codDtra,
                'error'=>$e->getMessage(),
            ]);
        }
    }

    private function mensajePagoUsadoNoAtentado(object $uso): string
    {
        $fecha=trim((string)($uso->created_at ?? ''));

        if($fecha!==''){
            $timestamp=strtotime($fecha);
            if($timestamp!==false){
                $fecha=date('d/m/Y H:i',$timestamp);
            }
        }

        $mensaje='Este pago ya fue utilizado en otro trámite del sistema';
        if($fecha!==''){
            $mensaje.=' el '.$fecha;
        }

        return $mensaje.'.';
    }

    private function guardarCandidatosDesdeJson(array $candidatos,D_tramita $tramite): int
    {
        $ciRegistrados=[];
        $registrados=0;

        foreach($candidatos as $item){
            if(!is_array($item)){
                continue;
            }

            $ci=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)($item['ci'] ?? ''),30));
            $nombre=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)($item['nombre'] ?? ''),120));
            $apellido=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)($item['apellido'] ?? ''),120));
            $codSis=$this->limpiarTextoPlanoNoAtentado((string)($item['cod_sis'] ?? ''),50);
            $unidad=$this->limpiarTextoPlanoNoAtentado((string)($item['unidad'] ?? ''),120);
            $cargoTexto=$this->normalizarTextoCargoNoAtentado($this->limpiarTextoPlanoNoAtentado((string)($item['cargo'] ?? ''),120));
            $cargoNombre=$this->normalizarTextoCargoNoAtentado($this->limpiarTextoPlanoNoAtentado((string)($item['cargo_nombre'] ?? ''),120));
            $cargoConvocatoria=(int)($item['cargo_convocatoria'] ?? 0);
            $cargoNoatentado=$cargoTexto!=='' ? $cargoTexto : $cargoNombre;

            if($ci==='' || $nombre==='' || $apellido===''){
                continue;
            }

            if(array_key_exists($ci,$ciRegistrados)){
                continue;
            }
            $ciRegistrados[$ci]=true;

            $persona=Persona::where('per_ci','=',$ci)->first();
            if(!$persona){
                $persona=Persona::create([
                    'per_ci'=>$ci,
                    'per_nombre'=>$nombre,
                    'per_apellido'=>$apellido,
                    'per_cod_sis'=>$codSis,
                    'per_sistema'=>8,
                ]);
                SessionController::write('C','',json_encode($persona),'personas','8',$persona->id_per);
            }

            $noatentado=Noatentado::create([
                'cod_dtra'=>$tramite->cod_dtra,
                'id_per'=>$persona->id_per,
                'noa_unidad'=>$unidad,
                'noa_cargo'=>$cargoNoatentado!=='' ? $cargoNoatentado : null,
            ]);

            $codCargo=$this->resolverCargoCandidatoNoAtentado($cargoNoatentado,$cargoConvocatoria,$tramite);
            if($codCargo>0){
                $noatentado->cod_carg=$codCargo;
                $noatentado->save();
            }

            SessionController::write('C','',json_encode($noatentado),'noatentado.noatentado','8',$noatentado->cod_noa);
            $registrados++;
        }

        return $registrados;
    }

    private function resolverCargoCandidatoNoAtentado(string $cargoTexto,int $cargoConvocatoria,D_tramita $tramite): int
    {
        if($cargoConvocatoria>0){
            $cargo=Cargo_convocatoria::where('cod_carg','=',$cargoConvocatoria)
                ->where('cod_con','=',$tramite->cod_con)
                ->first();
            if($cargo){
                return (int)$cargo->cod_carg;
            }
        }

        $cargoTexto=$this->normalizarTextoCargoNoAtentado($cargoTexto);
        if($cargoTexto===''){
            return 0;
        }

        $cargo=Cargo_convocatoria::where('cod_con','=',$tramite->cod_con)
            ->where('carg_nombre','=',$cargoTexto)
            ->first();

        if(!$cargo){
            $cargo=Cargo_convocatoria::create([
                'carg_nombre'=>$cargoTexto,
                'cod_con'=>$tramite->cod_con,
            ]);
            SessionController::write('C','',json_encode($cargo),'claustros.cargo_convocatoria','8',$cargo->cod_carg);
        }

        return (int)$cargo->cod_carg;
    }

    private function normalizarNumeroNoAtentado(string $texto): string
    {
        return preg_replace('/\D+/','',$texto) ?? '';
    }

    private function esControlNumericoNoAtentado(string $texto): bool
    {
        return (bool)preg_match('/^\d+$/',trim($texto));
    }

    private function limpiarTextoPlanoNoAtentado(string $texto,int $max=255): string
    {
        $valor=trim(strip_tags($texto));
        $valor=preg_replace('/[\x00-\x1F\x7F]/','',$valor) ?? '';

        if($max>0){
            $valor=mb_substr($valor,0,$max);
        }

        return trim($valor);
    }

    private function normalizarMontoRecaudacionNoAtentado($valor): float
    {
        $texto=trim((string)$valor);
        if($texto===''){
            return 0.0;
        }

        $texto=preg_replace('/[^0-9,\.\-]/','',$texto) ?? '';
        if($texto===''){
            return 0.0;
        }

        if(strpos($texto,',')!==false && strpos($texto,'.')===false){
            $texto=str_replace(',','.',$texto);
        }elseif(strpos($texto,',')!==false && strpos($texto,'.')!==false){
            $texto=str_replace(',','',$texto);
        }

        if(!is_numeric($texto)){
            return 0.0;
        }

        return round((float)$texto,2);
    }

    private function normalizarTextoCargoNoAtentado(string $cargoTexto): string
    {
        $cargo=mb_strtoupper(trim($cargoTexto));
        if($cargo===''){
            return '';
        }

        $clave=mb_strtolower($cargo);
        $clave=strtr($clave,[
            'á'=>'a',
            'é'=>'e',
            'í'=>'i',
            'ó'=>'o',
            'ú'=>'u',
        ]);
        $clave=preg_replace('/\s+/',' ',trim((string)$clave)) ?? '';

        if(in_array($clave,['seleccione','seleccionar','select','ninguno','sin cargo'],true)){
            return '';
        }

        return $cargo;
    }

    private function seleccionarTramiteSugeridoPagoNoAtentado(array $tiposPermitidosRaw,string $nombreCuenta): ?Tramite
    {
        if(sizeof($tiposPermitidosRaw)===0){
            return null;
        }

        $candidatos=[];
        foreach($tiposPermitidosRaw as $item){
            if($item instanceof Tramite){
                $candidatos[]=$item;
            }
        }

        if(sizeof($candidatos)===0){
            return null;
        }

        if(sizeof($candidatos)===1){
            return $candidatos[0];
        }

        $mejor=null;
        $mejorScore=-1.0;
        foreach($candidatos as $tramiteCand){
            $score=$this->puntajeSimilitudCuentaNoAtentado($nombreCuenta,(string)($tramiteCand->tre_nombre ?? ''));
            if($score>$mejorScore){
                $mejorScore=$score;
                $mejor=$tramiteCand;
            }
        }

        if($mejor){
            return $mejor;
        }

        return $candidatos[0];
    }

    private function puntajeSimilitudCuentaNoAtentado(string $cuentaApi,string $nombreTramite): float
    {
        $cuenta=$this->normalizarTextoComparacionNoAtentado($cuentaApi);
        $tramite=$this->normalizarTextoComparacionNoAtentado($nombreTramite);
        if($cuenta==='' || $tramite===''){
            return 0.0;
        }

        if($cuenta===$tramite){
            return 100.0;
        }

        similar_text($cuenta,$tramite,$porcentaje);
        return (float)$porcentaje;
    }

    private function normalizarTextoComparacionNoAtentado(string $texto): string
    {
        $valor=mb_strtoupper(trim($texto));
        $valor=strtr($valor,[
            'Á'=>'A',
            'É'=>'E',
            'Í'=>'I',
            'Ó'=>'O',
            'Ú'=>'U',
            'Ñ'=>'N',
        ]);
        $valor=preg_replace('/[^A-Z0-9 ]+/',' ',$valor) ?? '';
        $valor=preg_replace('/\s+/',' ',trim((string)$valor)) ?? '';
        return (string)$valor;
    }

    private function normalizarDocumentoNoAtentado(string $texto): string
    {
        $valor=mb_strtoupper(trim($texto));
        $valor=preg_replace('/\s+/','',$valor);
        return preg_replace('/[^A-Z0-9]/','',(string)$valor) ?? '';
    }

    private function normalizarDocumentosCandidatosNoAtentado($valor): array
    {
        if(is_string($valor)){
            $texto=trim($valor);
            if($texto===''){
                return [];
            }

            $json=json_decode($texto,true);
            if(json_last_error()===JSON_ERROR_NONE && is_array($json)){
                $valor=$json;
            }else{
                $valor=preg_split('/[\s,;|]+/',$texto) ?: [];
            }
        }

        if(!is_array($valor)){
            $valor=[$valor];
        }

        $documentos=[];
        foreach($valor as $item){
            $documento=$this->normalizarDocumentoNoAtentado((string)$item);
            if($documento===''){
                continue;
            }
            // Evita que PHP convierta claves numéricas a int y rompa comparaciones estrictas.
            $documentos['doc:'.$documento]=$documento;
        }

        return array_values($documentos);
    }

    private function normalizarPreimpresoFilaNoAtentado(array $fila): string
    {
        $preimpreso=(string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? '')));
        return $this->normalizarNumeroNoAtentado($preimpreso);
    }

    private function documentoPagoPerteneceACandidatosNoAtentado(array $validacionPago,$documentosCandidatos,int $cantidadCandidatos=0,bool $forzarDocumentoCandidato=false): bool
    {
        $documentos=$this->normalizarDocumentosCandidatosNoAtentado($documentosCandidatos);
        if(sizeof($documentos)===0){
            return false;
        }

        $cantidadNormalizada=sizeof($documentos);
        if($cantidadNormalizada===0){
            $cantidadNormalizada=max(0,$cantidadCandidatos);
        }

        $requiereCoincidenciaDocumento=$forzarDocumentoCandidato || $cantidadNormalizada<=1;
        if(!$requiereCoincidenciaDocumento){
            return true;
        }

        $documentoPago=$this->normalizarDocumentoNoAtentado((string)($validacionPago['documento'] ?? ''));
        if($documentoPago===''){
            return false;
        }

        return in_array($documentoPago,$documentos,true);
    }

    private function nombrePersonaFilaRecaudacionNoAtentado(array $fila): string
    {
        $nombre=trim((string)($fila['nombre_persona'] ?? ''));
        if($nombre!==''){
            return $nombre;
        }

        $nombre=trim((string)($fila['apellido_1'] ?? '').' '.(string)($fila['apellido_2'] ?? '').' '.(string)($fila['nombre_1'] ?? '').' '.(string)($fila['nombre_2'] ?? ''));
        return $nombre;
    }

    private function mensajeBloqueoGestionCandidatosNoAtentado(): string
    {
        return 'En edición no se permite agregar, eliminar o importar candidatos. Solo puede actualizar datos personales de candidatos existentes.';
    }

    private function obtenerTramiteNoAtentadoPorCodigo(int $codDtra): ?D_tramita
    {
        if($codDtra<=0){
            return null;
        }

        return D_tramita::where('cod_dtra','=',$codDtra)
            ->where('dtra_tipo','=','A')
            ->first();
    }

    private function tramiteNoAtentadoFueGenerado($tramite): bool
    {
        if(!$tramite){
            return false;
        }

        $estado=mb_strtolower(trim((string)($tramite->dtra_generado ?? '')));
        return in_array($estado,['t','1','true','si','s'],true);
    }

    //=================== CANDIDATOS
    public function fe_candidato($cod_dtra,$cod_noa){
        if(!Gate::allows('editar tramite - noa')){
            abort(403,'No tiene permisos para editar candidatos de trámites.');
        }

        $candidato=array();
        $tramite=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$tramite){
            abort(404,'No se encontró el trámite del candidato.');
        }

        if((int)$cod_noa===0){
            abort(403,$this->mensajeBloqueoGestionCandidatosNoAtentado());
        }

        if($this->tramiteNoAtentadoFueGenerado($tramite)){
            abort(403,'El trámite ya fue generado y no permite editar candidatos.');
        }

        $cargos=Cargo_convocatoria::where('cod_con','=',$tramite->cod_con)->get();
        if($cod_noa!=0){
            $candidato=DB::table('noatentado.noatentado')
                ->join('personas','noatentado.id_per','=','personas.id_per')
                ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
                ->where('cod_noa','=',$cod_noa)
                ->where('noatentado.cod_dtra','=',$cod_dtra)
                ->first();
            if(!$candidato){
                abort(404,'No se encontró el candidato para este trámite.');
            }
        }
        return view('servicios.no_atentado.tramite.fe_candidato',compact('candidato','cod_dtra','tramite','cargos'));
    }
    public function g_candidato(Request $form){
        if(!Gate::allows('editar tramite - noa')){
            \Session::flash('errorModal','No tiene permisos para editar candidatos de trámites.');
            return redirect()->back();
        }

        $form->validate([
            'cd'=>'required',
            'ci'=>'required',
            'nombre'=>'required',
            'apellido'=>'required',
        ]);
        $tramite=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cd']);
        if(!$tramite){
            \Session::flash('errorModal','No se encontró el trámite del candidato.');
            return redirect()->back();
        }

        if($this->tramiteNoAtentadoFueGenerado($tramite)){
            \Session::flash('errorModal','El trámite ya fue generado y no permite editar candidatos.');
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        if(!isset($form['cn']) || trim((string)$form['cn'])===''){
            \Session::flash('errorModal',$this->mensajeBloqueoGestionCandidatosNoAtentado());
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        $ci=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)$form['ci'],30));
        $nombre=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)$form['nombre'],120));
        $apellido=mb_strtoupper($this->limpiarTextoPlanoNoAtentado((string)$form['apellido'],120));
        $codSis=$this->limpiarTextoPlanoNoAtentado((string)($form['cod_sis'] ?? ''),50);

        if($ci==='' || $nombre==='' || $apellido===''){
            \Session::flash('errorModal','Los datos del candidato no son válidos.');
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        $noatentado=Noatentado::find($form['cn']);
        if(!$noatentado || (int)$noatentado->cod_dtra!==(int)$tramite->cod_dtra){
            \Session::flash('errorModal','No se encontró el candidato a editar.');
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        $persona=Persona::where('per_ci','=',$ci)->first();
        if(!$persona){
            $persona=Persona::create([
                'per_ci'=>$ci,
                'per_nombre'=>$nombre,
                'per_apellido'=>$apellido,
                'per_cod_sis'=>$codSis,
                'per_sistema'=>8,
            ]);
            SessionController::write('C','',json_encode($persona),'personas','8',$persona->id_per);
        }else{
            $antiguoPersona=json_encode($persona);
            $persona->per_nombre=$nombre;
            $persona->per_apellido=$apellido;
            $persona->per_cod_sis=$codSis;
            $persona->save();
            SessionController::write('U',$antiguoPersona,json_encode($persona),'personas','8',$persona->id_per);
        }

        $antiguoNoatentado=json_encode($noatentado);
        $noatentado->id_per=$persona->id_per;
        if(isset($form['cod_carg']) && (int)$form['cod_carg'] > 0){
            $noatentado->cod_carg = (int)$form['cod_carg'];
            // Limpiar texto libre si se seleccionó cargo oficial
            $noatentado->noa_cargo = null;
        }
        $noatentado->save();
        SessionController::write('U',$antiguoNoatentado,json_encode($noatentado),'noatentado.noatentado','8',$noatentado->cod_noa);
        return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
    }

    public function fe_eli_candidato($cod_noa){
        $candidato=Noatentado::find($cod_noa);
        if(!$candidato){
            abort(404,'No se encontró el candidato.');
        }

        $tramite=D_tramita::find($candidato->cod_dtra);
        if($this->tramiteNoAtentadoFueGenerado($tramite)){
            abort(403,'El trámite ya fue generado y no permite eliminar candidatos.');
        }

        $candidato=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
            ->where('cod_noa','=',$cod_noa)
            ->first();

        return view('servicios.no_atentado.tramite.fe_eli_candidato',compact('candidato'));
    }
    public function eli_candidato(Request $form){
        $form->validate(['cn'=>'required']);
        $candidato=Noatentado::find($form['cn']);
        if(!$candidato){
            \Session::flash('errorModal','No se encontró el candidato.');
            return redirect()->back();
        }

        $tramite=D_tramita::find($candidato->cod_dtra);
        if($this->tramiteNoAtentadoFueGenerado($tramite)){
            \Session::flash('errorModal','El trámite ya fue generado y no permite eliminar candidatos.');
            if($tramite){
                return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
            }
            return redirect()->back();
        }

        $candidato->delete();
        SessionController::write('D',json_encode($candidato),'','noatentado.noatentado','8',$candidato->cod_noa);
        
        \Session::flash('exito','Candidato eliminado correctamente.');
        return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
    }
    public function fe_agregar_excel($cod_dtra){
        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite.');
        }

        \Session::flash('errorModal',$this->mensajeBloqueoGestionCandidatosNoAtentado());
        return redirect("editar tramite convocatoria/".$tramite_noatentado->cod_con."/".$tramite_noatentado->cod_dtra);
    }

    public function fe_glosa($cod_dtra){
        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite solicitado.');
        }

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
                $preservarGlosa=$this->esTramiteAcreditativoDiplomaAcademico($tramite);
                $glosaExistente=trim((string)($tramite_noatentado->dtra_glosa ?? ''));
                if($preservarGlosa && $glosaExistente!=='' && $glosaExistente!=='0'){
                    if($modelo_glosa){
                        $tramite_noatentado->dtra_cod_glosa=$modelo_glosa->cod_glo;
                    }
                }else{
                    $tramite_noatentado->dtra_cod_glosa=$modelo_glosa->cod_glo;
                    $tramite_noatentado->dtra_glosa=Funciones::glosa_noatentado($tramite,$modelo_glosa,$tramite_noatentado,$convocatoria,$candidatos);
                }
            }else{
                $modelo_glosa=Glosa::find($tramite_noatentado->dtra_cod_glosa);
            }


            if(sizeof($candidatos)===1 && trim((string)$tramite_noatentado->dtra_glosa)!=='' && $tramite_noatentado->dtra_glosa!=='0'){
                // Pasar nombres RAW del DB — Funciones normaliza internamente solo para comparar
                $cargosConv=\App\Models\Noatentado\Cargo_convocatoria::where('cod_con','=',$tramite_noatentado->cod_con)
                    ->pluck('carg_nombre')
                    ->map(fn($n)=>trim((string)$n))
                    ->filter(fn($n)=>$n!=='')
                    ->values()
                    ->toArray();

                $tramite_noatentado->dtra_glosa=Funciones::ajustarGlosaNoAtentadoPorRol((string)$tramite_noatentado->dtra_glosa,$candidatos[0],$cargosConv);
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
        $form->validate([
            'cd'=>'required|integer',
            'glosa'=>'nullable|string',
            'posicion'=>'nullable|integer',
        ]);

        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cd']);
        if(!$tramite_noatentado){
            \Session::flash('error','No se encontró el trámite seleccionado.');
            return redirect()->back();
        }

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

        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite solicitado.');
        }

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
        $documento_tramite=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$documento_tramite){
            abort(404,'No se encontró el trámite seleccionado.');
        }

        $tramite=Tramite::find($documento_tramite->cod_tre);
        $eliminar=1;
        if($this->tramiteNoAtentadoFueGenerado($documento_tramite)){
            $eliminar=0;
        }
        return view('servicios.no_atentado.tramite.f_eli_tramite',compact('tramite','documento_tramite','eliminar'));
    }

    public function eli_tramite(Request $form){
        $form->validate([
            'cd'=>'required|integer',
        ]);
        $tramite=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cd']);
        if(!$tramite){
            \Session::flash('error','No se encontró el trámite seleccionado.');
            return redirect()->back();
        }

        $cod_con=$tramite->cod_con;
        if($this->tramiteNoAtentadoFueGenerado($tramite)){
            \Session::flash('error','No se puede eliminar el trámite porque ya fue generado');
        }else{
            // Eliminar candidatos primero
            $candidatos = Noatentado::where('cod_dtra', '=', $tramite->cod_dtra)->get();
            foreach($candidatos as $cand) {
                $cand->delete();
                SessionController::write('D',json_encode($cand),'','noatentado.noatentado','8',$cand->cod_noa);
            }
            
            $antiguo=json_encode($tramite);
            SessionController::write('D',$antiguo,'','d_tramitas','8',$tramite->cod_dtra);
            $this->eliminarUsosRecaudacionPorTramite((int)$tramite->cod_dtra);
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
                ->where('d_tramitas.dtra_tipo','=','A')
                ->select('d_tramitas.*','tramites.*')->first();
            if(!$tramite_noatentado){
                abort(404,'No se encontró el trámite seleccionado.');
            }
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
        if (!config('apoderado.habilitado', true)) {
            abort(404);
        }
        $form->validate([
            'cdtra'=>'required|integer',
            'ci'=>'required|string|max:30',
            'apellido'=>'required|string|max:120',
            'nombre'=>'required|string|max:120',
            'tipo'=>'required|string|max:5',
        ]);

        if (config('apoderado.requiere_boleta_dj', false) && ($form['tipo'] === 'd' || $form['tipo'] === 'a')) {
            $form->validate([
                'control_boleta' => 'required|string',
                'control_boleta_valido' => 'required|in:1',
            ]);
        }

        $tramita=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cdtra']);
        if(!$tramita){
            \Session::flash('error','No se encontró el trámite seleccionado.');
            return redirect()->back();
        }

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
            }else{
                $apoderado->apo_apellido=mb_strtoupper($form['apellido']);
                $apoderado->apo_nombre=mb_strtoupper($form['nombre']);
                $apoderado->save();
            }
            $tramita->cod_apo=$apoderado->cod_apo;
            $tramita->dtra_tipo_apoderado=$form['tipo'];
            $tramita->save();

            if(isset($form['control_boleta'])){
                $controlStr = preg_replace('/[^0-9]/','', $form['control_boleta']);
                if($controlStr !== ''){
                    $identificador = 'APO_NOATENTADO_'.$controlStr;
                    $existe = \Illuminate\Support\Facades\DB::table('recaudacion_usos')->where('identificador', $identificador)->exists();
                    if(!$existe) {
                        \Illuminate\Support\Facades\DB::table('recaudacion_usos')->insert([
                            'identificador' => $identificador,
                            'recibo' => $controlStr,
                            'documento' => $form['ci'] ?? '',
                            'nombre_persona' => ($form['nombre'] ?? '') . ' ' . ($form['apellido'] ?? ''),
                            'cod_tra' => $tramita->cod_dtra,
                            'modulo' => 'noatentado',
                            'tramite' => 'Apoderado Declaración Jurada',
                            'monto' => isset($form['monto_boleta']) ? floatval($form['monto_boleta']) : 0,
                            'usuario_registro' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'sistema',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $nuevo=json_encode($apoderado);
            SessionController::write('C','',$nuevo,'apoderados','8',$apoderado->cod_apo);
        }else{
            $apoderado=Apoderado::find($tramita->cod_apo);
            $apoderado->apo_apellido=$form['apellido'];
            $apoderado->apo_nombre=$form['nombre'];
            $tramita->dtra_tipo_apoderado=$form['tipo'];
            $tramita->save();
            $apoderado->save();

            if(isset($form['control_boleta'])){
                $controlStr = preg_replace('/[^0-9]/','', $form['control_boleta']);
                if($controlStr !== ''){
                    $identificador = 'APO_NOATENTADO_'.$controlStr;
                    $existe = \Illuminate\Support\Facades\DB::table('recaudacion_usos')->where('identificador', $identificador)->exists();
                    if(!$existe) {
                        \Illuminate\Support\Facades\DB::table('recaudacion_usos')->insert([
                            'identificador' => $identificador,
                            'recibo' => $controlStr,
                            'documento' => $form['ci'] ?? '',
                            'nombre_persona' => ($form['nombre'] ?? '') . ' ' . ($form['apellido'] ?? ''),
                            'cod_tra' => $tramita->cod_dtra,
                            'modulo' => 'noatentado',
                            'tramite' => 'Apoderado Declaración Jurada',
                            'monto' => isset($form['monto_boleta']) ? floatval($form['monto_boleta']) : 0,
                            'usuario_registro' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'sistema',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $nuevo=json_encode($apoderado);
            SessionController::write('U',$antiguo,$nuevo,'d_tramita','8',$apoderado->cod_apo);
        }
        \Session::flash('exito','Se ha guardado exitosamente los datos del apoderado');
        //return redirect('datos apoderado/'.$tramita->cod_tra);

        return redirect('formulario entrega tramite noatentado/'.$tramita->cod_dtra);
    }
    public function g_entrega(Request $form){
        $form->validate([
            'cdtra'=>'required|integer',
            'tipo'=>'required|string',
        ]);

            $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cdtra']);
            if(!$tramite_noatentado){
                \Session::flash('error','No se encontró el trámite seleccionado.');
                return redirect()->back();
            }

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

        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$cod_dtra);
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite seleccionado.');
        }

        $tramite=Tramite::find($tramite_noatentado->cod_tre);
        $noatentado=DB::table('noatentado.noatentado')
            ->join('personas','noatentado.id_per','=','personas.id_per')
            ->where('cod_dtra','=',$tramite_noatentado->cod_dtra)->get();
        return view('servicios.no_atentado.tramite.f_corregir_tramite_noa',compact('tramite_noatentado','noatentado','tramite'));
    }
    public function corregir_tramite_noa(Request $form){
        $form->validate([
            'cd'=>'required|integer'
        ]);
        $tramite_noatentado=$this->obtenerTramiteNoAtentadoPorCodigo((int)$form['cd']);
        if(!$tramite_noatentado){
            \Session::flash('error','No se encontró el trámite seleccionado.');
            return redirect()->back();
        }

        $tramite=Tramite::find($tramite_noatentado->cod_tre);
        $preservarGlosa=$this->esTramiteAcreditativoDiplomaAcademico($tramite);

        $tramite_noatentado->dtra_entregado=null;
        $tramite_noatentado->dtra_fecha_recojo=null;
        if(!$preservarGlosa){
            $tramite_noatentado->dtra_cod_glosa=null;
        }
        $tramite_noatentado->dtra_generado=null;
        $tramite_noatentado->save();
        SessionController::write('U','','Editar noatentado','d_tramitas','8',$tramite_noatentado->cod_dtra);
        \Session::flash('exito','Ahora puede editar el trámite '.$tramite_noatentado->dtra_numero_tramite." / ".$tramite_noatentado->dtra_gestion_tramite);
        return redirect('listar tramite convocatoria/'.$tramite_noatentado->cod_con);
    }
    public function f_conf_entrega_noa($cod_dtra){
        $tramite_noatentado=DB::table('d_tramitas')
            ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('cod_dtra','=',$cod_dtra)->where('dtra_generado','=','t')->where('d_tramitas.dtra_tipo','=','A')
            ->select('tre_nombre','d_tramitas.*')->first();
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite seleccionado.');
        }

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
