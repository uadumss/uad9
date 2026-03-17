<?php

namespace App\Http\Controllers;

use App\Models\Glosa;
use App\Models\Tramite;
use App\Models\Tramita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteController extends Controller
{
    public function lista_tramite(){
        $tramites=Tramite::all()->sortBy('tre_tipo');
        return view('servicios.tramite.l_legalizacion',compact('tramites'));
    }
    public function g_tramite(Request $form){
        /*
         * TIPO DE TRAMITE =    C->CERTIFICACION
         *                      F->CONFRONTACION
         *                      L->LEGALIZACION
         *                      B->BUSQUEDA
         *                      A->NO-ATENTADO
         *                      E->CONSEJEROS
         */
        $sello=$form['sello']=='on' ? 't': '' ;
        if(isset($form['ct'])){
            $tramite=Tramite::find($form['ct']);
            $tramite->tre_nombre=$form['nombre'];
            $tramite->tre_numero_cuenta=$form['cuenta'];
            $tramite->tre_costo=$form['costo'];
            $tramite->tre_duracion=$form['duracion'];
            $tramite->tre_desc=$form['desc'];
            $tramite->tre_titulo=$form['titulo'];
            $tramite->tre_solo_sello=$sello;
            $tramite->tre_buscar_en=$form['buscar_en'];
            $tramite->tre_titulo_interno=$form['titulo_interno'];
            $tramite->save();
            \Session::flash('exito', 'Se ha actualizado exitosamente el trámite');
            return redirect('listar tramites');
        }else{

            $tramite=Tramite::create([
                'tre_nombre'=>$form['nombre'],
                'tre_numero_cuenta'=>$form['cuenta'],
                'tre_costo'=>$form['costo'],
                'tre_duracion'=>$form['duracion'],
                'tre_desc'=>$form['desc'],
                'tre_titulo'=>$form['titulo'],
                'tre_solo_sello'=>$sello,
                'tre_buscar_en'=>$form['buscar_en'],
                'tre_titulo_interno'=>$form['titulo_interno'],
                'tre_tipo'=>$form['tipo'],
                'tre_hab'=>'t',
            ]);
            \Session::flash('exito', 'Se ha creado exitosamente el trámite');
            return redirect('listar tramites');
        }
    }
    public function hab_tramite($cod_tre){
        $tramite=Tramite::find($cod_tre);
        if($tramite->tre_hab=='t'){
            $tramite->tre_hab='f';
            $tramite->save();
        }else{
            $tramite->tre_hab='t';
            $tramite->save();
        }
        \Session::flash('exito', 'Se ha actualizado exitosamente el trámite');
        return redirect('listar tramites');
    }
    public function fe_tramite($tipo,$cod_tre){
        $tramite=($cod_tre==0)? "": Tramite::find($cod_tre);
        return view('servicios.tramite.fe_tramite',compact('tramite','cod_tre','tipo'));
    }
    public function f_eli_tramite($cod_tre){
        $tramite=Tramite::find($cod_tre);
        $tramites=DB::select("select count(cod_dtra) as cantidad from d_tramitas where cod_tre=".$cod_tre);
        $glosa=Glosa::all()->where('cod_tre','=',$cod_tre);
        return view('servicios.tramite.f_eli_tramite',compact('tramite','tramites','glosa'));
    }
    public function eli_tramite(Request $form){
        if(isset($form['ct'])){
            $tramites=DB::table('d_tramitas')->where('cod_tre','=',$form['ct'])->count();
            if($tramites>0){
                \Session::flash('error', 'No se puede eliminar el la legalización debido a que tiene trámites registrados');
                return redirect('listar tramites');
            }else{
                $tramite=Tramite::find($form['ct']);
                $tramite->delete();
                \Session::flash('exito', 'Se ha eliminado exitosamente la legalización');
                return redirect('listar tramites');
            }
        }
    }
}
