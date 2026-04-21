<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CarreraAcreditacion;
use App\Models\CarreraHistorialNombre;
use App\Models\Facultad;
use App\Models\FacultadHistorialNombre;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultadController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear editar facultad - f'], ['only' => ['fe_facultad','g_facultad']]);
        $this->middleware(['permission:eliminar facultad - f'], ['only' => ['f_eli_facultad','eli_facultad']]);
        $this->middleware(['permission:crear editar carrera - f'], ['only' => ['fe_carrera','g_carrera']]);
        $this->middleware(['permission:eliminar carrera - f'], ['only' => ['f_eli_carrera','eli_carrera']]);

    }
    public function l_facultad(){
        $facultades=Facultad::all()->sortBy('cod_fac');
        return view('unidad.facultad.l_facultad',compact('facultades'));
    }
    public function fe_facultad($cod_fac){
        $facultad="";
        if($cod_fac!=0){
            $facultad=Facultad::find($cod_fac);
        }
        return view('unidad.facultad.fe_facultad',compact('facultad','cod_fac'));
    }
    public function g_facultad(Request $form){
        $nombre=trim((string)$form['nombre']);
        $corto=trim((string)$form['corto']);
        $facultades=Facultad::all()->where('fac_nombre','=',$nombre)->where('cod_fac','<>',$form['cf']);
        if(sizeof($facultades)<1) {
            if (isset($form['cf'])) {
                $facultad = Facultad::find($form['cf']);
                $nombreAnterior=trim((string)$facultad->fac_nombre);
                $abreviacionAnterior=trim((string)$facultad->fac_abreviacion);
                $antiguo=json_encode($facultad);
                $facultad->fac_nombre = $nombre;
                $facultad->fac_abreviacion = $corto;
                $facultad->save();

                $nombreNuevo=trim((string)$facultad->fac_nombre);
                $abreviacionNueva=trim((string)$facultad->fac_abreviacion);
                if($nombreAnterior!==$nombreNuevo || $abreviacionAnterior!==$abreviacionNueva){
                    FacultadHistorialNombre::create([
                        'cod_fac'=>$facultad->cod_fac,
                        'nombre_anterior'=>$nombreAnterior,
                        'nombre_nuevo'=>$nombreNuevo,
                        'abreviacion_anterior'=>$abreviacionAnterior,
                        'abreviacion_nueva'=>$abreviacionNueva,
                    ]);
                }

                $nuevo=json_encode($facultad);
                SessionController::write('U',$antiguo,$nuevo,'Facultads','1',$facultad->cod_fac);
            } else {
                $facultad = Facultad::create([
                    'fac_nombre' => $nombre,
                    'fac_abreviacion' => $corto,
                ]);
                $nuevo=json_encode($facultad);
                SessionController::write('C','',$nuevo,'Facultads','1',$facultad->cod_fac);
            }
            \Session::flash('exito', 'Se ha guardado con éxito la facultad');
        }else{
            \Session::flash('error', 'Ya existe una facultad con ese nombre');
        }
        return redirect('listar facultad');
    }
    public function f_eli_facultad($cod_fac){
        $facultad=Facultad::find($cod_fac);
        $carreras=DB::select('select count(cod_car) from carreras where cod_fac='.$facultad->cod_fac);
        $eliminar=1;
        if($carreras[0]->count>0){
            $eliminar=0;
        }
        return view('unidad.facultad.f_eli_facultad',compact('facultad','eliminar'));
    }
    public function f_historial_facultad($cod_fac){
        $facultad=Facultad::find($cod_fac);
        if(!$facultad){
            return response('<div class="modal-content"><div class="modal-body"><div class="alert alert-danger mb-0">No se encontró la facultad solicitada.</div></div></div>');
        }

        $historial=FacultadHistorialNombre::where('cod_fac','=',$cod_fac)
            ->orderByDesc('fecha_cambio')
            ->orderByDesc('cod_fhn')
            ->get();

        return view('unidad.facultad.f_historial_facultad',compact('facultad','historial'));
    }
    public function eli_facultad(Request $form){
        $facultad=Facultad::find($form['cf']);
        $facultad->delete();
        $antiguo=json_encode($facultad);
        SessionController::write('D',$antiguo,'','Facultads','1',$facultad->cod_fac);
        \Session::flash('exito','Se ha eliminado con éxito la facultad');
        return redirect('listar facultad');
    }
    //==========================CARRERA=================================
    public function l_carrera($cod_fac){
        $facultad=Facultad::find($cod_fac);
        $carreras=Carrera::where('cod_fac','=',$cod_fac)->orderBy('car_nombre')->get();

        $acreditacionesCarrera=collect();
        if($carreras->count()>0){
            $acreditacionesCarrera=CarreraAcreditacion::whereIn('cod_car',$carreras->pluck('cod_car')->toArray())
                ->orderByRaw("CASE WHEN tipo='Nacional' THEN 1 WHEN tipo='Internacional' THEN 2 ELSE 3 END")
                ->orderByDesc('anio')
                ->orderByDesc('fecha_acreditacion')
                ->orderByDesc('cod_cac')
                ->get()
                ->map(function ($item) {
                    $item->tipo_vista=$this->resolverTipoAcreditacion($item);
                    $item->estado_vista=$this->calcularEstadoAcreditacion(
                        $item->fecha_acreditacion,
                        $item->fecha_vencimiento,
                        $item->proc_sc,
                        $item->proc_nc
                    );
                    return $item;
                })
                ->groupBy('cod_car')
                ->map(function ($items) {
                    return $items->groupBy(function ($item) {
                        return $item->tipo_vista;
                    })->map(function ($grupoTipo) {
                        return $grupoTipo->first();
                    })->values();
                });
        }

        return view('unidad.carrera.l_carrera',compact('facultad','carreras','cod_fac','acreditacionesCarrera'));
    }
    public function fe_carrera($cod_fac,$cod_car){
        $facultad="";
        $carrera="";
        $acreditacionNacional=null;
        $acreditacionInternacional=null;
        if($cod_car!=0){
            $carrera=Carrera::find($cod_car);
            if($carrera){
                $facultad=Facultad::find($carrera->cod_fac);

                $acreditaciones=CarreraAcreditacion::where('cod_car','=',$carrera->cod_car)
                    ->orderByDesc('anio')
                    ->orderByDesc('fecha_acreditacion')
                    ->orderByDesc('cod_cac')
                    ->get();

                $acreditacionNacional=$acreditaciones->first(function ($item) {
                    return $item->tipo==='Nacional';
                });

                $acreditacionInternacional=$acreditaciones->first(function ($item) {
                    return $item->tipo==='Internacional';
                });

                if(!$acreditacionNacional){
                    $acreditacionNacional=$acreditaciones->first(function ($item) {
                        return strtoupper(trim((string)$item->sistema))==='CEUB';
                    });
                }

                if(!$acreditacionInternacional){
                    $acreditacionInternacional=$acreditaciones->first(function ($item) {
                        return strtoupper(trim((string)$item->sistema))==='ARCU SUR';
                    });
                }

                if(!$acreditacionNacional){
                    $acreditacionNacional=$acreditaciones->first(function ($item) {
                        return trim((string)$item->tipo)==='';
                    });
                }
            }
        }
        if($cod_fac!=0){
            $facultad=Facultad::find($cod_fac);
        }
        return view('unidad.carrera.fe_carrera',compact('facultad','carrera','cod_fac','cod_car','acreditacionNacional','acreditacionInternacional'));
    }
    public function g_carrera(Request $form){
        $nombre=trim((string)$form['nombre']);
        $corto=trim((string)$form['corto_c']);
        $codCarrera=$form['cc'] ?? 0;

        $carreras=Carrera::all()->where('car_nombre','=',$nombre)->where('cod_car','<>',$codCarrera);
        if(sizeof($carreras)<1){
            if(isset($form['cc'])){
                $carrera=Carrera::find($form['cc']);
                $nombreAnterior=trim((string)$carrera->car_nombre);
                $abreviacionAnterior=trim((string)$carrera->car_abreviacion);
                $antiguo=json_encode($carrera);
                $carrera->car_nombre=$nombre;
                $carrera->car_abreviacion=$corto;
                $carrera->save();

                $nombreNuevo=trim((string)$carrera->car_nombre);
                $abreviacionNueva=trim((string)$carrera->car_abreviacion);
                if($nombreAnterior!==$nombreNuevo || $abreviacionAnterior!==$abreviacionNueva){
                    CarreraHistorialNombre::create([
                        'cod_car'=>$carrera->cod_car,
                        'nombre_anterior'=>$nombreAnterior,
                        'nombre_nuevo'=>$nombreNuevo,
                        'abreviacion_anterior'=>$abreviacionAnterior,
                        'abreviacion_nueva'=>$abreviacionNueva,
                    ]);
                }

                $nuevo=json_encode($carrera);
                SessionController::write('U',$antiguo,$nuevo,'Carreras','1',$carrera->cod_car);
                $this->guardarAcreditacionPorTipo($form,$carrera->cod_car,'nac','Nacional');
                $this->guardarAcreditacionPorTipo($form,$carrera->cod_car,'int','Internacional');
            }else{
                $carrera=Carrera::create([
                    'car_nombre'=>$nombre,
                    'cod_fac'=>$form['cf'],
                    'car_abreviacion'=>$corto,
                ]);
                $nuevo=json_encode($carrera);
                SessionController::write('C','',$nuevo,'Carreras','1',$carrera->cod_car);
                $this->guardarAcreditacionPorTipo($form,$carrera->cod_car,'nac','Nacional');
                $this->guardarAcreditacionPorTipo($form,$carrera->cod_car,'int','Internacional');
            }
            \Session::flash('exito','Se ha guardado con éxito la carrera');
        }else{
            \Session::flash('error','Ya existe una carrera con ese nombre');
        }
        //return redirect('listar facultad');
    }
    public function f_historial_carrera($cod_fac,$cod_car){
        $carrera=Carrera::find($cod_car);
        if(!$carrera){
            return response('<div class="modal-content"><div class="modal-body"><div class="alert alert-danger mb-0">No se encontro la carrera solicitada.</div></div></div>');
        }

        $facultad=Facultad::find($carrera->cod_fac);
        $historial=CarreraHistorialNombre::where('cod_car','=',$cod_car)
            ->orderByDesc('fecha_cambio')
            ->orderByDesc('cod_chn')
            ->get();

        $acreditaciones=CarreraAcreditacion::where('cod_car','=',$cod_car)
            ->orderByDesc('anio')
            ->orderByDesc('fecha_acreditacion')
            ->orderByDesc('cod_cac')
            ->get();

        $acreditaciones=$acreditaciones->map(function ($item) {
            $item->tipo_vista=$this->resolverTipoAcreditacion($item);
            $item->estado_vista=$this->calcularEstadoAcreditacion(
                $item->fecha_acreditacion,
                $item->fecha_vencimiento,
                $item->proc_sc,
                $item->proc_nc
            );
            return $item;
        });

        $acreditacionesNacional=$acreditaciones->where('tipo_vista','Nacional')->values();
        $acreditacionesInternacional=$acreditaciones->where('tipo_vista','Internacional')->values();
        $codAcreditacionNacionalActiva=$acreditacionesNacional->first()->cod_cac ?? 0;
        $codAcreditacionInternacionalActiva=$acreditacionesInternacional->first()->cod_cac ?? 0;

        return view('unidad.carrera.f_historial_carrera',compact(
            'facultad',
            'carrera',
            'historial',
            'acreditacionesNacional',
            'acreditacionesInternacional',
            'codAcreditacionNacionalActiva',
            'codAcreditacionInternacionalActiva'
        ));
    }
    public function f_eli_carrera($cod_fac,$cod_car){
        $carrera=Carrera::find($cod_car);
        $facultad=Facultad::find($carrera->cod_fac);

        $carreras=DB::select('select count(cod_car) from tomo_carreras where cod_car='.$carrera->cod_car);
        $eliminar=1;
        if($carreras[0]->count>0){
            $eliminar=0;
        }
        return view('unidad.carrera.f_eli_carrera',compact('facultad','carrera','eliminar'));
    }
    public function eli_carrera(Request $form){
        $carrera=Carrera::find($form['cc']);
        $carrera->delete();
        $antiguo=json_encode($carrera);
        SessionController::write('D',$antiguo,'','Carreras','1',$carrera->cod_car);
        \Session::flash('exito','Se ha eliminado con éxito la carrera');
    }

    private function guardarAcreditacionPorTipo(Request $form,$codCar,$prefijo,$tipo){
        $registrar=$form->has($prefijo.'_habilitada');

        $acreditaciones=CarreraAcreditacion::where('cod_car','=',$codCar)
            ->orderByDesc('cod_cac')
            ->get();

        $acreditacion=$acreditaciones->first(function ($item) use ($tipo) {
            return $item->tipo===$tipo;
        });

        if(!$acreditacion){
            $sistemaEsperado=$tipo==='Nacional' ? 'CEUB' : 'ARCU SUR';
            $acreditacion=$acreditaciones->first(function ($item) use ($sistemaEsperado) {
                return strtoupper(trim((string)$item->sistema))===$sistemaEsperado;
            });
        }

        if(!$acreditacion && $tipo==='Nacional'){
            $acreditacion=$acreditaciones->first(function ($item) {
                return trim((string)$item->tipo)==='';
            });
        }

        if(!$registrar){
            CarreraAcreditacion::where('cod_car','=',$codCar)
                ->where(function ($query) use ($tipo) {
                    $query->where('tipo','=',$tipo);

                    if($tipo==='Nacional'){
                        $query->orWhereRaw('UPPER(TRIM(COALESCE(sistema,\'\'))) = ?', ['CEUB'])
                            ->orWhereRaw('TRIM(COALESCE(tipo,\'\')) = ?',[""]);
                    }else{
                        $query->orWhereRaw('UPPER(TRIM(COALESCE(sistema,\'\'))) = ?', ['ARCU SUR']);
                    }
                })
                ->delete();
            return;
        }

        $procSc=$this->toNullableInt($form[$prefijo.'_proc_sc'] ?? null);
        $procNc=$this->toNullableInt($form[$prefijo.'_proc_nc'] ?? null);

        $procTotal=null;
        if($procSc!==null || $procNc!==null){
            $procTotal=(int)($procSc ?? 0)+(int)($procNc ?? 0);
        }

        $fechaAcreditacion=$this->toNullableDate($form[$prefijo.'_fecha_acreditacion'] ?? null);
        $fechaVencimiento=$this->toNullableDate($form[$prefijo.'_fecha_vencimiento'] ?? null);
        $resolucionInicio=$this->toNullableDate($form[$prefijo.'_resolucion_inicio'] ?? null);
        $resolucionFin=$this->toNullableDate($form[$prefijo.'_resolucion_fin'] ?? null);
        $resolucionFechaEmision=$this->toNullableDate($form[$prefijo.'_resolucion_fecha_emision'] ?? null);
        $resolucionNumero=$this->toNullableResolutionNumber($form[$prefijo.'_resolucion_numero'] ?? null);
        $resolucionAnio=$this->toNullableInt($form[$prefijo.'_resolucion_anio'] ?? null);

        $puntajeModo=trim((string)($form[$prefijo.'_puntaje_modo'] ?? ''));
        $puntajeNumero=trim((string)($form[$prefijo.'_puntaje_numero'] ?? ''));

        $puntaje=null;
        if($puntajeModo==='NUMERO'){
            $puntaje=$this->toNullableScore($puntajeNumero);
        }elseif(in_array($puntajeModo,['Cumple','Homologado','S/D'])){
            $puntaje=$puntajeModo;
        }

        $datosAcreditacion=[
            'acreditada'=>strtoupper(trim((string)($form[$prefijo.'_acred'] ?? 'NO')))==='SI',
            'tipo'=>$tipo,
            'sistema'=>$tipo==='Nacional' ? 'CEUB' : 'ARCU SUR',
            'anio'=>$this->toNullableInt($form[$prefijo.'_anio_base'] ?? null),
            'proc_sc'=>$procSc,
            'proc_nc'=>$procNc,
            'proc_total'=>$procTotal,
            'fecha_acreditacion'=>$fechaAcreditacion,
            'fecha_vencimiento'=>$fechaVencimiento,
            'resolucion_inicio'=>$resolucionInicio,
            'resolucion_fin'=>$resolucionFin,
            'resolucion_fecha_emision'=>$resolucionFechaEmision,
            'resolucion_numero'=>$resolucionNumero,
            'resolucion_anio'=>$resolucionAnio,
            'estado'=>$this->calcularEstadoAcreditacion($fechaAcreditacion,$fechaVencimiento,$procSc,$procNc),
            'puntaje'=>$puntaje,
            'certificado'=>strtoupper(trim((string)($form[$prefijo.'_certificado'] ?? 'NO')))==='SI',
        ];

        if($acreditacion && !$this->acreditacionTieneCambios($acreditacion,$datosAcreditacion)){
            return;
        }

        $datosAcreditacion['cod_car']=$codCar;
        CarreraAcreditacion::create($datosAcreditacion);
    }

    private function acreditacionTieneCambios(CarreraAcreditacion $acreditacion,array $datosAcreditacion){
        $camposBooleanos=['acreditada','certificado'];
        $camposNumericos=['anio','proc_sc','proc_nc','proc_total','resolucion_anio'];
        $camposFecha=['fecha_acreditacion','fecha_vencimiento','resolucion_inicio','resolucion_fin','resolucion_fecha_emision'];

        foreach($datosAcreditacion as $campo=>$nuevoValor){
            $valorActual=$acreditacion->{$campo};

            if(in_array($campo,$camposBooleanos,true)){
                if((bool)$valorActual!==((bool)$nuevoValor)){
                    return true;
                }
                continue;
            }

            if(in_array($campo,$camposNumericos,true)){
                $actualNormalizado=$valorActual===null ? null : (int)$valorActual;
                $nuevoNormalizado=$nuevoValor===null ? null : (int)$nuevoValor;
                if($actualNormalizado!==$nuevoNormalizado){
                    return true;
                }
                continue;
            }

            if(in_array($campo,$camposFecha,true)){
                $actualNormalizado=$valorActual ? date('Y-m-d',strtotime($valorActual)) : null;
                $nuevoNormalizado=$nuevoValor ? date('Y-m-d',strtotime($nuevoValor)) : null;
                if($actualNormalizado!==$nuevoNormalizado){
                    return true;
                }
                continue;
            }

            $actualNormalizado=$valorActual===null ? null : trim((string)$valorActual);
            $nuevoNormalizado=$nuevoValor===null ? null : trim((string)$nuevoValor);
            if($actualNormalizado!==$nuevoNormalizado){
                return true;
            }
        }

        return false;
    }

    private function calcularEstadoAcreditacion($fechaAcreditacion,$fechaVencimiento,$procSc,$procNc){
        $sc=(int)($procSc ?? 0);
        $nc=(int)($procNc ?? 0);

        if($sc===0 && $nc>0){
            return 'No cumple';
        }

        $hoy=strtotime(date('Y-m-d'));

        if($fechaAcreditacion && $fechaVencimiento){
            $fechaIni=strtotime($fechaAcreditacion);
            $fechaFin=strtotime($fechaVencimiento);
            return ($hoy>=$fechaIni && $hoy<=$fechaFin) ? 'Vigente' : 'Vencido';
        }

        if($fechaAcreditacion && !$fechaVencimiento){
            $fechaIni=strtotime($fechaAcreditacion);
            return $hoy>=$fechaIni ? 'Vigente' : 'Vencido';
        }

        if(!$fechaAcreditacion && $fechaVencimiento){
            $fechaFin=strtotime($fechaVencimiento);
            return $hoy<=$fechaFin ? 'Vigente' : 'Vencido';
        }

        return '';
    }

    private function resolverTipoAcreditacion(CarreraAcreditacion $acreditacion){
        $tipo=trim((string)$acreditacion->tipo);
        if(in_array($tipo,['Nacional','Internacional'],true)){
            return $tipo;
        }

        $sistema=strtoupper(trim((string)$acreditacion->sistema));
        if($sistema==='CEUB'){
            return 'Nacional';
        }
        if($sistema==='ARCU SUR'){
            return 'Internacional';
        }

        return 'OTRO';
    }

    private function toNullableInt($valor){
        $texto=trim((string)$valor);
        if($texto===''){
            return null;
        }

        return (int)$texto;
    }

    private function toNullableDate($valor){
        $texto=trim((string)$valor);
        return $texto!=='' ? $texto : null;
    }

    private function toNullableResolutionNumber($valor){
        $texto=trim((string)$valor);
        if($texto===''){
            return null;
        }

        return preg_match('/^\d+$/',$texto) ? $texto : null;
    }

    private function toNullableScore($valor){
        $texto=str_replace(',','.',trim((string)$valor));
        if($texto===''){
            return null;
        }

        if(!is_numeric($texto)){
            return null;
        }

        $numero=(float)$texto;
        if($numero<0){
            $numero=0;
        }
        if($numero>100){
            $numero=100;
        }

        $formateado=number_format($numero,2,'.','');
        return rtrim(rtrim($formateado,'0'),'.');
    }

}
