<?php

namespace App\Http\Controllers;

use App\Imports\ImportarDoc;
use App\Imports\ImportarTitularidad;
use App\Models\Carrera;
use App\Models\D_observacion;
use App\Models\Documento;
use App\Models\Funcionario;
use App\Models\Titularidad;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DocumentoController extends Controller
{
    private function normalizeFuncionarioDocAdmType($funcionario){
        return strtoupper(trim((string)($funcionario->fun_doc_adm ?? '')));
    }

    private function isTrueFlag($value){
        return $value === true || $value === 1 || $value === 't' || $value === '1';
    }

    private function getPendingObservationDocIdsByFuncionario($cod_fun){
        return DB::table('doc_adm.d_observacions as o')
            ->join('doc_adm.documentos as d', 'o.cod_doc', '=', 'd.cod_doc')
            ->where('d.cod_fun', '=', $cod_fun)
            ->where(function($query){
                $query->whereNull('o.od_solucion')
                    ->orWhereRaw("TRIM(o.od_solucion) = ''");
            })
            ->pluck('o.cod_doc')
            ->unique()
            ->toArray();
    }

    private function refreshObservationStatusForDocument($cod_doc){
        $documento = Documento::find($cod_doc);
        if(!$documento){
            return;
        }

        $hasPending = DB::table('doc_adm.d_observacions')
            ->where('cod_doc', '=', $cod_doc)
            ->where(function($query){
                $query->whereNull('od_solucion')
                    ->orWhereRaw("TRIM(od_solucion) = ''");
            })
            ->exists();

        $documento->doc_obs = $hasPending ? 't' : 'f';
        $documento->save();

        $funcionario = Funcionario::find($documento->cod_fun);
        if($funcionario){
            $funcionarioHasPending = DB::table('doc_adm.d_observacions as o')
                ->join('doc_adm.documentos as d', 'o.cod_doc', '=', 'd.cod_doc')
                ->where('d.cod_fun', '=', $funcionario->cod_fun)
                ->where(function($query){
                    $query->whereNull('o.od_solucion')
                        ->orWhereRaw("TRIM(o.od_solucion) = ''");
                })
                ->exists();

            $funcionario->fun_obs = $funcionarioHasPending ? 't' : 'f';
            $funcionario->save();
        }
    }

    public function l_documentos($cod_fun){
        $funcionario=Funcionario::find($cod_fun);
        $funcionarioTipo = $this->normalizeFuncionarioDocAdmType($funcionario);
        $requiresEduSuperior = $funcionarioTipo === 'D' || $funcionarioTipo === 'E';
        $documentos=Documento::where('cod_fun','=',$cod_fun)->orderBy('doc_tipo')->get();
        $titularidades=DB::table('doc_adm.titularidads')
            ->leftJoin('carreras','titularidads.cod_car','=','carreras.cod_car')
            ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('titularidads.*','car_nombre','fac_nombre','fac_abreviacion')
            ->where('cod_fun','=',$cod_fun)->get();

        $enviosDpa = DB::table('doc_adm.envio_dpas')
            ->where('cod_fun', '=', $cod_fun)
            ->orderBy('cod_env_dpa')
            ->get();

        $enviosDpaDocumentos = DB::table('doc_adm.envio_dpa_detalles as ded')
            ->join('doc_adm.envio_dpas as ed', 'ded.cod_env_dpa', '=', 'ed.cod_env_dpa')
            ->join('doc_adm.documentos as d', 'ded.cod_doc', '=', 'd.cod_doc')
            ->select(
                'ded.cod_env_dpa',
                'd.cod_doc',
                'd.doc_tipo',
                'd.doc_titulo',
                'd.doc_grado',
                'd.doc_universidad',
                'd.doc_fecha_emision'
            )
            ->where('ed.cod_fun', '=', $cod_fun)
            ->orderBy('ded.cod_env_dpa')
            ->get()
            ->groupBy('cod_env_dpa');

        $documentosDisponiblesEnvio = $documentos->reject(function($doc){
            return $this->isTrueFlag($doc->doc_enviado_dpa);
        });
        $hasPreviousDpaEnvio = $enviosDpa->count() > 0;
        $hasDocumentosHabilitados = $documentosDisponiblesEnvio->count() > 0;
        $pendingObsDocIds = $this->getPendingObservationDocIdsByFuncionario($cod_fun);
        $hasDpaCandidates = $hasDocumentosHabilitados;

        return view('funcionario.documento.l_documento',compact('funcionario','documentos','cod_fun','titularidades','enviosDpa','enviosDpaDocumentos','hasDpaCandidates','hasPreviousDpaEnvio','requiresEduSuperior','pendingObsDocIds'));
    }
    public function fe_documento($cod_doc,$cod_fun){
        $documento='';
        if($cod_doc!=0){
            $documento=Documento::find($cod_doc);
        }
        return view('funcionario.documento.fe_documento',compact('cod_doc','documento','cod_fun'));
    }
    public function g_documento(Request $form){

        $verificado=$form['verificado']=='on'?'t':'f';
        $legalizado=$form['legalizado']=='on'?'t':'f';
        $umss=$form['umss']=='on'?'t':'f';
        //$form['universidad']=$form['umss']=='on'? 'Universidad Mayor de San Simon':$form['universidad'];
        $superior=$form['superior']=='on'?'t':'f';

        if(isset($form['cd'])){
            $documento=Documento::find($form['cd']);
                $wasEnviadoDpa = $this->isTrueFlag($documento->doc_enviado_dpa);
                $documento->doc_titulo=$form['titulo'];
                $documento->doc_tipo=$form['tipo'];
                $documento->doc_gestion=$form['gestion'];
                $documento->doc_fecha_emision=$form['fecha'];
                $documento->doc_universidad=$form['universidad'];
                $documento->doc_verificado=$verificado;
                $documento->doc_legalizado=$legalizado;
                $documento->doc_umss=$umss;
                $documento->doc_edu_superior=$superior;
                $documento->doc_numero_revalida=$form['revalida'];
                $documento->doc_grado=$form['grado'];
                $documento->doc_numero_registro=$form['numero_registro'] ?? '';
                
                // Procesar el PDF si se adjuntó uno
                if($form->hasFile('pdf')){
                    // Eliminar el PDF anterior si existe
                    if($documento->doc_pdf && Storage::disk('public')->exists('documentos/'.$documento->doc_pdf)){
                        Storage::disk('public')->delete('documentos/'.$documento->doc_pdf);
                    }
                    
                    // Guardar el nuevo PDF
                    $nombreOriginal = pathinfo($form->file('pdf')->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $form->file('pdf')->getClientOriginalExtension();
                    $nombreArchivo = 'documento-' . $form['cd'] . '-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
                    $ruta = 'documentos';
                    if(!Storage::disk('public')->exists($ruta)){
                        Storage::disk('public')->makeDirectory($ruta);
                    }
                    Storage::disk('public')->putFileAs($ruta, $form->file('pdf'), $nombreArchivo);
                    $documento->doc_pdf = $nombreArchivo;
                }

                $hasDocumentChanges = $documento->isDirty();
                $seInvalidoEnvioDpa = false;
                if($wasEnviadoDpa && $hasDocumentChanges){
                    $documento->doc_enviado_dpa = false;
                    $seInvalidoEnvioDpa = true;
                }
                
                $documento->save();

                if($seInvalidoEnvioDpa){
                    $funcionario = Funcionario::find($documento->cod_fun);
                    if($funcionario){
                        $funcionario->fun_env_dpa = false;
                        $funcionario->save();
                    }
                    \Session::flash('exito','Se guardaron los cambios y el documento quedó pendiente de reenvío a la DPA.');
                }else{
                    \Session::flash('exito','Se ha guardado exitosamente los datos');
                }
        }else{
            // Variables para el nuevo documento
            $nombreArchivoPdf = null;
            
            // Procesar el PDF si se adjuntó uno
            if($form->hasFile('pdf')){
                $nombreOriginal = pathinfo($form->file('pdf')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $form->file('pdf')->getClientOriginalExtension();
                // Usar un valor temporal para el nombre del archivo (se actualizará con el ID real)
                $nombreArchivoPdf = 'documento-temp-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
            }
            
            $documento=Documento::create([
                'cod_fun'=>$form['cf'],
                'doc_titulo'=>$form['titulo'],
                'doc_tipo'=>$form['tipo'],
                'doc_gestion'=>$form['gestion'],
                'doc_fecha_emision'=>$form['fecha'],
                'doc_universidad'=>$form['universidad'],
                'doc_verificado'=>$verificado,
                'doc_legalizado'=>$legalizado,
                'doc_umss'=>$umss,
                'doc_edu_superior'=>$superior,
                'doc_grado'=>$form['grado'],
                'doc_numero_revalida'=>$form['revalida'],
                'doc_pdf'=>$nombreArchivoPdf,
                'doc_numero_registro'=>$form['numero_registro'] ?? '',
            ]);
            
            // Si hay PDF, actualizar el nombre del archivo con el ID real del documento
            if($form->hasFile('pdf')){
                $nombreOriginal = pathinfo($form->file('pdf')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $form->file('pdf')->getClientOriginalExtension();
                $nombreArchivo = 'documento-' . $documento->cod_doc . '-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
                $ruta = 'documentos';
                if(!Storage::disk('public')->exists($ruta)){
                    Storage::disk('public')->makeDirectory($ruta);
                }
                Storage::disk('public')->putFileAs($ruta, $form->file('pdf'), $nombreArchivo);
                $documento->doc_pdf = $nombreArchivo;
                $documento->save();
            }

            // Si se agrega un nuevo diploma/titulo, se invalida el envio anterior a la DPA.
            $funcionario = Funcionario::find($form['cf']);
            if($funcionario){
                $funcionario->fun_env_dpa = false;
                $funcionario->save();
            }
            
            \Session::flash('exito','Se ha creado exitosamente el documento');
        }
        return redirect('listar documentos funcionario/'.$form['cf']);
    }
    public function fe_eli_documento($cod_d,$cod_fun){
        $documento="";
        if($cod_d!=''){
            $documento=Documento::find($cod_d);
            $funcionario=Funcionario::find($cod_fun);
            return view('funcionario.documento.f_eli_documento',compact('cod_d','documento','cod_fun','funcionario'));
        }
        else{
            return redirect('listar documentos funcionario/docente');
        }
    }
    public function eli_documento(Request $form){
        $form->validate([
            'cd'=>'required'
        ]);
        $documento=documento::find($form['cd']);
        
        // Eliminar el PDF si existe
        if($documento->doc_pdf && Storage::disk('public')->exists('documentos/'.$documento->doc_pdf)){
            Storage::disk('public')->delete('documentos/'.$documento->doc_pdf);
        }
        
        DB::delete('delete from doc_adm.d_observacions where cod_doc='.$form['cd']);
        $cod_fun=$documento->cod_fun;
        $documento->delete();
        \Session::flash("exito","Se ha eliminado correctamente el documento");
        return redirect('listar documentos funcionario/'.$cod_fun);
    }

    public function fe_obs_documento($cod_doc){
        $documento=Documento::find($cod_doc);
        $funcionario=Funcionario::find($documento->cod_fun);
        $observaciones=D_observacion::all()->where('cod_doc','=',$cod_doc);
        return view('funcionario.l_observacion',compact('documento','observaciones'));
    }
    public function g_obs_documento(Request $form){

        if(isset($form['co'])){
            $obs=D_observacion::find($form['co']);
            $obs->od_solucion=$form['obs'];
            $obs->od_fecha_solucion=date('d/m/Y');
            $obs->save();
            $this->refreshObservationStatusForDocument($form['cd']);
            \Session::flash('exito','Se ha guardado exitosamente la correción');
        }else{
            if($form['obs']!=''){
                $obs=D_observacion::create([
                    'cod_doc'=>$form['cd'],
                    'od_obs'=>$form['obs'],
                    'od_fecha'=>date('d/m/Y'),
                ]);
                $this->refreshObservationStatusForDocument($form['cd']);

                \Session::flash('exito','Se ha guardado exitosamente la observacion');
            }else{
                \Session::flash('error','Debe ingresar una observación válida');
            }
        }
        return redirect('fe_observacion documento/'.$form['cd']);
    }
    public function e_obs_documento(Request $form){
        $codDoc = null;
        if(isset($form['co'])){
            $obs=D_observacion::find($form['co']);
            if(!$obs){
                \Session::flash('error','No se encontró la observación.');
                return redirect()->back();
            }
            $codDoc = $obs->cod_doc;
            $obs->delete();
            $cantObs=D_observacion::where('cod_doc','=',$codDoc)
                ->where(function($query){
                    $query->whereNull('od_solucion')
                        ->orWhereRaw("TRIM(od_solucion) = ''");
                })
                ->count();
            $this->refreshObservationStatusForDocument($codDoc);

            \Session::flash('exito','Se ha eliminado exitosamente la observacion '.$cantObs);
        }else{
            \Session::flash('error','No se puedo eliminar la observación');
            return redirect()->back();
        }
        return redirect('fe_observacion documento/'.$codDoc);
    }
    public function fe_documento_titularidad($cod_dt,$cod_fun){
        $titularidad='';
        if($cod_dt!=0){
            $titularidad=DB::table('doc_adm.titularidads')
                ->leftJoin('carreras','titularidads.cod_car','=','carreras.cod_car')
                ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->select('titularidads.*','car_nombre','fac_nombre','fac_abreviacion')
                ->where('cod_dt','=',$cod_dt)->first();
        }
        $carreras=DB::table('carreras')
            ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('cod_car','car_nombre','fac_nombre','fac_abreviacion')->orderBy('fac_abreviacion')->get();

        return view('funcionario.documento.fe_documento_titularidad',compact('cod_dt','titularidad','cod_fun','carreras'));
    }
    public function g_documento_titularidad(Request $form){
        $verificado=$form['verificado']=='on'?'t':'f';
        if(isset($form['ct'])){

            $titularidad=Titularidad::find($form['ct']);
            $titularidad->cod_car=$form['carrera'];
            $titularidad->dt_materia=$form['materia'];
            $titularidad->dt_fecha=$form['fecha'];
            $titularidad->dt_gestion=$form['gestion'];
            $titularidad->dt_categoria=$form['categoria'];
            $titularidad->dt_numero_resolucion=$form['numero'];
            $titularidad->dt_fecha_resolucion=$form['fecha_resolucion'];
            $titularidad->dt_verificado=$verificado;
            $titularidad->dt_detalle=$form['detalle'];
            $titularidad->dt_obs=$form['observacion'];
            $titularidad->dt_universidad=$form['universidad'];
            $titularidad->save();
            \Session::flash('exito','Se ha guardado exitosamente la correción');
        }else{
                $titularidad=Titularidad::create([
                    'cod_fun'=>$form['cf'],
                    'cod_car'=>$form['carrera'],
                    'dt_materia'=>$form['materia'],
                    'dt_fecha'=>$form['fecha'],
                    'dt_gestion'=>$form['gestion'],
                    'dt_categoria'=>$form['categoria'],
                    'dt_numero_resolucion'=>$form['numero'],
                    'dt_fecha_resolucion'=>$form['fecha_resolucion'],
                    'dt_verificado'=>$verificado,
                    'dt_detalle'=>$form['detalle'],
                    'dt_obs'=>$form['observacion'],
                    'dt_universidad'=>$form['universidad'],
                ]);

                $funcionario=Funcionario::find($titularidad['cod_fun']);
                $funcionario->fun_titular='t';
                $funcionario->save();

                \Session::flash('exito','Se ha guardado exitosamente el documento de titularidad');
            }
        return redirect('listar documentos funcionario/'.$form['cf']);
    }
    public function fe_eli_titularidad($cod_dt,$cod_fun){
        $titularidad="";
        if($cod_dt!=''){
            $titularidad=DB::table('doc_adm.titularidads')
                ->leftJoin('carreras','titularidads.cod_car','=','carreras.cod_car')
                ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->select('titularidads.*','car_nombre','fac_nombre','fac_abreviacion')
                ->where('cod_dt','=',$cod_dt)->first();
        }
        $funcionario=Funcionario::find($cod_fun);
        return view('funcionario.documento.f_eli_titularidad',compact('cod_dt','titularidad','cod_fun','funcionario'));
    }
    public function eli_titularidad(Request $form){
        $form->validate([
            'ct'=>'required'
        ]);
        $titularidad=Titularidad::find($form['ct']);
        $cod_fun=$titularidad->cod_fun;
        $titularidad->delete();
        \Session::flash("exito","Se ha eliminado correctamente el documento de titularidad");
        return redirect('listar documentos funcionario/'.$cod_fun);
    }
    public function descargar_pdf_documento($cod_doc){
        $documento=Documento::find($cod_doc);
        if(!$documento || !$documento->doc_pdf){
            return redirect()->back()->with('error','El documento no tiene archivo PDF');
        }
        $rutaArchivo = storage_path('app/public/documentos/'.$documento->doc_pdf);
        if(!file_exists($rutaArchivo)){
            return redirect()->back()->with('error','El archivo PDF no se encontró');
        }
        return response()->download($rutaArchivo, $documento->doc_pdf);
    }
    
    public function ver_pdf_documento($cod_doc){
        $documento=Documento::find($cod_doc);
        if(!$documento || !$documento->doc_pdf){
            return redirect()->back()->with('error','El documento no tiene archivo PDF');
        }
        $rutaArchivo = storage_path('app/public/documentos/'.$documento->doc_pdf);
        if(!file_exists($rutaArchivo)){
            return redirect()->back()->with('error','El archivo PDF no se encontró');
        }
        return response()->file($rutaArchivo, ['Content-Type' => 'application/pdf']);
    }

    private function getRequiredDocuments($funcionario){
        $tipo = $this->normalizeFuncionarioDocAdmType($funcionario);
        if($tipo === 'D' || $tipo === 'E'){
            return [
                ['type' => 'DIPLOMA DE BACHILLER', 'name' => 'Diploma de Bachiller'],
                ['type' => 'DIPLOMA ACADEMICO', 'name' => 'Diploma Académico'],
                ['type' => 'TITULO PROFESIONAL', 'name' => 'Título Profesional'],
                ['edu_superior' => true, 'name' => 'Diplomado/Postgrado en Educación Superior']
            ];
        }else{
            return [
                ['type' => 'DIPLOMA DE BACHILLER', 'name' => 'Diploma de Bachiller'],
                ['type' => 'DIPLOMA ACADEMICO', 'name' => 'Diploma Académico'],
                ['type' => 'TITULO PROFESIONAL', 'name' => 'Título Profesional']
            ];
        }
    }

    private function verifyRequiredDocuments($cod_fun, $funcionario){
        $required = $this->getRequiredDocuments($funcionario);
        $documentos = Documento::where('cod_fun', '=', $cod_fun)->get();

        foreach($required as $req){
            if(isset($req['type'])){
                $found = $documentos->filter(function($doc) use ($req){
                    return strtoupper(trim($doc->doc_tipo)) === strtoupper(trim($req['type']));
                })->first();
                if(!$found) return false;
            }else if(isset($req['edu_superior'])){
                $found = $documentos->where('doc_edu_superior', 't')->first();
                if(!$found) return false;
            }
        }
        return true;
    }

    public function fe_enviar_dpa($cod_fun){
        $funcionario = Funcionario::find($cod_fun);
        if(!$funcionario){
            return redirect()->back()->with('error','No se encontró el funcionario');
        }

        $documentos = Documento::where('cod_fun', '=', $cod_fun)
            ->orderBy('doc_tipo')
            ->orderBy('doc_titulo')
            ->get()
            ->reject(function($doc){
                return $this->isTrueFlag($doc->doc_enviado_dpa);
            });

        if($documentos->count() < 1){
            return redirect()->back()->with('error','El funcionario no tiene diplomas o titulos disponibles para enviar a la DPA.');
        }

        return view('funcionario.documento.fe_enviar_dpa', compact('funcionario', 'cod_fun', 'documentos'));
    }

    public function enviar_dpa(Request $form){
        $form->validate([
            'cod_fun' => 'required|integer',
            'pdf_control' => 'required|file|mimes:pdf|max:5120',
            'documentos_envio' => 'required|array|min:1',
            'documentos_envio.*' => 'integer',
            'confirmar_envio' => 'required|accepted',
        ],[
            'pdf_control.required' => 'Debe adjuntar el PDF de control de envio.',
            'pdf_control.mimes' => 'El archivo debe estar en formato PDF.',
            'pdf_control.max' => 'El PDF no debe superar los 5MB.',
            'documentos_envio.required' => 'Debe seleccionar al menos un diploma o titulo para el envio.',
            'documentos_envio.min' => 'Debe seleccionar al menos un diploma o titulo para el envio.',
            'confirmar_envio.accepted' => 'Debe confirmar el envio a la DPA.',
        ]);

        $funcionario = Funcionario::find($form['cod_fun']);
        if(!$funcionario){
            return redirect()->back()->with('error','No se encontró el funcionario');
        }

        $documentosSeleccionados = Documento::where('cod_fun', '=', $funcionario->cod_fun)
            ->whereIn('cod_doc', $form['documentos_envio'])
            ->get();

        if($documentosSeleccionados->count() < 1){
            return redirect()->back()->with('error','No se encontraron documentos validos para el envio a la DPA.');
        }



        $conEnvioPrevio = $documentosSeleccionados->first(function($doc){
            return $this->isTrueFlag($doc->doc_enviado_dpa);
        });
        if($conEnvioPrevio){
            return redirect()->back()->with('error','No se puede reenviar a la DPA un título que ya fue enviado anteriormente.');
        }

        $ruta = 'dpa_envios';
        if(!Storage::disk('public')->exists($ruta)){
            Storage::disk('public')->makeDirectory($ruta);
        }

        $nombreOriginal = pathinfo($form->file('pdf_control')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $form->file('pdf_control')->getClientOriginalExtension();
        $nombreArchivo = 'envio-dpa-' . $funcionario->cod_fun . '-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
        Storage::disk('public')->putFileAs($ruta, $form->file('pdf_control'), $nombreArchivo);

        $documentosSeleccionados = $documentosSeleccionados->pluck('cod_doc')->toArray();

        $codEnvio = DB::table('doc_adm.envio_dpas')->insertGetId([
            'cod_fun' => $funcionario->cod_fun,
            'env_pdf_control' => $nombreArchivo,
            'env_fecha' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ], 'cod_env_dpa');

        $detalles = [];
        foreach($documentosSeleccionados as $codDoc){
            $detalles[] = [
                'cod_env_dpa' => $codEnvio,
                'cod_doc' => $codDoc,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('doc_adm.envio_dpa_detalles')->insert($detalles);

        Documento::whereIn('cod_doc', $documentosSeleccionados)->update(['doc_enviado_dpa' => true]);

        if(!$funcionario->fun_env_dpa){
            $funcionario->fun_env_dpa = true;
            $funcionario->fun_pdf_env_dpa = $nombreArchivo;
        }
        $funcionario->save();

        \Session::flash('exito','Se registró el envio a la DPA correctamente.');
        return redirect('listar documentos funcionario/'.$funcionario->cod_fun);
    }

    public function ver_pdf_envio_dpa($cod_env_dpa){
        $envio = DB::table('doc_adm.envio_dpas')->where('cod_env_dpa', '=', $cod_env_dpa)->first();
        if(!$envio || !$envio->env_pdf_control){
            return redirect()->back()->with('error','No existe PDF de control de envio a la DPA.');
        }

        $rutaArchivo = storage_path('app/public/dpa_envios/'.$envio->env_pdf_control);
        if(!file_exists($rutaArchivo)){
            return redirect()->back()->with('error','El archivo PDF de control no se encontró.');
        }

        return response()->file($rutaArchivo, ['Content-Type' => 'application/pdf']);
    }

    public function descargar_pdf_envio_dpa($cod_env_dpa){
        $envio = DB::table('doc_adm.envio_dpas')->where('cod_env_dpa', '=', $cod_env_dpa)->first();
        if(!$envio || !$envio->env_pdf_control){
            return redirect()->back()->with('error','No existe PDF de control de envio a la DPA.');
        }

        $rutaArchivo = storage_path('app/public/dpa_envios/'.$envio->env_pdf_control);
        if(!file_exists($rutaArchivo)){
            return redirect()->back()->with('error','El archivo PDF de control no se encontró.');
        }

        return response()->download($rutaArchivo, $envio->env_pdf_control);
    }

    public function importar_docente(Request $form){

        try {
            if ($form->hasFile('archivo')) {

                //$lista=Excel::toArray(new ImportarDoc(), $form->file('archivo'));
                $importado = Excel::import(new ImportarDoc(), $form->file('archivo'));
                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                //return $lista[0];
                //dd($lista);
                return redirect('listar funcionario/docente');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            return view('importacion.resultado_importacion', compact('fallas'));
        }
        return redirect('listar funcionario/docente');
    }
    public function importar_titularidad(Request $form){

        try {
            if ($form->hasFile('archivo')) {

                /*$array = Excel::toArray(new importarTitularidad(), $form->file('archivo'));
                $texto="<table>";
                foreach ($array as $a){
                    $texto.="<tr> <td>".$a[0]['ci']."</td></tr>";

                }
                $texto="</table>";*/
                //$lista=Excel::toArray(new ImportarDoc(), $form->file('archivo'));
                $importado = Excel::import(new ImportarTitularidad(), $form->file('archivo'));
                
                // Guardar el archivo en storage con nombre original + fecha
                $nombreOriginal = pathinfo($form->file('archivo')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $form->file('archivo')->getClientOriginalExtension();
                $nombreArchivo = 'titularidad-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
                $ruta = 'importaciones/titularidad/';
                Storage::putFileAs($ruta, $form->file('archivo'), $nombreArchivo);
                
                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                //return $lista[0];
                //dd($lista);
                //return $array;
                return redirect('listar funcionario/docente');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            return view('importacion.resultado_importacion', compact('fallas'));
        }
        return redirect('listar funcionario/docente');
    }
    public function importar_nuevo(Request $form){

        try {
            if ($form->hasFile('archivo')) {

                /*$array = Excel::toArray(new importarTitularidad(), $form->file('archivo'));
                $texto="<table>";
                foreach ($array as $a){
                    $texto.="<tr> <td>".$a[0]['ci']."</td></tr>";

                }
                $texto="</table>";*/
                //$lista=Excel::toArray(new ImportarDoc(), $form->file('archivo'));
                $importado = Excel::import(new ImportarTitularidad(), $form->file('archivo'));
                
                // Guardar el archivo en storage con nombre original + fecha
                $nombreOriginal = pathinfo($form->file('archivo')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $form->file('archivo')->getClientOriginalExtension();
                $nombreArchivo = 'funcionarios-' . date('Y-m-d_H-i-s') . '-' . $nombreOriginal . '.' . $extension;
                $ruta = 'importaciones/funcionarios/';
                Storage::putFileAs($ruta, $form->file('archivo'), $nombreArchivo);
                
                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                //return $lista[0];
                //dd($lista);
                //return $array;
                return redirect('listar funcionario/docente');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            return view('importacion.resultado_importacion', compact('fallas'));
        }
        return redirect('listar funcionario/docente');
    }
}
