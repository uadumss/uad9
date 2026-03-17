<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\D_tramita;
use App\Models\Facultad;
use App\Models\Funciones;
use App\Models\Noatentado\Convocatoria;
use App\Models\Noatentado\Noatentado;
use App\Models\Persona;
use App\Models\Titulo;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QRController extends Controller
{
    public function verificar_QR($qr){
        $d_tramitas=D_tramita::where('dtra_qr','=',$qr)->first();
        $datos=array();
        if($d_tramitas){
            $tramite=Tramite::find($d_tramitas->cod_tre);
            if($d_tramitas->dtra_tipo=='A'){
                $convocatoria=Convocatoria::find($d_tramitas->cod_con);
                $noatentado=DB::table('noatentado.noatentado')
                    ->join('personas','noatentado.id_per','=','personas.id_per')
                    ->leftJoin('noatentado.sancionados','personas.id_per','=','sancionados.id_per')
                    ->where('cod_dtra','=',$d_tramitas->cod_dtra)
                    ->select('per_nombre','per_apellido','per_ci','cod_san')->orderBy('per_apellido','ASC')->get();

                $nombre='';
                //dd($noatentado);
                //return sizeof($noatentado);
                foreach ($noatentado as $n):
                    $nombre.='* '.$n->per_apellido." ".$n->per_nombre." - CI: ".$n->per_ci;
                    if($n->cod_san!=''){
                        $nombre.=" - <span style='color: #cc0000;font-weight: bold'>OBSERVADO</span> <br/>";
                    }else{
                        $nombre.="<br/>";
                    }
                endforeach;
                $datos=array($d_tramitas->dtra_numero_tramite."/".$d_tramitas->dtra_gestion_tramite,'NO ATENTADO',$convocatoria->con_nombre
                ,$d_tramitas->dtra_fecha_firma,$nombre,'',$d_tramitas->dtra_tipo);
                //return "hola";
                return ($datos);
            }else{
                $titulo=Titulo::find($d_tramitas->dtra_cod_tit);
                $persona=DB::table('personas')
                    ->join('tramitas','personas.id_per','=','tramitas.id_per')
                    ->select('per_nombre','per_apellido','per_ci')
                    ->where('cod_tra','=',$d_tramitas->cod_tra)->first();
                //$tipo_documento=Funciones::nombre_titulo($titulo->tit_tipo);
                $tipo_documento=Funciones::nombre_titulo($tramite->tre_buscar_en);

                if($titulo){
                    $datos=array($persona->per_apellido." ".$persona->per_nombre,$titulo->tit_titulo,$d_tramitas->dtra_numero."/".$d_tramitas->dtra_gestion,$d_tramitas->dtra_numero_tramite."/".$d_tramitas->dtra_gestion_tramite,strtoupper($tipo_documento)
                    ,$d_tramitas->dtra_fecha_firma,$d_tramitas->dtra_tipo);
                }else{
                    if($d_tramitas->dtra_tipo=='E'){
                        $consulta=DB::select("select e.*,f.fac_nombre,c.car_nombre from d_tramitas dt join tramitas t on dt.cod_tra=t.cod_tra,
                                                        claustros.electos e left join facultads f on e.cod_fac=f.cod_fac left join carreras c on e.cod_car=c.cod_car
                                                        where t.id_per=e.id_per and dt.cod_dtra=".$d_tramitas->cod_dtra);
                        $glosa_periodo="<ul>";
                        foreach ($consulta as $c){
                            $glosa_periodo.="<li>";
                            $glosa_periodo.=($c->ele_titular=='t')? "<span style='font-weight: bold'>titular </span>":"<span style='font-weight: bold'>suplente</span> ";
                            $glosa_periodo.=($c->ele_docente=='t')? "<span style='font-weight: bold'>DOCENTE </span>":"<span style='font-weight: bold'>ESTUDIANTIL</span> ";

                            if($c->ele_tipo=='u'){
                                $glosa_periodo.=" Honorable Consejo Universitario por la ".$c->fac_nombre;

                            }else{
                                if($c->ele_tipo=='f'){
                                    $glosa_periodo.=" Honorable Consejo Facultativo por la ".$c->car_nombre;
                                }else{
                                    if($c->ele_tipo=='c'){
                                        $glosa_periodo.=" Honorable Consejo de Carrea por la ".$c->fac_nombre;

                                    }
                                }
                            }
                            $glosa_periodo.=" durante los periodos ".date('Y',strtotime($c->ele_fecha_inicio))." - ".date('Y',strtotime($c->ele_fecha_fin))." desde el ";
                            $f_inicio= date('d',strtotime($c->ele_fecha_inicio))." de ".Funciones::mes(date('n')).' de '.date('Y',strtotime($c->ele_fecha_inicio));
                            $f_fin= date('d',strtotime($c->ele_fecha_fin))." de ".Funciones::mes(date('n')).' de '.date('Y',strtotime($c->ele_fecha_fin));
                            $glosa_periodo.=$f_inicio." hasta el ".$f_fin;
                            if($c->ele_fecha_renuncia!=''){
                                $glosa_periodo.= ", <span style='font-weight: bold'>habiendo RENUNCIADO</span> en fecha ".date('d',strtotime($c->ele_fecha_renuncia))." de ".Funciones::mes(date('n')).' de '.date('Y',strtotime($c->ele_fecha_renuncia));
                            }

                            $glosa_periodo.="</li>";
                        }
                        $glosa_periodo.="</ul>";
                        //dd($glosa_periodo);
                        $datos=array($persona->per_apellido." ".$persona->per_nombre,$glosa_periodo,'',$d_tramitas->dtra_numero_tramite."/".$d_tramitas->dtra_gestion_tramite,strtoupper($tipo_documento)
                        ,$d_tramitas->dtra_fecha_firma,$d_tramitas->dtra_tipo);
                    }else{
                        $datos=array($persona->per_apellido." ".$persona->per_nombre,'',$d_tramitas->dtra_numero."/".$d_tramitas->dtra_gestion,$d_tramitas->dtra_numero_tramite."/".$d_tramitas->dtra_gestion_tramite,strtoupper($tipo_documento)
                        ,$d_tramitas->dtra_fecha_firma,$d_tramitas->dtra_tipo);
                    }

                }
                return ($datos);
            }
        }else{
            return array(0);
        }
    }
}
