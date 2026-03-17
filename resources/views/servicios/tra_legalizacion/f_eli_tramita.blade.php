<div class="modal-dialog modal-lg" role="document" id="panel_reg_busqueda">
<div class="modal-content border-bottom-danger shadow-lg">
    <form action="{{url('eli_traleg')}}" method="post">
        @csrf
        <input  type="hidden" name="ct" id="ct" value=""/>
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar trámite</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <span class="font-italic">Esta seguro de eliminar el trámite de legalización : </span><br/><br/>
            <div class="row">
                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2">

                    <div>
                        @if(sizeof($docleg)>0)
                            <div>
                                <p> No puede eliminar este trámite debido a que tiene documentos asociados </p>
                            </div>
                        @else
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark font-italic">Nro Trámite : </th>
                                    <td class="border-bottom border-dark">
                                        {{$tramita->tra_numero}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark font-italic">Fecha de solicitud : </th>
                                    <td class="border-bottom border-dark">
                                        {{date('y/m/Y', strtotime($tramita->tra_fecha_solicitud))}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark font-italic">Titular : </th>
                                    <td class="border-bottom border-dark">
                                        {{$tramita->per_apellido." ".$tramita->per_nombre}}
                                    </td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark font-italic">Tipo de trámite : </th>
                                    <td class="border-bottom border-dark">

                                        @php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';

                                        @endphp
                                        <span class="font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$tramita->tra_tipo_tramite.'C']}}" style="font-size: 0.75em">
                                                {{$tipo_tramite[$tramita->tra_tipo_tramite]}}
                                            </span>
                                    </td>

                                </tr>
                            </table>
                        @endif
                    </div>
                    <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                </div>
                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
            </div>
            <br/>
            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            @if(sizeof($docleg)==0)
                <input class="btn btn-danger" type="submit" value="Aceptar"/>
            @endif
        </div>
    </form>
</div>
</div>
