<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Diploma_academico;
use App\Http\Requests\TituloRequest;
use App\Models\Modalidad;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Revalida;
use App\Models\T_observacion;
use App\Models\Titulo;
use App\Models\Tomo;
use App\Models\Tomo_carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TituloController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear titulo - dyt|editar titulo - dyt'], ['only' => ['GuardarTitulo']]);
        $this->middleware(['permission:editar titulo - dyt'], ['only' => ['fe_titulo']]);
        $this->middleware(['permission:eliminar titulo - dyt'], ['only' => ['f_eli_titulo']]);
        $this->middleware(['permission:eliminar titulo - dyt'], ['only' => ['e_Titulo']]);
        $this->middleware(['permission:cambiar titulo a tomo - dyt'], ['only' => ['cambiar_tomo','o_tomos','f_cambiarTomo']]);
        $this->middleware(['permission:verificar titulos faltantes - dyt'], ['only' => ['verificar_titulos']]);

    }
    public function ListarTitulo($cod_tomo){

        $tomo=Tomo::find($cod_tomo);
        $tomo_carrera=array();
        $gestion=$tomo['tom_gestion'];
        $listaTomo=Tomo::where('tom_gestion','=',$gestion)->where('tom_tipo','=',$tomo['tom_tipo'])->orderBy('tom_numero')->get();
        $tipo=$tomo['tom_tipo'];
        $titulo="";
        if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
            if(Auth::user()->can('listado completo titulos - dyt')) {
                $titulo = DB::table('titulos')
                    ->join('personas', 'titulos.id_per', '=', 'personas.id_per')
                    ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                    ->leftJoin('diploma_academicos', 'titulos.cod_tit', '=', 'diploma_academicos.cod_tit')
                    ->leftJoin('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                    ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                    ->leftJoin('modalidads', 'titulos.cod_mod', '=', 'modalidads.cod_mod')
                    ->where('cod_tom', '=', $cod_tomo)
                    ->select('tit_nro_titulo', 'tit_nro_folio', 'titulos.cod_tit', 'per_apellido', 'tit_fecha_emision', 'tit_pdf', 'tit_antecedentes',
                        'tit_obs', 'per_nombre', 'car_nombre','car_abreviacion', 'mod_nombre', 'fac_nombre', 'tit_revalida', 'tit_usr','fac_abreviacion')
                    ->orderBy('fac_abreviacion', 'ASC')
                    ->orderBy('car_abreviacion', 'ASC')
                    ->orderBy('per_apellido', 'ASC')
                    ->orderBy('per_nombre', 'ASC')
                    ->get();
            }else{
                $titulo = DB::table('titulos')
                    ->join('personas', 'titulos.id_per', '=', 'personas.id_per')
                    ->leftJoin('diploma_academicos', 'titulos.cod_tit', '=', 'diploma_academicos.cod_tit')
                    ->leftJoin('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                    ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                    ->leftJoin('modalidads', 'titulos.cod_mod', '=', 'modalidads.cod_mod')
                    ->where('cod_tom', '=', $cod_tomo)
                    ->where('tit_usr', '=', Auth::user()->id)
                    ->select('tit_nro_titulo', 'tit_nro_folio', 'titulos.cod_tit', 'per_apellido', 'tit_fecha_emision', 'tit_pdf', 'tit_antecedentes',
                        'tit_obs', 'per_nombre', 'car_nombre', 'mod_nombre', 'fac_nombre', 'tit_revalida', 'tit_usr','fac_abreviacion')
                    ->orderBy('fac_nombre', 'ASC')
                    ->orderBy('car_nombre', 'ASC')
                    ->orderBy('per_apellido', 'ASC')
                    ->orderBy('per_nombre', 'ASC')
                    ->get();
            }
            $tomo_carrera=DB::table('tomo_carreras')
                ->join('carreras','tomo_carreras.cod_car','=','carreras.cod_car')
                ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->where('tomo_carreras.cod_tom','=',$cod_tomo)
                ->select('car_nombre','fac_nombre')
                ->get();
        }else{
            if($tipo=='db' || $tipo=='su' || $tipo=='re'){

                $titulo=DB::table('titulos')
                    ->join('personas','titulos.id_per','=','personas.id_per')
                    ->where('cod_tom','=',$cod_tomo)
                    ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','tit_revalida',
                        'tit_obs','tit_pdf','tit_antecedentes','tit_usr','tit_reconocimiento')
                    ->orderBy('per_apellido','ASC')
                    ->orderBy('per_nombre','ASC')
                    ->get();
            }else{
                if($tipo=='tpos' || $tipo=='di'){
                    $titulo=DB::table('titulos')
                        ->join('personas','titulos.id_per','=','personas.id_per')
                        ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                        ->where('cod_tom','=',$cod_tomo)
                        ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','tit_titulo',
                            'tit_obs','per_nombre','mod_nombre','tit_pdf','tit_antecedentes','tit_usr')
                        ->orderBy('per_apellido','ASC')
                        ->orderBy('per_nombre','ASC')
                        ->get();
                }
            }
        }

        $c_tomo=new TomoController();
        $tipo_completo=$c_tomo->tipoTomo($tomo['tom_tipo']);
        $carrera="";
        if($tomo->tom_numero=='0'){
            $carrera = DB::table('carreras')
                ->join('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                ->select('car_nombre', 'carreras.cod_car','fac_abreviacion')
                ->orderBy('fac_abreviacion')
                ->orderBy('car_nombre')
                ->get();
        }else {
            $carrera = DB::table('carreras')
                ->join('tomo_carreras', 'carreras.cod_car', '=', 'tomo_carreras.cod_car')
                ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                ->where('tomo_carreras.cod_tom', $cod_tomo)
                ->select('car_nombre', 'carreras.cod_car','fac_abreviacion')
                ->orderBy('fac_abreviacion')
                ->orderBy('car_nombre')
                ->get();
        }
        $nacionalidad=Nacionalidad::orderBy('nac_nombre')->get();
        $modalidad=Modalidad::get();
        $grado=$this->grados($tomo['tom_tipo']);
        return view('diplomas.titulo.l_titulo',compact('titulo','tomo','tipo_completo','carrera','nacionalidad','modalidad','grado','listaTomo','tomo_carrera'));
    }
    public function GuardarTitulo(TituloRequest $form){

        $tomo=Tomo::find($form['ct']);
        if($tomo->tom_cerrado!='t'){
            $tipo=$form['tipo'];
            //return $tipo;
            $titulos=array();
            //============SE PERMITE NUMERO REPETIDOS DE SUPLETORIO
            if($tipo!='su' && $tipo!='ca') {
                if(isset($form['ctit'])){
                    $titulos=DB::select("select cod_tit from titulos where cod_tom=".$form['ct']." and tit_nro_titulo='".$form['nro']."' and cod_tit<>".$form['ctit']);
                }else{
                    $titulos=DB::select("select cod_tit from titulos where cod_tom=".$form['ct']." and tit_nro_titulo='".$form['nro']."'");
                }
            }
            if(sizeof($titulos)<1){
                if(isset($form['ctit'])){
                    $nuevo='';
                    $antiguo='';
                    $objCompleto='';
                    $titulo=Titulo::find($form['ctit']);
                    $antiguo=$titulo->toArray();
                    $cod_tom=Tomo::find($titulo['cod_tom']);
                    $cambio = ($titulo->tit_nro_titulo == $form['nro']) ? false : true;
                    //guarda los datos editados del tomo
                    $titulo->tit_nro_titulo=$form['nro'];
                    $titulo->tit_nro_folio=$form['folio'];       $titulo->tit_fecha_emision=$form['fecha'];      $titulo->tit_grado=$form['grado'];
                    $titulo->cod_mod=$form['mod'];           $titulo->tit_titulo=mb_strtoupper($form['titulo']);   $titulo->tit_ref=$form['ref'];
                    $titulo->tit_otra_modalidad=mb_strtoupper($form['otra_modalidad']);
                    $titulo->tit_reconocimiento=$form['reconocimiento']=='on' ? 't':'';
                    $titulo->tit_fecha_folio=$form['fecha_folio'];
                    $gestion=date('Y',strtotime($form['fecha']));
                    if($titulo->tit_gestion!=$gestion){
                        $titulo->tit_gestion=$gestion;
                    }
                    //da formato al nombre del archivo
                    $nombreArch="";
                    if($tipo=='su' || $tipo=='ca'){
                        //return $form['nro'].'-'.$titulo->tit_gestion.'-'.$form['tipo'];
                        $nombreArch=TituloController::nombreArchivo($form['nro'],$form['tipo']).'-'.$titulo->tit_gestion.".pdf";

                    }else{
                        $nombreArch=TituloController::nombreArchivo($form['nro'],$form['tipo']).".pdf";
                    }

                    $ruta='alma/dt/'.$tipo.'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/';
                    $archivo=$nombreArch;
                    //para registrar la ruta de los eliminados
                    $archivoEliminado=array();
                    //caso para cambiar titulos sin afectar a los archivos
                    if($cambio){
                        if($titulo->tit_pdf!=''){
                            if ($form->hasFile('pdf')){
                                Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                                $valor= rand(0,999999999999);
                                Storage::move($ruta.$titulo->tit_pdf, 'trash/dt/'.$valor.'-'.$nombreArch);
                                //Storage::delete($ruta.$titulo->tit_pdf);
                                $titulo->tit_pdf=$nombreArch;
                                $archivoEliminado['pdf']=$valor.'-'.$nombreArch;

                            }else{
                                $valor= rand(0,999999999999);

                                Storage::move($ruta.$titulo->tit_pdf,$ruta.$nombreArch);
                                //Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                                //Storage::move($ruta.$titulo->tit_pdf, $ruta.$nombreArch);
                                $titulo->tit_pdf=$nombreArch;
                                $archivoEliminado['pdf']=$valor.'-'.$nombreArch;
                            }
                        }else{
                            if ($form->hasFile('pdf')){
                                Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                                $titulo->tit_pdf=$nombreArch;
                            }
                        }
                    }else{
                        if($titulo->tit_pdf!=''){
                            if ($form->hasFile('pdf')){
                                $valor= rand(0,999999999999);
                                Storage::move($ruta.$titulo->tit_pdf, 'trash/dt/'.$valor.'-'.$nombreArch);
                                Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                                $archivoEliminado['pdf']=$valor.'-'.$nombreArch;
                            }
                        }else{
                            if ($form->hasFile('pdf')){
                                Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                                $titulo->tit_pdf=$nombreArch;
                            }
                        }
                    }
                    //casos para subir antecedentes sin afectar a los archivos
                    $antecedente='A-'.$nombreArch;
                    if($cambio){
                        if($titulo->tit_antecedentes!=''){
                            if ($form->hasFile('pdf_ant')){
                                Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                                $valor= rand(0,999999999999);
                                Storage::move($ruta.$titulo->tit_antecedentes, 'trash/dt/'.$valor.'-'.$antecedente);
                                $titulo->tit_antecedentes=$antecedente;
                                $archivoEliminado['pdf_ant']=$valor.'-'.$antecedente;
                            }else{
                                Storage::move($ruta.$titulo->tit_antecedentes,$ruta.$antecedente);
                                //Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                                $titulo->tit_antecedentes=$antecedente;
                                $archivoEliminado['pdf_ant']=$valor.'-'.$antecedente;
                            }
                        }else{
                            if ($form->hasFile('pdf_ant')){
                                Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                                $titulo->tit_antecedentes=$antecedente;
                            }
                        }
                    }else{
                        if($titulo->tit_antecedentes!=''){
                            if ($form->hasFile('pdf_ant')){
                                $valor= rand(0,999999999999);
                                Storage::move($ruta.$titulo->tit_antecedentes, 'trash/dt/'.$valor.'-'.$antecedente);
                                Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                                $archivoEliminado['pdf_ant']=$valor.'-'.$antecedente;
                            }
                        }else{
                            if ($form->hasFile('pdf_ant')){
                                Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                                $titulo->tit_antecedentes=$antecedente;
                            }
                        }
                    }
                    $titulo->save();
                    $nuevo=$titulo;
                    $persona=Persona::find($titulo->id_per);
                    $antiguo=(object) array_merge($antiguo,$persona->toArray(),$archivoEliminado);

                    $persona->per_ci=$form['ci'];            $persona->per_pasaporte=$form['pass'];
                    $persona->per_apellido=mb_strtoupper($form['apellido']);            $persona->per_nombre=mb_strtoupper($form['nombre']);
                    $persona->per_sexo=$form['sexo'];            $persona->cod_nac=$form['nac'];
                    $persona->per_ci_exp=$form['expedido'];
                    $persona->save();
                    $nuevo=(object) array_merge($nuevo->toArray(),$persona->toArray());

                    if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
			            $da=Diploma_academico::find($form['ctit']);
    			        if($da){
                            	$da->cod_car=$form['car'];
                            	$da->save();
			                }else{
				                $diploma_a = Diploma_academico::create([
	                        		'cod_tit' => $titulo->cod_tit,
	                        		'cod_car' => $form['car'],
                    		]);
			            }
                    }
                    if($tipo=='re' || ($tipo=='tp' && $form['revalida']=='t')){
                        $re=Revalida::find($form['ctit']);
                        $antiguo=(object) array_merge((array)$antiguo,$re->toArray());
                        $re->cod_nac=$form['pais_origen'];
                        $re->re_fecha=$form['fecha_revalida'];             $re->re_universidad=strtoupper($form['universidad']);
                        $re->save();
                        $nuevo=(object) array_merge((array)$nuevo,$re->toArray());
                    }
                    //dd($antiguo);
                    $antiguo=json_encode($antiguo);
                    $nuevo=json_encode($nuevo);
                    SessionController::write('U',$antiguo,$nuevo,'Titulos','1',$titulo->cod_tit);
                    \Session::flash('exito','El titulo se ha actualizado correctamente el titulo');
                    return redirect('fe_titulo/'.$form['ctit']);
                }else{
                    $nombreArch="";
                    $gestion=date('Y',strtotime($form['fecha']));
                    if($tipo=='su' || $tipo=='ca'){
                        $nombreArch=TituloController::nombreArchivo($form['nro'],$form['tipo']).'-'.$gestion.".pdf";
                    }else{
                        $nombreArch=TituloController::nombreArchivo($form['nro'],$form['tipo']).".pdf";
                    }


                    if(!Storage::exists('alma/dt/'.$tipo.'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$nombreArch)){
                        $ci=$form['ci'];
                        $personaExistente=array();
                        if($ci!=''){
                            $personaExistente=DB::select("select id_per,per_ci,per_nombre,per_apellido from personas where per_ci='".$ci."'");
                        }
                        $persona="";
                        $id_per='';
                        $objetoCompleto='';
                        $nuevo="";
                        if(sizeof($personaExistente)<1){ //si no encuentra una persona con ese CI
                            $persona=Persona::create([
                                'per_ci'=>$form['ci'],
                                'per_pasaporte'=>$form['pass'],
                                'per_apellido'=>mb_strtoupper($form['apellido']),
                                'per_nombre'=>mb_strtoupper($form['nombre']),
                                'per_sexo'=>$form['sexo'],
                                'per_sistema'=>1,
                                'cod_nac'=>$form['nac'],
                                'per_ci_exp'=>$form['expedido'],
                            ]);
                            $id_per=$persona->id_per;
                            $objetoCompleto=$persona;
                        }else{
                            //si existe ese CI, compara que el nombre y el apellido sean el mismo, sino no saca un error
                            if($personaExistente[0]->per_nombre==strtoupper($form['nombre']) && $personaExistente[0]->per_apellido==strtoupper($form['apellido'])){
                                $id_per=$personaExistente[0]->id_per;
                                $persona_aux=Persona::find($id_per);
                                if($form['expedido']!=''){
                                    $persona_aux->per_ci_exp=$form['expedido'];
                                    $persona_aux->per_sexo=$form['sexo'];

                                }
                                $persona_aux->cod_nac=$form['nac'];
                                $persona_aux->save();
                            }else{
                                \Session::flash('error','No se puede guardar los datos personales debido a que el numero de CI: '  .$personaExistente[0]->per_ci
                                    .' esta registrado a nombre de: '.$personaExistente[0]->per_apellido.", ".$personaExistente[0]->per_nombre);
                                return redirect('l_titulo/'.$form['ct']);
                            }
                            $objetoCompleto=$personaExistente[0];
                        }
                        //dd($objetoCompleto);
                        $archivo='';
                        $ruta='alma/dt/'.$tipo.'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/';
                        if ($form->hasFile('pdf')){
                            $archivo=$nombreArch;
                            Storage::putFileAs($ruta, $form->file('pdf'),$nombreArch);
                        }
                        $antecedente='';
                        if ($form->hasFile('pdf_ant')){
                            $antecedente='A-'.$nombreArch;
                            Storage::putFileAs($ruta, $form->file('pdf_ant'),$antecedente);
                        }
                        $reconocimiento=$form['reconocimiento']=='on' ? 't':'';

                        $obs=0;
                        if($form['obs']!=''){
                            $obs=1;
                        }

                        $titulo=Titulo::create([
                            'cod_tom'=>$form['ct'],
                            'tit_nro_titulo'=>$form['nro'],
                            'tit_nro_folio'=>$form['folio'],
                            'tit_fecha_emision'=>$form['fecha'],
                            'tit_fecha_folio'=>$form['fecha_folio'],
                            'tit_grado'=>$form['grado'],
                            'cod_mod'=>$form['mod'],
                            'id_per'=>$id_per,
                            'tit_titulo'=>mb_strtoupper($form['titulo']),
                            'tit_ref'=>$form['ref'],
                            'tit_revalida'=>$form['revalida'],
                            'tit_pdf'=>$archivo,
                            'tit_antecedentes'=>$antecedente,
                            'tit_obs'=>$obs,
                            'tit_tipo'=>$tomo['tom_tipo'],
                            'tit_gestion'=>$gestion,
                            'tit_otra_modalidad'=>mb_strtoupper($form['otra_modalidad']),
                            'tit_reconocimiento'=>$reconocimiento,
                            'tit_usr'=>Auth::user()->id,
                        ]);

                        //dd($objetoCompleto);
                        $objetoCompleto=(object) array_merge((array)$objetoCompleto,$titulo->toArray());

                        if($obs==1){
                            $obs=T_observacion::create([
                                'cod_tit'=>$titulo->cod_tit,
                                'obs_observacion'=>$form['obs'],
                                'obs_fecha'=>date('d/m/Y'),
                            ]);
                        }
                        if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
                            $da=Diploma_academico::create([
                                'cod_tit'=>$titulo->cod_tit,
                                'cod_car'=>$form['car'],
                            ]);
                        }
                        if($tipo=='re' || ($tipo=='tp' && $form['revalida']=='t')){
                            $re=Revalida::create([
                                'cod_tit'=>$titulo->cod_tit,
                                'cod_nac'=>$form['pais_origen'],
                                're_fecha'=>$form['fecha_revalida'],
                                're_universidad'=>mb_strtoupper($form['universidad']),
                            ]);
                            $objetoCompleto=(object) array_merge((array)$objetoCompleto,$re->toArray());
                        }
                        $nuevo=json_encode($objetoCompleto);
                        SessionController::write('C','',$nuevo,'Titulos','1',$titulo->cod_tit);
                        \Session::flash('exito','El titulo se ha creado exitosamente');
                        return redirect('l_titulo/'.$form['ct']);
                    }else{
                        \Session::flash('error','No se ha podido guardar debido a que ya existe un archivo con el numero '.$form['nro']);
                        return redirect('l_titulo/'.$form['ct']);
                    }
                }
            }else{
                \Session::flash('error','No se ha podido guardar debido a que ya existe un título con el número '.$form['nro']);
                if(!isset($form['ctit'])){
                    return redirect('l_titulo/'.$form['ct']);
                }else{
                    return redirect('fe_titulo/'.$form['ctit']);
                }
            }
        }else{
            \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede modificar el título');
            return redirect('fe_titulo/'.$form['ctit']);
        }
    }
    public function ListarTituloSinTomo($gestion,$tipo){

        $tomo=Tomo::all()->where('tom_gestion','=',$gestion)
            ->where('tom_tipo','=',$tipo)
            ->where('tom_numero','=','0')->first();
        $cod_tomo=0;
        //dd($tomo);
        if(!$tomo){
            Storage::makeDirectory('alma/dt/'.$tipo.'/'.$gestion.'/0');
            $tomo=Tomo::create([
                'tom_numero'=>0,
                'tom_gestion'=>$gestion,
                'tom_tipo'=>$tipo,
            ]);
            $carrera=Carrera::all()->sortBy('cod_car');
            foreach ($carrera as $c)
            {
                Tomo_carrera::create(['cod_car'=>$c['cod_car'],'cod_tom'=>$tomo['cod_tom'], ]);
            }
            $cod_tomo=$tomo['cod_tom'];
        }else{
            $cod_tomo=$tomo->cod_tom;
        }
        return redirect('l_titulo/'.$cod_tomo);
    }
    public function l_SinTomo($gestion, $tipo,$cod_tomo_actual){
        $tomo=Tomo::all()->where('tom_gestion','=',$gestion)
            ->where('tom_tipo','=',$tipo)
            ->where('tom_numero','=',0)->first();
        $cod_tom=0;
        if($tomo){
            $cod_tom=$tomo->cod_tom;
        }
        $titulo=DB::table('titulos')
            ->join('personas','titulos.id_per','=','personas.id_per')
            ->where('cod_tom','=',$cod_tom)
            ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','per_nombre','per_ci')
            ->orderBy('per_apellido','ASC')
            ->orderBy('per_nombre','ASC')
            ->get();
        return view('diplomas.titulo.l_sintomo',compact('titulo','cod_tom','cod_tomo_actual'));
    }
    public function asignar_tomo(Request $form){
        $titulo=Titulo::find($form['cod_tit']);
        $tomo_actual=Tomo::find($form['cod_tomo_actual']);
        $tomo_antiguo=Tomo::find($form['cod_tomo_antiguo']);
        $mensaje='';
        $titulos=Titulo::where('tit_nro_titulo','=',$titulo->tit_nro_titulo)
            ->where('cod_tom','=',$form['cod_tomo_actual'])->first();

        if(!$titulos) {
            $ruta1 = 'alma/dt/' . $tomo_actual['tom_tipo'] . '/' . $tomo_actual['tom_gestion'] . '/' . $tomo_actual['tom_numero'] . '/';
            $ruta2 = 'alma/dt/' . $tomo_antiguo['tom_tipo'] . '/' . $tomo_antiguo['tom_gestion'] . '/' . $tomo_antiguo['tom_numero'] . '/';

            if ($titulo->tit_pdf != '') {
                Storage::move($ruta2 . $titulo->tit_pdf, $ruta1 . $titulo->tit_pdf);
            }
            if ($titulo->tit_antecedentes != '') {
                Storage::move($ruta2 . $titulo->tit_antecedentes, $ruta1 . $titulo->tit_antecedentes);
            }
            $titulo->cod_tom = $form['cod_tomo_actual'];
            $titulo->save();
        }else{
            $mensaje='Ya existe ese título en este tomo';
        }
        return $mensaje;
    }
    public function fe_titulo($id_titulo){

        $titulo_ref=Titulo::find($id_titulo);
        $tomo=Tomo::find($titulo_ref['cod_tom']);
        $c_tomo=new TomoController();
        $tipo_completo=$c_tomo->tipoTomo($tomo['tom_tipo']);
        $tipo=$tomo['tom_tipo'];
        $tipo_revalida='f';
        $titulo="";
        $revalida="";
        if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){
            $titulo=DB::table('titulos')
                ->join('personas','titulos.id_per','=','personas.id_per')
                ->leftJoin('nacionalidads','personas.cod_nac','=','nacionalidads.cod_nac')
                ->leftjoin('diploma_academicos','titulos.cod_tit','=','diploma_academicos.cod_tit')
                ->leftJoin('carreras','diploma_academicos.cod_car','=','carreras.cod_car')
                ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                ->where('titulos.cod_tit','=',$id_titulo)
                ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','tit_titulo','tit_pdf','tit_antecedentes',
                    'tit_fecha_folio','tit_otra_modalidad','per_nombre','car_nombre','fac_nombre','tit_grado','diploma_academicos.cod_car','per_ci','per_sexo',
                    'per_pasaporte','titulos.cod_mod','mod_nombre','personas.cod_nac','nac_nombre','tit_revalida','per_ci_exp','fac_abreviacion')
                ->get();

