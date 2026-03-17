<?php
namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Facultad;
use App\Models\Funciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReporteController extends Controller
{
    public function form_reporte(){
        $carreras=Carrera::all();
        $facultades=Facultad::all();

        $consulta="select t.tom_tipo, count(ti.cod_tit) as cantidad from tomos as t, titulos as ti where t.cod_tom=ti.cod_tom group by t.tom_tipo";
        $resultado=DB::select($consulta);
        return view('diplomas.reporte.reporte',compact('carreras','facultades','resultado'));

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
                    $consulta="select tom_tipo, tit_gestion  as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by tom_tipo,tit_gestion";
                    $gestion=" tit_gestion between ".$inicio." and ".$fin;

                }else{
                    $consulta="select EXTRACT(MONTH from ti.tit_fecha_emision) as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by titulo order by titulo";
                    $gestion=" tit_gestion=".$inicio;
                    $mes=1;
                }
            }else{
                $consulta="select tom_tipo,tit_gestion  as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                $agrupar=" group by tom_tipo, tit_gestion";
            }
        }else{
            if($inicio!=''){
                if($form['fin']!=''){
                    $consulta="select tit_gestion  as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by tit_gestion order by titulo";
                    $gestion=" tit_gestion between ".$inicio." and ".$fin;

                }else{
                    $consulta="select EXTRACT(MONTH from ti.tit_fecha_emision)  as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
                    $agrupar=" group by mes";
                    $gestion=" tit_gestion=".$inicio;
                    $mes=1;
                }
            }else{
                $consulta="select tit_gestion as titulo, count(ti.cod_tit) as cantidad from titulos ti join tomos t on ti.cod_tom=t.cod_tom ";
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
}
