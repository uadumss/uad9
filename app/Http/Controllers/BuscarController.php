<?php

namespace App\Http\Controllers;

use App\Exports\BusquedaResolucionExport;
use App\Models\D_tramita;
use App\Models\Tema;
use App\Models\Titulo;
use App\Models\Tomo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BuscarController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:busqueda - dyt'], ['only' => ['f_buscar']]);
        $this->middleware(['permission:mostrar antecedente - dyt'], ['only' => ['pdf_a']]);

        $this->middleware(['permission:buscar - rr'], ['only' => ['f_buscar_resolucion_post']]);

    }

    public function f_buscar(){
        $resultado=array();
        $primeraBusqueda=1;
        return view('diplomas.buscar.f_buscar',compact('resultado','primeraBusqueda'));
    }

    public function f_buscarPost(Request $form){
        $resultado=array();
        $consulta="select per_ci,per_nombre,per_apellido,cod_tit,tit_nro_titulo,tit_fecha_emision,tom_numero,tom_gestion,tom_tipo,tit_pdf,tit_antecedentes
                    from titulos ti INNER JOIN tomos t ON ti.cod_tom=t.cod_tom INNER JOIN personas p ON ti.id_per=p.id_per where ";
        $clausulas=array();
        $i=0;
        if($form['nro']!=''){$clausulas[$i]=" tit_nro_titulo='".$form['nro']."'";$i+=1;}
        if($form['tipo']!=''){
            if($form['tipo']=='re'){
                $clausulas[$i]=" (tom_tipo='".$form['tipo']."' or tit_revalida='t')";$i+=1;
            }else{
                $clausulas[$i]=" tom_tipo='".$form['tipo']."'";$i+=1;
            }
        }
        if($form['ci']!=''){$clausulas[$i]=" per_ci='".$form['ci']."'";$i+=1;}
        if($form['fecha']!=''){$clausulas[$i]=" tit_fecha_emision='".$form['fecha']."'";$i+=1;}
        if($form['apellido']!=''){$clausulas[$i]=" per_apellido like '%".mb_strtoupper($form['apellido'])."%'";$i+=1;}
        if($form['nombre']!=''){$clausulas[$i]=" per_nombre like '%".mb_strtoupper($form['nombre'])."%'";$i+=1;}
        if($form['gestion']!=''){$clausulas[$i]=" tom_gestion=".$form['gestion'];$i+=1;}
        $tam=sizeof($clausulas);

        if($tam>0){
            for ($i=0;$i<$tam;$i++){
                $consulta.=" ".$clausulas[$i];
                if($i<($tam-1)){
                    $consulta.=" and";
                }
            }
            $consulta.=" order by per_apellido, per_nombre ASC";
            $resultado=DB::select($consulta);
            SessionController::write('B','',$consulta,'titulos','1','');
            return view('diplomas.buscar.f_buscar',compact('resultado'));
        }else{
            \Session::flash('error','Debe ingresar por lo menos un criterio de búsqueda');
            return view('diplomas.buscar.f_buscar',compact('resultado'));
        }
    }

    public function f_ver_datos($cod_tit){
        $diploma_academico=array();
        $revalida=array();
        $titulo=DB::table('titulos')
            ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
            ->join('tomos','titulos.cod_tom','=','tomos.cod_tom')
            ->join('personas','titulos.id_per','=','personas.id_per')
            ->leftJoin('nacionalidads','personas.cod_nac','=','nacionalidads.cod_nac')
            ->where('cod_tit','=',$cod_tit)
            ->select('tom_numero','tom_gestion','tom_tipo','tit_nro_folio','tit_ref','tit_titulo','tit_pdf','tit_antecedentes',
                'titulos.*','per_nombre','per_apellido','per_ci','per_sexo','per_pasaporte','per_ci_exp',
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
        return view('diplomas.buscar.detalleTitulo',compact('titulo','revalida','diploma_academico'));
    }

    public function pdf($id){
        $titulo=Titulo::find($id);
        $tomo=Tomo::find($titulo['cod_tom']);
        if($titulo->tit_pdf!='') {
            $ruta = 'alma/dt/' . $tomo->tom_tipo . '/' . $tomo->tom_gestion . '/' . $tomo->tom_numero . '/' . $titulo->tit_pdf;
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
        $titulo=Titulo::find($id);
        $tomo=Tomo::find($titulo['cod_tom']);

        if($titulo->tit_antecedentes!='') {
            $ruta = 'alma/dt/' . $tomo->tom_tipo . '/' . $tomo->tom_gestion . '/' . $tomo->tom_numero . '/' . $titulo->tit_antecedentes;
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

    //========================BUSQUEDAS DE RESOLUCIONES===========
    public function f_buscar_resolucion(){
        $resultado=array();
        $criterio=array();
        $clave='';
        $filtrosExcel=array();
        $primeraBusqueda=1;
        $tema=$this->obtenerTemasResolucion();
        return view('resoluciones.buscar.f_buscar_resolucion',compact('resultado','primeraBusqueda','criterio','tema','clave','filtrosExcel'));
    }

    public function f_buscar_resolucion_post(Request $form){
        $resultado=array();
        $filtros=$this->normalizarFiltrosBusquedaResolucion($form);
        $configuracionBusqueda=$this->consultaBusquedaResoluciones($filtros);

        if($configuracionBusqueda['tieneFiltros']){
            $query=$configuracionBusqueda['query'];
            $criterio=$configuracionBusqueda['criterio'];
            $clave=$configuracionBusqueda['clave'];
            $consulta=$this->consultaConBindings($query);

            SessionController::write('B','',$consulta,'resoluciones','2','');
            $resultado=$query->get();
            $tema=$this->obtenerTemasResolucion();
            $filtrosExcel=$this->filtrosParaExportacionExcel($filtros);

            if(isset($form['te']) && $form['te']=='t'){
                $cod_tem=$form['ct'];
                return view('resoluciones.temas.tema_resolucion.resultado_busqueda',compact('resultado','clave','criterio','cod_tem','tema'));
            }else{
                return view('resoluciones.buscar.f_buscar_resolucion',compact('resultado','clave','criterio','tema','filtrosExcel'));
            }

        }else{
            $tema=$this->obtenerTemasResolucion();
            $criterio=array();
            $clave='';
            $filtrosExcel=array();
            if(isset($form['te']) && $form['te']=='t') {
                return view('resoluciones.temas.tema_resolucion.resultado_busqueda', compact('resultado','tema','clave','criterio'));
            }else{
                \Session::flash('error', 'Debe ingresar por lo menos un criterio de búsqueda');
                return view('resoluciones.buscar.f_buscar_resolucion', compact('resultado','tema','clave','criterio','filtrosExcel'));
            }
        }
    }

    public function exportar_busqueda_resolucion_excel(Request $request){
        $filtros=$this->normalizarFiltrosBusquedaResolucion($request);
        $configuracionBusqueda=$this->consultaBusquedaResoluciones($filtros);

        if(!$configuracionBusqueda['tieneFiltros']){
            return redirect('buscar resolucion')->with('error','Debe ingresar por lo menos un criterio de búsqueda para exportar');
        }

        $nombreArchivo='busqueda_resoluciones_'.date('Ymd_His').'.xlsx';
        return Excel::download(new BusquedaResolucionExport($configuracionBusqueda['query']),$nombreArchivo);
    }

    private function obtenerTemasResolucion(){
        return DB::table('resolucions')
            ->select('res_tema')
            ->whereNotNull('res_tema')
            ->where('res_tema','<>','')
            ->distinct()
            ->orderBy('res_tema','ASC')
            ->get();
    }

    private function normalizarFiltrosBusquedaResolucion(Request $request){
        return [
            'numero'=>trim((string)$request->input('numero','')),
            'tipo'=>mb_strtolower(trim((string)$request->input('tipo',''))),
            'gestion'=>trim((string)$request->input('gestion','')),
            'gestion_i'=>trim((string)$request->input('gestion_i','')),
            'gestion_f'=>trim((string)$request->input('gestion_f','')),
            'clave'=>trim((string)$request->input('clave','')),
            'tema'=>trim((string)$request->input('tema','')),
            'vistos'=>$request->boolean('vistos'),
            'considerando'=>$request->boolean('considerando'),
            'resuelve'=>$request->boolean('resuelve'),
        ];
    }

    private function consultaBusquedaResoluciones(array $filtros){
        $query=DB::table('resolucions')
            ->select('cod_res','res_numero','res_tipo','res_fecha','res_objeto','res_tema','res_desc','res_ant','res_pdf');

        $criterio=array();
        $tieneFiltros=false;

        if($filtros['numero']!==''){
            $query->whereRaw('TRIM(CAST(res_numero AS TEXT)) = ?',[$filtros['numero']]);
            $criterio[]=array('Número: ',$filtros['numero']);
            $tieneFiltros=true;
        }

        if($filtros['tipo']!==''){
            $query->whereRaw('UPPER(TRIM(CAST(res_tipo AS TEXT))) = ?',[mb_strtoupper($filtros['tipo'])]);
            $criterio[]=array('Tipo: ',strtoupper($filtros['tipo']));
            $tieneFiltros=true;
        }

        if($filtros['gestion']!==''){
            $query->where(function($subQuery) use ($filtros){
                $subQuery->whereRaw('TRIM(CAST(res_gestion AS TEXT)) = ?',[$filtros['gestion']])
                    ->orWhereRaw("TO_CHAR(res_fecha,'YYYY') = ?",[$filtros['gestion']]);
            });
            $criterio[]=array('Gestión: ',$filtros['gestion']);
            $tieneFiltros=true;
        }

        if($filtros['gestion_i']!==''){
            if($filtros['gestion_f']!==''){
                $fechaInicio=$filtros['gestion_i'];
                $fechaFin=$filtros['gestion_f'];
                if($fechaInicio>$fechaFin){
                    $fechaInicio=$filtros['gestion_f'];
                    $fechaFin=$filtros['gestion_i'];
                }

                $query->whereBetween('res_fecha',[$fechaInicio,$fechaFin]);
                $criterio[]=array(
                    'Rango de fecha: ',
                    date('d/m/Y',strtotime($fechaInicio))."<span class='font-weight-bold'> - </span> ".date('d/m/Y',strtotime($fechaFin))
                );
            }else{
                $query->whereDate('res_fecha','=',$filtros['gestion_i']);
                $criterio[]=array('Fecha: ',date('d/m/Y',strtotime($filtros['gestion_i'])));
            }
            $tieneFiltros=true;
        }

        if($filtros['tema']!==''){
            $query->where('res_tema','ILIKE','%'.$filtros['tema'].'%');
            $criterio[]=array('Tema: ',$filtros['tema']);
            $tieneFiltros=true;
        }

        if($filtros['clave']!==''){
            $clave=$filtros['clave'];
            $query->where(function($subQuery)use($filtros,$clave){
                if($filtros['tema']!==''){
                    $subQuery->where('res_objeto','ILIKE','%'.$clave.'%')
                        ->orWhere('res_desc','ILIKE','%'.$clave.'%');
                }else{
                    $subQuery->where('res_tema','ILIKE','%'.$clave.'%')
                        ->orWhere('res_objeto','ILIKE','%'.$clave.'%')
                        ->orWhere('res_desc','ILIKE','%'.$clave.'%');
                }

                if($filtros['vistos']){
                    $subQuery->orWhere('res_vistos','ILIKE','%'.$clave.'%');
                }
                if($filtros['considerando']){
                    $subQuery->orWhere('res_considerando','ILIKE','%'.$clave.'%');
                }
                if($filtros['resuelve']){
                    $subQuery->orWhere('res_resuelve','ILIKE','%'.$clave.'%');
                }
            });

            $descripcionClave=$clave;
            if($filtros['vistos']){
                $descripcionClave.=" | <span class='font-weight-bold'>Vistos: </span>Sí";
            }
            if($filtros['considerando']){
                $descripcionClave.=" | <span class='font-weight-bold'>Considerando: </span>Sí";
            }
            if($filtros['resuelve']){
                $descripcionClave.=" | <span class='font-weight-bold'>Resuelve: </span>Sí";
            }

            $criterio[]=array('Palabras clave: ',$descripcionClave);
            $tieneFiltros=true;
        }

        $query->orderBy('res_numero','ASC')
            ->orderBy('res_fecha','ASC');

        return [
            'query'=>$query,
            'criterio'=>$criterio,
            'clave'=>$filtros['clave'],
            'tieneFiltros'=>$tieneFiltros,
        ];
    }

    private function filtrosParaExportacionExcel(array $filtros){
        $filtrosExcel=array();
        foreach (['numero','tipo','gestion','gestion_i','gestion_f','clave','tema'] as $campo){
            if($filtros[$campo]!==''){
                $filtrosExcel[$campo]=$filtros[$campo];
            }
        }

        if($filtros['vistos']){
            $filtrosExcel['vistos']=1;
        }
        if($filtros['considerando']){
            $filtrosExcel['considerando']=1;
        }
        if($filtros['resuelve']){
            $filtrosExcel['resuelve']=1;
        }

        return $filtrosExcel;
    }

    private function consultaConBindings($query){
        $consulta=$query->toSql();
        $bindings=$query->getBindings();

        foreach ($bindings as $valor){
            if($valor===null){
                $reemplazo='null';
            }elseif(is_numeric($valor)){
                $reemplazo=$valor;
            }else{
                $reemplazo="'".str_replace("'","''",(string)$valor)."'";
            }

            $consulta=preg_replace('/\?/',$reemplazo,$consulta,1);
        }

        return $consulta;
    }

    //=========================END======================
    public function buscar_valorado($valorado){

        $valorado=DB::table('d_tramitas')
            ->leftJoin('tramitas','d_tramitas.cod_tra','=','tramitas.cod_tra')
            ->leftJoin('personas','tramitas.id_per','=','personas.id_per')
            ->leftJoin('apoderados','tramitas.cod_apo','=','apoderados.cod_apo')
            ->join('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
            ->where('dtra_control','=',$valorado)
            ->select('per_nombre','per_apellido','per_ci','tre_nombre','dtra_control','tra_fecha_solicitud','dtra_fecha_recojo','dtra_numero_tramite'
                    ,'dtra_gestion_tramite','dtra_entregado','dtra_numero','dtra_gestion','apo_nombre','apo_apellido','tra_tipo_apoderado')->first();
        return view('servicios.tra_legalizacion.valorado',compact('valorado'));
    }
}
