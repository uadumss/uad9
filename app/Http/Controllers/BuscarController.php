<?php

namespace App\Http\Controllers;

use App\Models\D_tramita;
use App\Models\Tema;
use App\Models\Titulo;
use App\Models\Tomo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        //dd($form);

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
        $primeraBusqueda=1;
        $tema=DB::select("select distinct(res_tema) from resolucions order by res_tema ASC");
        return view('resoluciones.buscar.f_buscar_resolucion',compact('resultado','primeraBusqueda','criterio','tema'));
    }
    public function f_buscar_resolucion_post(Request $form){
        //dd($form);
        $resultado=array();
        $consulta="SELECT cod_res,res_numero,res_tipo,res_fecha,res_objeto,res_tema,res_desc,res_ant,res_pdf FROM resolucions WHERE";
        $clausulas=array();
        $criterio=array();
        $i=0;
        if($form['numero']!=''){$clausulas[$i]=" res_numero='".$form['numero']."'";$criterio[$i][0]='Número: ';$criterio[$i][1]=$form['numero']; $i+=1; }
        if($form['tipo']!=''){$clausulas[$i]=" res_tipo='".$form['tipo']."'";$criterio[$i][0]='Tipo: ';$criterio[$i][1]=$form['tipo'];$i+=1;}
        if($form['gestion']!=''){$clausulas[$i]=" res_gestion='".$form['gestion']."'";$criterio[$i][0]='Gestión: ';$criterio[$i][1]=$form['gestion'];$i+=1;}
            if($form['gestion_i']!=''){
                if($form['gestion_f']!=''){
                    $clausulas[$i]=" res_fecha>='".$form['gestion_i']."' and res_fecha<='".$form['gestion_f']."'";
                    $criterio[$i][0]='Rango de fecha: ';$criterio[$i][1]=date('d/m/Y',strtotime($form['gestion_i'])).
                        "<span class='font-weight-bold'> - </span> ".date('d/m/Y',strtotime($form['gestion_f']));
                        $i+=1;
                }else{
                    $clausulas[$i]=" res_fecha='".$form['gestion_i']."'";$criterio[$i][0]='Fecha: ';$criterio[$i][1]=date('d/m/Y',strtotime($form['gestion_i']));
                    $i+=1;
                }

            }
        //$claves=explode(' ',$form['clave']);

        if($form['clave']!=''){
            if($form['tema']!=''){
                $clausulas[$i]=" res_tema ilike '%".$form['tema'].
                    "%' and (res_objeto ilike '%".$form['clave'].
                    "%' or res_desc ilike '%".$form['clave']."%' ";
            }else{
                $clausulas[$i]=" (res_tema ilike '%".$form['clave'].
                    "%' or res_objeto ilike '%".$form['clave'].
                    "%' or res_desc ilike '%".$form['clave']."%' ";
            }


                $criterio[$i][0]='Palabras clave: ';$criterio[$i][1]=$form['clave'];
        }

        if($form['clave']!='' && ($form['vistos'] || $form['considerando'] || $form['resuelve'])){
            $clausulas[$i].=' or';
            $ban=0;
                if($form['vistos']){
                    $clausulas[$i].=" res_vistos ilike '%".$form['clave']."%'";
                    $criterio[$i][1].=" | <span class='font-weight-bold'>Vistos: </span>Sí ";
                    $ban=1;
                }
                if($form['considerando']) {
                    if($ban==1){$clausulas[$i].=" or";}
                    $clausulas[$i].=" res_considerando ilike '%".$form['clave']."%'";
                    $criterio[$i][1].=" | <span class='font-weight-bold'>Considerando: </span>Sí ";
                    $ban=2;
                }
                if($form['resuelve']) {
                    if($ban==2 || $ban==1 ){$clausulas[$i].=" or";}
                    $clausulas[$i].=" res_resuelve ilike '%".$form['clave']."%'";
                    $criterio[$i][1].=" | <span class='font-weight-bold'>Resuelve: </span>Sí ";
                }
            $clausulas[$i].=") ";
                $i+=1;
        }else{
            if($form['clave']) {
                $clausulas[$i] .= ") ";
                $i += 1;
            }
        }
        $tam=sizeof($clausulas);
        if($tam>0){
            for ($i=0;$i<$tam;$i++){
                $consulta.=" ".$clausulas[$i];
                if($i<($tam-1)){
                    $consulta.=" and";
                }
            }
            $clave=$form['clave'];
            $consulta.=" order by res_numero, res_fecha ASC";
            //echo $consulta;
            SessionController::write('B','',$consulta,'resoluciones','2','');
            $resultado=DB::select($consulta);
            $tema=DB::select("select distinct(res_tema) from resolucions order by res_tema ASC");
            if(isset($form['te']) && $form['te']=='t'){
                $cod_tem=$form['ct'];
                return view('resoluciones.temas.tema_resolucion.resultado_busqueda',compact('resultado','clave','criterio','cod_tem','tema'));
            }else{
                return view('resoluciones.buscar.f_buscar_resolucion',compact('resultado','clave','criterio','tema'));
            }

        }else{
            $tema=DB::select("select distinct(res_tema) from resolucions order by res_tema ASC");
            if(isset($form['te']) && $form['te']=='t') {
                return view('resoluciones.temas.tema_resolucion.resultado_busqueda', compact('resultado','tema'));
            }else{
                \Session::flash('error', 'Debe ingresar por lo menos un criterio de búsqueda');
                return view('resoluciones.buscar.f_buscar_resolucion', compact('resultado','tema'));
            }
        }
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
