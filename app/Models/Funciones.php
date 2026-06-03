<?php

namespace App\Models;

use App\Http\Controllers\Noatentado\SancionadosController;
use App\Models\Noatentado\D_sancion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Funciones extends Model
{
    private static function fechaLiteralDesdeValor($fecha): string
    {
        $valor = trim((string)$fecha);
        if ($valor === '') {
            return '';
        }

        $formatos = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d', 'Y-m-d H:i:s', 'd/m/Y H:i:s', 'd-m-Y H:i:s'];
        foreach ($formatos as $formato) {
            $dt = \DateTime::createFromFormat($formato, $valor);
            if ($dt instanceof \DateTime) {
                return $dt->format('d').' de '.self::mes((int)$dt->format('n')).' de '.$dt->format('Y');
            }
        }

        $timestamp = strtotime($valor);
        if ($timestamp !== false) {
            return date('d', $timestamp).' de '.self::mes((int)date('n', $timestamp)).' de '.date('Y', $timestamp);
        }

        // Si no se puede parsear, se usa el texto original para no perder el dato cargado.
        return $valor;
    }

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

        // Género e interesado
        $sexo = mb_strtoupper(trim((string)($persona->per_sexo ?? '')));
        $esFemenino = in_array($sexo, ['F', 'FEMENINO', 'MUJER']);
        $tratamiento = $esFemenino ? 'la señora' : 'el señor';
        $interesado = $esFemenino ? 'DE LA INTERESADA' : 'DEL INTERESADO';
        $delSenor = $esFemenino ? 'de la señora' : 'del señor';

        $glosa= str_replace("{--el la señora--}", $tratamiento, $glosa);
        $glosa= str_replace("{--el la se&ntilde;ora--}", $tratamiento, $glosa);
        $glosa= str_replace("{el la señora}", $tratamiento, $glosa);
        $glosa= str_replace("{el la se&ntilde;ora}", $tratamiento, $glosa);
        $glosa= str_replace("{--DEL INTERESADO--}", $interesado, $glosa);
        $glosa= str_replace("{DEL INTERESADO}", $interesado, $glosa);
        $glosa= str_replace("{--del señor--}", $delSenor, $glosa);
        $glosa= str_replace("{--del se&ntilde;or--}", $delSenor, $glosa);
        $glosa= str_replace("{del señor}", $delSenor, $glosa);
        $glosa= str_replace("{del se&ntilde;or}", $delSenor, $glosa);

        // TinyMCE puede partir placeholders con etiquetas; cubrir ese formato también.
        $glosa = preg_replace('/\{--\s*el\s+la\s+se(?:ñ|&ntilde;)ora\s*--(?:\s*<[^>]+>\s*)*\}/iu', $tratamiento, $glosa);
        $glosa = preg_replace('/\{--\s*del\s+se(?:ñ|&ntilde;)or\s*--(?:\s*<[^>]+>\s*)*\}/iu', $delSenor, $glosa);

        $glosa= str_replace("{nombre}", mb_strtoupper($nombre), $glosa);
        $glosa= str_replace("{titulo_glosa}", $titulo_glosa, $glosa);
        $glosa= str_replace("{titulo_glosa_interno}", $titulo_glosa_interno, $glosa);
        //$glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        //$glosa= str_replace("{numero_tramite}", $numero_tramite, $glosa);
        $glosa= str_replace("{fecha_tramite}", $fecha_tramite, $glosa);
        
        // CI disponible ahora sin depender de $titulo
        $glosa= str_replace("{ci}", $persona->per_ci, $glosa);
        
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
            $f_e = self::fechaLiteralDesdeValor($titulo->tit_fecha_emision ?? '');
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
            $tituloBase = trim((string)($titulo->tit_titulo ?? ''));
            if ($tituloBase === '') {
                $tituloBase = trim((string)($docleg->dtra_titulo ?? ''));
            }
            $titulo_formatted = "<span style='font-weight:bold'>" . $tituloBase . "</span>"; //numero de titulo
            $fecha_titulo = "<span style='font-weight:bold'>" . $f_e . "</span>"; // fecha emision del titulo
            $grado = "<span style='font-weight:bold'>" . $gr . "</span>";// grado del titulo
            $autoridad = "<span style='font-weight:bold'>AUT 1 </span>"; // firma autoridad 1
            $autoridad2 = "<span style='font-wight:bold'>AUT 2</span>"; // firma autoridad 2

            if($unidadAcademica && !empty($unidadAcademica->car_nombre)){
                $facultad = "<span style='font-weight:bold'>" . $unidadAcademica->fac_nombre . "</span>"; // nombre de la facultad
                $carrera = "<span style='font-weight:bold'>" . $unidadAcademica->car_nombre . "</span>"; // nombre de la carrera

                $glosa= str_replace("{facultad}", $facultad, $glosa);
                $glosa= str_replace("{carrera}", $carrera, $glosa);
            }

            $n_folio="<span style='font-weight:bold'>".$numero_folio."</span>"; // numero de folio del documento
            $fecha_folio="<span style='font-weight:bold'>".date('d-m-Y',strtotime($f_folio))."</span>";//fecha del folio del documento

            $glosa= str_replace("{numero}", $numero, $glosa);
            $glosa= str_replace("{titulo}", $titulo_formatted, $glosa);
            $glosa= str_replace("{fecha_titulo}", $fecha_titulo, $glosa);
            $glosa= str_replace("{titulo_glosa}", $titulo_glosa, $glosa);

            $glosa= str_replace("{grado}", $grado, $glosa);
            $glosa= str_replace("{autoridad}", $autoridad, $glosa);
            $glosa= str_replace("{autoridad2}", $autoridad2, $glosa);
            $glosa= str_replace("{n_folio}", $n_folio, $glosa);
            $glosa= str_replace("{fecha_folio}", $fecha_folio, $glosa);

        } else {
            // Si no hay título, se mantiene el placeholder original.
        }

        if ($titulo && strpos($glosa, '{fecha_titulo}') !== false) {
            $fechaTituloValor = self::fechaLiteralDesdeValor($titulo->tit_fecha_emision ?? '');
            $glosa = str_replace('{fecha_titulo}', "<span style='font-weight:bold'>".$fechaTituloValor."</span>", $glosa);
        }
        if (!empty($unidadAcademica) && !empty($unidadAcademica->cod_car)) {
            if (strpos($glosa, '{carrera}') !== false && !empty($unidadAcademica->car_nombre)) {
                $glosa = str_replace('{carrera}', "<span style='font-weight:bold'>".$unidadAcademica->car_nombre."</span>", $glosa);
            }

            // Obtener el campo de forma segura
            $campo = null;
            if ($unidadAcademica instanceof \Illuminate\Database\Eloquent\Model) {
                $campo = $unidadAcademica->campo;
            } else {
                $campo = \App\Models\CarreraCampo::where('cod_car', $unidadAcademica->cod_car)->first();
            }

            if ($campo) {
                $glosa = str_replace("{campo_amplio}", "<span style='font-weight:bold'>".$campo->campo_amplio."</span>", $glosa);
                $glosa = str_replace("{campo_especifico}", "<span style='font-weight:bold'>".$campo->campo_especifico."</span>", $glosa);
                $glosa = str_replace("{campo_detallado}", "<span style='font-weight:bold'>".$campo->campo_detallado."</span>", $glosa);
            } else {
                $glosa = str_replace("{campo_amplio}", "", $glosa);
                $glosa = str_replace("{campo_especifico}", "", $glosa);
                $glosa = str_replace("{campo_detallado}", "", $glosa);
            }
        }
        
        // Resolución de acreditación (aplica siempre)
        if (!empty($unidadAcademica) && !empty($unidadAcademica->cod_car)) {
            $acreditacion = DB::table('carrera_acreditaciones')
                ->where('cod_car', '=', $unidadAcademica->cod_car)
                ->orderByDesc('resolucion_fecha_emision')
                ->orderByDesc('cod_cac')
                ->first();

            if ($acreditacion) {
                $numero_res = trim((string)($acreditacion->resolucion_numero ?? ''));
                $anio_res = trim((string)($acreditacion->resolucion_anio ?? ''));
                if ($numero_res !== '' && $anio_res !== '' && strpos($numero_res, '/') === false) {
                    $numero_res .= '/'.$anio_res;
                }
                if ($numero_res !== '') {
                    $glosa= str_replace("{resolucion_numero}", $numero_res, $glosa);
                    $glosa= str_replace("{numero_resolucion}", $numero_res, $glosa);
                }

                $fechaResolucion = $acreditacion->resolucion_fecha_emision
                    ?: $acreditacion->resolucion_inicio
                    ?: $acreditacion->fecha_acreditacion;
                if ($fechaResolucion) {
                    $fecha_res_ts = strtotime((string)$fechaResolucion);
                    if ($fecha_res_ts !== false) {
                        $fecha_res_literal = date('j', $fecha_res_ts).' de '.self::mes((int)date('n', $fecha_res_ts)).' de '.date('Y', $fecha_res_ts);
                        $glosa= str_replace("{resolucion_fecha}", $fecha_res_literal, $glosa);
                        $glosa= str_replace("{fecha_resolucion}", $fecha_res_literal, $glosa);
                    }
                }
            }
        }

        // Si no hay datos de respaldo, se mantienen placeholders para revisión manual.
        
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
                    $cargoCandidato=self::resolverCargoCandidatoGlosaNoAtentado($s);
                    $glosa_grupal.="<tr style='font-size: 9px'>
                                    <td style='border: 1px solid'>".$j++."</td>
                                    <td style='border: 1px solid'>".$s->per_apellido." ".$s->per_nombre."</td>
                                    <td style='border: 1px solid'>".$s->per_ci."</td>
                                    <td style='border: 1px solid'>".$cargoCandidato."</td>

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
                // Obtener los cargos registrados en la convocatoria para este trámite
                $cargosConvocatoria=self::obtenerCargosConvocatoriaNoAtentado((int)($tramite_noatentado->cod_con ?? 0));
                $nombre_convocatoria=self::ajustarConvocatoriaPorCargoCandidato(
                    $nombre_convocatoria,
                    $candidatos[0],
                    $cargosConvocatoria
                );
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
        
        // Soporte para placeholder {cargo} en glosa unitaria
        if(sizeof($candidatos) == 1){
            $cargoUnico = self::resolverCargoCandidatoGlosaNoAtentado($candidatos[0]);
            $glosa = str_replace("{cargo}", $cargoUnico, $glosa);
        }
        if(sizeof($candidatos)==0){
            $glosa="0";
        }
        return $glosa;
    }

    private static function resolverCargoCandidatoGlosaNoAtentado($candidato): string
    {
        $cargo=self::normalizarCargoGlosaNoAtentado((string)($candidato->noa_cargo ?? ''));
        if($cargo!==''){
            return $cargo;
        }

        $cargo=self::normalizarCargoGlosaNoAtentado((string)($candidato->carg_nombre ?? ''));
        if($cargo!==''){
            return $cargo;
        }

        $titular=mb_strtolower(trim((string)($candidato->noa_titular ?? '')));
        if($titular==='t'){
            return 'TITULAR';
        }
        if($titular==='f'){
            return 'SUPLENTE';
        }

        return '-';
    }

    private static function normalizarCargoGlosaNoAtentado(string $cargo): string
    {
        $cargo=trim($cargo);
        if($cargo===''){
            return '';
        }

        $cargo=preg_replace('/\s+/u',' ',$cargo);
        return mb_strtoupper((string)$cargo);
    }

    private static function resolverRolCandidatoNoAtentado($candidato): string
    {
        $flagTitular=self::normalizarTextoAjusteNoAtentado((string)($candidato->noa_titular ?? ''));
        if(in_array($flagTitular,['t','1','true','si','s','titular'],true)){
            return 'TITULAR';
        }
        if(in_array($flagTitular,['f','0','false','no','n','suplente'],true)){
            return 'SUPLENTE';
        }

        $textoCargo=self::normalizarTextoAjusteNoAtentado(
            (string)($candidato->noa_cargo ?? '').' '.(string)($candidato->carg_nombre ?? '')
        );
        if($textoCargo===''){
            return '';
        }

        $tieneTitular=strpos($textoCargo,'titular')!==false;
        $tieneSuplente=strpos($textoCargo,'suplente')!==false;

        if($tieneTitular && !$tieneSuplente){
            return 'TITULAR';
        }
        if($tieneSuplente && !$tieneTitular){
            return 'SUPLENTE';
        }

        return '';
    }

    private static function normalizarTextoAjusteNoAtentado(string $texto): string
    {
        $texto=trim($texto);
        if($texto===''){
            return '';
        }
        $texto=mb_strtolower($texto,'UTF-8');
        $texto=str_replace(['á','é','í','ó','ú','ñ'],['a','e','i','o','u','n'],$texto);
        $texto=preg_replace('/\s+/u',' ',$texto) ?? $texto;
        return trim($texto);
    }

    /**
     * Obtiene los cargos de una convocatoria como pares [raw => normalizado].
     * raw   = nombre exacto del DB (con tildes, tal como está).
     * norm  = uppercase sin tildes, para comparación.
     * Retorna array de ['raw'=>string, 'norm'=>string].
     */
    private static function obtenerCargosConvocatoriaNoAtentado(int $codCon): array
    {
        if ($codCon <= 0) {
            return [];
        }

        $filas = DB::table('claustros.cargo_convocatoria')
            ->where('cod_con', '=', $codCon)
            ->pluck('carg_nombre');

        $cargos = [];
        foreach ($filas as $nombre) {
            $raw  = trim((string)$nombre);
            $norm = self::normalizarTextoCargoAjusteNoAtentado($raw);
            if ($norm !== '') {
                $cargos[] = ['raw' => $raw, 'norm' => $norm];
            }
        }

        return $cargos;
    }

    /**
     * Normaliza un cargo para comparación: mayúsculas, sin tilde, sin espacios extra.
     */
    private static function normalizarTextoCargoAjusteNoAtentado(string $texto): string
    {
        $valor = mb_strtoupper(trim($texto), 'UTF-8');
        $valor = strtr($valor, ['Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N']);
        $valor = preg_replace('/\s+/u', ' ', $valor) ?? $valor;
        return trim($valor);
    }

    /**
     * Resuelve el cargo normalizado del candidato.
     * Prioridad: carg_nombre (cargo oficial de la convocatoria) → noa_cargo (texto libre).
     */
    private static function resolverCargoCandidatoNormalizado($candidato): string
    {
        // Primero el cargo oficial registrado en la tabla cargo_convocatoria
        $raw = trim((string)($candidato->carg_nombre ?? ''));
        if ($raw === '') {
            // Fallback: cargo libre ingresado manualmente
            $raw = trim((string)($candidato->noa_cargo ?? ''));
        }
        return self::normalizarTextoCargoAjusteNoAtentado($raw);
    }

    /**
     * Ajusta el nombre de la convocatoria (placeholder {nombre_convocatoria})
     * eliminando los cargos de la lista que NO pertenecen al candidato.
     *
     * $cargosConvocatoria = array de ['raw'=>..., 'norm'=>...] (de obtenerCargosConvocatoriaNoAtentado).
     * Fallback: lógica TITULAR/SUPLENTE si el cargo del candidato no está en la lista.
     */
    private static function ajustarConvocatoriaPorCargoCandidato(
        string $nombreConvocatoria,
        $candidato,
        array $cargosConvocatoria
    ): string {
        $texto = trim($nombreConvocatoria);
        if ($texto === '') {
            return $nombreConvocatoria;
        }

        $cargoCandidatoNorm = self::resolverCargoCandidatoNormalizado($candidato);

        if (count($cargosConvocatoria) > 0 && $cargoCandidatoNorm !== '') {
            // Buscar si el cargo del candidato está en la lista
            $encontrado = false;
            foreach ($cargosConvocatoria as $item) {
                if ($item['norm'] === $cargoCandidatoNorm) {
                    $encontrado = true;
                    break;
                }
            }

            if ($encontrado) {
                // Cargo raw del candidato para fallback de palabras únicas
                $cargoCandidatoRawStr = trim((string)($candidato->carg_nombre ?? ''));
                if ($cargoCandidatoRawStr === '') {
                    $cargoCandidatoRawStr = trim((string)($candidato->noa_cargo ?? ''));
                }
                // Eliminar del texto todos los cargos de la lista que NO son del candidato
                foreach ($cargosConvocatoria as $item) {
                    if ($item['norm'] === $cargoCandidatoNorm) {
                        continue; // conservar el del candidato
                    }
                    $texto = self::eliminarCargoDelTextoNoAtentado($texto, $item['raw'], $cargoCandidatoRawStr);
                }
                return $texto;
            }
        }

        // Fallback: lógica TITULAR/SUPLENTE
        $rol = self::resolverRolCandidatoNoAtentado($candidato);
        if ($rol === 'TITULAR' || $rol === 'SUPLENTE') {
            return self::reemplazarCombinacionesRolNoAtentado($texto, $rol, true);
        }

        return $texto;
    }

    /**
     * Limpia separadores colgantes tras eliminar texto.
     */
    private static function limpiarSeparadoresNoAtentado(string $texto): string
    {
        $texto = preg_replace('/,\s*,/u',      ',', $texto) ?? $texto;
        $texto = preg_replace('/\/\s*\//u',    '/', $texto) ?? $texto;
        $texto = preg_replace('/[ \t]+,\s+/u',  ', ', $texto) ?? $texto;
        $texto = preg_replace('/,\s*de la Carrera/iu', ', de la Carrera', $texto) ?? $texto;
        $texto = preg_replace('/^[\s,\/\-]+/u', '', $texto) ?? $texto;
        $texto = preg_replace('/[\s,\/\-]+$/u', '', $texto) ?? $texto;
        $texto = preg_replace('/[ \t]{2,}/u',   ' ', $texto) ?? $texto;
        return trim($texto);
    }

    /**
     * Elimina $cargoRaw del $texto en dos intentos:
     *   1) Frase exacta: \bCARGO\b  (case-insensitive, unicode)
     *   2) Si falla, elimina solo las PALABRAS Únicas del cargo
     *      (las que no comparte con $cargoCandidatoRaw).
     *
     * Ejemplo:
     *   cargoRaw = "CONSEJERO SUPLENTE", cargoCandidatoRaw = "CONSEJERO TITULAR"
     *   Palabras únicas de cargoRaw: ["SUPLENTE"]
     *   Si el texto solo tiene "suplente" (abreviado), elimina solo "suplente".
     */
    private static function eliminarCargoDelTextoNoAtentado(
        string $texto,
        string $cargoRaw,
        string $cargoCandidatoRaw = ''
    ): string {
        if ($cargoRaw === '' || $texto === '') {
            return $texto;
        }

        // --- Intento 1: frase exacta ---
        // Se usa límite 1 para evitar borrar menciones accidentales en otras partes del texto (ej. nombre de carrera)
        $cargoEscapado = preg_quote($cargoRaw, '/');
        $resultado = preg_replace('/\b' . $cargoEscapado . '\b/iu', '', $texto, 1);

        if ($resultado !== null && $resultado !== $texto) {
            return self::limpiarSeparadoresNoAtentado($resultado);
        }

        // --- Intento 2: palabras únicas del cargo ---
        // Solo se aplica si el cargo es corto (rol) o contiene palabras clave de rol.
        // Para nombres de asignaturas largos, el "word-by-word" es demasiado agresivo y borra partes de la carrera.
        $palabrasCargoRaw  = preg_split('/\s+/u', trim($cargoRaw)) ?: [];
        $palabrasCargoNorm = preg_split('/\s+/u', self::normalizarTextoCargoAjusteNoAtentado($cargoRaw)) ?: [];
        $numPalabras = count($palabrasCargoNorm);

        $esRol = preg_match('/\b(titular|suplente|consejero|delegado|estudiantil|docente)\b/iu', $cargoRaw);
        
        // Si no es un rol conocido y es un nombre largo, abortamos el Intento 2 para proteger la integridad del texto.
        if (!$esRol && $numPalabras > 2) {
            return $texto;
        }

        // Normalizar cargo candidato para comparar palabras
        $cargoCandidatoNorm = self::normalizarTextoCargoAjusteNoAtentado($cargoCandidatoRaw);
        $palabrasCandidato  = $cargoCandidatoNorm !== ''
            ? (preg_split('/\s+/u', $cargoCandidatoNorm) ?: [])
            : [];

        $resultado = $texto;
        foreach ($palabrasCargoNorm as $idx => $wordNorm) {
            if ($wordNorm === '') {
                continue;
            }
            // Saltar si esta palabra también está en el cargo del candidato
            if (in_array($wordNorm, $palabrasCandidato, true)) {
                continue;
            }
            // Saltar palabras muy cortas o conectores comunes para evitar borrar "y", "de", "la", "I", "II" 
            // que podrían ser parte de la estructura de la frase o nombre de carrera.
            if (mb_strlen($wordNorm) <= 3 && $numPalabras > 1) {
                continue;
            }

            // Eliminar la palabra del texto usando la versión raw (respeta tildes)
            $wordRaw     = $palabrasCargoRaw[$idx] ?? $wordNorm;
            $wordEsc     = preg_quote($wordRaw, '/');
            // Aquí también limitamos a 1 para mayor seguridad
            $resultado   = preg_replace('/\b' . $wordEsc . '\b/iu', '', $resultado, 1) ?? $resultado;
        }

        return self::limpiarSeparadoresNoAtentado($resultado);
    }

    /**
     * Llamado desde fe_glosa() en el controller para ajustar la glosa completa
     * (candidato único). $cargosConvocatoria = array de strings RAW del DB.
     * Fallback a TITULAR/SUPLENTE si el cargo no está en la lista.
     */
    public static function ajustarGlosaNoAtentadoPorRol(string $glosa, $candidato, array $cargosConvocatoria = []): string
    {
        $texto = trim($glosa);
        if ($texto === '') {
            return $glosa;
        }

        if (count($cargosConvocatoria) > 0) {
            $cargoCandidatoNorm = self::resolverCargoCandidatoNormalizado($candidato);

            if ($cargoCandidatoNorm !== '') {
                // Construir pares raw/norm a partir del array de strings raw que llega del controller
                $pares = [];
                foreach ($cargosConvocatoria as $raw) {
                    $norm = self::normalizarTextoCargoAjusteNoAtentado((string)$raw);
                    if ($norm !== '') {
                        $pares[] = ['raw' => (string)$raw, 'norm' => $norm];
                    }
                }

                // Verificar si el cargo del candidato está en la lista
                $encontrado = false;
                foreach ($pares as $item) {
                    if ($item['norm'] === $cargoCandidatoNorm) {
                        $encontrado = true;
                        break;
                    }
                }

                if ($encontrado) {
                    // Obtener el raw del cargo del candidato para el fallback de palabras únicas
                    $cargoCandidatoRawStr = trim((string)($candidato->carg_nombre ?? ''));
                    if ($cargoCandidatoRawStr === '') {
                        $cargoCandidatoRawStr = trim((string)($candidato->noa_cargo ?? ''));
                    }
                    foreach ($pares as $item) {
                        if ($item['norm'] === $cargoCandidatoNorm) {
                            continue;
                        }
                        $texto = self::eliminarCargoDelTextoNoAtentado($texto, $item['raw'], $cargoCandidatoRawStr);
                    }
                    return $texto;
                }
            }
        }

        // Fallback: lógica TITULAR/SUPLENTE
        $rol = self::resolverRolCandidatoNoAtentado($candidato);
        if ($rol !== 'TITULAR' && $rol !== 'SUPLENTE') {
            return $glosa;
        }

        return self::reemplazarCombinacionesRolNoAtentado($glosa, $rol, true);
    }

    private static function reemplazarCombinacionesRolNoAtentado(string $texto, string $rol, bool $minusculas=true): string
    {
        $reemplazo=$rol;
        if($minusculas){
            $reemplazo=mb_strtolower($reemplazo,'UTF-8');
        }

        // Patrones frecuentes en convocatorias/glosas con rol mixto titular-suplente.
        $patrones=[
            '/\btitular\s*\/\s*suplente\b/iu',
            '/\btitular\s+o\s+suplente\b/iu',
            '/\btitular\s+y\/?o\s+suplente\b/iu',
            '/\btitular\s*,\s*suplente\b/iu',
            '/\btitular\s+suplente\b/iu',
            '/\btitular\(a\)\s*\/\s*suplente\(a\)\b/iu',
            '/\btitular\(a\)\s+o\s+suplente\(a\)\b/iu',
            '/\btitular\(a\)\s+suplente\(a\)\b/iu',
        ];

        foreach($patrones as $patron){
            $texto=preg_replace($patron,$reemplazo,$texto) ?? $texto;
        }

        return $texto;
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
        $parts = explode(',', $documento);
        $nombres = [];
        foreach($parts as $part) {
            switch (trim($part)){
                case 'db': $nombres[] = 'Diploma de Bachiller'; break;
                case 'da': $nombres[] = 'Diploma Académico'; break;
                case 'ca': $nombres[] = 'Certificado Académico'; break;
                case 'tp': $nombres[] = 'Título Profesional'; break;
                case 'tpos': $nombres[] = 'Título de posgrado'; break;
                case 'di': $nombres[] = 'Diploma Académico'; break;
                case 'su': $nombres[] = 'Certificado Supletorio'; break;
                case 're': $nombres[] = 'Reválida'; break;
                case 'res': $nombres[] = 'Resolución'; break;
            }
        }
        return implode(' / ', $nombres);
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
        switch (strtolower(trim((string)$tipo))) {
            case "db":
                return 'DB';
            case "da":
            case "ac":
                return 'AC';
            case "ca":
                return 'CA';
            case "tp":
            case "pn":
                return 'PN';
            case "di":
                return 'DI';
            case "tpos":
                return 'TPOS';
            case "re":
            case "res":
            case "rr":
            case "rcu":
            case "rvr":
            case "rs":
            case "rcf":
            case "rcc":
            case "rc":
                return 'RE';
            case "su":
                return 'SU';
            default:
                return null;
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
