<?php

namespace App\Http\Controllers;

use App\Imports\ImportarAPO;
use App\Models\Apoderado;
use App\Models\Apostilla;
use App\Models\Detalle_apostilla;
use App\Models\Funciones;

use App\Models\Lista_doc_apostilla;
use App\Models\Objeto;
use App\Models\Persona;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class ApostillaController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear documento apostilla - apo|editar documento apostilla - apo'], ['only' => ['fe_doc_apostilla','g_doc_apostilla']]);
        $this->middleware(['permission:habilitar documento apostilla - apo'], ['only' => ['hab_doc_apostilla']]);
        $this->middleware(['permission:eliminar documento apostilla - apo'], ['only' => ['f_eli_doc_apostilla','eli_doc_apostilla']]);

        $this->middleware(['permission:crear trámite - apo|editar trámite - apo'], ['only' => ['fe_tramite_apostilla','g_tramite_apostilla','g_apoderado_tramite_apostilla']]);
        $this->middleware(['permission:editar apoderado - apo'], ['only' => ['g_apoderado_tramite_apostilla']]);
        $this->middleware(['permission:eliminar trámite - apo'], ['only' => ['f_eli_tramite_apostilla','eli_tramite_apostilla']]);
        $this->middleware(['permission:firma trámite - apo'], ['only' => ['firmar_tramite_apostilla']]);

        $this->middleware(['permission:agregar documento - apo'],['only'=>['fe_agregar_tramite_apostilla','g_agregar_tramite_apostilla','ajax_tabla_agregar']]);
        $this->middleware(['permission:quitar doumento - apo'],['only'=>['eliminar_tramite_agregado','ajax_tabla_agregar']]);
        $this->middleware(['permission:entregar trámite - apo'],['only'=>['fe_entrega_tramite_apostilla','entrega_tramite_apostilla']]);
        $this->middleware(['permission:importar trámite - apo'],['only'=>['importar_Apostilla']]);

        $this->middleware(['permission:generar pdf - apo'],['only'=>['generar_pdf_apostilla']]);
        $this->middleware(['permission:buscar trámite - apo'],['only'=>['buscar_apostilla','fe_buscar_apostilla','ver_datos_apostilla']]);

        $this->middleware(['permission:ver reportes - apo'],['only'=>['lista_reporte_apostilla','reporte_apostilla']]);

    }
    public function l_doc_apostilla(){
        $tramites=Lista_doc_apostilla::all()->SortBy('lis_nombre');
        return view('apostilla.apostilla.l_apostilla',compact('tramites'));
    }
    public function fe_doc_apostilla($cod_lis){
        $tramite="";
        if($cod_lis!=0){
            $tramite=Lista_doc_apostilla::find($cod_lis);
        }
        return view('apostilla.apostilla.fe_tra_apostilla',compact('cod_lis','tramite'));
    }
    public function g_doc_apostilla(Request $form){
        $form->validate([
            'nombre'=>'required',
            'alias'=>'required',
            'cuenta'=>'required',
            'monto'=>'required',
        ]);

        if(isset($form['cl']) && $form['cl']!=''){
            $apostilla=Lista_doc_apostilla::find($form['cl']);
            $antiguo=json_encode($apostilla);
            $apostilla->lis_nombre=$form['nombre'];
            $apostilla->lis_cuenta=$form['cuenta'];
            $apostilla->lis_monto=$form['monto'];
            $apostilla->lis_resolucion=$form['resolucion'];
            $apostilla->lis_tipo=$form['tipo'];
            $apostilla->lis_desc=$form['desc'];
            $apostilla->lis_alias=$form['alias'];
            $apostilla->save();

            \Session::flash('exito','Se ha editado el trámite exitosamente');

            $nuevo=json_encode($apostilla);
            SessionController::write('U',$antiguo,$nuevo,'lista_doc_apostilla','4',$apostilla->cod_lis);
        }else{
            $apostilla=Lista_doc_apostilla::create([
                'lis_nombre'=>$form['nombre'],
                'lis_alias'=>$form['alias'],
                'lis_cuenta'=>$form['cuenta'],
                'lis_monto'=>$form['monto'],
                'lis_hab'=>'t',
                'lis_resolucion'=>$form['resolucion'],
                'lis_tipo'=>$form['tipo'],
                'lis_desc'=>$form['desc'],
            ]);
            \Session::flash('exito','Se ha creado el trámite exitosamente');
            $nuevo=json_encode($apostilla);
            SessionController::write('C','',$nuevo,'lista_doc_apostilla','4',$apostilla->cod_lis);
        }
        return redirect('listar documentos apostilla');
    }
    public function hab_doc_apostilla($cod_lis){
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        if($apostilla->lis_hab=='t'){
            $apostilla->lis_hab='f';
        }else{
            $apostilla->lis_hab='t';
        }
        $apostilla->save();
        \Session::flash('exito','Se ha modificado el trámite exitosamente');
        return redirect('listar documentos apostilla');
    }
    public function f_eli_doc_apostilla($cod_lis){

        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')->where('cod_lis','=',$cod_lis)->get();
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        $eliminar=true;
        if(sizeof($detalle_apostilla)>0){
            $eliminar=false;
        }
        return view('apostilla.apostilla.f_eli_apostilla',compact('apostilla','eliminar'));
    }
    public function eli_doc_apostilla(Request $form){
        $form->validate([
            'cl'=>'required'
        ]);
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')->where('cod_lis','=',$form['cl'])->get();
        if(sizeof($detalle_apostilla)>0){
            \Session::flash('error','No se puede eliminar el trámite');

        }else{
            $apostilla=Lista_doc_apostilla::find($form['cl']);
            $antiguo=json_encode($apostilla);
            SessionController::write('D',$antiguo,'','lista_doc_apostilla','4',$apostilla->cod_lis);
            $apostilla->delete();
            \Session::flash('exito','El trámite se eliminó correctamente');
        }
        return redirect('listar documentos apostilla');
    }
