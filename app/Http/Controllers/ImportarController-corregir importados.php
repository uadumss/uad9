<?php

namespace App\Http\Controllers;

use App\Models\Firma;
use App\Models\Importacion;
use App\Imports\ImportarDB;
use App\Imports\importarRES;
use App\Imports\importarRESC;
use App\Models\Persona;
use App\Models\Titulo;
use App\Models\Tomo;
use App\Models\Resolucion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;

class ImportarController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:realizar importación - dyt'], ['only' => ['verificar_importacion']]);
        $this->middleware(['permission:importar - rr'], ['only' => ['verificar_importacion_res']]);

    }
    public function lista_importaciones($id){
        if(Auth::user()->id==$id) {
            $importaciones = Importacion::all()->where('imp_id','=',$id)->where('imp_sistema','=','1')->sortByDesc('cod_imp');
            return view('importacion.lista_importaciones',compact('importaciones'));

        }else{
            //PARA NO MOSTRAR IMPORTACIONES DE OTROS USUARIOS
            return "Acceso denegado";
        }
    }
    public function lista_importacionUsuario($id){
        $usuario=User::find($id);
        $importaciones = Importacion::all()->where('imp_id','=',$id)->sortByDesc('cod_imp');
        return view('importacion.lista_importacionAdm',compact('importaciones','usuario'));
    }
    public function verificar_importacion(Request $form){

            try {
                if ($form->hasFile('archivo')) {
                    //$array = Excel::toArray(new importarDB(), $form->file('archivo'));

                    $array = Excel::toArray(new ImportarDB(), $form->file('archivo'));
                    $identificador = Auth::user()->id . "-" . rand(0, 9999999999);
                    $banderaIden = 0;

                    $año = $array[0][0]['ano'];
                    $tipo = TomoController::tipoTomoUnitario($array[0][0]['tipo']);
                    $ruta = 'imports/dt/';
                    $extension = $form->file('archivo')->getClientOriginalExtension();
                    $nombre = '';
                    $identificador = '';
                    while ($banderaIden == 0) {
                        $nombre = Auth::user()->id . '-' . rand(0, 9999999999) . '-' . strtoupper($array[0][0]['tipo']) . $año . "." . $extension;
                        $identificador = Auth::user()->id . '-' . rand(0, 9999999999) . '-' . strtoupper($array[0][0]['tipo']) . $año ;
                        if (!Storage::exists('imports/dt/' . $nombre)) {
                            $banderaIden = 1;
                            \Session::put('id_import', $identificador);
                        }
                    }
                    $importado = Excel::import(new ImportarDB(), $form->file('archivo'));
                    Storage::putFileAs($ruta, $form->file('archivo'), $nombre);

                    $importacion = Importacion::create([
                        'imp_usuario' => Auth::user()->name,
                        'imp_id' => Auth::user()->id,
                        'imp_fecha' => date('d-m-Y H:i:s'),
                        'imp_tipo' => $tipo,
                        'imp_gestion' => $año,
                        'imp_usuario' => Auth::user()->name,
                        'imp_archivo' => $nombre,
                        'imp_sistema'=>1,
                        'imp_identificador' => $identificador,
                    ]);

                    \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                    return redirect('lista importaciones/' . Auth::user()->id);
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $fallas = $e->failures();
                return view('importacion.resultado_importacion', compact('fallas'));
            }

    }
    public function ver_importacion($cod_imp){
        $importacion=Importacion::find($cod_imp);
        $titulosExcel = Excel::toArray(new ImportarDB(), 'imports/dt/'.$importacion->imp_archivo);
        $titulosImportados=Titulo::all()->where('tit_importacion','=',$importacion->imp_identificador);
        $tam=sizeof($titulosExcel[0]);
        for($i=0;$i<$tam;$i++){
            $nro_titulo=$titulosExcel[0][$i]['numero'];
            $encontrado='NO';
            foreach ($titulosImportados as $t):
                if($nro_titulo==$t->tit_nro_titulo){
                    $encontrado='SI';
                }
            endforeach;
            if($encontrado=='NO'){
                $titulosExcel[0][$i]['importado']=0;
            }else{
                $titulosExcel[0][$i]['importado']=1;
            }
            if($titulosExcel[0][$i]['fecha']!=''){
               $titulosExcel[0][$i]['fecha']=date('d/m/Y', strtotime($titulosExcel[0][$i]['fecha']));
            }
        }
        return view('importacion.ver_datos_importacion', compact('importacion','titulosExcel'));
    }
    public function form_eliminar_importacion($cod_imp){
        $importacion=Importacion::find($cod_imp);
        $usuario=User::find($importacion->imp_id);
        return view('importacion.datos_importacion',compact('importacion','usuario'));
    }
    public function eliminar_importacion(Request $form){
        $importacion=Importacion::find($form['ci']);
        //dd($importacion);
        $usuario=User::find($importacion['imp_id']);

            $importacion->imp_deshecho='t';
            $importacion->save();

            if($importacion->imp_sistema=='1'){
                $titulosImportados=Titulo::all()->where('tit_importacion','=',$importacion->imp_identificador);

                foreach ($titulosImportados as $t):
                    $personas=Titulo::all()->where('id_per','=',$t['id_per']);
                    $antiguo='';
                    $objeto='';
                    if(sizeof($personas)<2){
                        DB::delete('delete from revalidas where cod_tit='.$t['cod_tit']);
                        DB::delete('delete from t_observacions where cod_tit='.$t['cod_tit']);
                        DB::delete('delete from diploma_academicos where cod_tit='.$t['cod_tit']);
                        $t->delete();
                        $persona=Persona::find($t['id_per']);
                        //$persona->delete();

                    }else{
                        DB::delete('delete from revalidas where cod_tit='.$t['cod_tit']);
                        DB::delete('delete from t_observacions where cod_tit='.$t['cod_tit']);
                        DB::delete('delete from diploma_academicos where cod_tit='.$t['cod_tit']);
                        $t->delete();
                    }
                    $pdf['pdf']='';
                    $pdf['pdf_ant']='';
                    $tomo=Tomo::find($t['cod_tom']);
                    if($t['tit_pdf']!=''){
                        $ruta='alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$t['tit_pdf'];
                        if(Storage::exists($ruta)){
                            $valor= 'I'.rand(0,999999999999);
                            Storage::move($ruta, 'trash/dt/'.$valor.'-'.$t->tit_pdf);
                            //Storage::delete($ruta);
                            $pdf['pdf']=$valor.'-'.$t->tit_pdf;
                        }
                    }
                    if($t['tit_antecedentes']!=''){
                        $ruta='alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$t['tit_antecedentes'];
                        if(Storage::exists($ruta)){
                            $valor= 'I'.rand(0,999999999999);
                            Storage::move($ruta, 'trash/dt/'.$valor.'-'.$t->tit_antecedentes);
                            $pdf['pdf_ant']=$valor.'-'.$t->tit_antecedentes;
                        }
                    }
                endforeach;
            }else{
                $resoluciones=Resolucion::all()->where('res_importacion','=',$importacion->imp_identificador);
                foreach ($resoluciones as $r):
                    DB::delete('delete from firmas where cod_res='.$r['cod_res']);
                    DB::delete('delete from archivados where cod_res='.$r['cod_res']);

                    $tomo=Tomo::find($r['cod_tom']);
                    if($r['res_pdf']!=''){
                        $ruta='alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$r['res_pdf'];
                        if(Storage::exists($ruta)){
                            $valor= "I-".rand(0,999999999999);
                            Storage::move($ruta, 'trash/res/'.$valor.'-'.$r->res_pdf);
                            $pdf['pdf']=$valor.'-'.$r->res_pdf;
                        }
                    }
                    if($r['res_ant']!=''){
                        $ruta='alma/dt/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$r['res_ant'];
                        if(Storage::exists($ruta)){
                            $valor= "I-".rand(0,999999999999);
                            Storage::move($ruta, 'trash/res/'.$valor.'-'.$r->res_ant);
                            $pdf['pdf_ant']=$valor.'-'.$r->res_ant;
                        }
                    }
                    $r->delete();
                endforeach;
            }
            \Session::flash('exito', 'La importación se ha deshecho correctamente');
            return redirect('listar importacionUsuario/'.$usuario->id);
    }
    public function descargar_importacion($cod_imp){
        try{
            $importacion=Importacion::find($cod_imp);
            if($importacion->imp_archivo!='') {
                $ruta = 'imports/dt/'.$importacion->imp_archivo;
                if($importacion->imp_sistema==2){
                    $ruta = 'imports/res/'.$importacion->imp_archivo;
                }

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

        }catch (\Throwable $e){
            return "No existe el archivo";
        }

    }
//=========================RESOLUCIONES==============
    public function lista_importaciones_res($id){
        if(Auth::user()->id==$id) {
            $importaciones = Importacion::all()->where('imp_id','=',$id)->where('imp_sistema','=','2')->sortByDesc('cod_imp');
            return view('resoluciones.importacion.lista_importaciones_res',compact('importaciones'));

        }else{
            //PARA NO MOSTRAR IMPORTACIONES DE OTROS USUARIOS
            return "Acceso denegado";
        }
    }
    public function verificar_importacion_res(Request $form){

            try {
                if ($form->hasFile('archivo')) {
                    //$array = Excel::toArray(new importarDB(), $form->file('archivo'));

                    $array = Excel::toArray(new importarDB(), $form->file('archivo'));
                    $identificador = Auth::user()->id . "-" . rand(0, 9999999999);
                    $banderaIden = 0;

                    $año = $array[0][0]['ano'];
                    $tipo = TomoController::tipoTomoUnitario($array[0][0]['tipo']);
                    $ruta = 'imports/res/';
                    $extension = $form->file('archivo')->getClientOriginalExtension();
                    $nombre = '';
                    $identificador = '';
                    while ($banderaIden == 0) {
                        $nombre = Auth::user()->id . '-' . rand(0, 9999999999) . '-' . strtoupper($array[0][0]['tipo']) . $año . "." . $extension;
                        $identificador = Auth::user()->id . '-' . rand(0, 9999999999) . '-' . strtoupper($array[0][0]['tipo']) . $año ;
                        if (!Storage::exists('imports/res/' . $nombre)) {
                            $banderaIden = 1;
                            \Session::put('id_import', $identificador);
                        }
                    }
                    $importado = Excel::import(new importarRESC(), $form->file('archivo'));
                    //Storage::putFileAs($ruta, $form->file('archivo'), $nombre);

                   /* $importacion = Importacion::create([
                        'imp_usuario' => Auth::user()->name,
                        'imp_id' => Auth::user()->id,
                        'imp_fecha' => date('d-m-Y H:i:s'),
                        'imp_tipo' => 'Resolución',
                        'imp_gestion' => $año,
                        'imp_usuario' => Auth::user()->name,
                        'imp_archivo' => $nombre,
                        'imp_sistema'=>2,
                        'imp_identificador' => $identificador,
                    ]);
                    */
                    \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                    return redirect('lista importaciones resolucion/' . Auth::user()->id);
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $fallas = $e->failures();
                return view('resoluciones.importacion.resultado_importacion_res', compact('fallas'));
            }

    }
    public function ver_importacion_res($cod_imp){
        $importacion=Importacion::find($cod_imp);
        $resolucionExcel = Excel::toArray(new importarDB(), 'imports/res/'.$importacion->imp_archivo);
        $resolucionImportados=Resolucion::all()->where('res_importacion','=',$importacion->imp_identificador);
        $tam=sizeof($resolucionExcel[0]);
        for($i=0;$i<$tam;$i++){
            $nro_resolucion=$resolucionExcel[0][$i]['numero'];
            $encontrado='NO';
            foreach ($resolucionImportados as $t):
                if($nro_resolucion==$t->res_numero){
                    $encontrado='SI';
                }
            endforeach;
            if($encontrado=='NO'){
                $resolucionExcel[0][$i]['importado']=0;
            }else{
                $resolucionExcel[0][$i]['importado']=1;
            }
            if($resolucionExcel[0][$i]['fecha']!=''){
                $resolucionExcel[0][$i]['fecha']=date('d/m/Y', strtotime($resolucionExcel[0][$i]['fecha']));
            }
        }
        return view('resoluciones.importacion.ver_datos_importacion_res', compact('importacion','resolucionExcel'));
    }
}
