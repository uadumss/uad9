

<style type="text/css">
    #outerContainer #mainContainer div.toolbar {
        display: none !important; /* hide PDF viewer toolbar */
    }
    #outerContainer #mainContainer #viewerContainer {
        top: 0 !important; /* move doc up into empty bar space */
    }
</style>
<form action="{{url('enlazar resolucion')}}" method="POST">
    @csrf
    <input type="hidden" name="cr" value="{{$resolucion->cod_res}}">
    <input type="hidden" name="ct" value="{{$cod_tom}}">
    <input type="submit" class="btn btn-sm btn-danger" name="enviar" value="+ Enlazar">
</form>
        <div class="bg-verde-oscuro centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Detalle de la Resolución</h6>
        </div>
 <br/>
    <div style="height: 400px" class="overflow-auto">

        <div class="row">
            <div class="col-md-4">
                <div>
                    <table style="font-size:0.9em;">
                        <tr>
                            <th class="text-right text-primary font-italic" colspan="2">DATOS DE LA RESOLUCIÓN</th>
                        </tr>
                        <tr>
                            <th class="text-right font-italic border-bottom">Nº Resolución : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$resolucion['res_numero']}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic border-bottom">Nro. Tomo :</th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$tomo['tom_numero']}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic border-bottom">Gestión :</th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$tomo['tom_gestion']}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic border-bottom">Fecha : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{date('d/m/Y',strtotime($resolucion['res_fecha']))}}</td>
                        </tr><tr>
                            <th class="text-right font-italic border-bottom">Tipo de Resolución : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{strtoupper($resolucion['res_tipo'])}}</td>
                        </tr><tr>
                            <th class="text-right font-italic border-bottom ">Plan de archivo : </th> <td class="border-bottom border-dark">
                                @foreach($archivado as $a)
                                    {{$a->plan_numero.'/'.$a->carch_numero.''}}<br/>
                                @endforeach
                            </td>
                        </tr><tr>
                            <th class="text-right font-italic border-bottom">Tema : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$resolucion['res_tema']}}</td>
                        </tr><tr>
                            <th class="text-right font-italic border-bottom">Objeto : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$resolucion['res_objeto']}}</td>
                        </tr><tr>
                            <th class="text-right font-italic border-bottom">Descripción : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$resolucion['res_desc']}}</td>
                        </tr><tr>
                            <th class="text-right text-primary font-italic" colspan="2"><br/><br/>FIRMA AUTORIDADES</th>
                        </tr><tr>
                            <?php $nombre="";
                            $codigo="";
                            $cargo='';
                            $ban=false;
                            if(isset($fir)){
                                foreach($autoridad as $a){
                                    if($a->cod_aut==$fir->cod_aut){
                                        $nombre=$a->aut_nombre;
                                        $codigo=$a->cod_aut;
                                        $cargo=$a->aut_cargo;
                                        $ban=true;
                                    }
                                }
                            }
                            ?>
                            @if($ban)
                                <th class="text-right font-italic border-bottom">Primera autoridad : </th> <td class="border-bottom border-dark"> {{$nombre}}<br/><span class="font-weight-bold">{{$cargo}}</span>&nbsp;&nbsp;</td>
                            @else
                                <th class="text-right font-italic border-bottom">Primera autoridad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;</td>
                            @endif


                        </tr><tr>
                            <?php $ban=false;
                            if(isset($fir)){
                                foreach($autoridad as $a){
                                    if($a->cod_aut==$fir->cod_aut2){
                                        $nombre=$a->aut_nombre;
                                        $codigo=$a->cod_aut;
                                        $cargo=$a->aut_cargo;
                                        $ban=true;
                                    }
                                }
                            }
                            ?>
                            @if($ban)
                                <th class="text-right font-italic border-bottom">Segunda autoridad : </th> <td class="border-bottom border-dark"> {{$nombre}}<br/><span class="font-weight-bold">{{$cargo}}</span>&nbsp;&nbsp;</td>
                            @else
                                <th class="text-right font-italic border-bottom">Segunda autoridad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;</td>
                            @endif

                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-7" >
                <h5 class="text-center text-primary">Resolución</h5>
                @if($resolucion->res_pdf!='')
                    <embed src="{{url('pdf resolucion/'.$resolucion['cod_res'])}}#toolbar=0" class="col-md-12" height="600"/>
                @else
                    <div class="alert alert-danger alert-dismissible">
                        <span class="">No existe el archivo digital</span>
                    </div>
                @endif
                <hr class="sidebar-divider bg-primary"/>
                @can('mostrar antecedentes - rr')
                    <h5 class="text-center text-primary">Antecedentes</h5>
                    @if($resolucion->res_ant!='')

                        <a class="btn btn-light" data-toggle="collapse" href="#collapseExample" role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                            Antecedentes <i class="fas fa-arrow-alt-circle-down"></i>
                        </a>
                        <br/>
                        <br/>
                        <div class="collapse" id="collapseExample">
                            <div>
                                <embed src="{{url('pdf_a resolucion/'.$resolucion['cod_res'])}}#toolbar=0" class="col-md-12" height="600"/>
                            </div>
                        </div>

                    @else
                        <div class="alert alert-danger alert-dismissible">
                            <span class="">No existe el archivo digital</span>
                        </div>
                    @endif
                @endcan
            </div>

        </div>
    </div>

