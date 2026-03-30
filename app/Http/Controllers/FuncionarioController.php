<?php

namespace App\Http\Controllers;

use App\Exports\ExportFuncionarioConsulta;
use App\Helpers\UniversidadHelper;
use App\Models\Carrera;
use App\Models\Documento;
use App\Models\Funcionario;
use App\Models\Nacionalidad;
use App\Models\Titularidad;
use App\Models\Trabaja;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class FuncionarioController extends Controller
{
    public function l_funcionario($funcionario, Request $request){
        $tipoFun = $funcionario=='docente' ? 'D' : 'A';
        $search = trim($request->input('q',''));

        $funcionarios = DB::table('doc_adm.funcionarios')
            ->select('cod_fun','fun_nombre','fun_ci','fun_sexo','fun_telefonos','fun_email','fun_fecha_ingreso','fun_nacionalidad','cod_nac','fun_obs','fun_folder','fun_habilitado','fun_env_dpa')
            ->where(function($query) use ($tipoFun) {
                $query->where('fun_doc_adm','=',$tipoFun)
                    ->orWhere('fun_doc_adm','=','E');
            })
            ->when($search, function($query, $search){
                $query->where(function($query) use ($search){
                    $query->where('fun_nombre','ilike','%'.$search.'%')
                          ->orWhere('fun_ci','ilike','%'.$search.'%')
                          ->orWhere('fun_email','ilike','%'.$search.'%');
                });
            })
            ->orderBy('fun_nombre')
            ->paginate(200)
            ->appends(['q'=>$search]);

        return view('funcionario.l_funcionario',compact('funcionarios','tipoFun','funcionario','search'));
    }
    public function fe_funcionario($cod_fun){
        $funcionario='';
        $carrera=array();
        $pais="";
        if($cod_fun!=0){
            $funcionario=Funcionario::find($cod_fun);
            $pais=Nacionalidad::find($funcionario->cod_nac);
            $carrera=DB::table('carreras')
                ->join('doc_adm.trabajas','carreras.cod_car','=','doc_adm.trabajas.cod_car')
                ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->select('carreras.cod_car','car_nombre','fac_abreviacion','cod_trb')
                ->where('doc_adm.trabajas.cod_fun','=',$funcionario->cod_fun)->get();
        }

        $nacionalidad=Nacionalidad::all();
        $carreras=DB::table('carreras')
            ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('carreras.cod_car','car_nombre','fac_abreviacion')
            ->orderBy('fac_abreviacion')->get();

        return view('funcionario.fe_funcionario',compact('funcionario','cod_fun','nacionalidad','carreras','carrera','pais'));
    }
    public function g_funcionario(Request $form){

        // Sincronizar secuencia de PostgreSQL si es necesario
        if(!isset($form['cf'])){
            DB::statement("SELECT setval('doc_adm.funcionarios_cod_fun_seq', COALESCE((SELECT MAX(cod_fun) FROM doc_adm.funcionarios) + 1, 1))");
        }

        // Normalizar CI y validar clave única por CI + tipo de funcionario
        $ci = strtoupper(trim($form['ci']));
        $tipo = $form['tipo'];
        $idActual = $form->input('cf', 0);

        $duplicado = Funcionario::where(DB::raw('UPPER(fun_ci)'), $ci)
            ->where('fun_doc_adm', $tipo)
            ->when($idActual, function($query, $idActual){
                return $query->where('cod_fun', '<>', $idActual);
            })
            ->exists();

        if($duplicado){
            \Session::flash('error', 'Ya existe un funcionario con ese CI y tipo.');
            return redirect()->back()->withInput();
        }

        if(isset($form['cf'])){
            $funcionario=Funcionario::find($form['cf']);
            $funcionario->fun_nombre=$form['nombre'];
            $funcionario->fun_ci=$ci;
            $funcionario->fun_sexo=$form['sexo'];
            $funcionario->fun_telefonos=$form['telefonos'];
            $funcionario->fun_email=$form['email'];
            $funcionario->fun_doc_adm=$form['tipo'];
            $funcionario->fun_fecha_ingreso=$form['fecha'];
            $funcionario->fun_nacionalidad=$form['nacionalidad'];
            $funcionario->cod_nac=$form['pais'];
            $funcionario->fun_obs_personal=$form['observacion'];
            $funcionario->fun_facultad=$form['facultad'];
            $funcionario->fun_carrera=$form['carrera1'];
            if(isset($form['folder']) && $form['folder']=='on'){
                $funcionario->fun_folder='t';
                $funcionario->fun_fecha_folder=date('d/m/Y');
            }
            $funcionario->save();

            if($form['carrera']!=''){
                Trabaja::create([
                    'cod_car'=>$form['carrera'],
                    'cod_fun'=>$funcionario->cod_fun
                ]);
            }

            \Session::flash('exito','Se ha editado correctamente al funcionario');
        }else{
            $db="";$da="";$tp="";$ddu="";
            if($form['tipo']=='D') {
                $db="f";$da="f";$tp="f";$ddu="f";
            }
            $funcionario=Funcionario::create([
                'fun_nombre'=>$form['nombre'],
                'fun_ci'=>$ci,
                'fun_sexo'=>$form['sexo'],
                'fun_telefonos'=>$form['telefonos'],
                'fun_email'=>$form['email'],
                'fun_doc_adm'=>$form['tipo'],
                'fun_nacionalidad'=>$form['nacionalidad'],
                'cod_nac'=>$form['pais'],
                'fun_fecha_ingreso'=>$form['fecha'],
                'fun_db'=>$db,
                'fun_da'=>$da,
                'fun_tp'=>$tp,
                'fun_ddu'=>$ddu,
                'fun_facultad'=>$form['facultad'],
                'fun_carrera'=>$form['carrera1'],
                'fun_habilitado'=>true,
            ]);
            if($form['folder']=='on'){
                $funcionario->fun_folder='t';
                $funcionario->fun_fecha_folder=date('d/m/Y');
                $funcionario->save();
            }
            if($form['carrera']!=''){
                Trabaja::create([
                    'cod_car'=>$form['carrera'],
                    'cod_fun'=>$funcionario->cod_fun
                ]);
            }
            \Session::flash('exito','Se ha creado correctamente al funcionario');
        }
        $redireccion=$form['tipo']=='D'?'docente':'administrativo';
        return redirect('listar funcionario/'.$redireccion);
    }
    public function fe_presentar_folder($cod_fun){
        $funcionario=Funcionario::find($cod_fun);
        return view('funcionario.fe_folder',compact('funcionario'));
    }
    public function g_folder(Request $form){
        $form->validate([
            'cf'=>'required|numeric'
        ]);
        $funcionario=Funcionario::find($form['cf']);
        $funcionario->fun_folder='t';
        $funcionario->fun_fecha_folder=date('d/m/Y');
        $funcionario->save();
        $redireccion=$funcionario->fun_doc_adm=='D'?'docente':'administrativo';
        return redirect('listar funcionario/'.$redireccion);
    }
    public function fe_eli_funcionario($cod_fun){
        $eliminar=1;
        $funcionario="";
        if($cod_fun!=''){
            $funcionario=Funcionario::find($cod_fun);
            $documentos=Documento::all()->where('cod_fun','=',$cod_fun);
            $titularidad=Titularidad::all()->where('cod_fun','=',$cod_fun);
            if(sizeof($documentos)>0 || sizeof($titularidad)>0){
                $eliminar=0;
            }
        }else{
            \Session::flash('error','Ocurrio un error');
        }
        return view('funcionario.f_eli_funcionario',compact('funcionario','eliminar'));
    }
    public function eli_funcionario(Request $form){
        $form->validate([
            'cf'=>'required|numeric'
        ]);
        $funcionario=Funcionario::find($form['cf']);
        $documentos=Documento::all()->where('cod_fun','=',$form['cf']);
        $titularidad=Titularidad::all()->where('cod_fun','=',$form['cf']);
        $redireccion=$funcionario->fun_doc_adm=='D'?'docente':'administrativo';
        if(sizeof($documentos)>0 || sizeof($titularidad)>0){
            \Session::flash('error','Ocurrio un error');
        }else{
            \Session::flash('exito','Se ha eliminado correctamente al funcionario');
            $eliminar=DB::delete('delete from doc_adm.trabajas where cod_fun='.$funcionario->cod_fun);
            $funcionario->delete();
        }
        return redirect('listar funcionario/'.$redireccion);
    }
    public function e_carrera_funcionario($cod_trb){
        $trabaja=Trabaja::find($cod_trb);
        $trabaja->delete();
        return redirect('fe_funcionario/'.$trabaja->cod_fun);
    }
    //============================REPORTES=================
    public function fe_reporte(){
        return view('funcionario.reporte.fe_reporte');
    }
    public function procesar_reporte(Request $form){
        $parametros=array();

        $consulta="";
        $tipo_funcionario=$form['funcionario'];
        $funcionario=($form['funcionario']=='')?"-":" (fun_doc_adm='".$form['funcionario']."' or fun_doc_adm='E')";

        $i=1;
        if($funcionario=='-'){
            $consulta="select cod_fun,fun_nombre,fun_ci,fun_facultad,fun_carrera,fun_doc_adm from doc_adm.funcionarios";
        }else{
            $consulta="select cod_fun,fun_nombre,fun_ci,fun_facultad,fun_carrera,fun_doc_adm from doc_adm.funcionarios where ".$funcionario;
            $i=0;
        }

        $parametros=array();

        if($form['bachiller']=='on'){
            $parametros[0]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA DE BACHILLER'";
            $parametros[0].=$form['lbachiller']=='on'?" and doc_legalizado='t'":"";
            $parametros[0].=$form['nlbachiller']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[0].=$form['vbachiller']=='on'?" and doc_verificado='t'":"";
            $parametros[0].=$form['nvbachiller']=='on'?" and doc_verificado<>'t'":"";
            $parametros[0].=$form['ubachiller']=='on'?" and doc_umss='t'":"";
            $parametros[0].=$form['nubachiller']=='on'?" and doc_umss<>'t'":"";
            $parametros[0].=')';
        }else{
            $parametros[0]='-';
        }
        $parametros[1]=($form['nobachiller']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA DE BACHILLER')":'-';

        if($form['tmedio']=='on'){
            $parametros[2]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='TECNICO MEDIO'";
            $parametros[2].=$form['ltmedio']=='on'?" and doc_legalizado='t'":"";
            $parametros[2].=$form['nltmedio']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[2].=$form['vtmedio']=='on'?" and doc_verificado='t'":"";
            $parametros[2].=$form['nvtmedio']=='on'?" and doc_verificado<>'t'":"";
            $parametros[2].=$form['utmedio']=='on'?" and doc_umss='t'":"";
            $parametros[2].=$form['nutmedio']=='on'?" and doc_umss<>'t'":"";
            $parametros[2].=')';

        }else{
            $parametros[2]='-';
        }
        $parametros[3]=($form['notmedio']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='TECNICO MEDIO')":'-';

        if($form['tsuperior']=='on'){
            $parametros[4]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='TECNICO SUPERIOR'";
            $parametros[4].=$form['ltsuperior']=='on'?" and doc_legalizado='t'":"";
            $parametros[4].=$form['nltsuperior']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[4].=$form['vtsuperior']=='on'?" and doc_verificado='t'":"";
            $parametros[4].=$form['nvtsuperior']=='on'?" and doc_verificado<>'t'":"";
            $parametros[4].=$form['utsuperior']=='on'?" and doc_umss='t'":"";
            $parametros[4].=$form['nutsuperior']=='on'?" and doc_umss<>'t'":"";
            $parametros[4].=')';
        }else{
            $parametros[4]='-';
        }
        $parametros[5]=($form['notsuperior']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='TECNICO SUPERIOR')":'-';


        if($form['academico']=='on'){
            $parametros[6]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA ACADEMICO'";
            $parametros[6].=$form['lacademico']=='on'?" and doc_legalizado='t'":"";
            $parametros[6].=$form['nlacademico']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[6].=$form['vacademico']=='on'?" and doc_verificado='t'":"";
            $parametros[6].=$form['nvacademico']=='on'?" and doc_verificado<>'t'":"";
            $parametros[6].=$form['uacademico']=='on'?" and doc_umss='t'":"";
            $parametros[6].=$form['nuacademico']=='on'?" and doc_umss<>'t'":"";
            $parametros[6].=')';
        }else{
            $parametros[6]='-';
        }
        $parametros[7]=($form['noacademico']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA ACADEMICO')":'-';

        if($form['profesional']=='on'){
            $parametros[8]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='TITULO PROFESIONAL'";
            $parametros[8].=$form['lprofesional']=='on'?" and doc_legalizado='t'":"";
            $parametros[8].=$form['nlprofesional']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[8].=$form['vprofesional']=='on'?" and doc_verificado='t'":"";
            $parametros[8].=$form['nvprofesional']=='on'?" and doc_verificado<>'t'":"";
            $parametros[8].=$form['uprofesional']=='on'?" and doc_umss='t'":"";
            $parametros[8].=$form['nuprofesional']=='on'?" and doc_umss<>'t'":"";
            $parametros[8].=')';
        }else{
            $parametros[8]='-';
        }
        $parametros[9]=($form['noprofesional']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='TITULO PROFESIONAL')":'-';

        if($form['diplomado']=='on'){
            $parametros[10]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='DIPLOMADO'";
            $parametros[10].=$form['ldiplomado']=='on'?" and doc_legalizado='t'":"";
            $parametros[10].=$form['nldiplomado']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[10].=$form['vdiplomado']=='on'?" and doc_verificado='t'":"";
            $parametros[10].=$form['nvdiplomado']=='on'?" and doc_verificado<>'t'":"";
            $parametros[10].=$form['udiplomado']=='on'?" and doc_umss='t'":"";
            $parametros[10].=$form['nudiplomado']=='on'?" and doc_umss<>'t'":"";
            $parametros[10].=')';
        }else{
            $parametros[10]='-';
        }
        $parametros[11]=($form['nodiplomado']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos wheredoc_tipo='DIPLOMADO')":'-';

        if($form['especialidad']=='on'){
            $parametros[12]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='ESPECIALIDAD'";
            $parametros[12].=$form['lespecialidad']=='on'?" and doc_legalizado='t'":"";
            $parametros[12].=$form['nlespecialidad']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[12].=$form['vespecialidad']=='on'?" and doc_verificado='t'":"";
            $parametros[12].=$form['nvespecialidad']=='on'?" and doc_verificado<>'t'":"";
            $parametros[12].=$form['uespecialidad']=='on'?" and doc_umss='t'":"";
            $parametros[12].=$form['nuespecialidad']=='on'?" and doc_umss<>'t'":"";
            $parametros[12].=')';
        }else{
            $parametros[12]='-';
        }
        $parametros[13]=($form['noespecialidad']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='ESPECIALIDAD')":'-';
        if($form['maestria']=='on'){
            $parametros[14]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='MAESTRIA'";
            $parametros[14].=$form['lmaestria']=='on'?" and doc_legalizado='t'":"";
            $parametros[14].=$form['nlmaestria']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[14].=$form['vmaestria']=='on'?" and doc_verificado='t'":"";
            $parametros[14].=$form['nvmaestria']=='on'?" and doc_verificado<>'t'":"";
            $parametros[14].=$form['umaestria']=='on'?" and doc_umss='t'":"";
            $parametros[14].=$form['numaestria']=='on'?" and doc_umss<>'t'":"";
            $parametros[14].=')';
        }else{
            $parametros[14]='-';
        }

        $parametros[15]=($form['nomaestria']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='MAESTRIA')":'-';

        if($form['doctorado']=='on'){
            $parametros[16]="cod_fun in (select cod_fun from doc_adm.documentos where doc_tipo='DOCTORADO'";
            $parametros[16].=$form['ldoctorado']=='on'?" and doc_legalizado='t'":"";
            $parametros[16].=$form['nldoctorado']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[16].=$form['vdoctorado']=='on'?" and doc_verificado='t'":"";
            $parametros[16].=$form['nvdoctorado']=='on'?" and doc_verificado<>'t'":"";
            $parametros[16].=$form['udoctorado']=='on'?" and doc_umss='t'":"";
            $parametros[16].=$form['nudoctorado']=='on'?" and doc_umss<>'t'":"";
            $parametros[16].=')';
        }else{
            $parametros[16]='-';
        }
        $parametros[17]=($form['nodoctorado']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_tipo='DOCTORADO')":'-';

        $parametros[18]=($form['ddu']=='on')? "cod_fun in (select cod_fun from doc_adm.documentos where doc_edu_superior='t')":'-';
        if($form['ddu']=='on'){
            $parametros[18]="cod_fun in (select cod_fun from doc_adm.documentos where doc_edu_superior='t'";
            $parametros[18].=$form['lddu']=='on'?" and doc_legalizado='t'":"";
            $parametros[18].=$form['nlddu']=='on'?" and doc_legalizado<>'t'":"";
            $parametros[18].=$form['vddu']=='on'?" and doc_verificado='t'":"";
            $parametros[18].=$form['nvddu']=='on'?" and doc_verificado<>'t'":"";
            $parametros[18].=$form['uddu']=='on'?" and doc_umss='t'":"";
            $parametros[18].=$form['nuddu']=='on'?" and doc_umss<>'t'":"";
            $parametros[18].=')';
        }else{
            $parametros[18]='-';
        }
        $parametros[19]=($form['noddu']=='on')? "cod_fun not in (select cod_fun from doc_adm.documentos where doc_edu_superior='t')":'-';
        $parametros[20]=($form['folder']=='on')? "fun_folder='t'":'-';
        $parametros[21]=($form['nofolder']=='on')? "fun_folder is null":'-';

        foreach ($parametros as $p):
            if($p!='-'){
                if($funcionario=='-' && $i==1){
                    $consulta.=" where ";
                }
                if($i==0){
                    $consulta.=" and ".$p;
                }else{
                    $consulta.=$p;
                    $i=0;
                }
            }
        endforeach;
            $consulta.=" order by fun_nombre";
            $resultado=DB::select($consulta);

            // Procesar documentos y agregar información de validación
            foreach($resultado as $funcionario) {
                // Obtener documentos usando Eloquent
                $documentos = Documento::where('cod_fun', $funcionario->cod_fun)->get();
                
                // Procesar cada documento con información de universidad
                $documentos_procesados = [];
                $tipos_encontrados = [];
                
                foreach($documentos as $doc) {
                    $tipo_uni = UniversidadHelper::getTipoUniversidad($doc->doc_universidad);
                    
                    // Determinar estado de revalidación
                    $revalida = '';
                    if($tipo_uni === 'Extranjera') {
                        if(!empty(trim($doc->doc_numero_revalida))) {
                            $revalida = $doc->doc_numero_revalida;
                        } else {
                            $revalida = 'FALTA REVALIDACION';
                        }
                    }
                    
                    // Clasificación para validación de completud
                    $clasificacion_tipo = $this->clasificarTipoDocumento($doc->doc_tipo);
                    $tipos_encontrados[$clasificacion_tipo] = true;
                    
                    $documentos_procesados[] = [
                        'titulo' => $doc->doc_titulo,
                        'tipo' => $doc->doc_tipo,
                        'universidad' => $doc->doc_universidad,
                        'tipo_universidad' => $tipo_uni,
                        'edu_superior' => $doc->doc_edu_superior === 't' ? 'Sí' : '',
                        'revalida' => $revalida,
                        'verificado' => $doc->doc_verificado === 't' ? 'Verificado' : 'Pendiente',
                        'fecha_emision' => $doc->doc_fecha_emision ?? ''
                    ];
                }

                // Validar completud de la carpeta
                $tipos_requeridos = ['DB', 'DA', 'TP', 'POSTGRADO'];
                $documentos_faltantes = [];
                
                foreach($tipos_requeridos as $tipo) {
                    if(!isset($tipos_encontrados[$tipo])) {
                        $documentos_faltantes[] = $this->obtenerNombreTipoDocumento($tipo);
                    }
                }
                
                // Determinar estado
                $estado_carpeta = [
                    'completo' => count($documentos_faltantes) === 0,
                    'faltantes' => $documentos_faltantes,
                    'mensaje' => count($documentos_faltantes) === 0 ? 'COMPLETO' : 'INCOMPLETO'
                ];

                // Agregar documentos procesados y estado al objeto
                $funcionario->documentos = $documentos_procesados;
                $funcionario->estado_carpeta = $estado_carpeta;
            }

            /*Excel::create('Filename', function($excel) use($resultado) {
                $excel->sheet('Sheetname', function($sheet) use($resultado) {
                $sheet->fromArray($resultado);
            });})->export('xls');
            */
            if($form['excel']=='on'){
                return (new ExportFuncionarioConsulta($resultado))->download('Resultado.xlsx');
            }else{
                return view('funcionario.reporte.resultado_titulos',compact('resultado','tipo_funcionario'));
            }

            /*
            //dd($resultado);

            */
    }

    /**
     * Clasificar tipo de documento para validación de completud
     */
    private function clasificarTipoDocumento($tipo_doc)
    {
        $tipo = strtoupper(trim($tipo_doc));
        
        // DB = Diploma de bachiller
        if(strpos($tipo, 'DIPLOMA') !== false && strpos($tipo, 'BACHILLER') !== false) {
            return 'DB';
        }
        
        // DA = Diploma académico
        if(strpos($tipo, 'DIPLOMA') !== false && strpos($tipo, 'ACADEMICO') !== false) {
            return 'DA';
        }
        
        // TP = Título profesional
        if(strpos($tipo, 'TITULO') !== false && strpos($tipo, 'PROFESIONAL') !== false) {
            return 'TP';
        }
        
        // POSTGRADO = Diplomado, Maestría, Especialidad, Doctorado
        if(strpos($tipo, 'DIPLOMADO') !== false || 
           strpos($tipo, 'MAESTRIA') !== false || 
           strpos($tipo, 'ESPECIALIDAD') !== false || 
           strpos($tipo, 'DOCTORADO') !== false) {
            return 'POSTGRADO';
        }
        
        // Otras clasificaciones
        if(strpos($tipo, 'TECNICO') !== false) {
            return 'TECNICO';
        }
        
        return 'OTRO';
    }

    /**
     * Obtener nombre del tipo de documento para el mensaje de faltantes
     */
    private function obtenerNombreTipoDocumento($codigo)
    {
        $nombres = [
            'DB' => 'Diploma de Bachiller',
            'DA' => 'Diploma Académico',
            'TP' => 'Título Profesional',
            'POSTGRADO' => 'Postgrado (Diplomado/Maestría/Especialidad/Doctorado)',
            'TECNICO' => 'Título Técnico',
            'OTRO' => 'Otro'
        ];
        
        return $nombres[$codigo] ?? $codigo;
    }

    public function verificarDuplicado(Request $request){
        $ci = strtoupper(trim($request->input('ci', '')));
        $tipo = $request->input('tipo', '');
        $idActual = $request->input('id_actual', 0);

        if(empty($ci) || empty($tipo)){
            return response()->json(['existe' => false]);
        }

        $existe = Funcionario::where(DB::raw('UPPER(fun_ci)'), $ci)
            ->where('fun_doc_adm', $tipo)
            ->when($idActual, function($query, $idActual){
                return $query->where('cod_fun', '<>', $idActual);
            })
            ->exists();

        if($existe){
            return response()->json(['existe' => true]);
        }

        // Verificar si existe con CI pero diferente tipo
        $funcionarioExistente = Funcionario::where(DB::raw('UPPER(fun_ci)'), $ci)
            ->where('fun_doc_adm', '<>', $tipo)
            ->first();

        if($funcionarioExistente){
            return response()->json([
                'existe' => false,
                'autocompletar' => true,
                'datos' => [
                    'nombre' => $funcionarioExistente->fun_nombre,
                    'sexo' => $funcionarioExistente->fun_sexo,
                    'telefonos' => $funcionarioExistente->fun_telefonos,
                    'email' => $funcionarioExistente->fun_email,
                    'fecha_ingreso' => $funcionarioExistente->fun_fecha_ingreso,
                    'nacionalidad' => $funcionarioExistente->fun_nacionalidad,
                    'cod_nac' => $funcionarioExistente->cod_nac,
                    'facultad' => $funcionarioExistente->fun_facultad,
                    'carrera' => $funcionarioExistente->fun_carrera,
                    'observacion' => $funcionarioExistente->fun_obs_personal
                ]
            ]);
        }

        return response()->json(['existe' => false]);
    }

    /**
     * Listar todas las universidades (públicas, privadas y extranjeras)
     */
    public function listar_universidades(){
        $universidadesPublicas = Universidad::where('tipo', 'Pública')->get();
        $universidadesPrivadas = Universidad::where('tipo', 'Privada')->get();
        $universidadesExtranjeras = Universidad::where('tipo', 'Extranjera')->get();

        return view('funcionario.l_universidades', compact('universidadesPublicas', 'universidadesPrivadas', 'universidadesExtranjeras'));
    }

    /**
     * Crear nueva universidad
     */
    public function crear_universidad(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:universidades',
            'sigla' => 'required|unique:universidades',
            'tipo' => 'required|in:Pública,Privada,Extranjera'
        ]);

        Universidad::create([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla,
            'tipo' => $request->tipo
        ]);

        return redirect('listar universidades')->with('exito', 'Universidad creada exitosamente');
    }

    /**
     * Actualizar universidad
     */
    public function actualizar_universidad(Request $request, $id)
    {
        $universidad = Universidad::find($id);
        
        if (!$universidad) {
            return redirect('listar universidades')->with('error', 'Universidad no encontrada');
        }

        $request->validate([
            'nombre' => 'required|unique:universidades,nombre,'.$id,
            'sigla' => 'required|unique:universidades,sigla,'.$id,
            'tipo' => 'required|in:Pública,Privada,Extranjera'
        ]);

        $universidad->update([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla,
            'tipo' => $request->tipo
        ]);

        return redirect('listar universidades')->with('exito', 'Universidad actualizada exitosamente');
    }

    /**
     * Eliminar universidad
     */
    public function eliminar_universidad($id)
    {
        $universidad = Universidad::find($id);
        
        if (!$universidad) {
            return redirect('listar universidades')->with('error', 'Universidad no encontrada');
        }

        $universidad->delete();
        return redirect('listar universidades')->with('exito', 'Universidad eliminada exitosamente');
    }

    /**
     * Buscar funcionarios por término de búsqueda
     */
    public function buscar_funcionarios(Request $request)
    {
        $termino = $request->input('q', '');
        
        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $funcionarios = DB::table('doc_adm.funcionarios')
            ->select('cod_fun', 'fun_nombre', 'fun_ci', 'fun_email', 'fun_telefonos')
            ->where(function($query) use ($termino) {
                $query->where('fun_nombre', 'ilike', '%' . $termino . '%')
                      ->orWhere('fun_ci', 'ilike', '%' . $termino . '%')
                      ->orWhere('fun_email', 'ilike', '%' . $termino . '%');
            })
            ->orderBy('fun_nombre')
            ->limit(10)
            ->get();

        return response()->json($funcionarios);
    }

    /**
     * Guardar formulario de conformidad
     */
    public function guardar_conformidad(Request $request)
    {
        $codFun = $request->input('cod_fun');
        $observaciones = $request->input('observaciones', '');

        if (!$codFun) {
            return redirect()->back()->with('error', 'Debe seleccionar un funcionario');
        }

        $funcionario = Funcionario::find($codFun);
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Funcionario no encontrado');
        }

 
        \Session::flash('exito', 'Formulario de conformidad registrado para: ' . $funcionario->fun_nombre);
        
        return redirect()->back();
    }
}