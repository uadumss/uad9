<?php

namespace App\Http\Controllers;

use App\Models\Autoridad;
use Illuminate\Http\Request;

class AutoridadController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear autoridad - rr|editar autoridad - rr'],['only'=>['g_autoridad']]);
        $this->middleware(['permission:habilitar autoridad - rr|deshabilitar autoridad - rr'],['only'=>['h_autoridad']]);
        $this->middleware(['permission:editar autoridad - rr'],['only'=>['fe_autoridad']]);
        $this->middleware(['permission:habilitar autoridad - rr'],['only'=>['l_desHab_autoridad']]);
    }

    public function l_autoridad()
    {
        $autoridad=Autoridad::all()
            ->where('aut_hab','=','t')
            ->sortByDesc('cod_aut');
        return view('resoluciones.autoridad.l_autoridad',compact('autoridad'));
    }
    public function g_autoridad(Request $form){
        $form->validate([
            'nombre'=>'required',
            'cargo'=>'required',
            'inicio'=>'required|numeric',
            'fin'=>'required|numeric',
        ]);
        if(isset($form['ca'])){
            $autoridad=Autoridad::find($form['ca']);
            $antiguo=json_encode($autoridad);
            $autoridad->aut_nombre=$form['nombre'];
            $autoridad->aut_cargo=$form['cargo'];
            $autoridad->aut_inicio=$form['inicio'];
            $autoridad->aut_fin=$form['fin'];
            $autoridad->save();

            $nuevo=json_encode($autoridad);
            SessionController::write('CREATE',$antiguo,$nuevo,'Autoridad','2');
            \Session::flash('exito','Se ha editado exitosamente la autoridad');
        }else{
            $autoridad=Autoridad::create([
                'aut_nombre'=>$form['nombre'],
                'aut_cargo'=>$form['cargo'],
                'aut_inicio'=>$form['inicio'],
                'aut_fin'=>$form['fin'],
                'aut_hab'=>'t',
            ]);
            $nuevo=json_encode($autoridad);
            SessionController::write('CREATE','',$nuevo,'Autoridad','2');
            \Session::flash('exito','Se ha creado exitosamente la autoridad');
        }
        return redirect('listar autoridades');
    }
    public function h_autoridad($cod_aut,$valor){
        $autoridad=Autoridad::find($cod_aut);
        $autoridad->aut_hab=$valor;
        $autoridad->save();
        if($valor=='t'){
            \Session::flash('exito','Se ha restaurado con exito a la autoridad');
            return redirect('autoridad deshabilitado');
        }else{
            \Session::flash('exito','Se ha deshabilitado con exito a la autoridad');
            return redirect('listar autoridades');
        }

    }
    public function l_desHab_autoridad(){
        $autoridad=Autoridad::all()
            ->where('aut_hab','=','f')
            ->sortByDesc('cod_aut');
        return view('resoluciones.autoridad.l_desHab_autoridad',compact('autoridad'));
    }
    public function fe_autoridad($cod_aut){
        $autoridad=Autoridad::find($cod_aut);
        return view('resoluciones.autoridad.fe_autoridad',compact('autoridad'));
    }
}
