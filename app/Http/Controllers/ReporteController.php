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
        'db' => ['nombre' => 'DIPLOMA DE BACHILLER', 'abreviado' => 'D.B.'],
        'da' => ['nombre' => 'DIPLOMA ACADEMICO', 'abreviado' => 'D.A.'],
        'tp' => ['nombre' => 'TITULOS EN PROVISION NACIONAL', 'abreviado' => 'T.P.N.'],
        'ca' => ['nombre' => 'CERTIFICADO ACADEMICO', 'abreviado' => 'CERT.ACAD.'],
        'su' => ['nombre' => 'CERTIFICADO SUPLETORIO', 'abreviado' => 'CERT.SUPL.'],
        'di' => ['nombre' => 'DIPLOMADO', 'abreviado' => 'DIPLOMADO'],
        'tpos' => ['nombre' => 'POSGRADO', 'abreviado' => 'POSGRADO'],
        're' => ['nombre' => 'REVALIDA', 'abreviado' => 'R.E.'],
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
            'exportar_tomos' => ['nullable', 'in:1'],
            'exportar_titulos' => ['nullable', 'in:1'],
        ]);

        $inicio = (int) $request->inicio;
        $fin = (int) $request->fin;
        if ($fin < $inicio) {
            $tmp = $inicio;
            $inicio = $fin;
            $fin = $tmp;
        }

        $tiposSeleccionados = array_values(array_unique($request->tipos));
        $exportarTomos = $request->has('exportar_tomos');
        $exportarTitulos = $request->has('exportar_titulos');
        if (!$exportarTomos && !$exportarTitulos) {
            $exportarTomos = true;
            $exportarTitulos = true;
        }

        $datos = $this->obtenerDatosInventarioTomos($tiposSeleccionados, $inicio, $fin, $exportarTomos, $exportarTitulos);

        $nombre = 'Inventario-tomos-'.$inicio.'-'.$fin.'.xlsx';
        return (new ExportInventarioTomos(
            $datos['filasPrincipal'],
            $datos['anchoPrincipal'],
            $datos['filasTotales'],
            $datos['anchoTotales'],
            $datos['titulo'],
            $datos['filaTotalGeneralTotales']
        ))->download($nombre);
    }

    private function obtenerDatosInventarioTomos(array $tiposSeleccionados, int $inicio, int $fin, bool $exportarTomos, bool $exportarTitulos): array
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

        $filasPrincipal = [];
        $cabecera = ['NRO', 'GESTION'];
        foreach ($tiposSeleccionados as $tipo) {
            $nombreColumna = mb_strtoupper($this->tiposDocumento[$tipo]['nombre'], 'UTF-8');
            if ($exportarTomos) {
                $cabecera[] = $nombreColumna.' TOMOS';
            }
            if ($exportarTitulos) {
                $cabecera[] = $nombreColumna.' TITULOS';
            }
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
                $tomos = (int) ($indexado[$gestion][$tipo]['tomos'] ?? 0);
                $titulos = (int) ($indexado[$gestion][$tipo]['titulos'] ?? 0);
                if ($exportarTomos) {
                    $fila[] = $tomos;
                }
                if ($exportarTitulos) {
                    $fila[] = $titulos;
                }
                $totalesPorTipo[$tipo]['tomos'] += $tomos;
                $totalesPorTipo[$tipo]['titulos'] += $titulos;
            }
            $detalleAnios[] = $fila;
            $nro++;
        }

        // Totales para la hoja resumen: se recalculan por tipo en una sola consulta
        // para evitar sobrecontar tomos al sumar resultados agrupados por gestion.
        $resumenTotales = DB::table('titulos as ti')
            ->join('tomos as t', 'ti.cod_tom', '=', 't.cod_tom')
            ->whereIn('t.tom_tipo', $tiposSeleccionados)
            ->whereBetween('ti.tit_gestion', [$inicio, $fin])
            ->groupBy('t.tom_tipo')
            ->selectRaw('t.tom_tipo, COUNT(DISTINCT t.cod_tom) as tomos, COUNT(ti.cod_tit) as titulos')
            ->get();

        foreach ($resumenTotales as $item) {
            $totalesPorTipo[$item->tom_tipo]['tomos'] = (int) $item->tomos;
            $totalesPorTipo[$item->tom_tipo]['titulos'] = (int) $item->titulos;
        }

        $totalGeneralTomos = 0;
        $totalGeneralTitulos = 0;
        foreach ($tiposSeleccionados as $tipo) {
            $totalGeneralTomos += $totalesPorTipo[$tipo]['tomos'];
            $totalGeneralTitulos += $totalesPorTipo[$tipo]['titulos'];
        }

        $anchoPrincipal = count($cabecera);
        $titulo = 'TOTAL DE INVENTARIO DE TOMOS Y TITULOS ('.implode(' - ', $siglas).')';

        $filasPrincipal[] = $cabecera;
        foreach ($detalleAnios as $filaAnio) {
            $filasPrincipal[] = $filaAnio;
        }

        $filasTotales = [];
        $filasTotales[] = ['TOTALES'];

        $cabeceraTotales = ['TIPO DE DOCUMENTO'];
        if ($exportarTomos) {
            $cabeceraTotales[] = 'TOMOS';
        }
        if ($exportarTitulos) {
            $cabeceraTotales[] = 'TITULOS';
        }
        $filasTotales[] = $cabeceraTotales;

        foreach ($tiposSeleccionados as $tipo) {
            $filaTotalTipo = [mb_strtoupper($this->tiposDocumento[$tipo]['nombre'], 'UTF-8')];
            if ($exportarTomos) {
                $filaTotalTipo[] = (int) $totalesPorTipo[$tipo]['tomos'];
            }
            if ($exportarTitulos) {
                $filaTotalTipo[] = (int) $totalesPorTipo[$tipo]['titulos'];
            }
            $filasTotales[] = $filaTotalTipo;
        }

        $filaTotalGeneral = ['TOTAL'];
        if ($exportarTomos) {
            $filaTotalGeneral[] = (int) $totalGeneralTomos;
        }
        if ($exportarTitulos) {
            $filaTotalGeneral[] = (int) $totalGeneralTitulos;
        }
        $filasTotales[] = $filaTotalGeneral;

        $anchoTotales = count($cabeceraTotales);
        $filaTotalGeneralTotales = count($filasTotales);

        return [
            'filasPrincipal' => $filasPrincipal,
            'anchoPrincipal' => $anchoPrincipal,
            'filasTotales' => $filasTotales,
            'anchoTotales' => $anchoTotales,
            'titulo' => $titulo,
            'filaTotalGeneralTotales' => $filaTotalGeneralTotales,
        ];
    }
}
