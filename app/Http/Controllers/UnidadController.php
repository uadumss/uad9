<?php

namespace App\Http\Controllers;

use App\Models\Noatentado\Convocatoria;
use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function l_unidad(){
        $unidades=Unidad::where('uni_hab','=','t')->get();
        return view('unidad.unidad.l_unidad',compact('unidades'));
    }
    public function fe_unidad($cod_uni){
        $unidad=array();
        if($cod_uni!=0){
            $unidad=Unidad::find($cod_uni);
        }
        return view('unidad.unidad.fe_unidad',compact('unidad','cod_uni'));
    }
    public function g_unidad(Request $form){
        $form->validate([
            'nombre'=>'required',
        ]);
        if(isset($form['cu']) && $form['cu']!=''){
            $unidad=Unidad::find($form['cu']);
            $unidad->uni_nombre=mb_strtoupper($form['nombre']);
            $unidad->uni_nivel=mb_strtoupper($form['nivel']);
            $unidad->uni_abreviacion=mb_strtoupper($form['corto']);
            $unidad->save();
            \Session::flash('exito','Se ha editado la unidad exitosamente');
        }else{
            $unidad=Unidad::create([
                'uni_nombre'=>mb_strtoupper($form['nombre']),
                'uni_nivel'=>mb_strtoupper($form['nivel']),
                'uni_hab'=>'t',
                'uni_abreviacion'=>mb_strtoupper($form['corto']),
            ]);
            \Session::flash('exito','Se ha creado la unidad exitosamente');
        }
        return redirect('listar unidad');
    }
    public function f_eli_unidad($cod_uni){
        $unidad=Unidad::find($cod_uni);
        $convocatoria=Convocatoria::where('cod_uni','=', $unidad->cod_uni)->first();
        $eliminar=1;
        if($convocatoria){
            $eliminar=0;
        }
        return view('unidad.unidad.f_eli_unidad',compact('unidad','eliminar'));
    }
    public function eli_unidad(Request $form){
        $form->validate([
           'cu'=>'required'
        ]);
        $unidad=Unidad::find($form['cu']);
        $convocatoria=Convocatoria::where('cod_uni','=', $unidad->cod_uni)->first();
        if($convocatoria){
            \Session::flash('error','No se puede eliminar la unidad');
        }else{
            $unidad->delete();
            \Session::flash('exito','Se ha eliminado con éxito la unidad');
        }
        return redirect('listar unidad');
    }
}
