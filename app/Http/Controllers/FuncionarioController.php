<?php

namespace App\Http\Controllers;

use App\Exports\ExportFuncionarioConsulta;
use App\Helpers\UniversidadHelper;
use App\Models\Carrera;
use App\Models\Documento;
use App\Models\FormularioConformidad;
use App\Models\Funcionario;
use App\Models\Nacionalidad;
use App\Models\Titularidad;
use App\Models\Trabaja;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpWord\TemplateProcessor;

class FuncionarioController extends Controller
{
    public function l_funcionario($funcionario, Request $request){
        $tipoFun = $funcionario=='docente' ? 'D' : 'A';
        $search = trim($request->input('q',''));

        $funcionarios = DB::table('doc_adm.funcionarios')
            ->select('cod_fun','fun_nombre','fun_ci','fun_sexo','fun_telefonos','fun_email','fun_fecha_ingreso','fun_nacionalidad','cod_nac','fun_obs','fun_folder','fun_habilitado','fun_env_dpa')
            ->selectRaw("EXISTS (
                SELECT 1
                FROM doc_adm.documentos d
                JOIN doc_adm.d_observacions o ON o.cod_doc = d.cod_doc
                WHERE d.cod_fun = doc_adm.funcionarios.cod_fun
                AND (o.od_solucion IS NULL OR TRIM(o.od_solucion) = '')
            ) as has_pending_obs")
            ->selectRaw("EXISTS (
                SELECT 1
                FROM doc_adm.documentos d3
                WHERE d3.cod_fun = doc_adm.funcionarios.cod_fun
            ) as has_documents")
            ->selectRaw("EXISTS (
                SELECT 1
                FROM doc_adm.documentos d2
                WHERE d2.cod_fun = doc_adm.funcionarios.cod_fun
                AND COALESCE(d2.doc_enviado_dpa, false) = false
            ) as has_pending_dpa_docs")
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
            if($form->has('estado')){
                $funcionario->fun_habilitado=(int)$form['estado']===1;
            }
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
                'fun_habilitado'=>$form->has('estado') ? ((int)$form['estado']===1) : true,
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
        $estadoCarpetaFiltro = $form->input('estado_carpeta', '');

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

        // Bachiller
        $tiene_filtro_bachiller = $form['nobachiller']!='on' && $this->tieneFiltroPositivo($form, 'bachiller');
        if($tiene_filtro_bachiller){
            $parametros[0]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='DIPLOMA DE BACHILLER' OR d.doc_grado='Bachiller')";
            $parametros[0].=$form['lbachiller']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[0].=$form['nlbachiller']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[0].=$form['vbachiller']=='on'?" and d.doc_verificado='t'":"";
            $parametros[0].=$form['nvbachiller']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[0].=$this->construirFiltroTipoUniversidad($form, 'ubachiller');
            $parametros[0].=')';
        }else{
            $parametros[0]='-';
        }
        $parametros[1]=($form['nobachiller']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA DE BACHILLER' OR doc_grado='Bachiller')":"-";

        // Tecnico Medio
        $tiene_filtro_tmedio = $form['notmedio']!='on' && $this->tieneFiltroPositivo($form, 'tmedio');
        if($tiene_filtro_tmedio){
            $parametros[2]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='TECNICO MEDIO' OR d.doc_grado='Tecnico medio')";
            $parametros[2].=$form['ltmedio']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[2].=$form['nltmedio']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[2].=$form['vtmedio']=='on'?" and d.doc_verificado='t'":"";
            $parametros[2].=$form['nvtmedio']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[2].=$this->construirFiltroTipoUniversidad($form, 'utmedio');
            $parametros[2].=')';
        }else{
            $parametros[2]='-';
        }
        $parametros[3]=($form['notmedio']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='TECNICO MEDIO' OR doc_grado='Tecnico medio')":"-";

