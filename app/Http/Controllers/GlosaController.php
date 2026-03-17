<?php

namespace App\Http\Controllers;

use App\Models\Glosa;
use App\Models\Tramite;
use Illuminate\Http\Request;

class GlosaController extends Controller
{
    public function listar_glosa($cod_tre){
        $glosas=Glosa::all()->where('cod_tre','=',$cod_tre)->sortBy('glo_titulo');
        $tramite=Tramite::find($cod_tre);
        return view ('servicios.tramite.fe_glosa',compact('glosas','tramite'));
    }
    public function fe_glosa($cod_glo,$cod_tre){
        $glosa='';
        if($cod_glo!='0'){
            $glosa=Glosa::find($cod_glo);
        }
        return view('servicios.tramite.fe_modelo',compact('glosa','cod_glo','cod_tre'));
    }
    public function g_glosa(Request $form){
        $form->validate(['titulo'=>'required']);
        if(isset($form['cg'])){
            $glosa=Glosa::find($form['cg']);
            $glosa->glo_titulo=$form['titulo'];
            $glosa->glo_glosa=$form['glosa'];
            $glosa->save();
            \Session::flash('exito','Se ha editado con exito la glosa');
        }else{
            $glosa=Glosa::create([
                'glo_titulo'=>$form['titulo'],
                'glo_glosa'=>$form['glosa'],
                'cod_tre'=>$form['ct'],
            ]);
            \Session::flash('exito','Se ha creado con exito la glosa');
        }
        return redirect('l_glosa/'.$form['ct']);
    }
    public function f_eliminar_glosa($cod_glo){
        $glosa=Glosa::find($cod_glo);
        $tramite=Tramite::find($glosa->cod_tre);
        return view('servicios.tramite.f_eli_glosa',compact('glosa','tramite'));
    }
    public function eliminar_glosa(Request $form){
        $glosa=Glosa::find($form['cg']);
        $glosa->delete();
        \Session::flash('exito','La glosa se ha eliminado correctamente');
        return redirect('l_glosa/'.$glosa->cod_tre);
    }
}