//====================================================================== TRAMITE DE APOSTILLA
    public function l_tramite_apostilla($fecha){
        $array_fecha=explode('-',$fecha);
        $valido=checkdate($array_fecha[1],$array_fecha[2],$array_fecha[0]);
        if($valido){
            $tramites=DB::table('apostilla.apostilla')
                ->join('public.personas','apostilla.apostilla.id_per','=','public.personas.id_per')
                ->select('personas.*','apostilla.*')
                ->where('apos_fecha_ingreso','=',$fecha)->orderByDesc('apostilla.apos_fecha_ingreso')
                ->orderByDesc('apos_numero')->get();
            return view('apostilla.tramite.l_tramite_apostilla',compact('tramites','fecha','valido'));
        }else{
            \Session::flhas('error','La fecha no es correcta');
            return view('apostilla.tramite.l_tramite_apostilla','fecha','valido');
        }

    }
    public function l_tramite_apostilla_tabla($fecha){
        $tramites=DB::table('apostilla.apostilla')
            ->join('public.personas','apostilla.apostilla.id_per','=','public.personas.id_per')
            ->select('personas.*','apostilla.*')
            ->where('apos_fecha_ingreso','=',$fecha)->orderByDesc('apostilla.apos_fecha_ingreso')
            ->orderByDesc('apos_numero')->get();
        return view('apostilla.tramite.l_tramite_apostilla_tabla',compact('tramites','fecha'));
    }
    public function fe_tramite_apostilla($cod_apos){
        $persona=array();
        $apoderado=array();
        $apostilla=array();
        $detalle_apostilla=array();

        if($cod_apos==0){
            $tramite_apostilla=array();
            return view('apostilla.tramite.fe_tramite_apostilla',compact('tramite_apostilla','apostilla','cod_apos','persona','apoderado','detalle_apostilla'));
        }else{
            $apostilla=Lista_doc_apostilla::where('lis_hab','=','t')->orderBy('lis_nombre')->get();
            $tramite_apostilla=Apostilla::find($cod_apos);
            if($tramite_apostilla){
                $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
                    ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
                    ->where('cod_apos','=',$tramite_apostilla->cod_apos)
                    ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();
                //dd($detalle_apostilla);
                $persona=Persona::find($tramite_apostilla->id_per);
                $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
                return view('apostilla.tramite.fe_tramite_apostilla',compact('apostilla','tramite_apostilla','cod_apos','persona','apoderado','detalle_apostilla'));
            }else{
                \Session::flash('error','Hubo un error en los datos proveidos');
            }
        }

    }
    public function g_tramite_apostilla(Request $form){

        $persona=array();
        $apoderado=array();
        $nuevo="";
        $form->validate([
            'ci'=>'required',
            'nombre'=>'required',
            'apellido'=>'required',
        ]);
        if($form['ci_apoderado']!='' || $form['apellido_apoderado']!='' || $form['nombre_apoderado']!=''){
            $form->validate([
                'ci_apoderado'=>'required',
                'nombre_apoderado'=>'required',
                'apellido_apoderado'=>'required',
                'tipo'=>'required',
            ]);
            $apoderado=Apoderado::where('apo_ci','=',$form['ci_apoderado'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci_apoderado'],
                    'apo_nombre'=>mb_strtoupper($form['nombre_apoderado']),
                    'apo_apellido'=>mb_strtoupper($form['apellido_apoderado']),
                    'apo_sistema'=>4,
                ]);

            }
        }
        $nuevo=$apoderado;
        $persona=Persona::where('per_ci','=',$form['ci'])->first();
        if(!$persona){
            $persona=Persona::create([
                'per_ci'=>substr($form['ci'],0,12),
                'per_nombre'=>mb_strtoupper($form['nombre']),
                'per_apellido'=>mb_strtoupper($form['apellido']),
                'per_celular'=>substr($form['celular'], 0, 8),
                'per_sistema'=>4,
            ]);
        }else{
            $persona->per_celular=$form['celular'];
            $persona->save();
        }
        $nuevo=$persona->toArray();
        if($apoderado){
            $nuevo=(object) array_merge($nuevo,$apoderado->toArray());
        }
        $maximo=DB::select('select max(apos_numero) as max from apostilla.apostilla where apos_gestion='.date('Y'));
        $numero=500000;
       // return $maximo[0];
        if($maximo[0]->max){
            $numero=((int)$maximo[0]->max+1);
        }
        $uuid=(String)Str::uuid();
        $clave=Funciones::alfanumerico(10);

        if(!$apoderado){
            $form['tipo']='';
        }
        $tramite_apostilla=Apostilla::create([
            'cod_apos'=>$uuid,
            'id_per'=>$persona->id_per,
            'apos_numero'=>$numero,
            'apos_clave'=>$clave,
            'apos_fecha_ingreso'=>date('d/m/Y H:i:s'),
            'apos_estado'=>'0',
            'apos_hab'=>'t',
            'apos_apoderado'=>$form['tipo'],
            'apos_gestion'=>date('Y'),
        ]);
        $nuevo=(object) array_merge((Array)$nuevo,$tramite_apostilla->toArray());
        $nuevo=json_encode($nuevo);
        SessionController::write('C','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);

        if($apoderado){
            $tramite_apostilla->cod_apo=$apoderado->cod_apo;
            $tramite_apostilla->save();
        }
        return redirect('editar tramite apostilla/'.$tramite_apostilla->cod_apos);
    }
    public function g_apoderado_tramite_apostilla(Request $form){

            $form->validate([
                'ci_apoderado'=>'required',
                'nombre_apoderado'=>'required',
                'apellido_apoderado'=>'required',
                'tipo'=>'required',
                'ca'=>'required',
            ]);
            $nuevo="";
            $antiguo="";
            $apoderado=Apoderado::where('apo_ci','=',$form['ci_apoderado'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$form['ci_apoderado'],
                    'apo_nombre'=>mb_strtoupper($form['nombre_apoderado']),
                    'apo_apellido'=>mb_strtoupper($form['apellido_apoderado']),
                    'apo_sistema'=>4,
                ]);
                $nuevo=$apoderado;
            }
            $tramite_apostilla=Apostilla::find($form['ca']);
            $antiguo=json_encode($tramite_apostilla);

            $tramite_apostilla->cod_apo=$apoderado->cod_apo;
            $tramite_apostilla->apos_apoderado=$form['tipo'];
            $tramite_apostilla->save();
            $nuevo=(Object)array_merge($nuevo->toArray(),$tramite_apostilla->toArray());
            $nuevo=json_encode($nuevo);
            SessionController::write('C',$antiguo,$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);

            return redirect('editar tramite apostilla/'.$tramite_apostilla->cod_apos);
    }
    public function fe_agregar_tramite_apostilla($cod_lis,$cod_apos){
        $apostilla=Lista_doc_apostilla::find($cod_lis);
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=array();
        if($tramite_apostilla->cod_apo!=''){
            $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
        };
        return view('apostilla.tramite.fe_agregar_tramite_apostilla',compact('apostilla','tramite_apostilla','apoderado','persona','cod_lis','cod_apos'));
    }
    public function g_agregar_tramite_apostilla(Request $form){
        /*
         * Estado del tramite [apos_estado]
         * 0 -> Creado
         * 1 -> Registrado
         * 2 -> Firmado
         * 3 -> Entregado
         */
        $form->validate([
            'cl'=>'required',
            'ca'=>'required',
        ]);
        $apostilla=Lista_doc_apostilla::find($form['cl']);
        $tramite_apostilla=Apostilla::find($form['ca']);
        if($tramite_apostilla->apos_estado<=1){
            if($tramite_apostilla->apos_estado==0){
                $tramite_apostilla->apos_estado=1;
                $tramite_apostilla->save();
            }
            $uuid=(String)Str::uuid();
            $maximo=DB::select('select max(dapo_numero) as max from apostilla.detalle_apostilla');
            $numero=1;
            if($maximo[0]->max){
                $numero=((int)$maximo[0]->max+1);
            }
            $documento=Detalle_apostilla::create([
                'cod_dapo'=>$uuid,
                'cod_apos'=>$form['ca'],
                'cod_lis'=>$form['cl'],
                'dapo_fecha_ingreso'=>date('d/m/Y'),
                'dapo_hab'=>'t',
                'dapo_numero'=>$numero,
            ]);

            if(isset($form['numero'])){
                $documento->dapo_numero_documento=$form['numero'];
            }
            if(isset($form['gestion'])){
                $documento->dapo_gestion_documento=$form['gestion'];
            }
            $documento->dapo_buscar_en=$apostilla->lis_tipo;
            $documento->save();

            $nuevo=json_encode($documento);
            SessionController::write('C','',$nuevo,'detalle_apostilla','4',$documento->cod_dapo);

            \Session::flash('exitoagregar','Se ha agragado el tramite correctamente');
            return redirect('ajax tabla agregar/'.$form['ca']);
        }else{
            \Session::flash('erroragregar','No se puede agregar mas documentos');
            return redirect('ajax tabla agregar/'.$form['ca']);
        }

    }
    public function ajax_tabla_agregar($cod_apos){
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$cod_apos)->orderBy('lis_nombre')
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();
        $tramite_apostilla=Apostilla::find($cod_apos);
        $fecha=date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso));
        return view('apostilla.tramite.fe_tramite_apostilla_tabla',compact('detalle_apostilla','cod_apos','fecha','tramite_apostilla'));
    }
    public function eliminar_tramite_agregado($cod_dapo){
        $detalle_apostilla=Detalle_apostilla::find($cod_dapo);
        $cod_apos=0;
        if($detalle_apostilla){
            $cod_apos=$detalle_apostilla->cod_apos;

            $antiguo=json_encode($detalle_apostilla);
            SessionController::write('D',$antiguo,'','detalle_apostilla','4',$detalle_apostilla->cod_dapo);

            $detalle_apostilla->delete();
            $detalles=Detalle_apostilla::where('cod_apos','=',$cod_apos)->first();
            if(!$detalles){
                $tramite_Apostilla=Apostilla::find($cod_apos);
                $tramite_Apostilla->apos_estado=0;
                $tramite_Apostilla->save();
            }
            \Session::flash('exito_agregar','Se ha eliminado correctamente el tramite');
        }else{
            \Session::flash('error_agregar','No se puede eliminar el documento seleccionado');
        }
        return redirect('ajax tabla agregar/'.$cod_apos);
    }
    public function generar_pdf_apostilla($cod_apos){

        $tramite_apostilla=Apostilla::find($cod_apos);
        if($tramite_apostilla->apos_qr==''){
            $tramite_apostilla->apos_qr=Funciones::valorQR(date('d'),date('m'),date('Y'),5);
            //$qr_generado='http://www.archivos.umss.edu.bo/verificar_apostilla/index.php?q='.$qr;
            $tramite_apostilla->save();
        }
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=array();
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$tramite_apostilla->cod_apos)->orderBy('lis_nombre')
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();

        if($tramite_apostilla->cod_apos!=''){
            $apoderado=Apoderado::find($tramite_apostilla->cod_apo);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('letter');
        $pdf->loadView('apostilla.tramite.tramites_vista_PDF',compact('tramite_apostilla','persona','apoderado','detalle_apostilla'));
        //return "entro";
        return $pdf->download('Tramite '.$tramite_apostilla->apos_numero.'.pdf');

    }
    public function f_eli_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $detalle_apostilla=Detalle_apostilla::where('cod_apos','=',$cod_apos)->first();
        $eliminar=true;
        if($detalle_apostilla){
            $eliminar=false;
        }
        return view('apostilla.tramite.f_eli_tramite_apostilla',compact('tramite_apostilla','persona','eliminar'));
    }
    public function eli_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required'
        ]);
        $tramite_apostilla=Apostilla::find($form['ca']);
        $detalle_apostilla=Detalle_apostilla::where('cod_apos','=',$form['ca'])->first();
        $fecha=date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso));
        if($detalle_apostilla){
            \Session::flash('error','No se puede eliminar el trámite de apostilla');
        }else{
            \Session::flash('exito','Se ha eliminado correctamente el trámite');
            $antiguo=json_encode($tramite_apostilla);
            SessionController::write('D',$antiguo,'','apostilla','4',$tramite_apostilla->cod_apos);
            $tramite_apostilla->delete();
        }
        return redirect('listar tramite apostilla/'.$fecha);
    }
    public function firmar_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $tramite_apostilla->apos_estado=2;
        $tramite_apostilla->apos_fecha_firma=date('d/m/Y');
        $tramite_apostilla->save();
        $nuevo=json_encode($tramite_apostilla);
        SessionController::write('U','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);
        return redirect('listar tramite apostilla/'.$tramite_apostilla->apos_fecha_ingreso);
    }
    public function fe_entrega_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        $apoderado=Apoderado::find($tramite_apostilla->cod_apo);

        return view('apostilla.tramite.fe_entrega_tramite_apostilla',compact('tramite_apostilla','persona','apoderado'));
    }
    public function entrega_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required',
        ]);
        /*
         * apos_entregaddo-> a quien entrega
         * A -> Apoderado
         * T -> Titular
         */
        $tramite_apostilla=Apostilla::find($form['ca']);
        if(isset($form['apo'])){
            $tramite_apostilla->apos_fecha_recojo=date('d/m/Y');
            $tramite_apostilla->apos_estado=3;
            if($form['apo']=='A'){
                $tramite_apostilla->apos_entregado='A';
            }else{
                $tramite_apostilla->apos_entregado='T';
            }
            $tramite_apostilla->save();
            $nuevo=json_encode($tramite_apostilla);
            SessionController::write('U','',$nuevo,'apostilla','4',$tramite_apostilla->cod_apos);
        }else{

        }
        return redirect('listar tramite apostilla/'.date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)));
    }
    public function importar_apostilla(Request $form){
        try {
            if ($form->hasFile('archivo')) {
                //$array = Excel::toArray(new importarDB(), $form->file('archivo'));
                $importado = Excel::import(new ImportarAPO(), $form->file('archivo'));
                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                return redirect('listar tramite apostilla/'.date('Y-m-d'));
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            return view('importacion.resultado_importacion', compact('fallas'));
        }
    }
    public function fe_buscar_apostilla(){
        return view('apostilla.buscar.fe_busqueda');
    }
    public function buscar_apostilla(Request $form)
    {
        $parametro = 0;
        $resultado=array();
        $consulta = "select * from apostilla.apostilla a join personas p on p.id_per=a.id_per where ";

        if ($form['numero'] != '') {
            $parametro = 1;
            if ($form['gestion'] != '') {
                $consulta .= " (a.apos_numero=" . $form['numero'] . " and a.apos_gestion=" . $form['gestion'] . ")";
            } else {
                $consulta .= " a.apos_numero=" . $form['numero'];
            }
        }
        if ($form['ci'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_ci='" . $form['ci'] . "'";
            } else {
                $parametro = 1;

                $consulta .= " p.per_ci='" . $form['ci'] . "'";
            }
        }
        if ($form['nombre'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_nombre like'%" . mb_strtoupper($form['nombre']) . "%'";
            } else {
                $parametro = 1;
                $consulta .= " p.per_nombre like'%" . mb_strtoupper($form['nombre']) . "%'";
            }
        }
        if ($form['apellido'] != '') {
            if ($parametro == 1) {
                $consulta .= " or p.per_apellido like '%" . mb_strtoupper($form['apellido']) . "%'";
            } else {
                $parametro = 1;
                $consulta .= " p.per_apellido like '%" . mb_strtoupper($form['apellido']) . "%'";
            }
        }

            $consulta .= " order by a.apos_fecha_ingreso ";

        if ($parametro == 1)
        {
            $resultado = DB::select($consulta);
        }
        $busqueda=$consulta;
        SessionController::write('B','',$busqueda,'apostilla','4','');
        return view('apostilla.buscar.resultado_busqueda',compact('resultado'));
    }
    public function ver_datos_apostilla($cod_apos){
        $apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($apostilla->id_per);
        $apoderado=array();
        if($apostilla->cod_apo!=''){
            $apoderado=Apoderado::find($apostilla->cod_apo);
        }
        $detalle_apostilla=DB::table('apostilla.detalle_apostilla')
            ->join('apostilla.lista_doc_apostilla','detalle_apostilla.cod_lis','=','lista_doc_apostilla.cod_lis')
            ->where('cod_apos','=',$cod_apos)
            ->where('dapo_hab','=','t')->orderBy('lis_nombre')->get();

        return view('apostilla.buscar.detalle_busqueda_apostilla',compact('apostilla','persona','apoderado','detalle_apostilla'));
    }
    public function mostrar_observacion_tramite_apostilla($cod_apos){
        $tramite_apostilla=Apostilla::find($cod_apos);
        $persona=Persona::find($tramite_apostilla->id_per);
        return view('apostilla.tramite.fe_observacion',compact('tramite_apostilla','persona'));
    }
    public function g_observacion_tramite_apostilla(Request $form){
        $form->validate([
            'ca'=>'required',
        ]);
        $tramite_apostilla=Apostilla::find($form['ca']);
        $tramite_apostilla->apos_obs=$form['observacion'];
        $tramite_apostilla->save();
        return $tramite_apostilla->apos_obs;
    }
    //============================REPORTES
    public function lista_reporte_apostilla(){
        $lista=Lista_doc_apostilla::where('lis_hab','=','t')->get();
        $consulta="select lis_alias as nombre, count(cod_dapo) as cantidad from apostilla.lista_doc_apostilla l join apostilla.detalle_apostilla d on l.cod_lis=d.cod_lis group by nombre;";
        $resultado=DB::select($consulta);
        return view('apostilla.reporte.reporte_apostilla',compact('resultado','lista'));
    }
    public function reporte_apostilla(Request $form){

        $mensaje="";
        $fecha=$this->construirFecha($form['dia'],$form['mes'],$form['gestion'],0);
        $fecha_final=$this->construirFecha($form['dia_final'],$form['mes_final'],$form['gestion_final'],1);
        $documento=$form['documento'];
        $resultado=array();
        $consulta="";

        if($fecha==1 || $fecha_final==1){
            $mensaje="Error en las fechas";
        }else{

            if($fecha==0){
                if($form['documento']==''){
                    $consulta="select lis_nombre as nombre, count(cod_dapo) as cantidad
                                from apostilla.lista_doc_apostilla l join apostilla.detalle_apostilla d on l.cod_lis=d.cod_lis
                                group by lis_nombre;";
                }else{
                    if($form['documento']=='tramites'){
                        $consulta="select apos_gestion as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a group by apos_gestion
                                    order by apos_gestion";
                    }else{
                        $consulta="select EXTRACT(YEAR FROM a.dapo_fecha_ingreso) as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  a.cod_lis=".$form['documento']."
                                    group by nombre order by nombre";
                    }
                }

            }else{
                if($fecha_final==0){
                    if($form['documento']=='' || $form['documento']!='tramites'){
                        $aux_condicion=($form['documento']!='')?" a.cod_lis=".$form['documento']." and ":"";

                        if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                            $consulta="select lis_alias as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso='".$fecha."'
                                    group by nombre order by cantidad;";
                        }else{

                            if($form['mes']!='' && $form['gestion']!=''){

                                $month     = $form['gestion'].'-'.$form['mes'];
                                $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));

                                $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                $consulta="select dapo_fecha_ingreso as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre;";

                            }else{

                                $consulta="select to_char(dapo_fecha_ingreso, 'Month') as nombre,EXTRACT(MONTH from dapo_fecha_ingreso) as alias, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='01/01/".$form['gestion']."' and dapo_fecha_ingreso<='31/12/".$form['gestion']."'
                                    group by alias,nombre order by alias;";
                            }
                        }

                    }else{

                        if($form['documento']=='tramites'){
                            if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){

                                $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso='".$fecha."'
                                    group by nombre;";
                            }else{

                                if($form['mes']!='' && $form['gestion']!=''){
                                    $month     = $form['gestion'].'-'.$form['mes'];
                                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                    $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                    $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre";

                                }else{
                                    if($form['gestion']!=''){
                                        $consulta="select extract(MONTH from apos_fecha_ingreso) as mes, to_char(apos_fecha_ingreso,'Month') as nombre, count(cod_apos) as cantidad
                                                   from apostilla.apostilla a
                                                    where  apos_gestion='".$form['gestion']."'
                                                    group by mes, nombre order by mes";
                                    }
                                }
                            }
                        }
                    }

                }else{
                    if($form['documento']=='' || $form['documento']!='tramites'){
                        $aux_condicion=($form['documento']!='')?" a.cod_lis=".$form['documento']." and ":"";

                        if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                            if($form['dia_final']!='' && $form['mes_final']!='' && $form['gestion_final']!=''){
                                    $fechafinal=$form['dia_final'].'/'.$form['mes_final'].'/'.$form['gestion_final'];
                                    $consulta="select dapo_fecha_ingreso as nombre, count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre;";
                            }else{
                                $mensaje='Error en la fecha final';
                            }
                        }else{
                            if($form['mes']!='' && $form['gestion']!=''){
                                if($form['mes_final']!='' && $form['gestion_final']!=''){

                                    $month     = $form['gestion_final'].'-'.$form['mes_final'];
                                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                    $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                    $consulta="select (EXTRACT(YEAR from dapo_fecha_ingreso) ||'-'|| to_char(dapo_fecha_ingreso, 'Month')) as nombre,
                                                        EXTRACT(MONTH from dapo_fecha_ingreso) as mes,EXTRACT(YEAR from dapo_fecha_ingreso) as gestion,
                                                            count(cod_dapo) as cantidad
                                    from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                    where  ".$aux_condicion." dapo_fecha_ingreso>='".$fecha."' and dapo_fecha_ingreso<='".$fechafinal."'
                                    group by gestion,mes,nombre order by gestion,mes,nombre;";
                                }else{
                                    $mensaje='Error en la fecha final';
                                }
                            }else{
                                if($form['gestion']!=''){
                                    if($form['gestion_final']){
                                        $consulta="select EXTRACT(YEAR from dapo_fecha_ingreso) as nombre, count(cod_dapo) as cantidad
                                        from apostilla.detalle_apostilla a join apostilla.lista_doc_apostilla d on a.cod_lis=d.cod_lis
                                        where  ".$aux_condicion." dapo_fecha_ingreso>='01/01/".$form['gestion']."' and dapo_fecha_ingreso<='31/12/".$form['gestion_final']."'
                                        group by nombre order by nombre;";
                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }
                                }else{

                                }
                            }
                        }

                    }else{

                        if($form['documento']=='tramites'){

                            if($form['dia']!='' && $form['mes']!='' && $form['gestion']!=''){
                                if($form['dia_final']!='' && $form['mes_final']!='' && $form['gestion_final']!=''){
                                    $fechafinal=$form['dia_final'].'/'.$form['mes_final'].'/'.$form['gestion_final'];
                                    $consulta="select apos_fecha_ingreso as nombre, count(cod_apos) as cantidad
                                    from apostilla.apostilla a
                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                    group by nombre order by nombre";
                                }else{
                                    $mensaje='Error en la fecha final';
                                }

                            }else{
                                if($form['mes']!='' && $form['gestion']!=''){
                                    if($form['mes_final']!='' && $form['gestion_final']!=''){
                                        $month     = $form['gestion_final'].'-'.$form['mes_final'];
                                        $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                                        $fechafinal = date('d/m/Y', strtotime("{$aux} - 1 day"));

                                        $consulta="select (apos_gestion ||'-'||to_char(apos_fecha_ingreso, 'Month'))  as nombre, apos_gestion as gestion,
                                                            EXTRACT(MONTH from apos_fecha_ingreso) as mes,count(cod_apos) as cantidad
                                                    from apostilla.apostilla a
                                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='".$fechafinal."'
                                                    group by gestion, mes,nombre  order by gestion,mes";

                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }

                                }else{
                                    if($form['gestion']!=''){
                                        if($form['gestion_final']!=''){
                                            $consulta="select apos_gestion as nombre,count(cod_apos) as cantidad
                                                    from apostilla.apostilla a
                                                    where  apos_fecha_ingreso>='".$fecha."' and apos_fecha_ingreso<='31/12/".$form['gestion_final']."'
                                                    group by nombre  order by nombre";
                                        }else{
                                            $mensaje='Error en la fecha final';
                                        }
                                    }else{
                                        $mensaje='Error en la fecha final';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if($consulta!=''){
            $resultado=DB::select($consulta);
        }else{
            \Session::flash('error',$mensaje.", No se puede generar el reporte");
        }
        if($form['pdf']=='on'){

            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('letter');
            $pdf->loadView('apostilla.reporte.reporte_apostilla_PDF',compact('resultado','fecha','fecha_final','documento','mensaje','form'));
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf ->get_canvas();
            $canvas->page_text(495, 96, 'Página {PAGE_NUM} de {PAGE_COUNT}', null, 8, array(0, 0, 0));
            return $pdf->download('Reporte.pdf');

        }else{
            return view('apostilla.reporte.panel_estadistico_apostilla',compact('resultado','fecha','fecha_final','documento','mensaje','form'));
        }

    }

    public function construirFecha($dia,$mes,$gestion,$final){
        /*
         * return 0 -> sin fecha
         *        1 -> error en la fecha
         */
        $fecha='';
        if($final==0){
            if($dia!='' && $mes!='' && $gestion!=''){
                $fecha=$dia."/".$mes."/".$gestion;
            }else{
                if($mes!='' && $gestion!=''){
                    $dia=1;
                    $fecha='01/'.$mes.'/'.$gestion;

                }else{
                    if($gestion!=''){

                        $dia=1;
                        $mes=1;
                        $fecha='01/01/'.$gestion;

                    }else{
                        return "0";
                    }
                }
            }
        }else{
            if($dia!='' && $mes!='' && $gestion!=''){
                $fecha=$dia."/".$mes."/".$gestion;
            }else{
                if($mes!='' && $gestion!=''){
                    $month     = $gestion.'-'.$mes;
                    $aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
                    $fecha = date('d/m/Y', strtotime("{$aux} - 1 day"));
                    $mes=date('m',strtotime($fecha));
                    $dia=date('j',strtotime($fecha));
                }else{
                    if($gestion!=''){
                        $dia=31;
                        $mes=12;
                        $fecha='31/12/'.$gestion;
                    }else{
                        return "0";
                    }
                }
            }
        }
        if(checkdate($mes,$dia,$gestion)){
            return $fecha;
        }else{
            return 1;
        }
    }
}
