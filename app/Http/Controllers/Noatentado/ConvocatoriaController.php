<?php

namespace App\Http\Controllers\Noatentado;

use App\Http\Controllers\SessionController;
use App\Models\Cargo;
use App\Models\CargoConvocatoria;
use App\Models\D_tramita;
use App\Models\Facultad;
use App\Models\Carrera;
use App\Models\Funciones;
use App\Models\Noatentado\Cargo_convocatoria;
use App\Models\Noatentado\Noatentado;
use App\Models\Objeto;
use App\Models\PersonaSolicitudCargo;
use App\Models\Unidad;
use App\Models\Noatentado\Convocatoria;
use App\Models\Convocatorias;
use App\Models\SolicitudCargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class ConvocatoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa'], ['only' => ['fe_convocatoria', 'g_convocatoria', 'fe_cargos_convocatoria', 'g_cargo',
            'eli_cargo', 'actualizar_cargos', 'fe_unidad', 'lista_unidad', 'g_unidad']]);
        $this->middleware(['permission:eliminar convocatoria - noa'], ['only' => ['fe_eli_convocatoria', 'eli_convocatoria']]);
    }

    public function l_convocatoria($gestion){
        //$convocatorias=$this->convocatorias();
        $convocatorias=Convocatoria::where('con_gestion','=',$gestion)->where('con_hab','=','t')->orderByDesc('cod_con')->get();

        return view('servicios.no_atentado.convocatoria.l_convocatoria',compact('convocatorias','gestion'));
    }
    public function l_tabla_convocatoria($gestion){
        $convocatorias=Convocatoria::where('con_gestion','=',$gestion)->where('con_hab','=','t')->orderByDesc('cod_con')->get();
        return view('servicios.no_atentado.convocatoria.l_tabla_convocatoria',compact('convocatorias','gestion'));
    }
    public function fe_convocatoria($cod_con){
        $convocatoria=array();
        $cargos=array();
        if($cod_con!='0'){
            $convocatoria=Convocatoria::find($cod_con);
            $cargos=Cargo_convocatoria::where('cod_con','=',$convocatoria->cod_con)->orderBy('carg_nombre')->get();
        }
        return view('servicios.no_atentado.convocatoria.fe_convocatoria',compact('convocatoria','cargos'));
    }
    public function g_convocatoria(Request $form)
    {

        if(isset($form['cc'])){
            $convocatoria=Convocatoria::find($form['cc']);
            $antiguo=$convocatoria->toArray();;
            $convocatoria->con_nombre=$form['titulo'];
            $convocatoria->con_fecha_publicacion=$form['fi_convocatoria'];
            $convocatoria->con_fecha_entrega=$form['ff_convocatoria'];
            $convocatoria->con_fecha_eleccion=$form['fc_convocatoria'];
            if(date('Y',strtotime($form['fi_convocatoria']))>2022){
                if($convocatoria->con_gestion!=date('Y',strtotime($form['fi_convocatoria']))){

                    $ruta= 'alma/noate/' . $convocatoria->con_gestion;
                    $ruta2='alma/noate/' . date('Y',strtotime($form['fi_convocatoria']));
                    if($convocatoria->con_pdf!='') {
                        if (Storage::exists($ruta . '/' . $convocatoria->con_pdf)) {
                            Storage::move($ruta . '/' . $convocatoria->con_pdf,$ruta2.'/'.$convocatoria->con_pdf);
                            $convocatoria->save();
                        }
                    }
                    $convocatoria->con_gestion=date('Y',strtotime($form['fi_convocatoria']));
                }
            }
            $convocatoria->con_tipo=$form['tipo'];
            $convocatoria->con_dirigido_a=$form['dirigido'];
            $convocatoria->con_periodo_inicial=$form['inicial'];
            $convocatoria->con_periodo_final=$form['final'];
            $convocatoria->save();

            $ruta = 'alma/noate/' . $convocatoria->con_gestion;
            $nombreArch="C-".Funciones::alfanumerico(4).'-'.$convocatoria->con_gestion.'.pdf';
            if ($form->hasFile('pdf_conv')) {
                if($convocatoria->con_pdf!=''){
                    $valor= rand(0,999999999999);
                    if(Storage::exists($ruta.'/'.$convocatoria->con_pdf)) {
                        Storage::delete($ruta.'/'.$convocatoria->con_pdf);
                    }
                    Storage::putFileAs($ruta, $form->file('pdf_conv'),$nombreArch);
                    $convocatoria->con_pdf=$nombreArch;
                    $convocatoria->save();
                }else{
                    Storage::putFileAs($ruta.'/', $form->file('pdf_conv'), $nombreArch);
                    $convocatoria->con_pdf = $nombreArch;
                    $convocatoria->save();
                }
            }
            $nuevo=json_encode($convocatoria);
            SessionController::write('U',json_encode($antiguo),$nuevo,'claustros.convocatoria','8',$convocatoria->cod_con);
            \Session::flash('exito','La convocatoria se ha guardado correctamente');
            return redirect('lista convocatoria noatentado/'.$convocatoria->con_gestion);
        }else{
            $con_gestion=0;
            if($form['fi_convocatoria']!=''){
                $con_gestion=date('Y',strtotime($form['fi_convocatoria']));
            }else{
                $con_gestion=date('Y');
            }
            $convocatoria=Convocatoria::create([
                'con_nombre'=>$form['titulo'],
                'con_fecha_publicacion'=>$form['fi_convocatoria'],
                'con_fecha_entrega'=>$form['ff_convocatoria'],
                'con_fecha_eleccion'=>$form['fc_convocatoria'],
                'con_gestion'=>$con_gestion,
                'con_dirigido_a'=>$form['dirigido'],
                'con_clase'=>'A',
                'con_tipo'=>$form['tipo'],
                'con_hab'=>'t',
                'con_periodo_inicial'=>$form['inicial'],
                'con_periodo_final'=>$form['final'],

            ]);
            $nuevo=json_encode($convocatoria);
            SessionController::write('C','',$nuevo,'claustros.convocatoria','8',$convocatoria->cod_con);
            \Session::flash('exitoModal','La convocatoria se guardó correctamente');
            return redirect('editar convocatoria noatentado/'.$convocatoria->cod_con);
        }
    }
    public function fe_cargos_convocatoria($cod_carg,$cod_con){
        $cargo=array();
        if($cod_carg!=0){
            $cargo=Cargo_convocatoria::find($cod_carg);
        }
        $convocatoria=Convocatoria::find($cod_con);
        return view('servicios.no_atentado.convocatoria.fe_cargo',compact('convocatoria','cargo','cod_carg'));
    }
    public function g_cargo(Request $form){
        $form->validate([
            'cc'=>'required',
        ]);
        if($form['nombre']!=""){
            $cargos=Cargo_convocatoria::where('carg_nombre','=',$form['nombre'])
                ->where('cod_con','=',$form['cc'])->first();
            if($cargos){
                \Session::flash('errorCargo','Ya existe el Cargo');

            }else{
                if(isset($form['ca'])){
                    $cargo=Cargo_convocatoria::find($form['ca']);
                    $antiguo=$cargo->toArray();
                    $cargo->carg_nombre=mb_strtoupper($form['nombre']);
                    $cargo->save();
                    \Session::flash('exitoCargo','Se ha editado con exito el cargo');
                    $nuevo=json_encode($cargo);
                    SessionController::write('U',json_encode($antiguo),$nuevo,'claustros.cargo_convocatoria','8',$cargo->cod_carg);
                }else{
                    $cargo=Cargo_convocatoria::create([
                        'carg_nombre'=>mb_strtoupper($form['nombre']),
                        'cod_con'=>$form['cc'],
                    ]);
                    \Session::flash('exitoCargo','Se ha creado con exito el cargo');
                    $nuevo=json_encode($cargo);
                    SessionController::write('C','',$nuevo,'claustros.cargo_convocatoria','8',$cargo->cod_carg);
                }
            }

        }else{
            \Session::flash('errorCargo','El nombre del cargo no puede ser vacio');
        }
        return redirect('actualizar cargos convocatoria/'.$form['cc']);
    }
    public function eli_cargo(Request $form){
        $form->validate(['cc'=>'required','ca'=>'required']);
        $convocatoria=Convocatoria::find($form['cc']);
        //===IMPLEMENTAR RESTRICCION PARA ELIMINAR
        $cargo=Cargo_convocatoria::find($form['ca']);
        $cod_con=$cargo->cod_con;
        $noatentado=Noatentado::where('cod_carg','=',$form['ca'])->first();
        if($noatentado){
            \Session::flash('errorCargo','El cargo esta siendo usado en algún trámite');
        }else{
            \Session::flash('exitoCargo','Se ha eliminado con exito el cargo');
            $antiguo=json_encode($cargo);
            $cargo->delete();
            SessionController::write('D',$antiguo,'','claustros.cargo_convocatoria','8',$cargo->cod_carg);
        }
        return redirect('actualizar cargos convocatoria/'.$cod_con);
    }
    public function actualizar_cargos($cod_con){
        $cargos=Cargo_convocatoria::where('cod_con','=',$cod_con)->orderBy('carg_nombre')->get();
        $convocatoria=Convocatoria::find($cod_con);
        return view('servicios.no_atentado.convocatoria.fe_tabla_cargo',compact('cargos','convocatoria'));
    }
    public function fe_unidad($cod_con){
        $convocatoria=Convocatoria::find($cod_con);
        return view('servicios.no_atentado.convocatoria.fe_unidad',compact('convocatoria'));
    }
    public function lista_unidad($nombreUnidad,$cod_con){
        $unidad=array();

        if($nombreUnidad=='unidad'){
            $unidad=Unidad::where('uni_hab','=','t')->select('uni_nombre as nombre','cod_uni as cod')->orderBy('nombre','ASC')->get();
        }else{
            if($nombreUnidad=='facultad'){
                $unidad=Facultad::select('fac_nombre as nombre','cod_fac as cod')->get();
            }else{
                if($nombreUnidad=='carrera'){
                    $consulta="select (f.fac_abreviacion ||' - '||c.car_nombre) as nombre, cod_car as cod from carreras c join facultads f on c.cod_fac=f.cod_fac order by nombre ASC";
                    $unidad=DB::select($consulta);
                }
            }
        }
        //dd($unidad);
        return view('servicios.no_atentado.convocatoria.fe_tabla_unidad',compact('unidad','nombreUnidad','cod_con'));
    }
    public function g_unidad(Request $form){
        $form->validate([
            'cc'=>'required',
            'tipo'=>['required', Rule::in(['facultad','carrera','unidad'])],
        ]);
        $convocatoria=Convocatoria::find($form['cc']);
        if($form['tipo']=='unidad'){
            $unidad=Unidad::find($form['unidad']);
            $convocatoria->con_convocante=$unidad->uni_nombre;
            $convocatoria->cod_uni=$form['unidad'];
        }else{
            if($form['tipo']=='facultad'){
                $facultad=Facultad::find($form['unidad']);
                $convocatoria->con_convocante=$facultad->fac_nombre;
                $convocatoria->cod_fac=$form['unidad'];
            }else{
                if($form['tipo']=='carrera'){
                    $consulta="select (f.fac_abreviacion ||' - '||c.car_nombre) as nombre, cod_car as cod from carreras c join facultads f on c.cod_fac=f.cod_fac
                                                                           where cod_car=".$form['unidad'];
                    $carrera=DB::select($consulta);
                    //dd($carrera);
                    $convocatoria->con_convocante=$carrera[0]->nombre;
                    $convocatoria->cod_car=$form['unidad'];
                }
            }
        }
        $convocatoria->save();
        return redirect('editar convocatoria noatentado/'.$convocatoria->cod_con);
    }

    public function PDF_convocatoria($cod_con){
        $convocatoria=Convocatoria::find($cod_con);

        if($convocatoria->con_pdf!=''){
            $ruta='alma/noate/'.$convocatoria->con_gestion.'/'.$convocatoria->con_pdf;
            if(Storage::exists($ruta)){
                //return $ruta;
                SessionController::write('C','','Descarga convocatoria','claustros.convocatoria','8',$convocatoria->cod_con);
                return Storage::response($ruta);
            }else{
                return "No existe el archivo";
            }
        }else{
            return "No existe el archivo";
        }
    }

    public function fe_eli_convocatoria($cod_con){
        $convocatoria=Convocatoria::find($cod_con);
        $tramite_noatentado=D_tramita::where('cod_con','=',$cod_con)->first();
        return view('servicios.no_atentado.convocatoria.f_eli_convocatoria',compact('convocatoria','tramite_noatentado'));
    }
    public function eli_convocatoria(Request $form){
        $convocatoria=array();
        if($form['cc']!=''){
            $convocatoria = Convocatoria::find($form['cc']);
            $gestion=$convocatoria->con_gestion;
            if($convocatoria){
                $tramite_noatentado=D_tramita::where('cod_con','=',$form['cc'])->first();
                if($tramite_noatentado){
                    \Session::flash('error','La convocatoria tiene tramites registrados, no se puede eliminar');
                }else{
                    Cargo_convocatoria::where('cod_con','=',$convocatoria->cod_con)->delete();
                    if(Storage::exists('alma/noate/'.$convocatoria->con_gestion.'/'.$convocatoria->con_pdf)){
                        Storage::delete('alma/noate/'.$convocatoria->con_gestion.'/'.$convocatoria->con_pdf);
                    }
                    $antiguo=json_encode($convocatoria);
                    SessionController::write('D',$antiguo,'','claustros.convocatoria','8',$convocatoria->cod_con);
                    $convocatoria->delete();
                    \Session::flash('exito','Se ha eliminado exitosamente la convocatoria');
                }
            }else{
                \Session::flash('exito','Los datos proporcionados no son correctos');
            }
        }else{
            \Session::flash('exito','Los datos proporcionados no son correctos');
        }
        return redirect('lista convocatoria noatentado/'.$gestion);
    }

    //================================================ CODIGO

}
