<div class="modal-dialog modal-lg" role="document" id="panel_tramite_apostilla">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Detalle de sanción </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar detalle de la sanción</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form id="form_detalle">
                    @csrf
                    <table class="col-md-12">
                        <tr>
                            <th class="font-italic text-dark">Detalle de la sancion :
                                @if($detalle)
                                    <textarea class="form-control form-control-sm" name="detalle">{{$detalle->dsan_detalle}}</textarea>
                                @else
                                    <textarea class="form-control form-control-sm" name="detalle"></textarea>
                                @endif
                            </th>
                        </tr>
                        @if($detalle)
                            <input type="hidden" name="cd" value="{{$detalle->cod_dsan}}">
                        @endif
                        <input type="hidden" name="cs" value="{{$cod_san}}">
                    </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_detalle','{{url('guardar detalle sancion')}}','panel_modal');
                                $('#Modal2').modal('hide');cargarDatos('{{url('lista detalle sancion noatentado/'.$cod_san)}}','panel_lista_tramites')">
                Guardar</button>
        </div>
    </div>
</div>



