<?php

namespace App\Http\Controllers;

use App\Http\Requests\TomoRequest;
use App\Models\Resolucion;
use App\Models\Titulo;
use App\Models\Tomo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TomoResolucionController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear tomo - rr|editar tomo - rr'],['only'=>['g_tomos']]);
        $this->middleware(['permission:editar tomo - rr'],['only'=>['fe_tomo']]);
        $this->middleware(['permission:eliminar tomo - rr|consolidar tomo - rr'],['only'=>['f_cerrarTomo']]);
        $this->middleware(['permission:consolidar tomo - rr'],['only'=>['cerrarTomo']]);
        $this->middleware(['permission:eliminar tomo - rr'],['only'=>['EliminarTomo']]);

    }
    public function l_tomos($gestion){
        $tomos=Tomo::all()->where('tom_gestion','=',$gestion)->where('tom_tipo','=','res')
            ->where('tom_numero','<>',0)
            ->sortBy('tom_numero');
        return view('resoluciones.tomo.l_tomos_res',compact('tomos','gestion'));
    }
    public function g_tomos(TomoRequest $form){
        //dd($form);
        $tomo=Tomo::find($form['ct']);
        $antiguo=json_encode($tomo);
        if(isset($form['ct'])){
            if($tomo->tom_cerrado!='t'){
                $tomos=DB::table('tomos')->where('tom_gestion','=',$tomo['tom_gestion'])
                    ->where('tom_numero','=',$form['n_tomo'])
                    ->where('tom_tipo','=',$tomo['tom_tipo'])
                    ->where('cod_tom','<>',$form['ct'])
                    ->select('tom_numero','cod_tom')->get();
                //dd($tomos);
                if(sizeof($tomos)<1){

                    if(!Storage::exists('alma/res/'.$tomo['tom_gestion'].'/'.$form['n_tomo'].'/')) {
                        if (!Storage::exists('alma/res/' . $tomo['tom_gestion'] . '/' . $tomo['tom_numero'])) {
                            Storage::makeDirectory('alma/res/' . $tomo['tom_gestion'] . '/' . $tomo['tom_numero']);
                        } else {
                            Storage::move("alma/res/" . $tomo['tom_gestion'] . "/" . $tomo['tom_numero'] . "/", "alma/res/" . $tomo['tom_gestion'] . "/" . $form['n_tomo'] . "/");
                        }
                    }else{

                        if(!$form['n_tomo']==$tomo->tom_numero){
                            \Session::flash('error','No se puede actualizar, debido a que el almacenamiento para el tomo '.$form['n_tomo'].' ya existe ');
                            return redirect('/listar tomos resoluciones/'.$tomo['tom_gestion']);
                        }
                        //return "existe ".$form['n_tomo']." - ".$tomo->tom_numero;
                    }
                    $tomo->tom_numero=$form['n_tomo'];
                    $tomo->tom_rango=$form['r_menor'].'-'.$form['r_mayor'];
                    $tomo->tom_obs=$form['obs'];
                    $tomo->save();
                    \Session::flash('exito','El tomo se ha actualizado exitosamente');

                    $nuevo=json_encode($tomo);
                    SessionController::write('UPDATE',$antiguo,$nuevo,'Tomos','2',$tomo->cod_tom);
                }else{
                    \Session::flash('error','No se puede actualizar, debido a que el tomo '.$form['n_tomo'].' ya existe en la gestión '.$tomo['tom_gestion']);
                }
                return redirect('/listar tomos resoluciones/'.$tomo['tom_gestion']);
            }else{
                \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar');
                return redirect('/listar tomos resoluciones/'.$tomo->tom_gestion);
            }
        }else{
            $tomos=DB::table('tomos')->where('tom_gestion','=',$form['gestion'])
                ->where('tom_numero','=',$form['n_tomo'])
                ->where('tom_tipo','=','res')->get();

            if(sizeof($tomos)<1){
                if(!Storage::exists('alma/res/'.$form['gestion'])){
                    //Storage::makeDirectory('alma/dt/'.$tipo.'/'.$form['gestion']);
                    Storage::makeDirectory('alma/res/'.$form['gestion'].'/'.$form['n_tomo']);
                    $tomo=Tomo::create([
                        'tom_numero'=>$form['n_tomo'],
                        'tom_gestion'=>$form['gestion'],
                        'tom_rango'=>$form['r_menor'].'-'.$form['r_mayor'],
                        'tom_obs'=>$form['obs'],
                        'tom_tipo'=>'res',
                    ]);
                    \Session::flash('exito','El tomo '.$tomo->tom_numero.' se ha creado exitosamente en  la gestión '.$tomo->tom_gestion);
                    $nuevo=json_encode($tomo);
                    SessionController::write('CREATE','',$nuevo,'Tomos','2',$tomo->cod_tom);
                }else{
                    if(!Storage::exists('alma/res/'.$form['gestion'].'/'.$form['n_tomo'])){
                        Storage::makeDirectory('alma/res/'.$form['gestion'].'/'.$form['n_tomo']);
                        $tomo=Tomo::create([
                            'tom_numero'=>$form['n_tomo'],
                            'tom_gestion'=>$form['gestion'],
                            'tom_rango'=>$form['r_menor'].'-'.$form['r_mayor'],
                            'tom_obs'=>$form['obs'],
                            'tom_tipo'=>'res',
                        ]);

                        \Session::flash('exito','El tomo '.$tomo->tom_numero.' se ha creado exitosamente en  la gestión '.$tomo->tom_gestion);
                        $nuevo=json_encode($tomo);
                        SessionController::write('CREATE','',$nuevo,'Tomos','2',$tomo->cod_tom);
                    }
                    else{
                        \Session::flash('error','No se puede crear, debido a que el almacenamiento para el tomo '.$form['n_tomo'].' ya existe');
                    }
                }
            }else{
                \Session::flash('error','No se puede crear, debido a que el tomo '.$form['n_tomo'].' ya existe');
            }
            return redirect('/listar tomos resoluciones/'.$form['gestion']);
        }
    }
    public function l_resolucionSinTomo($gestion){
        $tomos=DB::table('tomos')->where('tom_gestion','=',$gestion)
            ->where('tom_numero','=','0')
            ->where('tom_tipo','=','res')->first();
        $cod_tom=0;
        if($tomos){
            $cod_tom=$tomos->cod_tom;
        }else{
            if(!Storage::exists('alma/res/'.$gestion.'/0')){
                Storage::makeDirectory('alma/res/'.$gestion.'/0');
            }
            $tomo=Tomo::create([
                'tom_numero'=>'0',
                'tom_gestion'=>$gestion,
                'tom_tipo'=>'res',
            ]);
            $cod_tom=$tomo->cod_tom;
        }
        return redirect('listar resoluciones/todos/'.$cod_tom);
    }
    public function fe_tomo($id){
        $tomo=Tomo::find($id);
        $gestion=$tomo->tom_gestion;
        return view('resoluciones.tomo.fe_tomo',compact('tomo','gestion'));
    }
    public function EliminarTomo (Request $form){
        $tomo=Tomo::find($form['ct']);
        $resoluciones=Resolucion::all()->where('cod_tom','=',$form['ct']);
        if(sizeof($resoluciones)>0){
            \Session::flash('error','El tomo '.$tomo['tom_numero'].' tiene resoluciones registradas, no se puede eliminar');
            return redirect("/listar tomos resoluciones/".$tomo['tom_gestion']);
        }else{
            if(Storage::exists('alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'])) {
                $files=Storage::files('alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero']);
                if(sizeof($files)<1){
                    Storage::deleteDirectory('alma/res/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero']);
                    $antiguo=json_encode($tomo);
                    DB::delete('delete from tomo_carreras where cod_tom ='.$form['ct']);
                    $tomo->delete();
                    SessionController::write('DELETE',$antiguo,'','Tomos','2',$tomo->cod_tom);
                    \Session::flash('exito','Se eliminado exitosamente el tomo');
                    return redirect("/listar tomos resoluciones/".$tomo['tom_gestion']);
                }else{
                    \Session::flash('error','El tomo '.$tomo['tom_numero'].' tiene documentos registrados, no se puede eliminar');
                    return redirect("/listar tomos resoluciones/".$tomo['tom_gestion']);
                }
            }else{
                DB::delete('delete from tomo_carreras where cod_tom ='.$form['ct']);
                $tomo->delete();
                $nuevo=json_encode($tomo);
                SessionController::write('DELETE','',$nuevo,'Tomo','2',$tomo->cod_tom);
                \Session::flash('exito','Se eliminado exitosamente el tomo');
                return redirect("/listar tomos resoluciones/".$tomo['tom_gestion']);
            }
        }
    }
    public function f_cerrarTomo($cod_tom){
        $tomo=Tomo::find($cod_tom);
        return view('resoluciones.tomo.fc_tomo_res',compact('tomo'));
    }
    public function cerrarTomo(Request $form){
        $tomo=Tomo::find($form['ct']);
        $tomo->tom_cerrado='t';
        $tomo->save();
        \Session::flash('exito','Se ha consolidado exitosamente el tomo');
        return redirect("/listar tomos resoluciones/".$tomo['tom_gestion']);
    }
}
