<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;Valorado</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow-sm">
                <h6 class="text-white font-weight-bold text-center">Datos de uso del valorado</h6>
            </div>
            <hr class="sidebar-divider"/>

                            @if($valorado)
                                <?php //dd($valorado)?>
                        @if($valorado->dtra_entregado=='a' || $valorado->dtra_entregado=='t')
                            <div class="rounded border-danger alert-danger p-3 col-md-6 centrar_bloque font-weight-bold text-center">
                                Trámite entregado
                            </div>
                            <br/>
                        @endif

                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right text-dark font-italic">Nombre:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{$valorado->per_apellido." ".$valorado->per_nombre}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-dark font-italic">CI:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{$valorado->per_ci}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-dark font-italic">Nombre trámite:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{$valorado->tre_nombre}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right  text-dark font-italic">Nro. Trámite:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{$valorado->dtra_numero_tramite.' / '.$valorado->dtra_gestion_tramite}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-dark font-italic">Nro. título:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{$valorado->dtra_numero." / ".$valorado->dtra_gestion}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-dark font-italic">Fecha solicitud:</th>
                                    <td class="text-dark text-left border-bottom pl-3">{{date('d/m/Y H:i:s',strtotime($valorado->tra_fecha_solicitud))}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-dark font-italic">Fecha recojo:</th>
                                    @if($valorado->dtra_fecha_recojo!='')
                                        <td class="text-dark text-left border-bottom pl-3">
                                            {{date('d/m/Y H:i:s',strtotime($valorado->dtra_fecha_recojo))}}
                                        </td>
                                    @else
                                        <td class="text-dark text-left border-bottom pl-3"></td>
                                    @endif
                                </tr>
                                @if($valorado->dtra_entregado=='a')
                                <tr>
                                    <th class="text-right text-dark font-italic">Entregado a :</th>
                                    <td class="text-dark text-left border-bottom pl-3"> {{$valorado->apo_apellido." ".$valorado->apo_nombre}} <i class="fas fa-arrow-alt-circle-right text-primary"></i> <span class="p-1 bg-success rounded text-white font-italic font-weight-bold">APODERADO</span></td>
                                </tr>
                                @endif
                            </table>
                    @else
                                <div class="border border-danger rounded alert-danger p-3 font-italic font-weight-bold centrar_bloque col-md-10 text-center">
                                    No se encontró ningún valorado
                                </div>
                    @endif
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