//return sizeof($titulo);
            if($tipo=='tp' && $titulo[0]->tit_revalida=='t'){
                $tipo_revalida='t';
                $revalida=DB::table('revalidas')
                    ->leftJoin('nacionalidads','revalidas.cod_nac','=','nacionalidads.cod_nac')
                    ->where('cod_tit','=',$id_titulo)
                    ->select('re_universidad','re_fecha','revalidas.cod_nac','nac_nombre')
                    ->get();
            }
        }else{
            if($tipo=='db' || $tipo=='su' || $tipo=='re'){
                $titulo=DB::table('titulos')
                    ->join('personas','titulos.id_per','=','personas.id_per')
                    ->leftJoin('nacionalidads','personas.cod_nac','=','nacionalidads.cod_nac')
                    ->where('cod_tit','=',$id_titulo)
                    ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','per_nombre','tit_fecha_emision','tit_pdf','tit_antecedentes',
                        'tit_fecha_folio','tit_grado','per_ci','per_pasaporte','per_sexo','per_apellido','per_nombre','nac_nombre','personas.cod_nac',
                        'tit_titulo','tit_ref','per_ci_exp','tit_reconocimiento')
                    ->get();
                if($tipo=='re'){
                    $revalida=DB::table('revalidas')
                        ->leftJoin('nacionalidads','revalidas.cod_nac','=','nacionalidads.cod_nac')
                        ->where('cod_tit','=',$id_titulo)
                        ->select('re_universidad','re_fecha','revalidas.cod_nac','nac_nombre')
                        ->get();
                    $tipo_revalida='t';
                }
            }else{
                if($tipo=='tpos' || $tipo=='di'){
                    $titulo=DB::table('titulos')
                        ->join('personas','titulos.id_per','=','personas.id_per')
                        ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                        ->leftJoin('nacionalidads','personas.cod_nac','=','nacionalidads.cod_nac')
                        ->where('cod_tit','=',$id_titulo)
                        ->select('tit_nro_titulo','tit_nro_folio','titulos.cod_tit','per_apellido','tit_fecha_emision','tit_titulo',
                            'tit_fecha_folio','tit_otra_modalidad','tit_pdf','tit_antecedentes','per_nombre','mod_nombre','tit_grado','per_ci','per_sexo',
                            'per_pasaporte','titulos.cod_mod','personas.cod_nac','nac_nombre','per_ci_exp')
                        ->get();
                }
            }
        }
        //dd($titulo);
        $carrera=array();
        if($tomo->tom_numero=='0'){
            $carrera = DB::table('carreras')->join('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                ->select('car_nombre', 'carreras.cod_car','fac_abreviacion')
                ->orderBy('fac_abreviacion')
                ->orderBy('car_nombre')
                ->get();
        }else {
            $carrera=DB::table('carreras')
                ->join('tomo_carreras','carreras.cod_car','=','tomo_carreras.cod_car')
                ->leftJoin('facultads','carreras.cod_fac','=','facultads.cod_fac')
                ->where('tomo_carreras.cod_tom',$tomo['cod_tom'])
                ->select('car_nombre','carreras.cod_car','fac_abreviacion')
                ->orderBy('fac_abreviacion')
                ->orderBy('car_nombre')
                ->get();
        }

        $nacionalidad=Nacionalidad::all()->sortBy('nac_nombre');
        $modalidad=Modalidad::all();
        $grado=$this->grados($tomo['tom_tipo']);

        if($tipo_revalida=='t'){
            return view('diplomas.titulo.editar_titulo',compact('titulo',
                'tomo','tipo_completo','carrera','nacionalidad','modalidad','grado','tipo','revalida','tipo_revalida'));
        }else{
            //return sizeof($titulo)." - ".$id_titulo;
            return view('diplomas.titulo.editar_titulo',compact('titulo','tomo','tipo_completo','carrera','nacionalidad','modalidad','grado','tipo'));
        }
    }
    public function f_eli_titulo($id){
        $titulo=DB::table('titulos')
                    ->join('personas','titulos.id_per','=','personas.id_per')
                    ->where('cod_tit',$id)
                    ->select('per_nombre','per_apellido','cod_tit','tit_nro_titulo','cod_tom','cod_tom')
                    ->get();
        $tomo=Tomo::find($titulo[0]->cod_tom);
        $tipoUnitario=TomoController::tipoTomoUnitario($tomo['tom_tipo']);
        return view('diplomas.titulo.f_eli_titulo',compact('titulo','tipoUnitario'));
    }
    public function e_Titulo(Request $form){
        if(isset($form['ctit'])){
            $titulo=Titulo::find($form['ctit']);
            $tomo=Tomo::find($titulo['cod_tom']);
            if($tomo->tom_cerrado!='t'){
                DB::delete("delete from diploma_academicos where cod_tit=".$titulo['cod_tit']);
                DB::delete("delete from t_observacions where cod_tit=".$titulo['cod_tit']);
                DB::delete("delete from revalidas where cod_tit=".$titulo['cod_tit']);
		//return "hola";
                $persona=Persona::find($titulo['id_per']);
                $antiguo='';
                $objeto='';

                $objeto=(object) array_merge($titulo->toArray(),$persona->toArray());
                $titulo->delete();

                $pdf['pdf']='';
                $pdf['pdf_ant']='';
                $tomo=Tomo::find($titulo['cod_tom']);
                if($titulo['tit_pdf']!=''){
                    $ruta='alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$titulo['tit_pdf'];
                    if(Storage::exists($ruta)){
                        $valor= 'E'.rand(0,999999999999);
                        Storage::move($ruta, 'trash/dt/'.$valor.'-'.$titulo->tit_pdf);
                        //Storage::delete($ruta);
                        $pdf['pdf']=$valor.'-'.$titulo->tit_pdf;
                    }
                }
                if($titulo['tit_antecedentes']!=''){
                    $ruta='alma/dt/'.$tomo['tom_tipo'].'/'.$tomo['tom_gestion'].'/'.$tomo['tom_numero'].'/'.$titulo['tit_antecedentes'];
                    if(Storage::exists($ruta)){
                        $valor= 'E'.rand(0,999999999999);
                        Storage::move($ruta, 'trash/dt/'.$valor.'-'.$titulo->tit_antecedentes);
                        $pdf['pdf_ant']=$valor.'-'.$titulo->tit_antecedentes;
                    }
                }
                $objeto=(object) array_merge((array) $objeto,$pdf);
                $antiguo=json_encode($objeto);
                SessionController::write('D',$antiguo,'','Titulos','1',$titulo->cod_tit);
                \Session::flash('exito','Se ha eliminado exitosamente el título '.$titulo['tit_nro_titulo']);
                return redirect('l_titulo/'.$form['ct']);
            }else{
                \Session::flash('error','El tomo '.$tomo->tom_numero.' ya esta consolidado, no se puede eliminar el título');
                return redirect('l_titulo/'.$tomo->cod_tom);
            }
        }else{
            \Session::flash('error','No se ha podido eliminar el título');
            return redirect('l_titulo/'.$form['ct']);
        }
    }
    public function verificar_titulos(Request $form){
        if($form['rango']==''){
            $form['rango']=0;
        }
        $form->validate([
           'gestion'=>'required|numeric',
           'tipo'=>['required', Rule::in(['di','ca','da','db','tp','tpos','re','su'])],
           'rango'=>'numeric',
        ]);

        $rango=$form['rango'];
        $gestion=$form['gestion'];
        $tipo=$form['tipo'];
            $max=DB::select("select max(tit_nro_titulo) from titulos where tit_gestion=".$gestion." and tit_tipo='".$tipo."'");
        if($form['rango']==0){
            $rango=$max[0]->max;
        }

        $titulos=DB::table('titulos')->where('tit_gestion','=',$gestion)
            ->select('tit_nro_titulo')
            ->where('tit_tipo','=',$tipo)->OrderBy('tit_nro_titulo')->get();
        $faltantes="";
        $existe=0;
        //dd($titulos);
        for($i=1;$i<=$rango;$i++){
            $existe=0;
            foreach ($titulos as $t){
                if($i==$t->tit_nro_titulo){
                    $existe=1;
                }
            }
            if($existe==0){
                $faltantes.=$i." - ";
            }
        }
        if($faltantes!=''){
            $faltantes=substr($faltantes, 0, -3);
        }
        return view('diplomas.titulo.f_verificacion_faltantes',compact('rango','faltantes','gestion','tipo','max'));
    }
    public function f_cambiarTomo($cod_tit){
        $titulo=Titulo::find($cod_tit);
        $tomo=Tomo::find($titulo->cod_tom);
        $persona=Persona::find($titulo->id_per);
        $tomos=Tomo::all()
            ->where('tom_gestion','=',$tomo->tom_gestion)
            ->where('tom_tipo','=',$tomo->tom_tipo)->sortBy('tom_numero');
        return view('diplomas.titulo.f_cambiarTomo',compact('titulo','tomo','persona','tomos'));
    }
    public function o_tomos($gestion,$tipo){
        $tomos=Tomo::all()
            ->where('tom_gestion','=',$gestion)
            ->where('tom_tipo','=',$tipo)->sortBy('tom_numero');;
        return view('diplomas.titulo.l_tomos',compact('tomos'));
    }
    public function cambiar_tomo(Request $form){
        $form->validate([
            'titulo'=>'required',
            'tomo'=>'required',
        ]);

        $titulo=Titulo::find($form['titulo']);
        $tomoAntiguo=Tomo::find($titulo->cod_tom);
        $tomoNuevo=Tomo::find($form['tomo']);
        if($form['tomo']!=$tomoAntiguo->cod_tom){

            $titulos=DB::table('titulos')->where('cod_tom','=',$tomoNuevo->cod_tom)
                ->where('tit_nro_titulo','=',$titulo->tit_nro_titulo)
                ->where('tit_tipo','=',$titulo->tit_tipo)
                ->first();

            if($titulos) {
                \Session::flash('error', 'Ya existe un titulo ' . $titulo->tit_nro_titulo . ' en el tomo ' . $tomoNuevo->tom_numero);
            }else{

                    $rutaAntigua='alma/dt/'.$tomoAntiguo->tom_tipo.'/'.$tomoAntiguo->tom_gestion.'/'.$tomoAntiguo->tom_numero.'/';
                    $rutaNueva='alma/dt/'.$tomoNuevo->tom_tipo.'/'.$tomoNuevo->tom_gestion.'/'.$tomoNuevo->tom_numero.'/';
                    try {
                        if($titulo->tit_pdf!='') {
                            if (Storage::exists($rutaAntigua . $titulo->tit_pdf)) {
                                    Storage::move($rutaAntigua . $titulo->tit_pdf, $rutaNueva . $titulo->tit_pdf);
                            }
                        }
                        if($titulo->tit_antecedentes!='') {
                            if (Storage::exists($rutaAntigua . $titulo->tit_antecedentes)) {
                                    Storage::move($rutaAntigua . $titulo->tit_antecedentes, $rutaNueva . $titulo->tit_antecedentes);
                            }
                        }
                    }catch (Exception $e){
                        return $e;
                    }


                $titulo->cod_tom=$form['tomo'];
                $titulo->tit_gestion=$tomoNuevo->tom_gestion;
                $titulo->save();

                \Session::flash('exito','Se realizado exitosamente el cambio del titulo '.$titulo->tit_nro_titulo.' al tomo '.$tomoNuevo->tom_numero);
            }

        }else{
            \Session::flash('error','No puede realizar el cambio al mismo tomo');
        }
        return redirect('l_titulo/'.$tomoAntiguo->cod_tom);
    }

    public function grados($tipo){
        $grados=array();
        switch ($tipo){
            case 'db': $grados[0]='BACHILLER'; break;
            case 'da': $grados[0]='LICENCIATURA';$grados[1]='TECNICO MEDIO';$grados[2]='TECNICO SUPERIOR'; break;
            case 'ca': $grados[0]='AUXILIAR'; break;
            case 'tp': $grados[0]='LICENCIATURA';$grados[1]='TECNICO MEDIO';$grados[2]='TECNICO SUPERIOR'; $grados[3]='AUXILIAR';break;
            case 'tpos': $grados[0]='ESPECIALIDAD';$grados[1]='MAESTRIA';$grados[2]='DOCTORADO'; break;
            case 'di': $grados[0]='DIPLOMADO'; break;
            case 'su': $grados[0]='BACHILLER';$grados[1]='LICENCIATURA';$grados[2]='TECNICO MEDIO';$grados[3]='TECNICO SUPERIOR';$grados[4]='AUXILIAR';
                        $grados[5]='DIPLOMADO';$grados[6]='ESPECIALIDAD';$grados[7]='MAESTRIA';$grados[8]='DOCTORADO';
            break;
            case 're': $grados[0]='BACHILLER';$grados[1]='LICENCIATURA';$grados[2]='TECNICO MEDIO';$grados[3]='TECNICO SUPERIOR';$grados[4]='AUXILIAR';
                        $grados[5]='DIPLOMADO';$grados[6]='ESPECIALIDAD';$grados[7]='MAESTRIA';$grados[8]='DOCTORADO';
                break;
        }
    return $grados;
    }
    public static function nombreArchivo($nroTitulo,$tipo){
        $tipo=strtoupper($tipo);
        $tam=strlen ($nroTitulo);
        $nombreArch="";
        switch ($tam){
            case 1: $nombreArch=$tipo.'-0000'.$nroTitulo; break;
            case 2: $nombreArch=$tipo.'-000'.$nroTitulo; break;
            case 3: $nombreArch=$tipo.'-00'.$nroTitulo; break;
            case 4: $nombreArch=$tipo.'-0'.$nroTitulo; break;
            case 5: $nombreArch=$tipo.'-'.$nroTitulo; break;
        }
        return $nombreArch;
    }
}
