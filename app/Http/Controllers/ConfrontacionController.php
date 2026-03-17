<?php

namespace App\Http\Controllers;

use App\Models\D_confrontacion;
use App\Models\D_tramita;
use App\Models\Tramita;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfrontacionController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:crear traleg - srv'], ['only' => ['generar_numero']]);
        $this->middleware(['permission:crear traleg - srv'], ['only' => ['generar_numero_busqueda']]);
        $this->middleware(['permission:crear traleg - srv'], ['only' => ['g_confrontacion']]);
        $this->middleware(['permission:generar glosa docleg - srv'], ['only' => ['f_busqueda_encontrado','g_busqueda_encontrado']]);


    }
    public function generar_numero(Request $form){

        $numero=DB::table('tramitas')->where('tra_fecha_solicitud','=',$form['fecha'])->max('tra_numero');
        $tramite=Tramita::create([
            'tra_numero'=>$numero+1,
            'tra_gestion'=>date('y'),
            'tra_fecha_solicitud'=>$form['fecha'],
            'tra_tipo_tramite'=>'F',
        ]);
        $lista_tramites=Tramite::all()->where('tre_hab','=','t')->sortBy('tre_nombre')
            ->where('tre_tipo','=','F');
        SessionController::write('C','',json_encode($tramite),'tramitas','3',$tramite->cod_tra);
        return view('servicios.tra_legalizacion.numero_confrontacion',compact('tramite','lista_tramites'));
    }
    public function g_confrontacion(Request $form){

        $tramita=Tramita::find($form['ctra']);

        $año_tramita=date('Y',strtotime($tramita->tra_fecha_solicitud));
        $numero_tramite=DB::table('d_tramitas')->where('dtra_gestion_tramite','=',$año_tramita)->max('dtra_numero_tramite');
        $numero_tramite+=1;

        $d_tramita=D_tramita::create([
            'cod_tre'=>$form['tramite'],
            'cod_tra'=>$form['ctra'],
            'dtra_tipo'=>'F',
            'dtra_valorado'=>$form['valorado'],
            'dtra_numero_tramite'=>$numero_tramite,
            'dtra_gestion_tramite'=>$año_tramita,
            'dtra_fecha_recojo'=>date('d/m/Y H:i:s'),
            'dtra_fecha_firma'=>date('d/m/Y H:i:s'),
            'dtra_estado_doc'=>'4',
            'dtra_entregado'=>'t',
        ]);

        /*
         * ci=Cedula de identidad
         * cn=Certificado de nacimiento
         * lm=Libreta de servicio militar
         * ce=Carnet de extranjeria
         * pa=Pasaporte
         * lc=Libreta de colegio
         */
        $docs=0;
        if($form['ci']){
            D_confrontacion::create([
                'dcon_doc'=>$form['ci'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($form['cn']){
            D_confrontacion::create([
                'dcon_doc'=>$form['cn'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($form['lm']){
            D_confrontacion::create([
                'dcon_doc'=>$form['lm'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($form['pa']){
            D_confrontacion::create([
                'dcon_doc'=>$form['pa'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($form['ce']){
            D_confrontacion::create([
                'dcon_doc'=>$form['ce'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($form['lc']){
            D_confrontacion::create([
                'dcon_doc'=>$form['lc'],
                'cod_dtra'=>$d_tramita->cod_dtra,
            ]);
            $docs=1;
        }
        if($docs==0){
            $d_tramita->delete();
            $tramita->delete();
        }
        //
        $nuevo=(object) array_merge($d_tramita->toArray(),$form->toArray());
        $nuevo=json_encode($nuevo);
        SessionController::write('C','',$nuevo,'d_tramitas','3',$d_tramita->cod_dtra);
        return  redirect('listar tramite legalizacion/'.date('Y-m-d',strtotime($tramita->tra_fecha_solicitud)));
    }
    public function generar_numero_busqueda(Request $form){
        $numero=DB::table('tramitas')->where('tra_fecha_solicitud','=',$form['fecha'])->max('tra_numero');
        $tramite=Tramita::create([
            'tra_numero'=>$numero+1,
            'tra_gestion'=>date('y'),
            'tra_fecha_solicitud'=>$form['fecha'],
            'tra_tipo_tramite'=>'B',
        ]);
        $lista_tramites=Tramite::all()->where('tre_hab','=','t')->sortBy('tre_nombre')
            ->where('tre_tipo','=','B');
        SessionController::write('C','',json_encode($tramite),'tramitas','3',$tramite->cod_tra);
        return view('servicios.tra_legalizacion.numero_busqueda',compact('tramite','lista_tramites'));
    }
    public function cancelar_tra($cod_tra){
        $tramita=Tramita::find($cod_tra);
        $docleg=D_tramita::all()->where('cod_tra','=',$cod_tra);
        if(sizeof($docleg)<1 && ($tramita->tra_tipo_tramite=="F" || $tramita->tra_tipo_tramite=="B")){
            $tramita->delete();
        }
        return  redirect('listar tramite legalizacion/'.date('Y-m-d',strtotime($tramita->tra_fecha_solicitud)));
    }
    public function f_busqueda_encontrado($cod_dtra){
        $docleg=D_tramita::find($cod_dtra);
        $tramita=Tramita::find($docleg->cod_tra);
        $tramite=Tramite::find($docleg->cod_tre);
        //$docleg->dtra_generado='t';
        $docleg->save();
        $documento=D_confrontacion::all()->where('cod_dtra','=',$docleg->cod_dtra)->first();
        return view('servicios.tra_legalizacion.f_registro_busqueda',compact('docleg','tramite','documento','tramite'));
    }
    public function g_busqueda_encontrado(Request $form){

        $docleg=D_tramita::find($form['cdtra']);
        $tramite=Tramita::find($docleg->cod_tra);
        $docleg->dtra_generado='t';
        $docleg->save();
        SessionController::write('U','','Encontrado','d_tramitas','3',$docleg->cod_dtra);
        return redirect('datos tramite legalizacion/'.$docleg->cod_tra);
    }
    public static function nombreDocumento($doc){
        switch ($doc) {
            case 'ci': return "Cédula de identidad"; break;
            case 'cn': return "Certificado de nacimiento"; break;
            case 'lm': return "Libreta de servicio militar"; break;
            case 'ce': return "Carnet de extranjería"; break;
            case 'pa': return "Passaporte"; break;
            case 'lc': return "Libreta de colegio"; break;
        }
    }
}
