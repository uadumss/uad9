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
    public function l_documentos($cod_fun){
        $funcionario=Funcionario::find($cod_fun);
        $documentos=Documento::all()->where('cod_fun','=',$cod_fun)->sortBy('doc_tipo');
        $titularidades=DB::table('doc_adm.titularidads')
            ->leftJoin('carreras','titularidads.cod_car','=','carreras.cod_car')
            ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('titularidads.*','car_nombre','fac_nombre','fac_abreviacion')
            ->where('cod_fun','=',$cod_fun)->get();
        return view('funcionario.documento.l_documento',compact('funcionario','documentos','cod_fun','titularidades'));
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
                $documento->save();
            \Session::flash('exito','Se ha guardado exitosamente los datos');
        }else{
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

            ]);
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
            \Session::flash('exito','Se ha guardado exitosamente la correción');
        }else{
            if($form['obs']!=''){
                $obs=D_observacion::create([
                    'cod_doc'=>$form['cd'],
                    'od_obs'=>$form['obs'],
                    'od_fecha'=>date('d/m/Y'),
                ]);
                $documento=Documento::find($form['cd']);
                $documento->doc_obs='t';
                $documento->save();

                $funcionario=Funcionario::find($documento['cod_fun']);
                $funcionario->fun_obs='t';
                $funcionario->save();

                \Session::flash('exito','Se ha guardado exitosamente la observacion');
            }else{
                \Session::flash('error','Debe ingresar una observación válida');
            }
        }
        return redirect('fe_observacion documento/'.$form['cd']);
    }
    public function e_obs_documento(Request $form){
        if(isset($form['co'])){
            $obs=D_observacion::find($form['co']);
            $obs->delete();
            $cantObs=D_observacion::all()->where('cod_tit','=',$obs->cod_tit);
            if(sizeof($cantObs)<1){

                $documento=Documento::find($obs->cod_doc);
                $documento->doc_obs='f';
                $documento->save();

                $funcionario=Funcionario::find($documento->cod_fun);
                $funcionario->fun_obs='f';
                $funcionario->save();
            }

            \Session::flash('exito','Se ha eliminado exitosamente la observacion '.sizeof($cantObs));
        }else{
            \Session::flash('error','No se puedo eliminar la observación');
        }
        return redirect('fe_observacion documento/'.$obs['cod_doc']);
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
