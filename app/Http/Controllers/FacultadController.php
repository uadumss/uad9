<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Facultad;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultadController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear editar facultad - f'], ['only' => ['fe_facultad','g_facultad']]);
        $this->middleware(['permission:eliminar facultad - f'], ['only' => ['f_eli_facultad','eli_facultad']]);
        $this->middleware(['permission:crear editar carrera - f'], ['only' => ['fe_carrera','g_carrera']]);
        $this->middleware(['permission:eliminar carrera - f'], ['only' => ['f_eli_carrera','eli_carrera']]);

    }
    public function l_facultad(){
        $facultades=Facultad::all()->sortBy('cod_fac');
        return view('unidad.facultad.l_facultad',compact('facultades'));
    }
    public function fe_facultad($cod_fac){
        $facultad="";
        if($cod_fac!=0){
            $facultad=Facultad::find($cod_fac);
        }
        return view('unidad.facultad.fe_facultad',compact('facultad','cod_fac'));
    }
    public function g_facultad(Request $form){
        $facultades=Facultad::all()->where('fac_nombre','=',$form['nombre'])->where('cod_fac','<>',$form['cf']);
        if(sizeof($facultades)<1) {
            if (isset($form['cf'])) {
                $facultad = Facultad::find($form['cf']);
                $antiguo=json_encode($facultad);
                $facultad->fac_nombre = $form['nombre'];
                $facultad->fac_abreviacion = $form['corto'];
                $facultad->save();
                $nuevo=json_encode($facultad);
                SessionController::write('U',$antiguo,$nuevo,'Facultads','1',$facultad->cod_fac);
            } else {
                $facultad = Facultad::create([
                    'fac_nombre' => $form['nombre'],
                    'fac_abreviacion' => $form['corto'],
                ]);
                $nuevo=json_encode($facultad);
                SessionController::write('C','',$nuevo,'Facultads','1',$facultad->cod_fac);
            }
            \Session::flash('exito', 'Se ha guardado con éxito la facultad');
        }else{
            \Session::flash('error', 'Ya existe una facultad con ese nombre');
        }
        return redirect('listar facultad');
    }
    public function f_eli_facultad($cod_fac){
        $facultad=Facultad::find($cod_fac);
        $carreras=DB::select('select count(cod_car) from carreras where cod_fac='.$facultad->cod_fac);
        $eliminar=1;
        if($carreras[0]->count>0){
            $eliminar=0;
        }
        return view('unidad.facultad.f_eli_facultad',compact('facultad','eliminar'));
    }
    public function eli_facultad(Request $form){
        $facultad=Facultad::find($form['cf']);
        $facultad->delete();
        $antiguo=json_encode($facultad);
        SessionController::write('D',$antiguo,'','Facultads','1',$facultad->cod_fac);
        \Session::flash('exito','Se ha eliminado con éxito la facultad');
        return redirect('listar facultad');
    }
    //==========================CARRERA=================================
    public function l_carrera($cod_fac){
        $facultad=Facultad::find($cod_fac);
        $carreras=Carrera::all()->where('cod_fac','=',$cod_fac)->sortBy('car_nombre');
        return view('unidad.carrera.l_carrera',compact('facultad','carreras','cod_fac'));
    }
    public function fe_carrera($cod_fac,$cod_car){
        $facultad="";
        $carrera="";
        if($cod_car!=0){
            $carrera=Carrera::find($cod_car);
            $facultad=Facultad::find($carrera->cod_fac);
        }
        if($cod_fac!=0){
            $facultad=Facultad::find($cod_fac);
        }
        return view('unidad.carrera.fe_carrera',compact('facultad','carrera','cod_fac','cod_car'));
    }
    public function g_carrera(Request $form){
        $carreras=Carrera::all()->where('car_nombre','=',$form['nombre'])->where('cod_car','<>',$form['cc']);
        if(sizeof($carreras)<1){
            if(isset($form['cc'])){
                $carrera=Carrera::find($form['cc']);
                $antiguo=json_encode($carrera);
                $carrera->car_nombre=$form['nombre'];
                $carrera->car_abreviacion=$form['corto_c'];
                $carrera->save();
                $nuevo=json_encode($carrera);
                SessionController::write('U',$antiguo,$nuevo,'Carreras','1',$carrera->cod_car);
            }else{
                $carrera=Carrera::create([
                    'car_nombre'=>$form['nombre'],
                    'cod_fac'=>$form['cf'],
                    'car_abreviacion'=>$form['corto_c'],
                ]);
                $nuevo=json_encode($carrera);
                SessionController::write('C','',$nuevo,'Carreras','1',$carrera->cod_car);
            }
            \Session::flash('exito','Se ha guardado con éxito la carrera');
        }else{
            \Session::flash('error','Ya existe una carrera con ese nombre');
        }
        //return redirect('listar facultad');
    }
    public function f_eli_carrera($cod_fac,$cod_car){
        $carrera=Carrera::find($cod_car);
        $facultad=Facultad::find($carrera->cod_fac);

        $carreras=DB::select('select count(cod_car) from tomo_carreras where cod_car='.$carrera->cod_car);
        $eliminar=1;
        if($carreras[0]->count>0){
            $eliminar=0;
        }
        return view('unidad.carrera.f_eli_carrera',compact('facultad','carrera','eliminar'));
    }
    public function eli_carrera(Request $form){
        $carrera=Carrera::find($form['cc']);
        $carrera->delete();
        $antiguo=json_encode($carrera);
        SessionController::write('D',$antiguo,'','Carreras','1',$carrera->cod_car);
        \Session::flash('exito','Se ha eliminado con éxito la carrera');
    }

}
