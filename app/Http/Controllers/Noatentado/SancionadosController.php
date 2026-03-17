<?php

namespace App\Http\Controllers\Noatentado;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SessionController;
use App\Models\Noatentado\D_sancion;
use App\Models\Noatentado\Noatentado;
use App\Models\Noatentado\Sancionado;
use App\Models\Persona;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SancionadosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:crear sancionado - noa|editar sancionado - noa'], ['only' => ['Lista_sancionados_tabla','fe_sancionado','g_sancionado',
            'l_resolucion_sancionado','buscar_resolucion','g_resolucion_sancionado']]);
        $this->middleware(['permission:eliminar sancionado - noa'], ['only' => ['f_eli_sancionado', 'eli_sancionado']]);

        $this->middleware(['permission:crear detalle sancioando - noa|editar detalle sancioando - noa'], ['only' => ['fe_detalle', 'g_detalle']]);
        $this->middleware(['permission:eliminar detalle sancionado - noa'], ['only' => ['f_eli_detalle', 'eli_detalle','f_conf_entrega_noa']]);
    }
    public function lista_sancionados(){
        $sancionados=DB::table('noatentado.sancionados')
            ->join('personas','sancionados.id_per','=','personas.id_per')
            ->leftJoin('resolucions','sancionados.cod_res','=','resolucions.cod_res')
            ->select('resolucions.*','sancionados.*','personas.*')
            ->orderBy('personas.per_apellido','ASC')
            ->orderBy('personas.per_nombre','ASC')->get();

        return view('servicios.no_atentado.sancionado.l_sancionado',compact('sancionados'));
    }
    public function Lista_sancionados_tabla(){
        $sancionados=DB::table('noatentado.sancionados')
            ->join('personas','sancionados.id_per','=','personas.id_per')
            ->leftJoin('resolucions','sancionados.cod_res','=','resolucions.cod_res')
            ->select('resolucions.*','sancionados.*','personas.*')
            ->orderBy('personas.per_apellido','ASC')
            ->orderBy('personas.per_nombre','ASC')->get();
        return view('servicios.no_atentado.sancionado.l_sancionado_tabla',compact('sancionados'));
    }
    public function fe_sancionado($cod_san){
        $sancionado=array();
        if($cod_san!=0){
            $sancionado=DB::table('noatentado.sancionados')
                ->join('personas','sancionados.id_per','=','personas.id_per')
                ->leftJoin('resolucions','sancionados.cod_res','=','resolucions.cod_res')
                ->where('cod_san','=',$cod_san)
                ->select('resolucions.*','sancionados.*','personas.*')->first();
        }
        $resolucion=array();
        iF($sancionado && $sancionado->cod_res!=''){
            $resolucion=Resolucion::find($sancionado->cod_res);
        }
        return view('servicios.no_atentado.sancionado.fe_sancionado',compact('sancionado','resolucion'));
    }
    public function g_sancionado(Request $form){

        $sancionado=array();
        if(isset($form['cs']) && $form['cs']!=''){
            $sancionado=Sancionado::find($form['cs']);
            $antiguo=json_encode($sancionado);
            $sancionado->san_referencia=$form['referencia'];
            $sancionado->san_sentencia=$form['sentencia'];
            $sancionado->san_resolucion=$form['resolucion'];
            $sancionado->save();
            $nuevo=json_encode($sancionado);
            SessionController::write('U',$antiguo,$nuevo,'noatentado.sancionados','8',$sancionado->cod_san);
            \Session::flash('exitoModal','Se ha editado exitosamente el registro');
        }else{
            $form->validate([
                'nombre'=>'required',
                'apellido'=>'required',
                'ci'=>'required',
            ]);
            $uuid=(String)Str::uuid();
            $sancionado=Sancionado::create([
                'cod_san'=>$uuid,
                'san_referencia'=>$form['referencia'],
                'san_sentencia'=>$form['sentencia'],
                'san_resolucion'=>$form['resolucion'],
            ]);
            $nuevo=json_encode($sancionado);
            SessionController::write('C','',$nuevo,'noatentado.sancionados','8',$sancionado->cod_san);
            $persona=Persona::where('per_ci','=',$form['ci'])->first();
            if(!$persona){
                $persona=Persona::create([
                    'per_ci'=>mb_strtoupper($form['ci']),
                    'per_nombre'=>mb_strtoupper($form['nombre']),
                    'per_apellido'=>mb_strtoupper($form['apellido']),
                    'per_cod_sis'=>$form['cod_sis'],
                    'per_sistema'=>8,
                ]);
                $nuevo=json_encode($persona);
                SessionController::write('C','',$nuevo,'personas.sancionados','8',$persona->id_per);
            }else{
                if($form['cod_sis']!='' && $persona->per_cod_sis==''){
                    $persona->per_cod_sis=$form['cod_sis'];
                    $persona->save();
                }
            }
            $sancionado->id_per=$persona->id_per;
            $sancionado->save();
            \Session::flash('exitoModal','Se ha insertado con exito los datos');
        }
        return redirect('editar sancionado/'.$sancionado->cod_san);
    }
    public function f_eli_sancionado($cod_san){
        $eliminar=1;
        $sancionado=DB::table('noatentado.sancionados')
            ->join('personas','sancionados.id_per','=','personas.id_per')
            ->where('cod_san','=',$cod_san)
            ->select('sancionados.*','personas.*')->first();
        $tramite_noatentado=Noatentado::where('id_per','=',$sancionado->id_per)->first();

        if($tramite_noatentado){
            $eliminar=0;
        }
        return view('servicios.no_atentado.sancionado.f_eli_sancionado',compact('sancionado','eliminar'));
    }
    public function eli_sancionado(Request $form){
        $form->validate(['cs'=>'required']);
        $sancionado=Sancionado::find($form['cs']);
        $tramite_noatentado=Noatentado::where('id_per','=',$sancionado->id_per)->first();
        if($tramite_noatentado){
            \Session::flash('error','No se puede eliminar el sancionado');
        }else{
            DB::delete("delete from noatentado.d_sancion where cod_san='".$form['cs']."'");
            $antiguo=json_encode($sancionado);
            SessionController::write('D',$antiguo,'','noatentado.sancionados','8',$sancionado->cod_san);
            $sancionado->delete();
        }
        return redirect('lista sancionados noatentado');
    }
    public function l_resolucion_sancionado($cod_san){
        $sancionado=Sancionado::find($cod_san);
        $resolucion=array();
        if($sancionado->cod_res!=''){
            $resolucion=Resolucion::find($sancionado->cod_res);
        }
        return view('servicios.no_atentado.sancionado.fe_resolucion',compact('sancionado','resolucion'));
    }
    public function buscar_resolucion(Request $form){
        $form->validate([
            'numero'=>'required',
            'tipo'=>'required',
            'gestion'=>'required',
            'cs'=>'required',
        ]);
        $cod_san=$form['cs'];
        $resoluciones=DB::table('resolucions')
            ->join('tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->where('res_numero','=',$form['numero'])
            ->where('res_gestion','=',$form['gestion'])
            ->where('res_tipo','=',$form['tipo'])
            ->get();
        //dd($resoluciones);
        return view('servicios.no_atentado.sancionado.lista_resolucion',compact('resoluciones','cod_san'));
    }
    public function g_resolucion_sancionado(Request $form){
        $form->validate([
           'cs'=>'required',
           'cr'=>'required',
        ]);
        $sancionado=Sancionado::find($form['cs']);
        $antiguo=json_encode($sancionado);
        $sancionado->cod_res=$form['cr'];
        $sancionado->save();
        $nuevo=json_encode($sancionado);
        SessionController::write('U',$antiguo,$nuevo,'noatentado.d_sancion','8',$sancionado->cod_dsan);
        $resolucion=Resolucion::find($form['cr']);
        \Session::flash('exitoModal','Se ha asignado con exito la resolución');
        return view('servicios.no_atentado.sancionado.panel_resolucion',compact('resolucion'));
    }
    public static function verificarSancionado($id_per){
        $sancionado=Sancionado::where('id_per','=',$id_per)->first();
        if($sancionado){
            return $sancionado;
        }
    }

    //=======================================DETALLE SANCION

    public function l_detalle_sancion($cod_san){
        $sancionado=DB::table('noatentado.sancionados')
            ->join('personas','sancionados.id_per','=','personas.id_per')
            ->leftJoin('resolucions','sancionados.cod_res','=','resolucions.cod_res')
            ->where('cod_san','=',$cod_san)
            ->select('resolucions.*','sancionados.*','personas.*')->first();
        $detalles=D_sancion::where('cod_san','=',$cod_san)->get();

        return view('servicios.no_atentado.sancionado.fe_sancion',compact('sancionado','detalles'));
    }
    public function fe_detalle($cod_san,$cod_dsan){
        $detalle=array();
        if($cod_dsan!=0){
            $detalle=D_sancion::find($cod_dsan);
        }
        return view('servicios.no_atentado.sancionado.fe_detalle',compact('detalle','cod_san','cod_dsan'));
    }
    public function g_detalle(Request $form){
        $form->validate(['cs'=>'required','detalle'=>'required']);
        if(isset($form['cd'])&& $form['cd']!=''){
            $detalle=D_sancion::find($form['cd']);
            $antiguo=json_encode($detalle);
            $detalle->dsan_detalle=$form['detalle'];
            $detalle->save();
            $nuevo=json_encode($detalle);
            SessionController::write('U',$antiguo,$nuevo,'noatentado.d_sancion','8',$detalle->cod_dsan);
            \Session::flash('exitoModal','Se ha editado con exito el detalle de la sanción');
        }else{
            $detalle=D_sancion::create([
                'cod_san'=>$form['cs'],
                'dsan_detalle'=>$form['detalle'],
            ]);
            $nuevo=json_encode($detalle);
            SessionController::write('C','',$nuevo,'noatentado.d_sancion','8',$detalle->cod_dsan);
            \Session::flash('exitoModal','Se ha creado con exito el detalle de la sanción');
        }
        return redirect('lista detalle sancion noatentado/'.$form['cs']);
    }
    public function f_eli_detalle($cod_dsan){
        $detalle=D_sancion::find($cod_dsan);

        $sancionado=DB::table('noatentado.sancionados')
            ->join('personas','sancionados.id_per','=','personas.id_per')
            ->leftJoin('resolucions','sancionados.cod_res','=','resolucions.cod_res')
            ->where('cod_san','=',$detalle->cod_san)
            ->select('resolucions.*','sancionados.*','personas.*')->first();
        return view('servicios.no_atentado.sancionado.f_eli_detalle',compact('detalle','sancionado'));

    }
    public function eli_detalle(Request $form){
        $form->validate(['cd'=>'required']);
        $detalle=D_sancion::find($form['cd']);
        $cod_san=$detalle->cod_san;
        $antiguo=json_encode($detalle);
        SessionController::write('C',$antiguo,'','noatentado.d_sancion','8',$detalle->cod_dsan);
        $detalle->delete();
        \Session::flash('exitoModal','Se ha eliminado con exito el detalle de la sanción');
        return redirect('lista detalle sancion noatentado/'.$cod_san);
    }
}
