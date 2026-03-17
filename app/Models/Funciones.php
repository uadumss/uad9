<?php

namespace App\Models;

use App\Http\Controllers\Noatentado\SancionadosController;
use App\Models\Noatentado\D_sancion;
use Illuminate\Database\Eloquent\Model;

class Funciones extends Model
{
    public static function dia($fecha)
    {
        $array_dias['Sunday'] = "Domingo";
        $array_dias['Monday'] = "Lunes";
        $array_dias['Tuesday'] = "Martes";
        $array_dias['Wednesday'] = "Miercoles";
        $array_dias['Thursday'] = "Jueves";
        $array_dias['Friday'] = "Viernes";
        $array_dias['Saturday'] = "Sabado";
        return $array_dias[date('l', strtotime($fecha))];
    }
    public static function mes($mes){
        $array_mes[1]='enero';
        $array_mes[2]='febrero';
        $array_mes[3]='marzo';
        $array_mes[4]='abril';
        $array_mes[5]='mayo';
        $array_mes[6]='junio';
        $array_mes[7]='julio';
        $array_mes[8]='agosto';
        $array_mes[9]='septiembre';
        $array_mes[10]='octubre';
        $array_mes[11]='noviembre';
        $array_mes[12]='diciembre';
        return $array_mes[$mes];
    }
    public static function glosa_tarmites($tramite,$glosa,$docleg,$persona,$titulo,$unidadAcademica){
        $glosa=$glosa->glo_glosa;
        $fecha_tram=$docleg->dtra_fecha_literal;
        //$numero_tramite=$docleg->dtra_numero_tramite."/".$docleg->dtra_gestion_tramite;

        $supletorio=$docleg->dtra_supletorio=='t'?'Certificado supletorio':Funciones::nombre_titulo($tramite->tre_buscar_en);
        $tituloSupletorio=$docleg->dtra_supletorio=='t'?" del ".Funciones::nombre_titulo($tramite->tre_buscar_en):'';

        $nombre="<span style='font-weight:bold'>".$persona->per_nombre." ".$persona->per_apellido."</span>"; // nombre de la persona
        $titulo_glosa="<span style='font-weight:bold'>".$docleg->dtra_titulo."</span>"; // titulo de la glosa
        //$titulo_glosa="<span style='font-weight:bold'>".$tramite->tre_titulo."</span>";
        $titulo_glosa_interno="<span style='font-weight:bold'>".$tramite->tre_titulo_interno."</span>"; // titulo de la glosa interna
        $fecha_tramite="<span style=''>".$fecha_tram."</span>";

        $glosa= str_replace("{supletorio}", $supletorio, $glosa);
        $glosa= str_replace("{titulosupletorio}", $tituloSupletorio, $glosa);


        $glosa= str_replace("{nombre}", mb_strtoupper($nombre), $glosa);
        $glosa= str_replace("{titulo_glosa}", $titulo_glosa, $glosa);
        $glosa= str_replace("{titulo_glosa_interno}", $titulo_glosa_interno, $glosa);
        //$glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        //$glosa= str_replace("{numero_tramite}", $numero_tramite, $glosa);
        $glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        $numero="";
        if($docleg->dtra_numero==0) {
            $numero = "<span style='font-weight:bold'>" . "-/" . substr($docleg->dtra_gestion, -2) . "</span>"; // numero del detalle de tramite
        }else{
            $numero = "<span style='font-weight:bold'>" . $docleg->dtra_numero . "/" . substr($docleg->dtra_gestion, -2) . "</span>"; // numero del detalle de tramite
        }
        $glosa= str_replace("{numero}", $numero, $glosa);
    //================== ESTO ES PARA LA GLOSA UNITARIA DE BUENA CONDUCTA
        $glosa_unitario="";
        if(strpos($glosa, '{glosa_unitaria}')!==false){
            $sancionado=SancionadosController::verificarSancionado($persona->id_per);
            $glosa_unitario=" <span style='font-weight: bold'>".$persona->per_apellido." ".$persona->per_nombre."</span> con cédula de identidad No. ".$persona->per_ci.", ";
            if($sancionado){
                $glosa_unitario.=" lo siguiente: ";
                $detalle=D_sancion::where('cod_san','=',$sancionado->cod_san)->get();
                $i=1;
                $glosa_unitario.="<div style='padding-left: 20px;padding-left: 20px'>";
                foreach ($detalle as $d) {
                    $glosa_unitario .="<span style='font-weight: bold'>". $i . "</span>. " . $d->dsan_detalle . "<br/><br/>";
                    $i++;
                }
                $glosa_unitario.="</div>";
            }else{
                $glosa_unitario.=" <span style='font-weight: bold'> NO </span>";
                $glosa_unitario.="ha sido sometido(a) a proceso; ni ha sido sancionado(a) por falta alguna. Así mismo se certifica que no ha sido
                                    condenado(a) por actividades violatorias a la <span style='font-weight: bold'>AUTONOMIA UNIVERSITARIA.</span> ";
            }
            $glosa= str_replace("{glosa_unitaria}", $glosa_unitario, $glosa);
        }

        //========================
        if($titulo) {
            $f_e= date('d',strtotime($titulo->tit_fecha_emision))." de ".Funciones::mes(date('n')).' de '.date('Y',strtotime($titulo->tit_fecha_emision));
            $gr=$titulo->tit_grado;
            $numero_folio=$titulo->tit_nro_folio;
            $f_folio=$titulo->tit_fecha_folio;
            $tipo=$titulo->tit_tipo;
            $numero="";
            if($docleg->dtra_numero==0){
                $numero="<span style='font-weight:bold'>"."-/".substr($docleg->dtra_gestion,-2)."</span>"; // numero del detalle de tramite
            }else{
                $numero="<span style='font-weight:bold'>".$docleg->dtra_numero."/".substr($docleg->dtra_gestion,-2)."</span>"; // numero del detalle de tramite
            }
            $numero="<span style='font-weight:bold'>".$docleg->dtra_numero."/".substr($docleg->dtra_gestion,-2)."</span>"; // numero del detalle de tramite
            $titulo = "<span style='font-weight:bold'>" . $titulo->tit_titulo . "</span>"; //numero de titulo
            $fecha_titulo = "<span style='font-weight:bold'>" . $f_e . "</span>"; // fecha emision del titulo
            $grado = "<span style='font-weight:bold'>" . $gr . "</span>";// grado del titulo
            $autoridad = "<span style='font-weight:bold'>AUT 1 </span>"; // firma autoridad 1
            $autoridad2 = "<span style='font-wight:bold'>AUT 2</span>"; // firma autoridad 2

            if(($tipo=="da" || $tipo=="ca" || $tipo=="tp") && $unidadAcademica){
                $facultad = "<span style='font-weight:bold'>" . $unidadAcademica->fac_nombre . "</span>"; // nombre de la facultad
                $carrera = "<span style='font-weight:bold'>" . $unidadAcademica->car_nombre . "</span>"; // nombre de la carrera

                $glosa= str_replace("{facultad}", $facultad, $glosa);
                $glosa= str_replace("{carrera}", $carrera, $glosa);
            }

            $n_folio="<span style='font-weight:bold'>".$numero_folio."</span>"; // numero de folio del documento
            $fecha_folio="<span style='font-weight:bold'>".date('d-m-Y',strtotime($f_folio))."</span>";//fecha del folio del documento

            $glosa= str_replace("{numero}", $numero, $glosa);
            $glosa= str_replace("{titulo}", $titulo, $glosa);
            $glosa= str_replace("{fecha_titulo}", $fecha_titulo, $glosa);
            $glosa= str_replace("{titulo_glosa}", $titulo_glosa, $glosa);
            $glosa= str_replace("{ci}", $persona->per_ci, $glosa);

            $glosa= str_replace("{grado}", $grado, $glosa);
            $glosa= str_replace("{autoridad}", $autoridad, $glosa);
            $glosa= str_replace("{autoridad2}", $autoridad2, $glosa);
            $glosa= str_replace("{n_folio}", $n_folio, $glosa);
            $glosa= str_replace("{fecha_folio}", $fecha_folio, $glosa);

        }
        return $glosa;
    }
    public static function glosa_noatentado($tramite,$modelo_glosa,$tramite_noatentado,$convocatoria,$candidatos){

        $glosa=$modelo_glosa->glo_glosa;
        $fecha_tramite="<span style=''>".$tramite_noatentado->dtra_fecha_literal."</span>";
        $titulo='';
        if($tramite_noatentado->dtra_interno=='t'){
            $titulo="<span style='font-weight:bold'>".$tramite->tre_titulo."</span>"; // titulo de la glosa
        }else{
            $titulo="<span style='font-weight:bold'>".$tramite->tre_titulo_interno."</span>"; // titulo de la glosa interna
        }
        $nombre_convocatoria=$convocatoria->con_nombre;
        $periodo="<span style='font-weight:bold'>".$convocatoria->con_periodo_inicial." - ".$convocatoria->con_periodo_final."</span>";
        $glosa_unitario="";
        $glosa_grupal="";
        if(sizeof($candidatos)>1){
            $con_sancion=array();
            $sin_sancion=array();
            $i=0;
            $j=0;
            foreach ($candidatos as $c){
                if(SancionadosController::verificarSancionado($c->id_per)){
                    $con_sancion[$i]=$c;
                    $i++;
                }else{
                    $sin_sancion[$j]=$c;
                    $j++;
                }
            }
            if(sizeof($sin_sancion)>0){
                $glosa_grupal="la presente nómina que se detalla a continuación de ".sizeof($sin_sancion)." candidatos <span style='font-weight: bold'>NO</span> han sido
                            sometidos(as) a proceso, ni han sido sancionados(as) por falta alguna. Así mismo se certifica que no han sido condenados(as) por
                            actividades violatorias a la <span style='font-weight: bold'>AUTONOMIA UNIVERSITARIA.</span> <br/><br/>";

                $glosa_grupal.="<table style='border-spacing: 0px; font-size: 9px' >
                                <tr>
                                    <th style='border: 1px solid;'>No.</th>
                                    <th style='border: 1px solid'>NOMBRE COMPLETO</th>
                                    <th style='border: 1px solid'>C.I.</th>
                                    <th style='border: 1px solid'>CARGO</th>

                                </tr>
                                ";
                $j=1;
                foreach ($sin_sancion as $s):
                    $glosa_grupal.="<tr style='font-size: 9px'>
                                    <td style='border: 1px solid'>".$j++."</td>
                                    <td style='border: 1px solid'>".$s->per_apellido." ".$s->per_nombre."</td>
                                    <td style='border: 1px solid'>".$s->per_ci."</td>
                                    <td style='border: 1px solid'>".$s->carg_nombre."</td>

                                </tr>";
                endforeach;
                $glosa_grupal.="</table>";
            }
            //dd($con_sancion);
            if(sizeof($con_sancion)>0){
                $glosa_grupal .= "<br/> Además se informa que : <br/><br/>";
                foreach ($con_sancion as $sancionado) {
                    $glosa_grupal.= " <span style='font-weight: bold'>" . $sancionado->per_apellido . " " . $sancionado->per_nombre . "</span> con cédula de identidad No. " . $sancionado->per_ci . ", ";
                    $aux_sancionado = SancionadosController::verificarSancionado($sancionado->id_per);
                    if ($aux_sancionado) {
                        $glosa_grupal .= " registra las siguientes observaciones : ";
                        $detalle = D_sancion::where('cod_san', '=', $aux_sancionado->cod_san)->get();
                        $i = 1;
                        $glosa_grupal .= "<div style='padding-left: 20px'>";
                        foreach ($detalle as $d) {
                            $glosa_grupal .="<span style='font-weight: bold'>". $i . "</span>. " . $d->dsan_detalle . "<br/><br/>";
                            $i++;
                        }
                        $glosa_grupal .= "</div>";
                    }
                }
            }
        }else{
            if(sizeof($candidatos)==1){
                $glosa_unitario=" <span style='font-weight: bold'>".$candidatos[0]->per_apellido." ".$candidatos[0]->per_nombre."</span> con cédula de identidad No. ".$candidatos[0]->per_ci.", ";
                $sancionado=SancionadosController::verificarSancionado($candidatos[0]->id_per);
                if($sancionado){
                    $glosa_unitario.=" lo siguiente: ";
                    $detalle=D_sancion::where('cod_san','=',$sancionado->cod_san)->get();
                    $i=1;
                    $glosa_unitario.="<div style='padding-left: 20px;padding-left: 20px'>";
                    foreach ($detalle as $d) {
                        $glosa_unitario .="<span style='font-weight: bold'>". $i . "</span>. " . $d->dsan_detalle . "<br/><br/>";
                        $i++;
                    }
                    $glosa_unitario.="</div>";

                }else{
                    $glosa_unitario.=" <span style='font-weight: bold'> NO </span>";
                    $glosa_unitario.="ha sido sometido(a) a proceso; ni ha sido sancionado(a) por falta alguna. Así mismo se certifica que no ha sido
                                    condenado(a) por actividades violatorias a la <span style='font-weight: bold'>AUTONOMIA UNIVERSITARIA.</span> ";
                }
            }
        }
        $glosa= str_replace("{glosa_unitaria}", $glosa_unitario, $glosa);
        $glosa= str_replace("{glosa_grupal}", $glosa_grupal, $glosa);
        $glosa= str_replace("{periodo}", $periodo, $glosa);
        $glosa= str_replace("{titulo}", $titulo, $glosa);
        $glosa= str_replace("{nombre_convocatoria}", $nombre_convocatoria, $glosa);
        $glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        if(sizeof($candidatos)==0){
            $glosa="0";
        }
        return $glosa;
    }
    public static function glosa_consejo($tramite,$glosa,$docleg,$persona){
        $glosa=$glosa->glo_glosa;
        $nombrado="";
        $fecha_tramite="<span style=''>".$tramite->dtra_fecha_literal."</span>";

        if($docleg->dtra_interno=='t'){
            $titulo="<span style='font-weight:bold'>".$tramite->tre_titulo."</span>"; // titulo de la glosa
        }else{
            $titulo="<span style='font-weight:bold'>".$tramite->tre_titulo_interno."</span>"; // titulo de la glosa interna
        }
        $electos=Electo::where('id_per','=',$persona->id_per)->orderBy('ele_fecha_inicio','ASC')->get();
        $glosa_periodo="";
        if(sizeof($electos)>0){
            $nombrado="ha sido nombrado:";
            $i=1;
            $glosa_periodo="<ul style='text-align: justify;'>";
            foreach ($electos as $e):
                $glosa_periodo.="<li> Delegado(a) ";
                $glosa_periodo.=($e->ele_titular=='t')? "<span style='font-weight: bold'>titular </span>":"<span style='font-weight: bold'>suplente</span> ";
                $glosa_periodo.=($e->ele_docente=='t')? "<span style='font-weight: bold'>DOCENTE </span>":"<span style='font-weight: bold'>ESTUDIANTIL</span> ";
                $glosa_periodo.=" ante el ";
                if($e->ele_tipo=='u'){
                    $glosa_periodo.=" Honorable Consejo Universitario por la ";
                    $facultad=Facultad::find($e->cod_fac);
                    $glosa_periodo.=$facultad->fac_nombre;
                }else{
                    if($e->ele_tipo=='f'){
                        $carrera=Carrera::find($e->cod_car);
                        $facultad=Facultad::find($carrera->cod_fac);
                        $glosa_periodo.=" Honorable Consejo Facultativo de la ".$facultad->fac_nombre." por la carrera de ";

                        $glosa_periodo.=$carrera->car_nombre;
                    }else{
                        if($e->ele_tipo=='c'){
                            $glosa_periodo.=" Honorable Consejo de Carrera de ";
                            $carrera=Carrera::find($e->cod_car);
                            $glosa_periodo.=$carrera->car_nombre;
                        }
                    }
                }
                $glosa_periodo.=" durante los periodos ".date('Y',strtotime($e->ele_fecha_inicio))." - ".date('Y',strtotime($e->ele_fecha_fin))." desde el ";
                $f_inicio= date('d',strtotime($e->ele_fecha_inicio))." de ".Funciones::mes(date('n',strtotime($e->ele_fecha_inicio))).' de '.date('Y',strtotime($e->ele_fecha_inicio));
                $f_fin= date('d',strtotime($e->ele_fecha_fin))." de ".Funciones::mes(date('n',strtotime($e->ele_fecha_fin))).' de '.date('Y',strtotime($e->ele_fecha_fin));
                $glosa_periodo.=$f_inicio." hasta el ".$f_fin;
                if($e->ele_fecha_renuncia!=''){
                    $glosa_periodo.= ", <span style='font-weight: bold'>habiendo RENUNCIADO</span> en fecha ".date('d',strtotime($e->ele_fecha_renuncia))." de ".Funciones::mes(date('n')).' de '.date('Y',strtotime($e->ele_fecha_renuncia));
                }
                $glosa_periodo.=". <br/><br/></li>";

            endforeach;
            $glosa_periodo.="</ul>";
        }else{
            $nombrado= "<span style='font-weight: bold'>NO</span> ha sido nombrado como delegado o consejero";
        }

        $nombre=$persona->per_apellido." ".$persona->per_nombre;
        $ci=$persona->per_ci;

        $glosa= str_replace("{periodo_consejo}", $glosa_periodo, $glosa);
        $glosa= str_replace("{nombre}", $nombre, $glosa);
        $glosa= str_replace("{ci}", $ci, $glosa);
        $glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        $glosa= str_replace("{nombrado}", $nombrado, $glosa);

        return $glosa;
    }
    public static function nombre_titulo($documento){

        switch ($documento){
            case 'db': return 'Diploma de Bachiller'; break;
            case 'da': return 'Diploma Académico'; break;
            case 'ca': return 'Certificado Académico'; break;
            case 'tp': return 'Título Profesional'; break;
            case 'tpos': return 'Título de posgrado'; break;
            case 'di': return 'Diploma Académico'; break;
            case 'su': return 'Certificado Supletorio';break;
            case 're': return 'Reválida'; break;
        }
    }
    public static function tipo_resolucion($documento){
        switch ($documento){
            case 'rr': return 'Resolución rectoral'; break;
            case 'rcu': return 'Resolución de Consejo Universitario'; break;
            case 'rvr': return 'Resolución Vicerrectoral'; break;
            case 'rs': return 'Resolución secretarial'; break;
            case 'rcf': return 'Resolución de Consejo Facultativo'; break;
            case 'rcc': return 'Resolución de Consejo de Carrera'; break;
            case 'rc': return 'Resolución de Congresos'; break;
        }
    }
    public static function tipo_tramite($tipo){
        switch ($tipo){
            case 'L': return 'Legalización'; break;
            case 'C': return 'Certificación'; break;
            case 'F': return 'Confrontación'; break;
            case 'B': return 'Búsqueda'; break;
        }
    }
    public static function nombre_documento($documento){
        switch ($documento){
            case "ci": return 'Cédula de Identidad'; break;
            case "cn": return 'Certificado de Nacimiento'; break;
            case "lm": return 'Libreta Servicio Militar'; break;
            case "ce": return 'Carnet Extranjería'; break;
            case "pa": return 'Passaporte'; break;
            case "lc": return 'Libreta de colegio'; break;
        }
    }
    public static function operacion_bitacora($operacion){
        switch ($operacion){
            case "C": return 'Creación'; break;
            case "U": return 'Actualización'; break;
            case "D": return 'Eliminacion'; break;
        }
    }
    public static function tipo_ptaang($ptaang){
        switch ($ptaang) {
            case "A": return 'DIPLOMA ACADEMICO'; break;
            case "B":  return 'DIPLOMA DE BACHILLER'; break;
        }
    }
    public static function DocumentoSitra($tipo){
        switch ($tipo){
            case "db": return 'DB'; break;
            case "da":  return 'AC'; break;
            case "ca":  return 'CA'; break;
            case "tp":  return 'PN'; break;
            case "re":  return 'RE'; break;
            case "su":  return 'SU'; break;
        }
    }
    public static function grados($tipo){
        $grados=array();
        switch ($tipo){
            case 'db': $grados[0]='BACHILLER'; break;
            case 'da': $grados[0]='LICENCIATURA';$grados[1]='TECNICO MEDIO';$grados[2]='TECNICO SUPERIOR'; break;
            case 'ca': $grados[0]='AUXILIAR'; break;
            case 'tp': $grados[0]='LICENCIATURA';$grados[1]='TECNICO MEDIO';$grados[2]='TECNICO SUPERIOR'; $grados[3]='AUXILIAR';break;
            case 'tpos': $grados[0]='ESPECIALIDAD';$grados[1]='MAESTRIA';$grados[2]='DOCTORADO'; break;
            case 'di': $grados[0]='DIPLOMADO'; break;
            case 'su': $grados[0]='BACHILLER';$grados[1]='LICENCIATURA';$grados[2]='TECNICO MEDIO';$grados[3]='TECNICO SUPERIOR';$grados[4]='AUXILIAR';
                $grados[5]='DIPLOMADO';$grados[6]='ESPECIALIDAD';$grados[7]='MAESTRIA';$grados[8]='DOCTORADO';
                break;
            case 're': $grados[0]='BACHILLER';$grados[1]='LICENCIATURA';$grados[2]='TECNICO MEDIO';$grados[3]='TECNICO SUPERIOR';$grados[4]='AUXILIAR';
                $grados[5]='DIPLOMADO';$grados[6]='ESPECIALIDAD';$grados[7]='MAESTRIA';$grados[8]='DOCTORADO';
                break;
        }
        return $grados;
    }
    public static function alfanumerico($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($pattern)-1;
        for($i=0;$i < $longitud;$i++) $key .= $pattern[mt_rand(0,$max)];
        return $key;
    }
    public static function valorQR($dia,$mes,$año,$tamaño) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($pattern)-1;
        for($i=0;$i < $tamaño;$i++) $key .= $pattern[mt_rand(0,$max)];
        return $dia.$mes.$año.$key;
    }
}
