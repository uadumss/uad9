<?php
namespace App\Http\Controllers;

use App\Exports\ExportInventarioTomos;
use App\Models\Carrera;
use App\Models\Facultad;
use App\Models\Funciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReporteController extends Controller
{
    private array $tiposDocumento = [
        'db' => ['nombre' => 'DIPLOMA DE BACHILLER', 'abreviado' => 'DB'],
        'da' => ['nombre' => 'DIPLOMA ACADEMICO', 'abreviado' => 'DA'],
        'tp' => ['nombre' => 'TITULO PROFESIONAL', 'abreviado' => 'TP'],
        'ca' => ['nombre' => 'CERTIFICADO ACADEMICO', 'abreviado' => 'CA'],
        'su' => ['nombre' => 'CERTIFICADO SUPLETORIO', 'abreviado' => 'SU'],
        'di' => ['nombre' => 'DIPLOMADO', 'abreviado' => 'DI'],
        'tpos' => ['nombre' => 'TITULO POSGRADO', 'abreviado' => 'POSGRADO'],
        're' => ['nombre' => 'REVALIDA', 'abreviado' => 'RE'],
    ];

    public function form_reporte(){
        $carreras=Carrera::all();
        $facultades=Facultad::all();
        $tiposDocumento = $this->tiposDocumento;

        $consulta="select t.tom_tipo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from tomos as t, titulos as ti where t.cod_tom=ti.cod_tom group by t.tom_tipo";
        $resultado=DB::select($consulta);
        return view('diplomas.reporte.reporte',compact('carreras','facultades','resultado','tiposDocumento'));

    }
    public function fe_reporte($tipo){
        $carreras=DB::table('carreras')
            ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('car_nombre','cod_car','carreras.cod_fac','fac_abreviacion')
            ->orderBy('fac_abreviacion')
            ->orderBy('car_nombre')
            ->get();
        $facultades=Facultad::select('fac_nombre','cod_fac','fac_abreviacion')->get();
            $grado=Funciones::grados($tipo);
        return view('diplomas.reporte.fe_reporte',compact('carreras','facultades','tipo','grado'));
    }
    public function generar_reporte(Request $form){
        $form->validate([
           'tipo'=>Rule::in(['todos','di','ca','da','db','tp','tpos','re','su']),
        ]);
        $tipo=$form['tipo'];
        $inicio=$form['inicio'];
        $fin=$form['fin'];
        $car="";
        $fac="";
        $carrera="";$datosCarrera="";
        $facultad="";$datosFacultad="";
        $grado=$form['grado'];
        $consulta="";
        $agrupar="";
        $gestion="";
        $mes=0;
        if($form['carrera']!=''){
            $car=" join diploma_academicos da on da.cod_tit=ti.cod_tit join carreras c on c.cod_car=da.cod_car";
            $carrera=" and c.cod_car=".$form['carrera'];
            $datosCarrera=Carrera::find($form['carrera']);

        }
        if($form['facultad']!=''){
            $fac=" join diploma_academicos da on da.cod_tit=ti.cod_tit join carreras c on c.cod_car=da.cod_car join facultads f on f.cod_fac=c.cod_fac ";
            $facultad="and f.cod_fac=".$form['facultad'];
            $datosFacultad=Facultad::find($form['facultad']);
        }
        if($tipo!='todos'){
            if($inicio!=''){
                if($form['fin']!=''){
                    $consulta="select tom_tipo, tit_gestion as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by tom_tipo,tit_gestion";
                    $gestion=" tit_gestion between ".$inicio." and ".$fin;

                }else{
                    $consulta="select EXTRACT(MONTH from ti.tit_fecha_emision) as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by titulo order by titulo";
                    $gestion=" tit_gestion=".$inicio;
                    $mes=1;
                }
            }else{
                $consulta="select tom_tipo, tit_gestion as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                $agrupar=" group by tom_tipo, tit_gestion";
            }
        }else{
            if($inicio!=''){
                if($form['fin']!=''){
                    $consulta="select tit_gestion as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by tit_gestion order by titulo";
                    $gestion=" tit_gestion between ".$inicio." and ".$fin;

                }else{
                    $consulta="select EXTRACT(MONTH from ti.tit_fecha_emision) as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by titulo";
                    $gestion=" tit_gestion=".$inicio;
                    $mes=1;
                }
            }else{
                $consulta="select tit_gestion as titulo, count(DISTINCT t.cod_tom) as cantidad_tomos, count(ti.cod_tit) as cantidad_titulos from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                $agrupar=" group by tit_gestion";
            }
        }
        $consulta.=$car.$fac;
        if($tipo!='todos'){
            $consulta.=" where tom_tipo='".$tipo."'";
            if($gestion!=''){
                $consulta.=" and ".$gestion;
            }
        }else{
            if($gestion!='') {
                $consulta .= "where " . $gestion;
            }
        }
        if($grado!=''){
            $consulta.=" and tit_grado='".$grado."' ";
        }
        $consulta.=$carrera.$facultad;
        $consulta.=$agrupar;
        //dd($consulta);
        $resultado=DB::select($consulta);
        //dd($resultado);
        return view('diplomas.reporte.panel_estadistico',compact('resultado','mes','datosFacultad','datosCarrera','tipo','inicio','fin','grado'));
    }

    public function exportar_inventario_excel(Request $request)
    {
        $tiposValidos = array_keys($this->tiposDocumento);
        $request->validate([
            'tipos' => ['required', 'array', 'min:1'],
            'tipos.*' => ['required', Rule::in($tiposValidos)],
            'inicio' => ['required', 'integer', 'min:1928'],
            'fin' => ['required', 'integer', 'min:1928'],
        ]);

        $inicio = (int) $request->inicio;
        $fin = (int) $request->fin;
        if ($fin < $inicio) {
            $tmp = $inicio;
            $inicio = $fin;
            $fin = $tmp;
        }

        $tiposSeleccionados = array_values(array_unique($request->tipos));
        $datos = $this->obtenerDatosInventarioTomos($tiposSeleccionados, $inicio, $fin);

        $nombre = 'Inventario-tomos-'.$inicio.'-'.$fin.'.xlsx';
        return (new ExportInventarioTomos(
            $datos['filasExcel'],
            $datos['anchoFila'],
            $datos['titulo']
        ))->download($nombre);
    }

    private function obtenerDatosInventarioTomos(array $tiposSeleccionados, int $inicio, int $fin): array
    {
        $registros = DB::table('titulos as ti')
            ->join('tomos as t', 'ti.cod_tom', '=', 't.cod_tom')
            ->whereIn('t.tom_tipo', $tiposSeleccionados)
            ->whereBetween('ti.tit_gestion', [$inicio, $fin])
            ->groupBy('ti.tit_gestion', 't.tom_tipo')
            ->orderBy('ti.tit_gestion')
            ->selectRaw('ti.tit_gestion as gestion, t.tom_tipo, COUNT(DISTINCT t.cod_tom) as tomos, COUNT(ti.cod_tit) as titulos')
            ->get();

        $indexado = [];
        foreach ($registros as $r) {
            $indexado[$r->gestion][$r->tom_tipo] = [
                'tomos' => (int) $r->tomos,
                'titulos' => (int) $r->titulos,
            ];
        }

        $siglas = [];
        foreach ($tiposSeleccionados as $tipo) {
            $siglas[] = $this->tiposDocumento[$tipo]['abreviado'];
        }

        $filas = [];
        $cabecera = ['NRO', 'GESTION'];
        foreach ($tiposSeleccionados as $tipo) {
            $cabecera[] = $this->tiposDocumento[$tipo]['abreviado'].' TOMOS';
            $cabecera[] = $this->tiposDocumento[$tipo]['abreviado'].' TITULOS';
        }

        $totalesPorTipo = [];
        foreach ($tiposSeleccionados as $tipo) {
            $totalesPorTipo[$tipo] = ['tomos' => 0, 'titulos' => 0];
        }

        $detalleAnios = [];
        $nro = 1;
        for ($gestion = $inicio; $gestion <= $fin; $gestion++) {
            $fila = [$nro, $gestion];
            foreach ($tiposSeleccionados as $tipo) {
                $tomos = $indexado[$gestion][$tipo]['tomos'] ?? 0;
                $titulos = $indexado[$gestion][$tipo]['titulos'] ?? 0;
                $fila[] = $tomos;
                $fila[] = $titulos;
                $totalesPorTipo[$tipo]['tomos'] += $tomos;
                $totalesPorTipo[$tipo]['titulos'] += $titulos;
            }
            $detalleAnios[] = $fila;
            $nro++;
        }

        $totalGeneralTomos = 0;
        $totalGeneralTitulos = 0;
        foreach ($tiposSeleccionados as $tipo) {
            $totalGeneralTomos += $totalesPorTipo[$tipo]['tomos'];
            $totalGeneralTitulos += $totalesPorTipo[$tipo]['titulos'];
        }

        $columnaResumenInicio = count($cabecera) + 1;
        $anchoFila = $columnaResumenInicio + 3;

        $titulo = 'TOTAL DE INVENTARIO DE TOMOS Y TITULOS ('.implode('.', $siglas).')';

        $filas[] = array_merge([$titulo], array_fill(0, $anchoFila - 1, ''));
        $filas[] = array_fill(0, $anchoFila, '');

        $cabeceraFila = array_fill(0, $anchoFila, '');
        foreach ($cabecera as $i => $valor) {
            $cabeceraFila[$i] = $valor;
        }
        $cabeceraFila[$columnaResumenInicio] = 'TOTALES';
        $filas[] = $cabeceraFila;

        $cabeceraResumen = array_fill(0, $anchoFila, '');
        $cabeceraResumen[$columnaResumenInicio] = 'TIPO';
        $cabeceraResumen[$columnaResumenInicio + 1] = 'TOMOS';
        $cabeceraResumen[$columnaResumenInicio + 2] = 'TITULOS';
        $filas[] = $cabeceraResumen;

        $resumenTipos = array_values($tiposSeleccionados);
        $cantidadFilasDetalle = count($detalleAnios);
        $cantidadFilasResumen = count($resumenTipos) + 1;
        $filasCuerpo = max($cantidadFilasDetalle, $cantidadFilasResumen);

        for ($i = 0; $i < $filasCuerpo; $i++) {
            $fila = array_fill(0, $anchoFila, '');

            if ($i < $cantidadFilasDetalle) {
                foreach ($detalleAnios[$i] as $j => $valor) {
                    $fila[$j] = $valor;
                }
            }

            if ($i < count($resumenTipos)) {
                $tipo = $resumenTipos[$i];
                $fila[$columnaResumenInicio] = $this->tiposDocumento[$tipo]['abreviado'];
                $fila[$columnaResumenInicio + 1] = $totalesPorTipo[$tipo]['tomos'];
                $fila[$columnaResumenInicio + 2] = $totalesPorTipo[$tipo]['titulos'];
            } elseif ($i === count($resumenTipos)) {
                $fila[$columnaResumenInicio] = 'TOTAL';
                $fila[$columnaResumenInicio + 1] = $totalGeneralTomos;
                $fila[$columnaResumenInicio + 2] = $totalGeneralTitulos;
            }

            $filas[] = $fila;
        }

        return [
            'filasExcel' => $filas,
            'anchoFila' => $anchoFila,
            'titulo' => $titulo,
        ];
    }
}
