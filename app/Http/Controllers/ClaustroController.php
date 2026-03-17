<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Electo;
use App\Models\Facultad;
use App\Models\Frente;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaustroController extends Controller
{
    function __construct(){
        $this->middleware(['permission:crear frente - cla|editar frente - cla'],['only'=>['g_frente']]);
        $this->middleware(['permission:crear consejero - cla|editar consejero - cla'],['only'=>['g_consejero']]);
        $this->middleware(['permission:ver datos consejero - cla'],['only'=>['fe_datos_consejero','g_renuncia','fe_consejero']]);
        $this->middleware(['permission:eliminar consejero - cla'],['only'=>['e_consejero']]);
        $this->middleware(['permission:eliminar frente - cla'],['only'=>['f_eli_frente','eli_frente']]);

    }
    /* ele_tipo
    *  u = universitario
     * f = facultativo
     * c = carrera
    */

    public function l_consejo(){
        $facultad=Facultad::all()->sortBy('fac_nombre');
        $carrera=DB::table('carreras')->join('facultads','carreras.cod_fac','=','facultads.cod_fac')
            ->select('cod_car','car_nombre','fac_abreviacion')->orderBy('fac_abreviacion')->orderBy('car_nombre')->get();

        //dd($consejeros);

        return view('claustro.l_consejo',compact('facultad','carrera'));
    }
    public function consejeros($cod,$tipo){
        $unidad=array();
        $facultades=array();
        $carreras=array();
        $tipoConsejo="";
        $nombreUnidad="";
        $electos=array();

        if($tipo=='hcu'){
            $facultades=Facultad::all()->sortBy('fac_abreviacion');
            $tipoConsejo="HCU - Honorable Consejo Universitario";
            $nombreUnidad="Universidad Mayor de San Simón";

            $electos=DB::select("select * from claustros.electos e join personas p on p.id_per=e.id_per
                                                     where ele_tipo='u' and ele_fecha_fin>='".date('d/m/Y')."'");

        }else{
            if($tipo=='hcf'){

                $unidad=Facultad::find($cod);
                $tipoConsejo="HCF - Honorable Consejo Facultativo";
                $nombreUnidad=$unidad->fac_nombre;
                $carreras=Carrera::all()->where('cod_fac','=',$unidad->cod_fac)->sortBy('car_nombre');

                $electos=DB::select("select * from claustros.electos e join personas p on p.id_per=e.id_per
                                                     where cod_fac=".$cod." and ele_tipo='f' and ele_fecha_fin>='".date('d/m/Y')."'");
                //return view('claustro.hcu.panel_consejo',compact('unidad','tipo','cod','tipoConsejo','nombreUnidad','facultades','carreras'));
            }else{
                if($tipo=='hcc'){
                    $unidad=Carrera::find($cod);
                    $tipoConsejo="HCC - Honorable Consejo de Carrera";
                    $nombreUnidad=$unidad->car_nombre;
                    $electos=DB::select("select * from claustros.electos e join personas p on p.id_per=e.id_per
                                                     where cod_car=".$cod." and ele_tipo='c' and ele_fecha_fin>='".date('d/m/Y')."'");
                }
            }
        }
        return view('claustro.hcu.panel_consejo',compact('electos','unidad','tipo','cod','tipoConsejo','nombreUnidad','facultades','carreras'));
    }
    public function fe_consejo($cod,$cod_fre,$tipo){
        if($cod_fre==0){
            $facultades=Facultad::all()->sortBy('fac_nombre');
            $carreras=Carrera::all()->sortBy('car_nombre');
            return view('claustro.fe_consejero',compact('cod','tipo','cod_fre','facultades','carreras'));
        }else{
            $consejeros=array();
            $facultad="";
            $carrera="";
            $frente=Frente::find($cod_fre);

            if($frente){
                $consejeros = DB::table('electos')
                    ->join('personas','electos.id_per','=','personas.id_per')
                    ->select('electos.*','per_nombre','per_apellido','per_ci','per_sis')
                    ->where('cod_fre', '=', $frente->cod_fre)->get();
                if($frente->cod_fac!=''){
                    $facultad=Facultad::find($frente->cod_fac);
                }
                if($frente->cod_car!=''){
                    $facultad=Carrera::find($frente->cod_car);
                }
            }
            return view('claustro.fe_consejero',compact('cod','tipo','facultad','carrera','cod_fre','frente','consejeros'));
        }
    }
    public function fe_consejero($ci){
        $persona=array();
        $electos=array();
        if($ci!=0){
            $persona=Persona::where('per_ci','=',$ci)->first();
            if($persona){
                $electos=DB::select("select e.*,f.fac_nombre,c.car_nombre from claustros.electos e left join facultads f on e.cod_fac=f.cod_fac left join carreras c on e.cod_car=c.cod_car
                                                     where id_per=".$persona->id_per." order by ele_fecha_inicio DESC");
            }
        }
        return view('claustro.fe_consejero',compact('persona','electos','ci'));
    }
    public function fe_datos_consejero($cod_ele){
        $electo=Electo::find($cod_ele);
        $persona=Persona::find($electo->id_per);
        $frente=Frente::find($electo->cod_fre);
        return view('claustro.datos_consejero',compact('electo','persona','frente'));
    }
    public function g_renuncia(Request $form){
        $form->validate([
            'ce'=>'required',
            'renuncia'=>'required'
        ]);
        $electo=Electo::find($form['ce']);
        $antiguo=json_encode($electo);
        $persona=Persona::find($electo->id_per);
        $electo->ele_fecha_renuncia=$form['renuncia'];
        $electo->save();
        $nuevo=json_encode($electo);
        SessionController::write('U',$antiguo,$nuevo,'claustros.electos','9',$electo->cod_ele);
        return redirect('editar consejero/'.$persona->per_ci);
    }
 //======================HCF===========
    public function hcf_consejo($cod_car)
    {
        $carrera=Carrera::find($cod_car);
        $facultad = Facultad::find($carrera->cod_fac);
        $frente_d = Frente::all()->where('cod_car', '=', $cod_car)
            ->where('fre_docente', '=', 't')->sortByDesc('fre_fecha_inicio');
        $frente_e = Frente::all()->where('cod_car', '=', $cod_car)
            ->where('fre_docente', '=', 'f')->sortByDesc('fre_fecha_inicio');
        return view('claustro.hcf.fu_hcf',compact('facultad','frente_d','frente_e','carrera'));
    }

//===================================HCU======
    public function fu_consejo($tipo,$cod)
    {
        if($tipo=='hcu'){
            $facultad = Facultad::find($cod);
            $frente_d = Frente::all()->where('cod_fac', '=', $cod)
                ->where('fre_docente', '=', 't')->sortByDesc('fre_fecha_inicio');
            $frente_e = Frente::all()->where('cod_fac', '=', $cod)
                ->where('fre_docente', '=', 'f')->sortByDesc('fre_fecha_inicio');

            return view('claustro.hcu.fu_hcu',compact('facultad','frente_d','frente_e','tipo'));
        }else{
            if($tipo=='hcf'){
                $carrera=Carrera::find($cod);
                $facultad = Facultad::find($carrera->cod_fac);
                $frente_d = Frente::all()->where('cod_car', '=', $cod)
                    ->where('fre_docente', '=', 't')
                    ->where('fre_tipo','=','f')
                    ->sortByDesc('fre_fecha_inicio');
                $frente_e = Frente::all()->where('cod_car', '=', $cod)
                    ->where('fre_docente', '=', 'f')
                    ->where('fre_tipo','=','f')
                    ->sortByDesc('fre_fecha_inicio');

                return view('claustro.hcu.fu_hcu',compact('facultad','carrera','frente_d','frente_e','tipo'));
            }else{
                if($tipo=='hcc'){
                    $carrera=Carrera::find($cod);
                    $facultad = Facultad::find($carrera->cod_fac);
                    $frente_d = Frente::all()->where('cod_car', '=', $cod)
                        ->where('fre_docente', '=', 't')
                        ->where('fre_tipo','=','c')
                        ->sortByDesc('fre_fecha_inicio');

                    $frente_e = Frente::all()->where('cod_car', '=', $cod)
                        ->where('fre_docente', '=', 'f')
                        ->where('fre_tipo','=','c')
                        ->sortByDesc('fre_fecha_inicio');

                    return view('claustro.hcu.fu_hcu',compact('facultad','carrera','frente_d','frente_e','tipo'));
                }
            }
        }

    }
    public function l_consejeros($tipo,$cod){
        $unidad=array();

        if($tipo=='hcu'){
            $unidad=Facultad::find($cod);
            $electos=DB::table('claustros.electos')
                ->join('personas','electos.id_per','=','personas.id_per')
                ->where('cod_fac','=',$unidad->cod_fac)
                ->where('ele_tipo','=','u')
                ->orderBy('ele_fecha_fin','DESC')->get();
        }else{
            if($tipo=='hcf'){
                $unidad=Carrera::find($cod);
                $electos=DB::table('claustros.electos')
                    ->join('personas','electos.id_per','=','personas.id_per')
                    ->where('cod_car','=',$unidad->cod_car)
                    ->where('ele_tipo','=','f')
                    ->orderBy('ele_fecha_fin','DESC')->get();
            }else{
                if($tipo=='hcc'){
                    $unidad=Carrera::find($cod);
                    $electos=DB::table('claustros.electos')
                        ->join('personas','electos.id_per','=','personas.id_per')
                        ->where('cod_car','=',$unidad->cod_car)
                        ->where('ele_tipo','=','c')
                        ->orderBy('ele_fecha_fin','DESC')->get();
                }
            }
        }
        return view('claustro.hcu.l_consejeros',compact('unidad','electos','tipo'));
    }
    public function fe_frente($tipo,$cod,$cod_fre){
        $frente=array();
        $consejeros=array();
        $facultad=array();
        $carrera=array();
        if($tipo=='hcu'){
            $facultad=Facultad::find($cod);
        }else{
            if($tipo=='hcf' || $tipo=='hcc'){
               //dd($cod);
                $carrera=Carrera::find($cod);
                $facultad=Facultad::find($carrera->cod_fac);
            }
        }
        //dd($unidad);
        if($cod_fre!=0){
            $frente=Frente::find($cod_fre);
            $consejeros=DB::table('claustros.electos')
                ->join('personas','electos.id_per','=','personas.id_per')
                ->select('per_nombre','per_apellido','ele_fecha_inicio','ele_fecha_fin','per_ci','ele_titular','cod_ele','ele_fecha_renuncia')
                ->where('cod_fre','=',$frente->cod_fre)
                ->orderByDesc('ele_titular')
                ->orderBy('per_apellido')
                ->get();
        }
        return view('claustro.hcu.fe_frente',compact('frente','cod_fre','facultad','carrera','consejeros','tipo'));

    }
    public function g_frente(Request $form){
        if($form['nombre']==''){
            $form['nombre']=='s/n';
        }
        /* fre_tipo
         *      u=Universitario
         *      f=Facultativo
         *      c=carrera
         */
        //dd($form);
        $cod=0;
        if($form['cfre']==0){
           $vigente=($form['vigente']=='on')?'t':'f';
            $frente=Frente::create([
                'fre_nombre'=>$form['nombre'],
                'fre_fecha_inicio'=>$form['inicio'],
                'fre_fecha_fin'=>$form['fin'],
                'fre_vigente'=>$vigente,
                'fre_docente'=>$form['estamento'],
            ]);

            if($form['tipo']=='hcu'){
                $frente->cod_fac=$form['cf'];
                $frente->fre_tipo='u';
                $frente->save();
                $cod=$frente->cod_fac;
            }else{
                if($form['tipo']=='hcf'){
                    $frente->cod_car=$form['cc'];
                    $frente->fre_tipo='f';
                    $frente->save();
                    $cod=$frente->cod_car;
                }else{
                    if($form['tipo']=='hcc'){
                        $frente->cod_car=$form['cc'];
                        $frente->fre_tipo='c';
                        $frente->save();
                        $cod=$frente->cod_car;
                    }
                }
            }

            $nuevo=json_encode($frente);
            SessionController::write('C','',$nuevo,'claustros.frentes','9',$frente->cod_fre);

            \Session::flash('exitof','Se ha creado el frente exitosamente');
        }else{
            $vigente=($form['vigente']=='on')?'t':'f';
            $frente=Frente::find($form['cfre']);
            $antiguo=json_encode($frente);
            $frente->fre_nombre=$form['nombre'];
            $frente->fre_fecha_inicio=date('d/m/Y',strtotime($form['inicio']));
            $frente->fre_fecha_fin=date('d/m/Y',strtotime($form['fin']));
            DB::update("update claustros.electos set ele_fecha_inicio='".$frente->fre_fecha_inicio."',ele_fecha_fin='".$frente->fre_fecha_fin."' where cod_fre=".$frente->cod_fre);
            $frente->fre_vigente=$vigente;
            $frente->save();
            if($form['tipo']=='hcu'){
                $cod=$frente->cod_fac;
            }else{
                if($form['tipo']=='hcf'){
                    $cod=$frente->cod_car;
                }else{
                    if($form['tipo']=='hcc'){
                        $cod=$frente->cod_car;
                    }
                }
            }
            $nuevo=json_encode($frente);
            SessionController::write('U',$antiguo,$nuevo,'claustros.frentes','9',$frente->cod_fre);
            \Session::flash('exitof','Se ha editado el frente exitosamente');
        }

        return redirect("fe_frente/".$form['tipo']."/".$cod."/".$frente->cod_fre);
    }
    public function lista_frente($cod_fre){
        $frente=Frente::find($cod_fre);
        $consejeros=DB::table('claustros.electos')
            ->join('personas','electos.id_per','=','personas.id_per')
            ->select('per_nombre','per_apellido','ele_fecha_inicio','ele_fecha_fin','per_ci','ele_titular','cod_ele','ele_fecha_renuncia')
            ->where('cod_fre','=',$frente->cod_fre)
            ->orderByDesc('ele_titular')
            ->orderBy('per_apellido')
            ->get();
        $facultad=Facultad::find($frente->cod_fac);
        return view('claustro.hcu.lista_frente',compact('frente','consejeros','facultad'));
    }
    public function g_consejero(Request $form){
        $form->validate([
            'cfre'=>'required',
            'ci'=>'required',
        ]);
        $frente=Frente::find($form['cfre']);
        $persona=DB::table('personas')->where('per_ci','=',$form['ci'])->first();
        if(!$persona){
            $persona=Persona::create([
                'per_nombre'=>$form['nombre'],
                'per_apellido'=>$form['apellido'],
                'per_ci'=>$form['ci'],
                'per_sistema'=>9,
            ]);
        }
        $cod=0;
        $atributo='';
        if($frente->fre_tipo=='u'){
            $cod=$frente->cod_fac;
            $atributo='cod_fac';
        }else{
            if($frente->fre_tipo=='f' || $frente->fre_tipo=='c'){
                $cod=$frente->cod_car;
                $atributo='cod_car';
            }
        }
        $electo=Electo::create([
            'id_per'=>$persona->id_per,
            'cod_fre'=>$frente->cod_fre,
            'ele_titular'=>$form['titular'],
            'ele_docente'=>$frente->fre_docente,
            'ele_fecha_inicio'=>$frente->fre_fecha_inicio,
            'ele_fecha_fin'=>$frente->fre_fecha_fin,
            'ele_estado'=>'t',
            'ele_vigente'=>$frente->fre_vigente,
            'ele_tipo'=>$frente->fre_tipo,
            $atributo=>$cod,
        ]);
        $nuevo=json_encode($electo);
        SessionController::write('C','',$nuevo,'claustros.electos','9',$electo->cod_ele);

        if($frente->fre_tipo=='u'){
            return redirect("fe_frente/hcu/".$frente->cod_fac."/".$frente->cod_fre);
        }else{
            if($frente->fre_tipo=='f'){
                return redirect("fe_frente/hcf/".$frente->cod_car."/".$frente->cod_fre);
            }else{
                if($frente->fre_tipo=='c'){
                    return redirect("fe_frente/hcc/".$frente->cod_car."/".$frente->cod_fre);
                }
            }
            //return redirect("fe_frente/hcf/".$frente->cod_car."/".$frente->cod_fre);
        }
    }
    public function e_consejero(Request $form){
        $form->validate([
            'cc'=>'required',
        ]);
        $electo=Electo::find($form['cc']);
        $frente=Frente::find($electo->cod_fre);
        $antiguo=json_encode($electo);
        SessionController::write('D',$antiguo,'','claustros.electos','9',$electo->cod_ele);
        $electo->delete();
        if($frente->fre_tipo=='u'){
            return redirect("fe_frente/hcu/".$frente->cod_fac."/".$frente->cod_fre);
        }else{
            if($frente->fre_tipo=='f'){
                return redirect("fe_frente/hcf/".$frente->cod_car."/".$frente->cod_fre);
            }else{
                if($frente->fre_tipo=='c'){
                    return redirect("fe_frente/hcc/".$frente->cod_car."/".$frente->cod_fre);
                }
            }
        }
    }
    public function f_eli_frente($cod_fre){
        $consejeros=Electo::all()->where('cod_fre','=',$cod_fre);
        $frente=Frente::find($cod_fre);
        //dd($frente);
        $carrera=Carrera::find($frente->cod_car);
        $facultad=Facultad::find($frente->cod_fac);
        $eliminar=1;
        if(sizeof($consejeros)>0){
            $eliminar=0;
        }
            return view('claustro.hcu.f_eli_frente',compact('frente','eliminar','facultad','carrera'));
    }
    public function eli_frente(Request $form){
        $form->validate([
           'cf'=>'required'
        ]);
        $frente=Frente::find($form['cf']);
        $tipo=$frente->fre_tipo;
        $antiguo=json_encode($frente);
        SessionController::write('D',$antiguo,'','claustros.frentes','9',$frente->cod_ele);
        $frente->delete();
        if($tipo=='u'){
            return redirect("fu_consejo/hcu/".$frente->cod_fac);
        }else{
            if($tipo=='f'){
                return redirect("fu_consejo/hcf/".$frente->cod_car);
            }else{
                return redirect("fu_consejo/hcc/".$frente->cod_car);
            }
        }

    }

}
