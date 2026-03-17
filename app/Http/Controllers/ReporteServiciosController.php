<?php

namespace App\Http\Controllers;

use App\Models\D_confrontacion;
use App\Models\D_tramita;
use App\Models\Persona;
use App\Models\Titulo;
use App\Models\Tramite;
use App\Models\Tramita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Charts\grafico;
use Illuminate\Support\Facades\Session;

class ReporteServiciosController extends Controller
{
    public function l_reportes(){
        return view('servicios.reporte.l_reportes');
    }
    public function panel_reportes($modal){
        switch ($modal){
            case 'personal':
                return view('servicios.reporte.personal.personal');
                break;
            case 'general':
                $tramites=Tramite::all();
                return view('servicios.reporte.general.general',compact('tramites'));
                break;
            case 'estadistico':
                $tramites=Tramite::all();
                return view('servicios.reporte.estadistica.estadistico',compact('tramites'));
                break;
            case 'listas':
                $tramites=Tramite::all()->sortBy('tre_tipo');
                return view('servicios.reporte.listas.listas',compact('tramites'));
                break;
            case 'entregas':
                //return "entregas";
                $tramites=Tramite::all()->sortBy('tre_tipo');
                return view('servicios.reporte.entrega.entregas',compact('tramites'));
                break;
                    /*$resultado=DB::select("select tre.cod_tre, tre.tre_nombre,count('dt.cod_dtra') as cantidad from d_tramitas dt right JOIN tramites tre on dt.cod_tre=tre.cod_tre left join tramitas tra on dt.cod_tra=tra.cod_tra
                                            group by tre.tre_nombre,tre.cod_tre;");
                    return view('servicios.reporte.general.general',compact('resultado'));*/
                break;
        }
    }
    public function reporte_servicios_personal(Request $form){

            if($form['ci']!=''){
                $id_per='';
                if(isset($form['ip'])){
                    $id_per=$form['ip'];
                }else{
                    $persona=DB::select("select id_per from personas where per_ci='".$form['ci']."' limit 1");
                    $id_per=$persona[0]->id_per;
                }
                $resultado=DB::table('d_tramitas')
                    ->leftJoin('tramitas','d_tramitas.cod_tra','=','tramitas.cod_tra')
                    ->leftJoin('tramites','d_tramitas.cod_tre','=','tramites.cod_tre')
                    ->select('cod_dtra','dtra_entregado','dtra_generado','dtra_falso','dtra_tipo','dtra_numero','dtra_gestion','dtra_numero_tramite','dtra_gestion_tramite',
                        'tra_fecha_solicitud','dtra_fecha_recojo','tre_nombre')
                    ->where('tramitas.id_per','=',$id_per)->orderByDesc('tra_fecha_solicitud')->get();
                $uno=1;

                return view('servicios.reporte.personal.resultado_personal',compact('resultado','form','uno'));

            }else{

                if($form['apellido']!='' || $form['nombre']!='') {
                    $consulta = "select id_per,per_ci,per_nombre,per_apellido from personas where ";
                    if ($form['apellido'] != '') {
                        $consulta.=" per_apellido ilike '%".$form['apellido']."%' ";
                        if($form['nombre']!=''){
                            $consulta.=" and per_nombre ilike '%".$form['nombre']."%' ";
                        }
                    }else{
                        if($form['nombre']!=''){
                            $consulta.=" per_nombre ilike '%".$form['nombre']."%'";
                        }
                    }
                    $resultado=DB::select($consulta);
                    $uno=0;
                    return view('servicios.reporte.personal.resultado_personal',compact('resultado','form','uno'));
                }
                return "<span class='text-danger font-italic font-weight-bold'>Busqueda inválida</span>";
            }
    }
    public function reporte_servicios_general(Request $form){
        $consulta="";
        $tramite='';
        //dd($form);
        //return $form['fecha_inicial']." - ".$form['fecha_final'];
        if($form['tramite']!=''){
            $consulta.="select tre.cod_tre,tre.tre_nombre, count('dt.cod_dtra') as cantidad from d_tramitas dt JOIN tramites tre on dt.cod_tre=tre.cod_tre
             where tre.cod_tre=".$form['tramite'];
                if($form['fecha_inicial']!='' && $form['fecha_final']!=''){
                    $consulta.=" and dt.created_at>='".date('Y-m-d',strtotime($form['fecha_inicial']))." 00:00:00' and dt.created_at<='".$form['fecha_final']." 23:59:59'";
                }else{
                    if($form['fecha_inicial']!=""){
                        $consulta.=" and dt.created_at>='".date('Y-m-d',strtotime($form['fecha_inicial']))." 00:00:00' and dt.created_at<='".$form['fecha_inicial']." 23:59:59'";
                    }
                }
                $consulta.=" group by tre.cod_tre, tre.tre_nombre";
                $tramite=Tramite::find($form['tramite']);
        }else{
            if($form['tipo']!=''){
                $consulta="select tre.cod_tre,tre.tre_nombre, count('dt.cod_dtra') as cantidad from d_tramitas dt JOIN tramites tre on dt.cod_tre=tre.cod_tre
                            where dtra_tipo='".$form['tipo']."'";

                if($form['fecha_inicial']!='' && $form['fecha_final']!=''){
                    $consulta.=" and dt.dtra_fecha_registro>='".date('Y-m-d',strtotime($form['fecha_inicial']))."' and dt.dtra_fecha_registro<='".$form['fecha_final']." '";
                }else{
                    if($form['fecha_inicial']!=""){
                        $consulta.=" and dt.dtra_fecha_registro>='".date('Y-m-d',strtotime($form['fecha_inicial']))."' and dt.dtra_fecha_registro<='".$form['fecha_inicial']." '";
                    }
                }
                $consulta.=" group by tre.cod_tre, tre.tre_nombre";
            }else{
                $consulta="select tre.cod_tre, tre.tre_nombre,count('dt.cod_dtra') as cantidad
                            from d_tramitas dt JOIN tramites tre on dt.cod_tre=tre.cod_tre left join tramitas tra on dt.cod_tra=tra.cod_tra";
                if($form['fecha_inicial']!='' && $form['fecha_final']!=''){
                    $consulta.=" dt.dtra_fecha_registro>='".date('Y-m-d',strtotime($form['fecha_inicial']))."' and dt.dtra_fecha_registro<='".$form['fecha_final']."'";
                }else{
                    if($form['fecha_inicial']!=""){
                        $consulta.=" dt.dtra_fecha_registro>='".date('Y-m-d',strtotime($form['fecha_inicial']))."' and dt.dtra_fecha_registro<='".$form['fecha_inicial']."'";
                    }
                }
                     $consulta.=" group by tre.tre_nombre,tre.cod_tre";
            }
        }
        $resultado=DB::select($consulta);
        return view('servicios.reporte.general.resultado_general',compact('resultado','form','tramite'));
    }
    public function reporte_servicios_estadistico(Request $form){
        $consulta='';
        $mes=0;
        $tramite='';
        $tipo='';
        if($form['tramite']!='' || $form['tipo']!=''){
            if($form['tramite']!=''){
                $tipo=='tramite';
                $tramite=Tramite::find($form['tramite']);
                if($form['gestion_inicial']!='' && $form['gestion_final']!=''){ //reporte por periodo
                    $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite>=".$form['gestion_inicial']." and dtra_gestion_tramite<=".$form['gestion_final']."
                                and cod_tre=".$form['tramite']." group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";

                    $consulta2="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite>=".$form['gestion_inicial']." and dtra_gestion_tramite<=".$form['gestion_final']."
                                and cod_tre=".$form['tramite']." group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";

                }else{
                    if($form['gestion_inicial']!=''){
                        $mes=1;
                        $consulta="select EXTRACT(MONTH from dt.dtra_fecha_registro) as mes,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite=".$form['gestion_inicial']." and cod_tre=".$form['tramite']."
                                group by mes order by mes ASC;";
                    }else{
                        $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where cod_tre=".$form['tramite']." group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";
                    }
                }
            }else {
                if ($form['tipo'] != '') {
                    $tipo=='tipo';
                    if($form['gestion_inicial']!='' && $form['gestion_final']!='') { //reporte por periodo
                        $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite>=".$form['gestion_inicial']." and dtra_gestion_tramite<=".$form['gestion_final']."
                                and dtra_tipo='".$form['tipo']."' group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";
                    }else{
                        if($form['gestion_inicial']!=''){
                            $mes=1;
                            $consulta="select EXTRACT(MONTH from dt.dtra_fecha_registro) as mes,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite=".$form['gestion_inicial']." and dtra_tipo='".$form['tipo']."'
                                group by mes order by mes ASC;";
                        }else{
                            $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dtra_tipo='".$form['tipo']."' group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";

                        }
                    }

                }
            }
            $no_valido='f';
            $resultado=DB::select($consulta);
            return view('servicios.reporte.estadistica.resultado_estadistico',compact('resultado','tramite','no_valido','form','mes','tipo'));
        }else{
            if($form['gestion_inicial']!='' && $form['gestion_final']!='') {
                $tipo='general';
                $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite>=".$form['gestion_inicial']." and dtra_gestion_tramite<=".$form['gestion_final']."
                                group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";
            }else{
                if($form['gestion_inicial']!=''){
                    $mes=1;
                    $consulta="select EXTRACT(MONTH from dt.dtra_fecha_registro) as mes,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt where dt.dtra_gestion_tramite=".$form['gestion_inicial']." group by mes order by mes ASC;";
                }else{
                    $consulta="select dtra_gestion_tramite,count(dt.cod_dtra) as cantidad
                                from d_tramitas dt group by dtra_gestion_tramite order by dtra_gestion_tramite ASC";
                }
            }
            $no_valido='f';
            $resultado=DB::select($consulta);
            return view('servicios.reporte.estadistica.resultado_estadistico',compact('resultado','tramite','no_valido','form','mes','tipo'));

        }
    }
    public function reporte_servicios_listas_PDF(Request $form){
        $form->validate([
            'inicial'=>'required',
            'final'=>'nullable|date',
        ]);
        $tramite="";
        $inicial=$form['inicial'];
        $final=$form['final'];
        $tipo_solicitud='INTERNO Y EXTERNO';
        $consulta="select personas.per_apellido, personas.per_nombre, d_tramitas.dtra_interno,d_tramitas.dtra_numero,dtra_gestion,tramitas.tra_fecha_solicitud,
                        tramitas.tra_numero
                    from personas join tramitas on personas.id_per=tramitas.id_per join d_tramitas on tramitas.cod_tra=d_tramitas.cod_tra
                                                 where ";
        if($form['final']!=''){
            $consulta.=" tramitas.tra_fecha_solicitud>='".$form['inicial']."' and tramitas.tra_fecha_solicitud<='".$form['final']."' ";
        }else{
            $consulta.=" tramitas.tra_fecha_solicitud='".$form['inicial']."' ";
        }

        if($form['tramite']){
            $tramite=Tramite::find($form['tramite']);
            $consulta.=" and d_tramitas.cod_tre=".$form['tramite'];
        }
        if(isset($form['tipo_tramite'])){
            if($form['tipo_tramite']=='I'){
                $tipo_solicitud="INTERNO";
                $consulta.=" and d_tramitas.dtra_interno='t'";
            }else{
                $tipo_solicitud="EXTERNO";
                $consulta.=" and d_tramitas.dtra_interno='f'";
            }
        }
        $consulta.=' order by tramitas.tra_numero';
        $resultado=DB::select($consulta);

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('legal');
        $pdf->loadView('servicios.reporte.listas.resultado_listasPDF',compact('resultado','tramite','tipo_solicitud','inicial','final'));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(495, 85, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->download('Reporte.pdf');


    }
    public function reporte_servicios_entrega_PDF(Request $form){
        $form->validate([
            'inicial'=>'required',
            'final'=>'nullable|date',
        ]);
        $tramite="";
        $inicial=$form['inicial'];
        $final=$form['final'];
        $tipo_solicitud='INTERNO Y EXTERNO';
        $consulta="select p.per_apellido, p.per_nombre, dt.dtra_interno,t.tra_fecha_solicitud,t.tra_numero,dt.dtra_entregado,dt.dtra_fecha_recojo,t.tra_numero,
                    dt.cod_dtra,p.per_ci,te.tre_nombre,dt.dtra_entregado
                    from d_tramitas dt left join tramitas t on dt.cod_tra=t.cod_tra left join personas p on p.id_per=t.id_per join tramites te on dt.cod_tre=te.cod_tre
                    where ";
        if($form['final']!=''){
            $consulta.=" dtra_fecha_recojo BETWEEN '".$form['inicial']." 00:00:00'::TIMESTAMP and '".$form['final']." 23:59:59'::TIMESTAMP";
        }else{
            $consulta.=" dtra_fecha_recojo BETWEEN '".$form['inicial']." 00:00:00'::TIMESTAMP and '".$form['inicial']." 23:59:59'::TIMESTAMP";
        }

        if($form['tramite']){
            $tramite=Tramite::find($form['tramite']);
            $consulta.=" and dt.cod_tre=".$form['tramite'];
        }
        if(isset($form['tipo_tramite'])){
            if($form['tipo_tramite']=='I'){
                $tipo_solicitud="INTERNO";
                $consulta.=" and dt.dtra_interno='t'";
            }else{
                $tipo_solicitud="EXTERNO";
                $consulta.=" and dt.dtra_interno='f'";
            }
        }
        $consulta.=' order by t.tra_numero';
        $resultado=DB::select($consulta);
        //return $consulta;
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('legal');
        $pdf->loadView('servicios.reporte.entrega.resultado_entregasPDF',compact('resultado','tramite','tipo_solicitud','inicial','final'));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(495, 85, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->download('Reporte.pdf');
    }
    public function reporte_detalle_tramite($cod_dtra){

        $d_tramita=D_tramita::find($cod_dtra);
        $tramite=Tramite::find($d_tramita->cod_tre);
        $tramita=Tramita::find($d_tramita->cod_tra);
        $persona=Persona::find($tramita->id_per);
        $titulo=Titulo::find($d_tramita->dtra_cod_tit);
        $confrontacion=D_confrontacion::where('cod_dtra','=',$cod_dtra)->get();

        $bitacora=SessionController::reporte_evento($d_tramita->cod_dtra,3,'d_tramitas');
        //return sizeof($bitacora);
        //dd($bitacora);
        return view('servicios.reporte.personal.detalle_tramite',compact('d_tramita','confrontacion','tramite','tramita'
            ,'persona','titulo','bitacora'));
    }
}
