<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;ELIMINAR DETALL DE SANCION</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            @if($detalle)

                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-12 p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">CI : </th>
                                        <td class="border-bottom border-dark text-left text-dark">{{$sancionado->per_ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre : </th>
                                        <td class="border-bottom border-dark text-left text-dark">{{$sancionado->per_nombre." ".$sancionado->per_apellido}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Referencia: </th>
                                        <td class="border-bottom border-dark text-left text-dark">{{$sancionado->san_referencia}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Sentencia: </th>
                                        <td class="border-bottom border-dark text-left text-dark">{{$sancionado->san_sentencia}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Resolución: </th>
                                        <td class="border-bottom border-dark text-left text-dark">{{$sancionado->san_resolucion}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Detalle: </th>
                                        <td class="border-bottom border-dark text-left">{{$detalle->dsan_detalle}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar la sanción
                </div>
            @endif
        </div>
        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            @if($detalle)
                <form id="form_eli_detalle" method="POST" action="{{url('eliminar sancionado noatentado')}}">
                    @csrf
                    <input type="hidden" name="cd" value="{{$detalle->cod_dsan}}"/>
                </form>
                <a class="btn btn-sm btn-danger text-white" onclick="enviar('form_eli_detalle','{{url('eliminar detalle sancion')}}','panel_modal');$('#Modal2').modal('hide')">Eliminar</a>
            @endif
        </div>
    </div>
</div>