        // Tecnico Superior
        $tiene_filtro_tsuperior = $form['notsuperior']!='on' && $this->tieneFiltroPositivo($form, 'tsuperior');
        if($tiene_filtro_tsuperior){
            $parametros[4]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='TECNICO SUPERIOR' OR d.doc_grado='Tecnico superior')";
            $parametros[4].=$form['ltsuperior']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[4].=$form['nltsuperior']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[4].=$form['vtsuperior']=='on'?" and d.doc_verificado='t'":"";
            $parametros[4].=$form['nvtsuperior']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[4].=$this->construirFiltroTipoUniversidad($form, 'utsuperior');
            $parametros[4].=')';
        }else{
            $parametros[4]='-';
        }
        $parametros[5]=($form['notsuperior']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='TECNICO SUPERIOR' OR doc_grado='Tecnico superior')":"-";

        // Diploma Academico
        $tiene_filtro_academico = $form['noacademico']!='on' && $this->tieneFiltroPositivo($form, 'academico');
        if($tiene_filtro_academico){
            $parametros[6]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='DIPLOMA ACADEMICO' OR d.doc_grado='Diploma academico')";
            $parametros[6].=$form['lacademico']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[6].=$form['nlacademico']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[6].=$form['vacademico']=='on'?" and d.doc_verificado='t'":"";
            $parametros[6].=$form['nvacademico']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[6].=$this->construirFiltroTipoUniversidad($form, 'uacademico');
            $parametros[6].=')';
        }else{
            $parametros[6]='-';
        }
        $parametros[7]=($form['noacademico']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='DIPLOMA ACADEMICO' OR doc_grado='Diploma academico')":"-";

        // Titulo Profesional
        $tiene_filtro_profesional = $form['noprofesional']!='on' && $this->tieneFiltroPositivo($form, 'profesional');
        if($tiene_filtro_profesional){
            $parametros[8]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='TITULO PROFESIONAL' OR d.doc_grado='Titulo profesional')";
            $parametros[8].=$form['lprofesional']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[8].=$form['nlprofesional']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[8].=$form['vprofesional']=='on'?" and d.doc_verificado='t'":"";
            $parametros[8].=$form['nvprofesional']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[8].=$this->construirFiltroTipoUniversidad($form, 'uprofesional');
            $parametros[8].=')';
        }else{
            $parametros[8]='-';
        }
        $parametros[9]=($form['noprofesional']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='TITULO PROFESIONAL' OR doc_grado='Titulo profesional')":"-";

        // Diplomado
        $tiene_filtro_diplomado = $form['nodiplomado']!='on' && $this->tieneFiltroPositivo($form, 'diplomado', true);
        if($tiene_filtro_diplomado){
            $parametros[10]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='DIPLOMADO' OR d.doc_grado='Diplomado')";
            $parametros[10].=$form['ldiplomado']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[10].=$form['nldiplomado']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[10].=$form['vdiplomado']=='on'?" and d.doc_verificado='t'":"";
            $parametros[10].=$form['nvdiplomado']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[10].=$this->construirFiltroTipoUniversidad($form, 'udiplomado');
            $parametros[10].=$form['tdiplomado']=='on'?" and d.doc_tesis='t'":"";
            $parametros[10].=$form['ntdiplomado']=='on'?" and d.doc_tesis<>'t'":"";
            $parametros[10].=')';
        }else{
            $parametros[10]='-';
        }
        $parametros[11]=($form['nodiplomado']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='DIPLOMADO' OR doc_grado='Diplomado')":"-";

        // Especialidad
        $tiene_filtro_especialidad = $form['noespecialidad']!='on' && $this->tieneFiltroPositivo($form, 'especialidad', true);
        if($tiene_filtro_especialidad){
            $parametros[12]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='ESPECIALIDAD' OR d.doc_grado='Especialidad')";
            $parametros[12].=$form['lespecialidad']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[12].=$form['nlespecialidad']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[12].=$form['vespecialidad']=='on'?" and d.doc_verificado='t'":"";
            $parametros[12].=$form['nvespecialidad']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[12].=$this->construirFiltroTipoUniversidad($form, 'uespecialidad');
            $parametros[12].=$form['tespecialidad']=='on'?" and d.doc_tesis='t'":"";
            $parametros[12].=$form['ntespecialidad']=='on'?" and d.doc_tesis<>'t'":"";
            $parametros[12].=')';
        }else{
            $parametros[12]='-';
        }
        $parametros[13]=($form['noespecialidad']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='ESPECIALIDAD' OR doc_grado='Especialidad')":"-";

        // Maestria
        $tiene_filtro_maestria = $form['nomaestria']!='on' && $this->tieneFiltroPositivo($form, 'maestria', true);
        if($tiene_filtro_maestria){
            $parametros[14]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='MAESTRIA' OR d.doc_grado='Maestria')";
            $parametros[14].=$form['lmaestria']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[14].=$form['nlmaestria']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[14].=$form['vmaestria']=='on'?" and d.doc_verificado='t'":"";
            $parametros[14].=$form['nvmaestria']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[14].=$this->construirFiltroTipoUniversidad($form, 'umaestria');
            $parametros[14].=$form['tmaestria']=='on'?" and d.doc_tesis='t'":"";
            $parametros[14].=$form['ntmaestria']=='on'?" and d.doc_tesis<>'t'":"";
            $parametros[14].=')';
        }else{
            $parametros[14]='-';
        }
        $parametros[15]=($form['nomaestria']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='MAESTRIA' OR doc_grado='Maestria')":"-";

        // Doctorado
        $tiene_filtro_doctorado = $form['nodoctorado']!='on' && $this->tieneFiltroPositivo($form, 'doctorado', true);
        if($tiene_filtro_doctorado){
            $parametros[16]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where (d.doc_tipo='DOCTORADO' OR d.doc_grado='Doctorado')";
            $parametros[16].=$form['ldoctorado']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[16].=$form['nldoctorado']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[16].=$form['vdoctorado']=='on'?" and d.doc_verificado='t'":"";
            $parametros[16].=$form['nvdoctorado']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[16].=$this->construirFiltroTipoUniversidad($form, 'udoctorado');
            $parametros[16].=$form['tdoctorado']=='on'?" and d.doc_tesis='t'":"";
            $parametros[16].=$form['ntdoctorado']=='on'?" and d.doc_tesis<>'t'":"";
            $parametros[16].=')';
        }else{
            $parametros[16]='-';
        }
        $parametros[17]=($form['nodoctorado']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tipo='DOCTORADO' OR doc_grado='Doctorado')":"-";

        // Educacion Superior
        $tiene_filtro_ddu = $form['noddu']!='on' && $this->tieneFiltroPositivo($form, 'ddu', true);
        if($tiene_filtro_ddu){
            $parametros[18]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where d.doc_edu_superior='t'";
            $parametros[18].=$form['lddu']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[18].=$form['nlddu']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[18].=$form['vddu']=='on'?" and d.doc_verificado='t'":"";
            $parametros[18].=$form['nvddu']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[18].=$this->construirFiltroTipoUniversidad($form, 'uddu');
            $parametros[18].=$form['tddu']=='on'?" and d.doc_tesis='t'":"";
            $parametros[18].=$form['ntddu']=='on'?" and d.doc_tesis<>'t'":"";
            $parametros[18].=')';
        }else{
            $parametros[18]='-';
        }
        $parametros[19]=($form['noddu']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_edu_superior='t')":'-';

        // Solo Tesis - busca documentos donde doc_tesis='t' independientemente del tipo
        $tiene_filtro_solotesis = $form['nosolotesis']!='on' && $this->tieneFiltroPositivo($form, 'solotesis');
        if($tiene_filtro_solotesis){
            $parametros[20]="cod_fun in (select distinct d.cod_fun from doc_adm.documentos d LEFT JOIN doc_adm.universidades u ON d.doc_universidad = u.sigla where d.doc_tesis='t'";
            $parametros[20].=$form['lsolotesis']=='on'?" and d.doc_legalizado='t'":"";
            $parametros[20].=$form['nlsolotesis']=='on'?" and d.doc_legalizado<>'t'":"";
            $parametros[20].=$form['vsolotesis']=='on'?" and d.doc_verificado='t'":"";
            $parametros[20].=$form['nvsolotesis']=='on'?" and d.doc_verificado<>'t'":"";
            $parametros[20].=$this->construirFiltroTipoUniversidad($form, 'usolotesis');
            $parametros[20].=')';
        }else{
            $parametros[20]='-';
        }
        $parametros[21]=($form['nosolotesis']=='on')? "cod_fun not in (select distinct cod_fun from doc_adm.documentos where doc_tesis='t')":'-';
        
        $parametros[22]=($form['folder']=='on')? "fun_folder='t'":'-';
        $parametros[23]=($form['nofolder']=='on')? "fun_folder is null":'-';
        $parametros[24]=($form['global_no_verificado']=='on')? "cod_fun in (select distinct cod_fun from doc_adm.documentos where doc_verificado<>'t')":'-';

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
                    
                    // Determinar si es tesis
                    $es_tesis = $doc->doc_tesis === 't' ? 'Sí' : '';
                    $titulo_tesis = ($doc->doc_tesis === 't' && !empty($doc->doc_tesis_titulo)) ? $doc->doc_tesis_titulo : '';
                    
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
                        'legalizado' => $doc->doc_legalizado === 't' ? 'Si' : 'No',
                        'umss' => $doc->doc_umss === 't' ? 'Si' : 'No',
                        'es_tesis' => $es_tesis,
                        'titulo_tesis' => $titulo_tesis,
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

            if($estadoCarpetaFiltro === 'completo'){
                $resultado = array_values(array_filter($resultado, function($funcionario){
                    return isset($funcionario->estado_carpeta['completo']) && $funcionario->estado_carpeta['completo'] === true;
                }));
            }

            if($estadoCarpetaFiltro === 'incompleto'){
                $resultado = array_values(array_filter($resultado, function($funcionario){
                    return !isset($funcionario->estado_carpeta['completo']) || $funcionario->estado_carpeta['completo'] !== true;
                }));
            }

            if($form['excel']=='on'){
                return (new ExportFuncionarioConsulta($resultado))->download('Resultado.xlsx');
            }else{
                return view('funcionario.reporte.resultado_titulos',compact('resultado','tipo_funcionario'));
            }
    }

    /**
     * Determina si un filtro positivo está activo (incluye subfiltros aunque presencia esté en "indiferente")
     */
    private function tieneFiltroPositivo(Request $form, string $prefix, bool $incluyeTesis = false): bool
    {
        $campos = [
            $prefix,
            'l'.$prefix,
            'nl'.$prefix,
            'v'.$prefix,
            'nv'.$prefix,
            'u'.$prefix.'_publica',
            'u'.$prefix.'_privada',
            'u'.$prefix.'_extranjera',
            'u'.$prefix.'_otra',
        ];

        if($incluyeTesis){
            $campos[] = 't'.$prefix;
            $campos[] = 'nt'.$prefix;
        }

        foreach($campos as $campo){
            if($form->input($campo) === 'on'){
                return true;
            }
        }

        return false;
    }

    /**
     * Construir fragmento SQL para filtro de tipo de universidad
     * @param Request $form
     * @param string $prefix Prefijo de los campos (ej: 'ubachiller')
     * @return string SQL WHERE condition o empty string si no hay selección
     */
    private function construirFiltroTipoUniversidad(Request $form, $prefix)
    {
        $conditions = [];

        if($form[$prefix.'_publica']=='on') {
            $conditions[] = "u.tipo='Pública'";
        }
        if($form[$prefix.'_privada']=='on') {
            $conditions[] = "u.tipo='Privada'";
        }
        if($form[$prefix.'_extranjera']=='on') {
            $conditions[] = "u.tipo='Extranjera'";
        }
        if($form[$prefix.'_otra']=='on') {
            $conditions[] = "u.tipo IN ('Otro','Otra')";
        }
        
        if(empty($conditions)) {
            return '';
        }
        
        return ' and (' . implode(' OR ', $conditions) . ')';
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
        $universidadesOtros = Universidad::where('tipo', 'Otro')->get();

        return view('funcionario.l_universidades', compact('universidadesPublicas', 'universidadesPrivadas', 'universidadesExtranjeras', 'universidadesOtros'));
    }

    /**
     * Crear nueva universidad
     */
    public function crear_universidad(Request $request)
    {
        // Validación inicial
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'sigla' => 'required|string|max:50',
            'tipo' => 'required|in:Pública,Privada,Extranjera,Otro'
        ]);

        // Validar que el nombre no exista (case-insensitive)
        $nombreExistente = Universidad::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->first();
        if ($nombreExistente) {
            return back()->withInput($request->all())->withErrors(['nombre' => 'Ya existe una universidad con este nombre.']);
        }

        // Validar que la sigla no exista (case-insensitive)
        $siglaExistente = Universidad::whereRaw('LOWER(sigla) = ?', [strtolower($request->sigla)])->first();
        if ($siglaExistente) {
            return back()->withInput($request->all())->withErrors(['sigla' => 'Ya existe una universidad con esta sigla.']);
        }

        try {
            Universidad::create([
                'nombre' => $request->nombre,
                'sigla' => $request->sigla,
                'tipo' => $request->tipo
            ]);

            return redirect('listar universidades')->with('exito', 'Universidad creada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput($request->all())->withErrors(['error' => 'Error al crear la universidad: ' . $e->getMessage()]);
        }
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
            'nombre' => 'required|string|max:255',
            'sigla' => 'required|string|max:50',
            'tipo' => 'required|in:Pública,Privada,Extranjera,Otro'
        ]);

        // Validar que el nombre no exista en otras universidades (case-insensitive)
        $nombreExistente = Universidad::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $id)
            ->exists();
        if ($nombreExistente) {
            return back()->withInput()->withErrors(['nombre' => 'Ya existe otra universidad con este nombre.']);
        }

        // Validar que la sigla no exista en otras universidades (case-insensitive)
        $siglaExistente = Universidad::whereRaw('LOWER(sigla) = ?', [strtolower($request->sigla)])
            ->where('id', '!=', $id)
            ->exists();
        if ($siglaExistente) {
            return back()->withInput()->withErrors(['sigla' => 'Ya existe otra universidad con esta sigla.']);
        }

        try {
            $universidad->update([
                'nombre' => $request->nombre,
                'sigla' => $request->sigla,
                'tipo' => $request->tipo
            ]);

            return redirect('listar universidades')->with('exito', 'Universidad actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al actualizar la universidad: ' . $e->getMessage()]);
        }
    }

    /**
     * Verificar si una universidad ya existe
     */
    public function verificar_universidad(Request $request)
    {
        $nombre = $request->input('nombre', '');
        $sigla = $request->input('sigla', '');
        $id = $request->input('id', null);

        // Validar nombre duplicado
        $nombreExistente = Universidad::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        // Validar sigla duplicada
        $siglaExistente = Universidad::whereRaw('LOWER(sigla) = ?', [strtolower($sigla)])
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json([
            'valido' => !($nombreExistente || $siglaExistente),
            'nombre_existe' => $nombreExistente,
            'sigla_existe' => $siglaExistente
        ]);
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
        $request->validate([
            'cod_fun' => 'required|integer',
            'lugarTrabajo' => 'required|string|max:255',
            'carrera' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $codFun = $request->input('cod_fun');
        $observaciones = $request->input('observaciones', '');
        $lugarTrabajo = $request->input('lugarTrabajo');
        $carrera = $request->input('carrera');

        $funcionario = Funcionario::find($codFun);
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Funcionario no encontrado');
        }

        $startTime = session('conformidad_start_time_' . $codFun, now());

        $prefix = ($funcionario->fun_doc_adm === 'D') ? 'DOC' : 'ADM';
        $contador = DB::table('doc_adm.formularios_conformidad')
            ->where('codigo', 'like', $prefix . '-%')
            ->count() + 1;

        $anio = date('y'); //26
        $codigo = $prefix . str_pad($contador, 2, '0', STR_PAD_LEFT) . '-' . $anio;//. '/' . $anio;

        $codFcon = DB::table('doc_adm.formularios_conformidad')->insertGetId([
            'cod_fun' => $codFun,
            'codigo' => $codigo,
            'lugar_trabajo' => $lugarTrabajo,
            'carrera' => $carrera,
            'observaciones' => $observaciones,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'cod_fcon');

        DB::table('doc_adm.documentos')
            ->where('cod_fun', $codFun)
            ->whereNull('cod_fcon')
            ->where('created_at', '>=', $startTime)
            ->update(['cod_fcon' => $codFcon]);

        DB::table('doc_adm.titularidads')
            ->where('cod_fun', $codFun)
            ->whereNull('cod_fcon')
            ->where('created_at', '>=', $startTime)
            ->update(['cod_fcon' => $codFcon]);

        session()->forget('conformidad_start_time_' . $codFun);

        \Session::flash('exito', 'Formulario de conformidad guardado correctamente.');
        \Session::flash('descargar_conformidad', $codFcon);
        return redirect(url('listar documentos funcionario/' . $codFun));
    }

    /**
     * Mostrar formulario de conformidad para un funcionario
     */
    public function l_conformidad($cod_fun)
    {
        $funcionario = Funcionario::find($cod_fun);
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Funcionario no encontrado');
        }
        $startTime = session('conformidad_start_time_' . $cod_fun, now());
        session(['conformidad_start_time_' . $cod_fun => $startTime]);

        $titularidades = DB::table('doc_adm.titularidads')
            ->leftJoin('carreras','titularidads.cod_car','=','carreras.cod_car')
            ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('titularidads.*','car_nombre','fac_nombre','fac_abreviacion')
            ->where('cod_fun','=',$cod_fun)
            ->where('titularidads.created_at', '>=', $startTime)
            ->get();
        $documentos = Documento::where('cod_fun','=',$cod_fun)
            ->where('created_at', '>=', $startTime)
            ->whereNull('cod_fcon')
            ->orderBy('doc_tipo')->get();
        $pendingObsDocIds = DB::table('doc_adm.d_observacions as o')
            ->join('doc_adm.documentos as d', 'o.cod_doc', '=', 'd.cod_doc')
            ->where('d.cod_fun', '=', $cod_fun)
            ->where(function($query){
                $query->whereNull('o.od_solucion')
                    ->orWhereRaw("TRIM(o.od_solucion) = ''");
            })
            ->pluck('o.cod_doc')
            ->unique()
            ->toArray();

        $formularios = FormularioConformidad::with(['documentos', 'titularidades'])
            ->where('cod_fun', $cod_fun)
            ->orderByDesc('created_at')
            ->get();

        $backUrl = url()->previous();
        if ($backUrl === url('l_conformidad/'.$cod_fun) || str_contains($backUrl, 'guardar-conformidad')) {
            $backUrl = url('listar funcionario/' . ($funcionario->fun_doc_adm === 'D' ? 'docente' : 'administrativo'));
        }

        return view('funcionario.documento.l_conformidad', compact('funcionario', 'titularidades', 'documentos', 'pendingObsDocIds', 'cod_fun', 'backUrl', 'formularios'));
    }

    /**
     * Descargar el formulario de conformidad como .docx con los datos llenos
     */
    public function descargar_conformidad($cod_fcon)
    {
        $formulario = FormularioConformidad::find($cod_fcon);
        if (!$formulario) {
            return redirect()->back()->with('error', 'Formulario no encontrado.');
        }

        $funcionario = Funcionario::find($formulario->cod_fun);
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Funcionario no encontrado.');
        }

        $plantilla = storage_path('app/plantillas/formulario_conformidad.docx');
        if (!file_exists($plantilla)) {
            return redirect()->back()->with('error', 'La plantilla del formulario no se encontró.');
        }

        $templateProcessor = new TemplateProcessor($plantilla);

        $fecha = $formulario->created_at
            ? \Carbon\Carbon::parse($formulario->created_at)->format('d/m/Y')
            : date('d/m/Y');

        $templateProcessor->setValue('nombre',      $funcionario->fun_nombre ?? '');
        $templateProcessor->setValue('lugar:trabajo', $formulario->lugar_trabajo ?? '');
        $templateProcessor->setValue('carrera',     $formulario->carrera ?? '');
        $templateProcessor->setValue('telefono',    $funcionario->fun_telefonos ?? '');
        $templateProcessor->setValue('email',       $funcionario->fun_email ?? '');
        $templateProcessor->setValue('fecha',       $fecha);
        $templateProcessor->setValue('codigo',      $formulario->codigo ?? '');

        $documentos = \App\Models\Documento::where('cod_fcon', $cod_fcon)->orderBy('doc_tipo')->get();
        $templateProcessor->setValue('cantidad_documentos', count($documentos) + 1);
        $valoresDocs = [];
        foreach ($documentos as $doc) {
            $valoresDocs[] = [
                'doc_tipo' => htmlspecialchars($doc->doc_tipo ?? ''),
                'doc_titulo' => htmlspecialchars($doc->doc_titulo ?? ''),
            ];
        }
        
        if (count($valoresDocs) > 0) {
            $templateProcessor->cloneRowAndSetValues('doc_tipo', $valoresDocs);
        } else {
            $templateProcessor->cloneRowAndSetValues('doc_tipo', [
                ['doc_tipo' => 'Sin documentos añadidos', 'doc_titulo' => '']
            ]);
        }

        $nombreArchivo = 'conformidad-' . ($formulario->codigo ?? $cod_fcon) . '.docx';
        $rutaTemporal  = storage_path('app/temp/' . $nombreArchivo);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $templateProcessor->saveAs($rutaTemporal);

        return response()->download($rutaTemporal, $nombreArchivo)->deleteFileAfterSend(true);
    }

    public function fe_formulario_conformidad($cod_fcon){
        $formulario = FormularioConformidad::find($cod_fcon);
        return view('funcionario.documento.fe_formulario_conformidad', compact('formulario'));
    }

    public function editar_conformidad(Request $request){
        $request->validate([
            'cod_fcon' => 'required|integer',
            'lugar_trabajo' => 'required|string|max:255',
            'carrera' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $formulario = FormularioConformidad::find($request->cod_fcon);
        if($formulario){
            $formulario->lugar_trabajo = $request->lugar_trabajo;
            $formulario->carrera = $request->carrera;
            $formulario->observaciones = $request->observaciones;
            // Eloquent automatically updates updated_at and leaves created_at alone.
            $formulario->save();

            \Session::flash('exito','El formulario se actualizó correctamente');
        } else {
            \Session::flash('error','Formulario no encontrado');
        }
        return redirect()->back();
    }

    public function fe_eli_conformidad($cod_fcon){
        $formulario = FormularioConformidad::find($cod_fcon);
        return view('funcionario.documento.f_eli_formulario_conformidad', compact('formulario'));
    }

    public function eliminar_conformidad(Request $request){
        $request->validate([
            'cod_fcon' => 'required|integer'
        ]);
        $formulario = FormularioConformidad::find($request->cod_fcon);
        if($formulario){
            $formulario->delete();
            \Session::flash('exito','El formulario fue eliminado exitosamente');
        } else {
            \Session::flash('error','Formulario no encontrado');
        }
        return redirect()->back();
    }
}