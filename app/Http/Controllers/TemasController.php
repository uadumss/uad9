<?php

namespace App\Http\Controllers;



use App\Imports\ImportarTema;

use App\Models\Resolucion;
use App\Models\Tema;
use App\Models\Temas_resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use function Sodium\compare;
use ZipArchive;


class TemasController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:acceder a temas - rr'], ['only' => [
            'l_temas',
            'fe_tema',
            'g_tema',
            'f_eli_tema',
            'eli_tema',
            'l_tema_resolucion',
            'fe_tema_resolucion',
            'mostrar_pdf_temas',
            'fe_asignar_resolucion',
            'asignar_resolucion',
            'l_tema_resolucion_modal',
            'f_eli_resolucion_tema',
            'eli_resolucion_tema',
            'actualizar_lista_tema',
            'descargar_resoluciones_temas',
            ]]);
    }
    public function l_temas(){
        $id=Auth::user()->id;
        $temas=Tema::where('id','=',$id)->get();
        return view('resoluciones.temas.l_temas',compact('temas'));
    }
    public function fe_tema($cod_tem){
        if($cod_tem==0){
            return view('resoluciones.temas.fe_tema',compact('cod_tem'));
        }else{
            $tema=Tema::find($cod_tem);
            return view('resoluciones.temas.fe_tema',compact('cod_tem','tema'));
        }
    }
    public function g_tema(Request $form){
        $form->validate([
            'titulo'=>'required'
        ]);

        if(isset($form['ct'])){
            $tema=Tema::find($form['ct']);
            $tema->tem_titulo=$form['titulo'];
            $tema->tem_des=$form['des'];
            $tema->save();
            \Session::flash('exito','Se ha editado con exito el tema de interes');
        }else{
            $uuid=(String)Str::uuid();
            $tema=Tema::create([
                'cod_tem'=>$uuid,
                'id'=>Auth::user()->id,
                'tem_titulo'=>$form['titulo'],
                'tem_des'=>$form['des'],
            ]);
            \Session::flash('exito','Se ha creado con exito el tema de interes');
        }
        return redirect('temas interes');
    }
    public function f_eli_tema($cod_tem){
        $tema=Tema::find($cod_tem);
        $temas_resoluciones=Temas_resolucion::where('cod_tem','=',$cod_tem)->get();
        $eliminar=sizeof($temas_resoluciones)>0?'0':'1';
        return view('resoluciones.temas.f_eli_tema',compact('tema','eliminar'));
    }
    public function eli_tema(Request $form){
        //dd($form);
        $form->validate([
           'ct'=>'required',
        ]);
        $tema=Tema::find($form['ct']);
        if($tema){
            $tema_resolucion=Temas_resolucion::where('cod_tem','=',$form['ct'])->get();
            if(sizeof($tema_resolucion)>0) {
                \Session::flash('Error','No se puedo eliminar el tema');
            }else{
                $tema->delete();
                \Session::flash('exito','Se ha eliminado con exito el tema de interes');
            }
        }
        return redirect('temas interes');
    }

    //========================TEMAS RESOLUCION====================
    public function l_tema_resolucion($cod_tem){
        $tema=Tema::find($cod_tem);

        $tema_resolucion=DB::table('public.tema_resolucion')
            ->join('public.resolucions','tema_resolucion.cod_res','=','resolucions.cod_res')
            ->join('public.tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->select('resolucions.*','tema_resolucion.cod_tr','tomos.*')
            ->where('cod_tem','=',$cod_tem)
            ->orderBy('res_numero')
            ->get();
        return view('resoluciones.temas.tema_resolucion.l_tema_resolucion',compact('tema','tema_resolucion'));
    }
    public function fe_tema_resolucion($cod_tem){
        $tema=Tema::find($cod_tem);
        $pagina=0;
        $tema_resolucion=DB::table('public.tema_resolucion')
            ->join('public.resolucions','tema_resolucion.cod_res','=','resolucions.cod_res')
            ->select('resolucions.*','tema_resolucion.cod_tr')
            ->where('cod_tem','=',$cod_tem)
            ->orderBy('res_numero')
            ->get();
        return view('resoluciones.temas.tema_resolucion.fe_tema_resolucion',compact('tema','tema_resolucion','pagina'));
    }
    public function mostrar_pdf_temas($cod_res){
        $resolucion=Resolucion::find($cod_res);
        return view('resoluciones.temas.tema_resolucion.pdf',compact('cod_res','resolucion'));
    }
    public function fe_asignar_resolucion($cod_tem,$cod_res){

        $resolucion_asignada=DB::select("select count(cod_tr) as cantidad from public.tema_resolucion where cod_res=".$cod_res." and cod_tem='".$cod_tem."'");
        $resolucion=Resolucion::find($cod_res);
        //dd($resolucion_asignada);
        return view('resoluciones.temas.tema_resolucion.fe_asignar_resolucion',compact('resolucion','resolucion_asignada','cod_tem'));
    }
    public function asignar_resolucion(Request $form){
        $resolucion_asignada=DB::select("select count(cod_tr) as cantidad from public.tema_resolucion where cod_res=".$form['cr']." and cod_tem='".$form['ct']."'");
        if($resolucion_asignada[0]->cantidad==0){
            $uuid=(String)Str::uuid();
            $tr=Temas_resolucion::create([
                'cod_tr'=>$uuid,
                'cod_res'=>$form['cr'],
                'cod_tem'=>$form['ct'],
            ]);
            \Session::flash('exito','Se ha añadido con exito la resolución al tema de interes');
        }else{
            \Session::flash('Error','Ya se ha asignado la resolución al tema anteriormente');
        }
        return redirect('l_resolucion_tema_modal/'.$form['ct']);
    }

    public function l_tema_resolucion_modal($cod_tem){
        //return "hola";

        $tema_resolucion=DB::table('public.tema_resolucion')
            ->join('public.resolucions','tema_resolucion.cod_res','=','resolucions.cod_res')
            ->select('resolucions.*','tema_resolucion.cod_tr')
            ->where('cod_tem','=',$cod_tem)
            ->orderBy('res_numero')
            ->get();

        return view('resoluciones.temas.tema_resolucion.l_tema_resolucion_modal',compact('tema_resolucion'));
    }
    public function f_eli_resolucion_tema($cod_tr){
        $tema_resolucion=Temas_resolucion::find($cod_tr);
        $tema=Tema::find($tema_resolucion->cod_tem);
        $resolucion=Resolucion::find($tema_resolucion->cod_res);
        return view('resoluciones.temas.tema_resolucion.f_eli_resolucion_tema',compact('resolucion','tema','tema_resolucion'));
    }
    public function eli_resolucion_tema(Request $form){
        $form->validate([
           'ctr'=>'required',
        ]);
        $tema_resolucion=Temas_resolucion::find($form['ctr']);
        $cod_tem=$tema_resolucion->cod_tem;
        $tema_resolucion->delete();
        return redirect('l_resolucion_tema_modal/'.$cod_tem);
    }
    public function actualizar_lista_tema($cod_tem){
        $tema_resolucion=DB::table('public.tema_resolucion')
            ->join('public.resolucions','tema_resolucion.cod_res','=','resolucions.cod_res')
            ->select('resolucions.*','tema_resolucion.cod_tr')
            ->where('cod_tem','=',$cod_tem)
            ->orderBy('res_numero')
            ->get();
        return view('resoluciones.temas.tema_resolucion.l_actualizar_tema',compact('tema_resolucion'));
    }
    public function descargar_resoluciones_temas($cod_tem){
        $tema=Tema::find($cod_tem);
        $tema_resolucion=DB::table('public.tema_resolucion')
            ->join('public.resolucions','tema_resolucion.cod_res','=','resolucions.cod_res')
            ->join('public.tomos','resolucions.cod_tom','=','tomos.cod_tom')
            ->select('resolucions.*','tema_resolucion.cod_tr','tomos.tom_gestion','tomos.tom_numero')
            ->where('cod_tem','=',$cod_tem)
            ->orderBy('res_numero')
            ->get();


        if(Storage::exists('alma/res/tema/'.$cod_tem)){
            $archivos=Storage::files('alma/res/tema/'.$cod_tem);
            foreach ($archivos as $a){
                Storage::delete($a);
            }
        }else{
            Storage::makeDirectory('alma/res/tema/'.$cod_tem);
        }
        foreach ($tema_resolucion as $tr){
            $ruta='alma/res/'.$tr->tom_gestion."/".$tr->tom_numero."/".$tr->res_pdf;
            $ruta2='alma/res/tema/'.$cod_tem."/";
            if($tr->res_pdf!='' && Storage::exists($ruta)){
                Storage::copy($ruta,$ruta2.strtoupper($tr->res_tipo)." ".$tr->res_numero.'-'.$tr->res_gestion.".pdf");
            }
        }

        $zip = new ZipArchive;
        $fileName = $tema->tem_titulo.'.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = \File::files(Storage::path('alma/res/tema/'.$cod_tem));

            foreach ($files as $key => $value) {
                $file = basename($value);
                $zip->addFile($value, $file);
            }
            $zip->close();
        }
        return response()->download(public_path($fileName));
    }
    public function fe_importar_tema($cod_tem){
        $tema=Tema::find($cod_tem);
        return view('resoluciones.temas.tema_resolucion.fe_importar',compact('tema'));
    }
    public function verificar_importacion_temas(Request $form){
    try {
        if ($form->hasFile('archivo')){
            //$array = Excel::toArray(new importarDB(), $form->file('archivo'));
                $tema=Tema::find($form['ct']);
                $banderaIden = 0;
                $ruta = 'imports/res/temas/';
                $extension = $form->file('archivo')->getClientOriginalExtension();
                $nombre = '';
                while ($banderaIden == 0) {
                    $nombre = Auth::user()->id . '-' . rand(0, 9999999999) . '-' . $tema->tem_titulo."." . $extension;
                    if (!Storage::exists('imports/res/temas/'. $nombre)) {
                        $banderaIden = 1;

                    }
                }

                \Session::put('cod_tem', $form['ct']);
                $importado = Excel::import(new ImportarTema(), $form->file('archivo'));
                Storage::putFileAs($ruta, $form->file('archivo'), $nombre);

                \Session::flash('exito_importacion', 'Se ha importado con exito los datos');
                return redirect('tema resolucion/'.$form['ct']);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();
            dd($fallas);
            return "<div class='alert-danger border border-danger rounded p-2'>Error al importar los datos</div>";
        }

    }
}
