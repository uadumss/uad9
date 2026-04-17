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
use App\Models\Noatentado\Noatentado;
use App\Models\Persona;
use App\Models\Tramite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $convocatoria=Convocatoria::find($cod_con);
        $tramites=Tramite::where('tre_tipo','=','A')->get();
        $cargos=Cargo_convocatoria::where('cod_con','=',$cod_con)->orderBy('carg_nombre','ASC')->get();
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
        return view('servicios.no_atentado.tramite.fe_noatentado_convocatoria',compact('convocatoria','tramites','tramite_noatentado','noatentados','cod_con','cargos'));
    }

    public function validar_pago_noatentado(Request $request,$cod_con){
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
        // El tipo de trámite se determina automáticamente desde la cuenta del pago.
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

        if((bool)($validacionPrincipal['ok'] ?? false) && (bool)($validacionReintegro['ok'] ?? false)){
            $respuesta=$validacionPrincipal;
            $respuesta['validacion_reintegro']=$validacionReintegro;
            $respuesta=$this->adjuntarMontosValidacionPagoNoAtentado($respuesta,$validacionPrincipal,$validacionReintegro);
            return response()->json($respuesta);
        }

        if(!(bool)($validacionPrincipal['ok'] ?? false)){
            $respuesta=$validacionPrincipal;
            $respuesta['validacion_reintegro']=$validacionReintegro;
            $respuesta=$this->adjuntarMontosValidacionPagoNoAtentado($respuesta,$validacionPrincipal,$validacionReintegro);
            return response()->json($respuesta);
        }

        $respuestaReintegro=$validacionReintegro;
        $respuestaReintegro['validacion_principal']=$validacionPrincipal;
        $respuestaReintegro['validacion_reintegro']=$validacionReintegro;
        $respuestaReintegro=$this->adjuntarMontosValidacionPagoNoAtentado($respuestaReintegro,$validacionPrincipal,$validacionReintegro);
        return response()->json($respuestaReintegro);
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
        $form->validate(['cc'=>'required']);
        $form->validate([
            'control'=>'required',
        ]);
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

        $tramite_noatentado=array();
        if(isset($form['cd']) && $form['cd']!=''){
            $tramite_noatentado=D_tramita::find($form['cd']);
            if(!$tramite_noatentado){
                return $responderError('No se encontró el trámite a editar.','listar tramite convocatoria/'.$form['cc'],404);
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
            // El tipo de trámite se define automáticamente desde recaudaciones.
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

            $controlReintegroGuardar=trim((string)($validacionReintegro['control'] ?? ($form['reintegro'] ?? '')));
            $controlReintegroNormalizado=$this->normalizarNumeroNoAtentado($controlReintegroGuardar);
            $controlReintegroGuardarValor=$controlReintegroNormalizado!=='' ? $controlReintegroNormalizado : null;

            $codTreSugerido=(int)($validacionPago['tipo_noatentado_sugerido'] ?? 0);
            if($codTreSugerido<=0){
                return $responderError(
                    'No se pudo determinar automáticamente el tipo de trámite desde la validación de pago.',
                    'editar tramite convocatoria/'.$form['cc'].'/0',
                    422
                );
            }
            $codTreFormulario=$codTreSugerido;

            $año_tramita=date('Y');
            $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
            $numero_tramite+=1;

            DB::beginTransaction();
            try{
                $tramite_noatentado=D_tramita::create([
                    'cod_con'=>$form['cc'],
                    'cod_tre'=>$codTreFormulario,
                    'dtra_interno'=>$form['tipo_tramite'],
                    'dtra_control'=>$form['control'],
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
                }elseif((bool)config('app.debug')){
                    $detalle=trim((string)$e->getMessage());
                    if($detalle!==''){
                        $mensajeError='No se pudo guardar el trámite. '.$detalle;
                    }
                }

                return $responderError($mensajeError,'editar tramite convocatoria/'.$form['cc'].'/0',($e instanceof \RuntimeException) ? 422 : 500);
            }
        }
        $rutaRedireccion="editar tramite convocatoria/".$form['cc']."/".$tramite_noatentado->cod_dtra;
        if($esPeticionAjax){
            return response()->json([
                'ok'=>true,
                'redirect'=>url($rutaRedireccion),
                'cod_dtra'=>$tramite_noatentado->cod_dtra,
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

        if(!is_numeric($control)){
            return [
                'ok'=>false,
                'code'=>'CONTROL_INVALIDO',
                'message'=>'El numero de control debe contener solo numeros.',
            ];
        }

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

        if(!is_numeric($controlReintegro)){
            return [
                'ok'=>false,
                'code'=>'REINTEGRO_CONTROL_INVALIDO',
                'message'=>'El número de control de reintegro debe contener solo números.',
                'aplica'=>true,
                'control'=>$controlReintegro,
                ...$metaReintegro,
            ];
        }

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
        $identificador=trim((string)($fila['identificador'] ?? ''));
        if($identificador!==''){
            return 'id:'.$identificador;
        }

        $fecha=trim((string)($fila['fecha'] ?? ''));
        $documento=$this->normalizarDocumentoNoAtentado((string)($fila['documento'] ?? ''));
        $preimpreso=$this->normalizarPreimpresoFilaNoAtentado($fila);
        return 'alt:'.$control.'|'.$fecha.'|'.$documento.'|'.$preimpreso;
    }

    private function consultarControlPrincipalDocumentoRecaudacionesNoAtentado(string $control,string $documento): array
    {
        try{
            $request=Request::create('/api/recaudaciones/buscar-control-documento', 'POST',[
                'unidad'=>122,
                'recibo'=>(int)$control,
                'documento'=>$documento,
            ]);
            $response=app(RecaudacionesController::class)->buscarPorControlYDocumento($request);
            if(!($response instanceof JsonResponse)){
                return [
                    'ok'=>false,
                    'code'=>'API_RESPUESTA_INVALIDA',
                    'message'=>'No se pudo validar el control y carnet en recaudaciones. Intente nuevamente.',
                ];
            }
            $json=$response->getData(true);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones por control+documento para pago principal No Atentado.',[
                'control'=>$control,
                'documento'=>$documento,
                'error'=>$e->getMessage(),
            ]);
            return [
                'ok'=>false,
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'No se pudo conectar con recaudaciones. Intente nuevamente.',
            ];
        }

        if(!is_array($json) || !(bool)($json['ok'] ?? false)){
            $mensaje=trim((string)($json['message'] ?? data_get($json,'error.message','')));
            $status=(int)($json['status'] ?? 0);
            $errorMap=$this->mapearMensajeErrorRecaudacionNoAtentado($mensaje,$status);

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

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($json['data'] ?? []));

        return [
            'ok'=>true,
            'resultados'=>$resultado,
        ];
    }

    private function consultarControlDocumentoRecaudacionesNoAtentado(string $control,string $documento): array
    {
        try{
            $request=Request::create('/api/recaudaciones/buscar-control-documento', 'POST',[
                'unidad'=>122,
                'recibo'=>(int)$control,
                'documento'=>$documento,
            ]);
            $response=app(RecaudacionesController::class)->buscarPorControlYDocumento($request);
            if(!($response instanceof JsonResponse)){
                return [
                    'ok'=>false,
                    'code'=>'API_RESPUESTA_INVALIDA',
                    'message'=>'No se pudo validar el control de reintegro en recaudaciones. Intente nuevamente.',
                ];
            }
            $json=$response->getData(true);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones por control+documento para reintegro No Atentado.',[
                'control'=>$control,
                'documento'=>$documento,
                'error'=>$e->getMessage(),
            ]);
            return [
                'ok'=>false,
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'No se pudo conectar con recaudaciones. Intente nuevamente.',
            ];
        }

        if(!is_array($json) || !(bool)($json['ok'] ?? false)){
            $mensaje=trim((string)($json['message'] ?? data_get($json,'error.message','')));
            $status=(int)($json['status'] ?? 0);
            $errorMap=$this->mapearMensajeErrorRecaudacionNoAtentado($mensaje,$status);

            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($json['data'] ?? []));

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
            $request=Request::create('/api/recaudaciones/buscar-control', 'POST',[
                'unidad'=>122,
                'recibo'=>(int)$control,
            ]);
            $response=app(RecaudacionesController::class)->buscarPorControl($request);
            if(!($response instanceof JsonResponse)){
                return [
                    'ok'=>false,
                    'code'=>'API_RESPUESTA_INVALIDA',
                    'message'=>'No se pudo validar el control en recaudaciones. Intente nuevamente.',
                ];
            }
            $json=$response->getData(true);
        }catch(\Throwable $e){
            Log::warning('Error inesperado al consultar recaudaciones para No Atentado.',[
                'control'=>$control,
                'error'=>$e->getMessage(),
            ]);
            return [
                'ok'=>false,
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'No se pudo conectar con recaudaciones. Intente nuevamente.',
            ];
        }

        if(!is_array($json) || !(bool)($json['ok'] ?? false)){
            $mensaje=trim((string)($json['message'] ?? data_get($json,'error.message','')));
            $status=(int)($json['status'] ?? 0);
            $errorMap=$this->mapearMensajeErrorRecaudacionNoAtentado($mensaje,$status);

            return [
                'ok'=>false,
                'code'=>$errorMap['code'],
                'message'=>$errorMap['message'],
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($json['data'] ?? []));

        return [
            'ok'=>true,
            'resultados'=>$resultado,
        ];
    }

    private function mapearMensajeErrorRecaudacionNoAtentado(string $mensajeApi,int $status=0): array
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
            strpos($msgNorm,'configur')!==false ||
            strpos($msgNorm,'services/.env')!==false ||
            strpos($msgNorm,'no esta configurado')!==false ||
            strpos($msgNorm,'no está configurado')!==false
        ){
            return [
                'code'=>'SISTEMA_NO_CONFIGURADO',
                'message'=>'Recaudaciones no está configurado. Contacte al área de sistemas.',
            ];
        }

        if(
            $status===404 ||
            strpos($msgNorm,'not found')!==false ||
            strpos($msgNorm,'no se encuentra')!==false ||
            strpos($msgNorm,'no encontrado')!==false ||
            strpos($msgNorm,'control')!==false ||
            strpos($msgNorm,'recibo')!==false
        ){
            return [
                'code'=>'CONTROL_NO_ENCONTRADO',
                'message'=>'No se encontró información del número de control en recaudaciones.',
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
            strpos($msgNorm,'timeout')!==false ||
            strpos($msgNorm,'sin conexion')!==false ||
            strpos($msgNorm,'sin conexión')!==false
        ){
            return [
                'code'=>'API_NO_DISPONIBLE',
                'message'=>'Sin conexión con recaudaciones. Intente nuevamente.',
            ];
        }

        return [
            'code'=>'API_RECAUDACIONES_ERROR',
            'message'=>'No se pudo validar el control en recaudaciones. Intente nuevamente.',
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

        $identificador=trim((string)($fila['identificador'] ?? ''));
        $fechaPago=trim((string)($fila['fecha'] ?? ''));
        $preimpreso=trim((string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? ''))));

        $query=DB::table('recaudacion_usos');
        if($identificador!==''){
            $query->where('identificador','=',$identificador);
        }else{
            $query->where('recibo','=',$control);
            if($fechaPago!==''){
                $query->where('fecha_pago','=',$fechaPago);
            }
            if($preimpreso!==''){
                $query->where('preimpreso','=',$preimpreso);
            }
        }

        if($codDtra>0){
            $query->where('cod_dtra','<>',$codDtra);
        }

        return $query->orderBy('created_at','DESC')->first();
    }

    private function registrarUsoRecaudacionNoAtentado(array $validacion,int $codTra,int $codDtra,string &$error): bool
    {
        $error='';
        if(!Schema::hasTable('recaudacion_usos')){
            $error='No existe la tabla de bloqueo de pagos.';
            return false;
        }

        $identificador=trim((string)($validacion['identificador'] ?? ''));
        $recibo=trim((string)($validacion['control'] ?? ''));
        $fechaPago=trim((string)($validacion['fecha_pago'] ?? ''));
        $preimpreso=trim((string)($validacion['preimpreso'] ?? ''));

        $query=DB::table('recaudacion_usos');
        if($identificador!==''){
            $query->where('identificador','=',$identificador);
        }else{
            if($recibo===''){
                $error='No se pudo registrar el bloqueo del pago.';
                return false;
            }
            $query->where('recibo','=',$recibo);
            if($fechaPago!==''){
                $query->where('fecha_pago','=',$fechaPago);
            }
            if($preimpreso!==''){
                $query->where('preimpreso','=',$preimpreso);
            }
        }

        if($codDtra>0){
            $query->where('cod_dtra','<>',$codDtra);
        }

        if($query->exists()){
            $error='Este pago ya fue utilizado en otro trámite.';
            return false;
        }

        try{
            DB::table('recaudacion_usos')->insert([
                'identificador'=>$identificador,
                'recibo'=>$recibo,
                'preimpreso'=>$preimpreso,
                'fecha_pago'=>$fechaPago,
                'documento'=>(string)($validacion['documento'] ?? ''),
                'nombre_persona'=>(string)($validacion['nombre_persona'] ?? ''),
                'cajero'=>(string)($validacion['cajero'] ?? ''),
                'cod_tra'=>$codTra,
                'cod_dtra'=>$codDtra,
                'usuario_registro'=>Auth::check() ? Auth::user()->name : 'sistema',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }catch(\Throwable $e){
            Log::error('Error al registrar uso de recaudacion para No Atentado.',[
                'cod_dtra'=>$codDtra,
                'identificador'=>$identificador,
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
        $nombre=trim((string)($uso->nombre_persona ?? ''));
        $documento=trim((string)($uso->documento ?? ''));
        $fecha=trim((string)($uso->created_at ?? ''));

        if($fecha!==''){
            $timestamp=strtotime($fecha);
            if($timestamp!==false){
                $fecha=date('d/m/Y H:i',$timestamp);
            }
        }

        $mensaje='Este pago ya fue utilizado';
        if($nombre!=='' || $documento!==''){
            $mensaje.=' por '.$nombre;
            if($documento!==''){
                $mensaje.=' (CI '.$documento.')';
            }
        }
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

            $ci=mb_strtoupper(trim((string)($item['ci'] ?? '')));
            $nombre=mb_strtoupper(trim((string)($item['nombre'] ?? '')));
            $apellido=mb_strtoupper(trim((string)($item['apellido'] ?? '')));
            $codSis=trim((string)($item['cod_sis'] ?? ''));
            $unidad=trim((string)($item['unidad'] ?? ''));
            $cargoTexto=$this->normalizarTextoCargoNoAtentado((string)($item['cargo'] ?? ''));
            $cargoConvocatoria=(int)($item['cargo_convocatoria'] ?? 0);

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
            ]);

            $codCargo=$this->resolverCargoCandidatoNoAtentado($cargoTexto,$cargoConvocatoria,$tramite);
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

    //=================== CANDIDATOS
    public function fe_candidato($cod_dtra,$cod_noa){
        $candidato=array();
        $tramite=D_tramita::find($cod_dtra);
        if(!$tramite){
            abort(404,'No se encontró el trámite del candidato.');
        }

        if((int)$cod_noa===0){
            abort(403,$this->mensajeBloqueoGestionCandidatosNoAtentado());
        }

        if((string)($tramite->dtra_generado ?? '')!==''){
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
        $form->validate([
            'cd'=>'required',
            'ci'=>'required',
            'nombre'=>'required',
            'apellido'=>'required',
        ]);
        $tramite=D_tramita::find($form['cd']);
        if(!$tramite){
            \Session::flash('errorModal','No se encontró el trámite del candidato.');
            return redirect()->back();
        }

        if((string)($tramite->dtra_generado ?? '')!==''){
            \Session::flash('errorModal','El trámite ya fue generado y no permite editar candidatos.');
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        if(!isset($form['cn']) || trim((string)$form['cn'])===''){
            \Session::flash('errorModal',$this->mensajeBloqueoGestionCandidatosNoAtentado());
            return redirect('editar tramite convocatoria/'.$tramite->cod_con.'/'.$tramite->cod_dtra);
        }

        $ci=mb_strtoupper(trim((string)$form['ci']));
        $nombre=mb_strtoupper(trim((string)$form['nombre']));
        $apellido=mb_strtoupper(trim((string)$form['apellido']));
        $codSis=trim((string)($form['cod_sis'] ?? ''));

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
        $noatentado->save();
        SessionController::write('U',$antiguoNoatentado,json_encode($noatentado),'noatentado.noatentado','8',$noatentado->cod_noa);
        return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
    }

    public function fe_eli_candidato($cod_noa){
        $candidato=Noatentado::find($cod_noa);
        if(!$candidato){
            abort(404,'No se encontró el candidato.');
        }

        abort(403,$this->mensajeBloqueoGestionCandidatosNoAtentado());
    }
    public function eli_candidato(Request $form){
        $form->validate(['cn'=>'required']);
        $candidato=Noatentado::find($form['cn']);
        if(!$candidato){
            \Session::flash('errorModal','No se encontró el candidato.');
            return redirect()->back();
        }

        $tramite=D_tramita::find($candidato->cod_dtra);
        \Session::flash('errorModal',$this->mensajeBloqueoGestionCandidatosNoAtentado());
        if($tramite){
            return redirect("editar tramite convocatoria/".$tramite->cod_con."/".$tramite->cod_dtra);
        }

        return redirect()->back();
    }
    public function fe_agregar_excel($cod_dtra){
        $tramite_noatentado=D_tramita::find($cod_dtra);
        if(!$tramite_noatentado){
            abort(404,'No se encontró el trámite.');
        }

        \Session::flash('errorModal',$this->mensajeBloqueoGestionCandidatosNoAtentado());
        return redirect("editar tramite convocatoria/".$tramite_noatentado->cod_con."/".$tramite_noatentado->cod_dtra);
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
        $tramite=D_tramita::find($form['cd']);
        if(!$tramite){
            \Session::flash('error','No se encontró el trámite seleccionado.');
            return redirect()->back();
        }

        $cod_con=$tramite->cod_con;
        $noatentado=Noatentado::where('cod_dtra','=',$form['cd'])->first();
        if($noatentado){
            \Session::flash('error','No se puede eliminar el tramite');
        }else{
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
