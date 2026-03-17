<?php

namespace App\Http\Controllers;

use App\Exports\ExportSinTomo;

use App\Models\Carrera;
use App\Models\Evento_bitacora;
use App\Models\Facultad;
use App\Http\Requests\TomoRequest;
use App\Models\Titulo;
use App\Models\Tomo;
use App\Models\Tomo_carrera;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Barryvdh\DomPDF\PDF;

use Maatwebsite\Excel\Facades\Excel;

class TomoController extends Controller
{

    public function __construct(){
            $this->middleware(['permission:crear tomo - dyt'],['only'=>['GuardarTomo']]);
            $this->middleware(['permission:editar tomo - dyt'],['only'=>['GuardarTomo']]);
            $this->middleware(['permission:eliminar tomo - dyt'],['only'=>['EliminarTomo']]);

            $this->middleware(['permission:asignar carrera - dyt'],['only'=>['GuardarCarreraTomo']]);
            $this->middleware(['permission:eliminar carrera tomo - dyt'],['only'=>['e_carrera']]);
            $this->middleware(['permission:consolidar tomo - dyt'],['only'=>['cerrarTomo']]);


    }
    public function listarTomos($tipo,$gestion){

        $tomo=Tomo::all()->where('tom_gestion',$gestion)->where('tom_tipo','=',"$tipo")
                            ->sortBy('tom_numero');
        $tipo_completo=$this->tipoTomo($tipo);
        $carrera=Carrera::all()->sortBy('car_nombre');
        return view('diplomas.tomo.l_tomos',compact('tomo','tipo','gestion','tipo_completo','carrera'));
    }
    public function GuardarTomo(TomoRequest $form){
        $tipo=$form['tipo'];
        $tomo=Tomo::find($form['ct']);
        $antiguo=json_encode($tomo);
        if(isset($form['ct'])){
            if($tomo->tom_cerrado!='t'){
                $tomos=DB::table('tomos')->where('tom_gestion','=',$tomo['tom_gestion'])
                    ->where('tom_numero','=',$form['n_tomo'])
                    ->where('tom_tipo','=',$tomo['tom_tipo'])
                    ->where('cod_tom','<>',$form['ct'])
                    ->select('tom_numero','cod_tom')->get();
                if(sizeof($tomos)<1){
                    if(!Storage::exists('alma/dt/'.$tipo.'/'.$tomo['tom_gestion'].'/'.$form['n_tomo'])) {
                        if (!Storage::exists('alma/dt/' . $tipo . '/' . $tomo['tom_gestion'] . '/' . $tomo['tom_numero'])) {
                            Storage::makeDirectory('alma/dt/' . $tipo . '/' . $tomo['tom_gestion'] . '/' . $tomo['tom_numero']);
                        } else {
                            Storage::move("alma/dt/" . $tomo['tom_tipo'] . "/" . $tomo['tom_gestion'] . "/" . $tomo['tom_numero'] . "/", "alma/dt/" . $tomo['tom_tipo'] . "/" . $tomo['tom_gestion'] . "/" . $form['n_tomo'] . "/");
                        }
                    }else{
                        if(!$form['n_tomo']==$tomo->tom_numero){
                            \Session::flash('error','No se puede actualizar, debido a que el almacenamiento para el tomo '.$form['n_tomo'].' ya existe ');
                            return redirect('/tomo/'.$tipo.'/'.$tomo['tom_gestion']);
                        }
                    }
                    $tomo->tom_numero=$form['n_tomo'];
                    $tomo->tom_rango=$form['r_menor'].'-'.$form['r_mayor'];
                    $tomo->tom_obs=$form['obs'];
                    $tomo->save();
                    \Session::flash('exito','El tomo se ha actualizado exitosamente');

                    $nuevo=json_encode($tomo);
                    SessionController::write('UPDATE',$antiguo,$nuevo,'Tomos','1',$tomo->cod_tom);
                }else{
                    \Session::flash('error','No se puede actualizar, debido a que el tomo '.$form['n_tomo'].' ya existe en la gestión '.$tomo['tom_gestion']);
                }
                return redirect('/tomo/'.$tipo.'/'.$tomo['tom_gestion']);
            }else{
                \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar');
                return redirect('/tomo/'.$tipo.'/'.$tomo->tom_gestion);
            }
        }else{
            $tomos=DB::table('tomos')->where('tom_gestion','=',$form['gestion'])
                    ->where('tom_numero','=',$form['n_tomo'])
                    ->where('tom_tipo','=',$form['tipo'])->get();

            if(sizeof($tomos)<1){
                if(!Storage::exists('alma/dt/'.$tipo.'/'.$form['gestion'])){
                    //Storage::makeDirectory('alma/dt/'.$tipo.'/'.$form['gestion']);
                    Storage::makeDirectory('alma/dt/'.$tipo.'/'.$form['gestion'].'/'.$form['n_tomo']);
                    $tomo=Tomo::create([
                        'tom_numero'=>$form['n_tomo'],
                        'tom_gestion'=>$form['gestion'],
                        'tom_rango'=>$form['r_menor'].'-'.$form['r_mayor'],
                        'tom_obs'=>$form['obs'],
                        'tom_tipo'=>$form['tipo'],
                        'tom_usr'=>Auth::user()->id,
                    ]);
                    if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
                        $cod_tom=$tomo->cod_tom; $cod_car=$form['car'];
                        DB::insert("insert into tomo_carreras (cod_tom,cod_car)values($cod_tom,$cod_car)");
                    }
                    \Session::flash('exito','El tomo '.$tomo->tom_numero.' se ha creado exitosamente en  la gestión '.$tomo->tom_gestion);
                    $nuevo=json_encode($tomo);
                    SessionController::write('CREATE','',$nuevo,'Tomos','1',$tomo->cod_tom);
                }else{
                    if(!Storage::exists('alma/dt/'.$tipo.'/'.$form['gestion'].'/'.$form['n_tomo'])){
                        Storage::makeDirectory('alma/dt/'.$tipo.'/'.$form['gestion'].'/'.$form['n_tomo']);
                        $tomo=Tomo::create([
                            'tom_numero'=>$form['n_tomo'],
                            'tom_gestion'=>$form['gestion'],
                            'tom_rango'=>$form['r_menor'].'-'.$form['r_mayor'],
                            'tom_obs'=>$form['obs'],
                            'tom_tipo'=>$form['tipo'],
                            'tom_usr'=>Auth::user()->id,
                        ]);
                        if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
                            $cod_tom=$tomo->cod_tom; $cod_car=$form['car'];
                            DB::insert("insert into tomo_carreras (cod_tom,cod_car)values($cod_tom,$cod_car)");
                        }
                        \Session::flash('exito','El tomo '.$tomo->tom_numero.' se ha creado exitosamente en  la gestión '.$tomo->tom_gestion);
                        $nuevo=json_encode($tomo);
                        SessionController::write('CREATE','',$nuevo,'Tomos','1',$tomo->cod_tom);
                    }
                    else{
                        \Session::flash('error','No se puede crear, debido a que el almacenamiento para el tomo '.$form['n_tomo'].' ya existe');
                    }
                }
            }else{
                \Session::flash('error','No se puede crear, debido a que el tomo '.$form['n_tomo'].' ya existe');
            }
            return redirect('/tomo/'.$tipo.'/'.$form['gestion']);
        }
    }
    public function tipoTomo($tipo){
        switch ($tipo){
            case 'da':return "Diplomas Académicos";break;
            case 'ca':return "Certificados Académicos";break;
            case 'db':return "Diplomas de Bachiller";break;
            case 'tp':return "Títulos Profesionales ";break;
            case 'tpos':return "Titulos de Posgrado";break;
            case 'di':return "Diplomados";break;
            case 're':return "Reválidas";break;
            case 'su':return "Certificados Supletorios";break;
        }
    }
    public  static function tipoTomoUnitario($tipo){
        switch ($tipo){
            case 'da':return "Diploma Académico";break;
            case 'ca':return "Certificado Académico";break;
            case 'db':return "Diploma de Bachiller";break;
            case 'tp':return "Título Profesional ";break;
            case 'tpos':return "Titulo de Posgrado";break;
            case 'di':return "Diplomado";break;
            case 're':return "Reválida";break;
            case 'su':return "Certificados Supletorio";break;

        }
    }
    public function fe_tomo($id){
        $tomo=Tomo::find($id);
        $gestion=$tomo->tom_gestion;
        $tipo=$tomo->tom_tipo;
        return view('diplomas.tomo.editar_tomo',compact('tomo','gestion','tipo'));
    }
    public function fe_carrera($id){
        $tomo=Tomo::find($id);

        $carrera=DB::table('tomo_carreras')
            ->join('carreras','tomo_carreras.cod_car','=','carreras.cod_car')
            ->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->where('cod_tom','=',$id)
            ->select('car_nombre','carreras.cod_car','facultads.cod_fac','fac_nombre','cod_tcar')
            ->get();
        $n_carrera=Carrera::all()->sortBy('car_nombre');
        $facultad=Facultad::all()->sortBy('fac_nombre');
        $gestion=$tomo->tom_gestion;
        $tipo=$tomo->tom_tipo;
        $tipo_completo=$this->tipoTomo($tipo);
        return view('diplomas.tomo.editar_carrera',compact('tomo','gestion','tipo','tipo_completo','carrera','n_carrera','facultad'));
    }
    public function añadir_carrera($cod_tom,$accion){
        $tomo=Tomo::find($cod_tom);
        $tipo=$tomo->tom_tipo;
        $tipo_completo=$this->tipoTomo($tipo);
        //$carreras=DB::select("select * from carreras where cod_car not in (select cod_car from tomo_carreras where cod_tom=$cod_tom)");
        $carreras=DB::select("select carreras.*,facultads.* from carreras, facultads where carreras.cod_fac=facultads.cod_fac and cod_car not in (select cod_car from tomo_carreras where cod_tom=$cod_tom)
                                                       order by fac_abreviacion,car_nombre ASC");

        return view('diplomas.titulo.editar_carrera',compact('tomo','carreras','tipo_completo','accion'));
    }
    public function GuardarCarreraTomo(Request $form){

        $tomo=Tomo::find($form['ct']);
        if($tomo->tom_cerrado!='t'){
            $tomoCar=Tomo_carrera::create([
                'cod_tom'=>$form['ct'],
                'cod_car'=>$form['cc']
            ]);
            if($form['c']!=1){
                \Session::flash('exito','Se ha añadido con exito la carrera');
                return redirect("fe_car/".$form['ct']);
            }else{
                $carreras=DB::table('carreras')
                    ->join('tomo_carreras','carreras.cod_car','=','tomo_carreras.cod_car')
                    ->where('tomo_carreras.cod_tom','=',$form['ct'])
                    ->select('*')->get();
                $option="<select class='form-control form-control-sm' name='car' id='car'>";
                foreach ($carreras as $c):
                    $option.="<option value='$c->cod_car'>".$c->car_nombre."</option>";
                endforeach;
                $option.="<select/>";
                return $option;
            }
        }else{
            if($form['c']!=1) {
                \Session::flash('error', 'El tomo ' . $tomo->tom_numero . ' ya esta consolidado, no se puede modificar');
                return redirect("fe_car/" . $tomo->cod_tom);
            }
        }
    }
    public function cargar_carrera($cod_tom){
        $carreras=DB::table('carreras')
                    ->join('tomo_carreras','carreras.cod_car','=','tomo_carreras.cod_car')
                    ->where('tomo_carreras.cod_tom','=',$cod_tom)
                    ->select('*')->get();
        dd($carreras);

    }
    public function GuardarFacultadTomo(Request $form){
        $tomo=Tomo::find($form['ct']);
        $carreras=Carrera::all()->where('cod_fac','=',$form['cc']);

        if($tomo->tom_cerrado!='t') {
            foreach ($carreras as $c) {
                $tomoCar=Tomo_carrera::create([
                    'cod_tom'=>$form['ct'],
                    'cod_car'=>$c->cod_car,
                ]);
            }
            \Session::flash('exito','Se ha añadido con exito las carreras de la facultad');
            return redirect("fe_car/".$form['ct']);
        }else{
            \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar');
            return redirect("fe_car/".$tomo->cod_tom);
        }
    }
    public function e_carrera($id){
        $tomo=Tomo::find($id);
        $tomo_car=Tomo_carrera::find($id);
        $cod_tom=$tomo_car['cod_tom'];
        $tomo=Tomo::find($cod_tom);
        if($tomo->tom_cerrado!='t'){
            $tomo_car->delete();
            \Session::flash('exito','Se eliminado exitosamente la carrera');
            return redirect("fe_car/".$cod_tom);
        }else{
            \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar');
            return redirect("fe_car/".$tomo->cod_tom);
        }

    }
    public function EliminarTomo (Request $form){
        $tomo=Tomo::find($form['ct']);
        $titulos=DB::select('select cod_tit from titulos where cod_tom='.$form['ct']);
        if(sizeof($titulos)>0){
            \Session::flash('error','El tomo '.$tomo['tom_numero'].' tiene títulos registrados, no se puede eliminar');
            return redirect("tomo/".$tomo['tom_tipo']."/".$tomo['tom_gestion']);
        }else{
            if(Storage::exists('alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'])) {
                $files=Storage::files('alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero']);
                if(sizeof($files)<1){
                    Storage::deleteDirectory('alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero']);
                    \Session::flash('exito','Se eliminado exitosamente el tomo');
                    $antiguo=json_encode($tomo);
                    DB::delete('delete from tomo_carreras where cod_tom ='.$form['ct']);
                    $tomo->delete();
                    SessionController::write('D',$antiguo,'','Tomos','1',$tomo->cod_tom);
                    \Session::flash('exito','Se eliminado exitosamente el tomo');
                    return redirect("tomo/".$tomo['tom_tipo']."/".$tomo['tom_gestion']);
                }else{
                    \Session::flash('error','El tomo '.$tomo['tom_numero'].' tiene documentos registrados, no se puede eliminar');
                    return redirect("tomo/".$tomo['tom_tipo']."/".$tomo['tom_gestion']);
                }
            }else{
                DB::delete('delete from tomo_carreras where cod_tom ='.$form['ct']);
                $tomo->delete();
                $nuevo=json_encode($tomo);
                SessionController::write('D','',$nuevo,'Tomo','1',$tomo->cod_tom);
                \Session::flash('exito','Se eliminado exitosamente el tomo');
                return redirect("tomo/".$tomo['tom_tipo']."/".$tomo['tom_gestion']);
            }
        }
    }
    public function f_cerrarTomo($cod_tom){
        $tomo=Tomo::find($cod_tom);
        return view('diplomas.tomo.fc_tomo',compact('tomo'));
    }
    public function cerrarTomo(Request $form){
        $tomo=Tomo::find($form['ct']);
        $tomo->tom_cerrado='t';
        $tomo->save();
        \Session::flash('exito','Se ha consolidado exitosamente el tomo');
        return redirect('tomo/'.$tomo->tom_tipo.'/'.$tomo->tom_gestion);
    }
    public function imprimir_lista($cod_tomo){
        $tomo=Tomo::find($cod_tomo);
        $tipo=$tomo['tom_tipo'];
        $gestion=$tomo['tom_gestion'];
        $titulo="";
        if($tomo->tom_numero==0){
            return (new ExportSinTomo($tomo->cod_tom,$tomo->tom_tipo))->download('Sin-tomo-'.strtoupper($tomo->tom_tipo).'-'.$tomo->tom_gestion.'.xlsx');
        }else{
            if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
                $titulo = DB::table('titulos')
                    ->join('personas', 'titulos.id_per', '=', 'personas.id_per')
                    ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                    ->leftJoin('diploma_academicos', 'titulos.cod_tit', '=', 'diploma_academicos.cod_tit')
                    ->leftJoin('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                    ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                    ->leftJoin('modalidads', 'titulos.cod_mod', '=', 'modalidads.cod_mod')
                    ->where('cod_tom', '=', $cod_tomo)
                    ->select('tit_nro_titulo', 'tit_nro_folio', 'titulos.cod_tit', 'per_apellido', 'tit_fecha_emision', 'tit_pdf', 'tit_antecedentes','nac_codigo',
                        'tit_obs', 'per_nombre','per_ci','per_pasaporte','car_abreviacion', 'mod_nombre', 'fac_abreviacion','tit_revalida', 'tit_usr')
                    ->orderBy('fac_abreviacion', 'ASC')
                    ->orderBy('car_abreviacion', 'ASC')
                    ->orderBy('per_apellido', 'ASC')
                    ->orderBy('per_nombre', 'ASC')
                    ->get();

            }else{
                if($tipo=='db' || $tipo=='re'){

                    $titulo=DB::table('titulos')
                        ->join('personas','titulos.id_per','=','personas.id_per')
                        ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                        ->where('cod_tom','=',$cod_tomo)
                        ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','tit_revalida','nac_codigo',
                            'tit_obs','tit_pdf','tit_antecedentes','tit_usr','per_ci','per_pasaporte')
                        ->orderBy('tit_nro_titulo','ASC')
                        ->get();

                }else{
                    if($tipo=='tpos' || $tipo=='di'){
                        $titulo=DB::table('titulos')
                            ->join('personas','titulos.id_per','=','personas.id_per')
                            ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                            ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                            ->where('cod_tom','=',$cod_tomo)
                            ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','tit_titulo','nac_codigo',
                                'tit_obs','per_nombre','mod_nombre','per_ci','per_pasaporte','tit_pdf','tit_antecedentes','tit_usr')
                            ->orderBy('tit_nro_titulo','ASC')
                            ->get();
                    }else{
                        if($tipo=='su'){
                            $titulo=DB::table('titulos')
                                ->join('personas','titulos.id_per','=','personas.id_per')
                                ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                                ->where('cod_tom','=',$cod_tomo)
                                ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','tit_revalida','nac_codigo',
                                    'tit_obs','tit_pdf','tit_antecedentes','tit_usr','per_ci','per_pasaporte')
                                ->orderBy('per_apellido','ASC')
                                ->orderBy('per_nombre','ASC')
                                ->get();
                        }
                    }
                }
            }
            $tipo_completo=$this->tipoTomo($tomo->tom_tipo);
            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('legal');
            $pdf->loadView('diplomas.tomo.listaPDF',compact('tomo','titulo','gestion','tipo','tipo_completo'));
            return $pdf->download('Tomo '.$tomo['tom_numero'].' '.$tomo['tom_tipo'].' - '.$tomo['tom_gestion'].'.pdf');
        }
    }
    public function f_asignar_rango_tomo(Request $form){
        $form->validate([
           'final'=>'required|numeric',
           'tomo'=>'required|numeric',
            'ct'=>'required|numeric',
        ]);
        $exito=1;
        $tomo=Tomo::find($form['ct']);
        $titulos=$this->obtener_rango($tomo->tom_tipo,$tomo->cod_tom,$form['final']);
        $tomoAsignado=Tomo::all()->where('tom_numero','=',$form['tomo'])
            ->where('tom_tipo','=',$tomo->tom_tipo)
            ->where('tom_gestion','=',$tomo->tom_gestion)->first();
        if($tomoAsignado){
            if($tomoAsignado->tom_cerrado=='t'){
                \Session::flash('errorf','El tomo esta cerrado');
                $exito=0;
            }
        }
        return view('diplomas.titulo.f_asignacion_rango_tomo',compact('titulos','exito','form','tomoAsignado','tomo'));
    }
    public function asignar_rango_tomo(Request $form){
        $form->validate([
            'ct'=>'required|numeric',
            'nt'=>'required|numeric',
            'final'=>'required|numeric',
        ]);
        $tomo=Tomo::find($form['ct']);
        $titulos=$this->obtener_rango($tomo->tom_tipo,$tomo->cod_tom,$form['final']);
        $tomoAsignado=Tomo::all()->where('tom_numero','=',$form['nt'])
            ->where('tom_tipo','=',$tomo->tom_tipo)
            ->where('tom_gestion','=',$tomo->tom_gestion)->first();
        if($tomoAsignado){
            if($tomoAsignado->tom_cerrado!='t'){
                foreach ($titulos as $t):
                    DB::update("update titulos set cod_tom=".$tomoAsignado->cod_tom." where cod_tit=".$t->cod_tit);
                endforeach;
                \Session::flash('exito','Se ha asignado exitosamente el rango de titulos');
            }else{
                \Session::flash('error','No se puede asignar debido a que el tomo esta consolidad');
            }
        }else{
            $tomoAsignado=Tomo::create([
               'tom_numero'=>$form['nt'],
               'tom_gestion'=>$tomo->tom_gestion,
               'tom_tipo'=>$tomo->tom_tipo,
                'tom_usr'=>Auth::user()->id,
            ]);
            foreach ($titulos as $t):
                DB::update("update titulos set cod_tom=".$tomoAsignado->cod_tom." where cod_tit=".$t->cod_tit);
            endforeach;
            \Session::flash('exito','Se ha asignado exitosamente el rango de titulos');
        }
        return redirect('sintomo/'.$tomo->tom_gestion."/".$tomo->tom_tipo);
    }
    public function obtener_rango($tipo,$cod_tomo,$limite){
        $titulo=array();
        if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
            $titulo = DB::table('titulos')
                ->join('personas', 'titulos.id_per', '=', 'personas.id_per')
                ->join('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                ->leftJoin('diploma_academicos', 'titulos.cod_tit', '=', 'diploma_academicos.cod_tit')
                ->leftJoin('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                ->leftJoin('modalidads', 'titulos.cod_mod', '=', 'modalidads.cod_mod')
                ->where('cod_tom', '=', $cod_tomo)
                ->select('tit_nro_titulo', 'tit_nro_folio', 'titulos.cod_tit', 'per_apellido', 'tit_fecha_emision', 'tit_pdf', 'tit_antecedentes','nac_codigo',
                    'tit_obs', 'per_nombre','per_ci','per_pasaporte','car_abreviacion', 'mod_nombre', 'fac_abreviacion','tit_revalida', 'tit_usr')
                ->orderBy('fac_abreviacion', 'ASC')
                ->orderBy('car_abreviacion', 'ASC')
                ->orderBy('per_apellido', 'ASC')
                ->orderBy('per_nombre', 'ASC')
                ->limit($limite)
                ->get();

        }else{
            if($tipo=='db' || $tipo=='re'){

                $titulo=DB::table('titulos')
                    ->join('personas','titulos.id_per','=','personas.id_per')
                    ->join('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                    ->where('cod_tom','=',$cod_tomo)
                    ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','tit_revalida','nac_codigo',
                        'tit_obs','tit_pdf','tit_antecedentes','tit_usr','per_ci','per_pasaporte')
                    ->orderBy('tit_nro_titulo','ASC')
                    ->limit($limite)
                    ->get();

            }else{
                if($tipo=='tpos' || $tipo=='di'){
                    $titulo=DB::table('titulos')
                        ->join('personas','titulos.id_per','=','personas.id_per')
                        ->join('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                        ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                        ->where('cod_tom','=',$cod_tomo)
                        ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','tit_titulo','nac_codigo',
                            'tit_obs','per_nombre','mod_nombre','per_ci','per_pasaporte','tit_pdf','tit_antecedentes','tit_usr')
                        ->orderBy('tit_nro_titulo','ASC')
                        ->limit($limite)
                        ->get();
                }else{
                    if($tipo=='su'){
                        $titulo=DB::table('titulos')
                            ->join('personas','titulos.id_per','=','personas.id_per')
                            ->join('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                            ->where('cod_tom','=',$cod_tomo)
                            ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','tit_revalida','nac_codigo',
                                'tit_obs','tit_pdf','tit_antecedentes','tit_usr','per_ci','per_pasaporte')
                            ->orderBy('per_apellido','ASC')
                            ->orderBy('per_nombre','ASC')
                            ->limit($limite)
                            ->get();
                    }
                }
            }
        }
        return $titulo;
    }
}
