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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TramiteNoAtentadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:crear tramite - noa|editar tramite - noa'], ['only' => ['fe_noatentado_convocatoria','g_tramite_convocatoria','fe_candidato',
            'g_candidato','fe_eli_candidato','eli_candidato','fe_agregar_excel','g_excel_noatentado','validar_pago_noatentado']]);
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
            'tramite'=>'required|integer',
            'control'=>'required',
        ]);

        $control=trim((string)$request['control']);
        $codTre=(int)$request['tramite'];
        $validacion=$this->validarControlPagoNoAtentado($control,$codTre,0);

        return response()->json($validacion);
    }

    public function g_tramite_convocatoria(Request $form){
        $form->validate(['cc'=>'required']);
        $form->validate([
            'control'=>'required',
        ]);
        $tramite_noatentado=array();
        if(isset($form['cd']) && $form['cd']!=''){
            $tramite_noatentado=D_tramita::find($form['cd']);
            if(!$tramite_noatentado){
                \Session::flash('errorModal','No se encontró el trámite a editar.');
                return redirect('listar tramite convocatoria/'.$form['cc']);
            }

            $antiguo=json_encode($tramite_noatentado);
            $tramite_noatentado->dtra_interno=$form['tipo_tramite'];
            $tramite_noatentado->dtra_control=$form['control'];
            $tramite_noatentado->dtra_valorado_reintegro=$form['reintegro'];
            $tramite_noatentado->save();
            $nuevo=json_encode($tramite_noatentado);
            SessionController::write('U',$antiguo,$nuevo,'d_tramitas','8',$tramite_noatentado->cod_dtra);
            \Session::flash('exitoModal','Se ha editado satisfactoriamente el tramite');
        }else{
            $form->validate([
                'tramite'=>'required',
                'candidatos_json'=>'required',
            ]);

            $candidatos=json_decode((string)$form['candidatos_json'],true);
            if(!is_array($candidatos) || sizeof($candidatos)===0){
                \Session::flash('errorModal','Debe registrar al menos un candidato antes de guardar el trámite.');
                return redirect('editar tramite convocatoria/'.$form['cc'].'/0');
            }

            $validacionPago=$this->validarControlPagoNoAtentado((string)$form['control'],(int)$form['tramite'],0);
            if(!(bool)($validacionPago['ok'] ?? false)){
                \Session::flash('errorModal',(string)($validacionPago['message'] ?? 'No se pudo validar el pago del trámite.'));
                return redirect('editar tramite convocatoria/'.$form['cc'].'/0');
            }

            $año_tramita=date('Y');
            $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
            $numero_tramite+=1;

            DB::beginTransaction();
            try{
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

                $cantidadCandidatos=$this->guardarCandidatosDesdeJson($candidatos,$tramite_noatentado);
                if($cantidadCandidatos===0){
                    throw new \RuntimeException('No se registraron candidatos válidos para el trámite.');
                }

                $errorUso='';
                if(!$this->registrarUsoRecaudacionNoAtentado($validacionPago,0,(int)$tramite_noatentado->cod_dtra,$errorUso)){
                    throw new \RuntimeException($errorUso!=='' ? $errorUso : 'No se pudo registrar el bloqueo del pago.');
                }

                DB::commit();
                \Session::flash('exitoModal','Se ha creado satisfactoriamente el tramite');
            }catch(\Throwable $e){
                DB::rollBack();
                Log::error('Error al registrar trámite no atentado.',[
                    'cod_con'=>$form['cc'],
                    'error'=>$e->getMessage(),
                ]);
                \Session::flash('errorModal','No se pudo guardar el trámite. Intente nuevamente.');
                return redirect('editar tramite convocatoria/'.$form['cc'].'/0');
            }
        }
        return redirect("editar tramite convocatoria/".$form['cc']."/".$tramite_noatentado->cod_dtra);
    }

    private function validarControlPagoNoAtentado(string $control,int $codTre,int $codDtra=0): array
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

        $tramite=Tramite::where('cod_tre','=',$codTre)->where('tre_tipo','=','A')->first();
        if(!$tramite){
            return [
                'ok'=>false,
                'code'=>'TRAMITE_INVALIDO',
                'message'=>'El tramite seleccionado no corresponde a No Atentado.',
            ];
        }

        $cuentaEsperada=(string)($tramite->tre_numero_cuenta ?? '');
        $cuentaEsperadaNormalizada=$this->normalizarNumeroNoAtentado($cuentaEsperada);
        if($cuentaEsperadaNormalizada===''){
            return [
                'ok'=>false,
                'code'=>'CUENTA_TRAMITE_NO_CONFIGURADA',
                'message'=>'El tramite seleccionado no tiene numero de cuenta configurado.',
            ];
        }

        $consulta=$this->consultarControlRecaudacionesNoAtentado($control);
        if(!(bool)($consulta['ok'] ?? false)){
            return $consulta;
        }

        $filas=$consulta['resultados'] ?? [];
        if(sizeof($filas)===0){
            return [
                'ok'=>false,
                'code'=>'CONTROL_NO_ENCONTRADO',
                'message'=>'No se encontro informacion del numero de control en recaudaciones.',
            ];
        }

        foreach($filas as $filaItem){
            $fila=(array)$filaItem;
            $codigoCuenta=(string)($fila['codigo_cuenta'] ?? '');
            $codigoCuentaNormalizado=$this->normalizarNumeroNoAtentado($codigoCuenta);

            if($codigoCuentaNormalizado==='' || $codigoCuentaNormalizado!==$cuentaEsperadaNormalizada){
                continue;
            }

            $usoExistente=$this->usoRecaudacionExistenteNoAtentado($fila,$control,$codDtra);
            if($usoExistente){
                return [
                    'ok'=>false,
                    'code'=>'PAGO_YA_USADO',
                    'message'=>$this->mensajePagoUsadoNoAtentado($usoExistente),
                ];
            }

            $nombrePersona=$this->nombrePersonaFilaRecaudacionNoAtentado($fila);
            $preimpreso=(string)($fila['preimpreso'] ?? ($fila['fmesa_numero_preimpreso'] ?? ($fila['impreso'] ?? '')));

            return [
                'ok'=>true,
                'message'=>'Pago validado correctamente para No Atentado.',
                'control'=>$control,
                'identificador'=>(string)($fila['identificador'] ?? ''),
                'codigo_cuenta'=>$codigoCuenta,
                'cuenta'=>(string)($fila['cuenta'] ?? ''),
                'fecha_pago'=>(string)($fila['fecha'] ?? ''),
                'cajero'=>(string)($fila['cajero'] ?? ''),
                'documento'=>(string)($fila['documento'] ?? ''),
                'nombre_persona'=>$nombrePersona,
                'preimpreso'=>$preimpreso,
            ];
        }

        return [
            'ok'=>false,
            'code'=>'CUENTA_NO_CORRESPONDE',
            'message'=>'La boleta no corresponde al numero de cuenta del tramite No Atentado seleccionado.',
            'cuenta_esperada'=>$cuentaEsperada,
        ];
    }

    private function consultarControlRecaudacionesNoAtentado(string $control): array
    {
        try{
            $request=Request::create('/', 'POST',[
                'unidad'=>122,
                'recibo'=>(int)$control,
            ]);
            $response=(new RecaudacionesController())->buscarPorControl($request);
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

        if(!(bool)($json['ok'] ?? false)){
            $mensaje=trim((string)($json['message'] ?? data_get($json,'error.message','')));
            if($mensaje===''){
                $mensaje='No se pudo validar el control en recaudaciones.';
            }

            $msgNorm=mb_strtolower($mensaje);
            if(strpos($msgNorm,'configur')!==false){
                $mensaje='Recaudaciones no esta configurado. Contacte a sistemas.';
            }elseif(
                strpos($msgNorm,'comunicacion')!==false ||
                strpos($msgNorm,'api')!==false ||
                strpos($msgNorm,'timeout')!==false
            ){
                $mensaje='Sin conexion con recaudaciones. Intente nuevamente.';
            }

            return [
                'ok'=>false,
                'code'=>'API_RECAUDACIONES_ERROR',
                'message'=>$mensaje,
            ];
        }

        $resultado=$this->extraerResultadoRecaudacionNoAtentado((array)($json['data'] ?? []));

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
            $cargoTexto=trim((string)($item['cargo'] ?? ''));
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

        $cargoTexto=mb_strtoupper(trim($cargoTexto));
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

    private function nombrePersonaFilaRecaudacionNoAtentado(array $fila): string
    {
        $nombre=trim((string)($fila['nombre_persona'] ?? ''));
        if($nombre!==''){
            return $nombre;
        }

        $nombre=trim((string)($fila['apellido_1'] ?? '').' '.(string)($fila['apellido_2'] ?? '').' '.(string)($fila['nombre_1'] ?? '').' '.(string)($fila['nombre_2'] ?? ''));
        return $nombre;
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
                ->leftJoin('claustros.cargo_convocatoria','noatentado.cod_carg','=','cargo_convocatoria.cod_carg')
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
        if(!$tramite){
            \Session::flash('errorModal','No se encontró el trámite del candidato.');
            return redirect()->back();
        }

        $ci=mb_strtoupper(trim((string)$form['ci']));
        $nombre=mb_strtoupper(trim((string)$form['nombre']));
        $apellido=mb_strtoupper(trim((string)$form['apellido']));
        $codSis=trim((string)($form['cod_sis'] ?? ''));
        $unidad=trim((string)($form['unidad'] ?? ''));

        if(isset($form['cn']) && $form['cn']!=''){
            $noatentado=Noatentado::find($form['cn']);
            if(!$noatentado){
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
            $noatentado->noa_unidad=$unidad;
            $codCargo=$this->resolverCargoCandidatoNoAtentado(
                (string)($form['cargo'] ?? ''),
                (int)($form['cargo_convocatoria'] ?? 0),
                $tramite
            );
            $noatentado->cod_carg=$codCargo>0 ? $codCargo : null;
            $noatentado->save();
            SessionController::write('U',$antiguoNoatentado,json_encode($noatentado),'noatentado.noatentado','8',$noatentado->cod_noa);

        }else{
            $persona=Persona::where('per_ci','=',$ci)->first();
            $id_per=0;
            if(!$persona){
                $persona=Persona::create([
                    'per_ci'=>$ci,
                    'per_nombre'=>$nombre,
                    'per_apellido'=>$apellido,
                    'per_cod_sis'=>$codSis,
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
                'noa_unidad'=>$unidad,
            ]);
            $codCargo=$this->resolverCargoCandidatoNoAtentado(
                (string)($form['cargo'] ?? ''),
                (int)($form['cargo_convocatoria'] ?? 0),
                $tramite
            );
            $noatentado->cod_carg=$codCargo>0 ? $codCargo : null;
            $noatentado->save();
            $nuevo=json_encode($noatentado);
            SessionController::write('C','',$nuevo,'noatentado.noatentado','8',$noatentado->cod_noa);
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
