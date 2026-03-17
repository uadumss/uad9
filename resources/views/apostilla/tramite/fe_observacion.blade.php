<div class="modal-dialog modal-lg shadow" role="document">
    <div class="modal-content border-bottom-primaryr">
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-verde-oscuro">
                <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;Observar trámite de apostilla</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                    <h6 class="text-white text-center">Formulario para observar tramite de apostilla</h6>
                </div>
                <hr/>
                    <div class="row">
                        <div class="font-weight-bold shadow alert-info rounded centrar_bloque col-md-9 p-3" id="panel_eli">
                            <div>

                                <table class="text-dark col-md-12" >
                                    <tr>
                                        <th class="text-right">Nombre : </th>
                                        <td class="text-dark text-left border-bottom border-dark pl-3">{{$persona->per_apellido." ".$persona->per_nombre}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Numero de tramite: </th>
                                        <td class="text-dark text-left border-bottom border-dark pl-3">UAD{{$tramite_apostilla->apos_numero}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-right">Fecha de ingreso : </th>
                                        <td class="text-dark text-left border-bottom border-dark pl-3">{{date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso))}}</td>
                                    </tr>

                                        <tr>
                                            <th class="text-right">Observacion : </th>
                                            <td class="text-dark text-left border-bottom border-dark pl-3">
                                                <form id="form_obs" method="POST">
                                                    @csrf
                                                    <textarea name="observacion" class="form-control form-control-sm">{{$tramite_apostilla->apos_obs}}</textarea>
                                                    <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                                                </form>
                                            </td>
                                        </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                    <br/>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="button" onclick="enviar('form_obs','{{url('guardar observacion tramite apostilla')}}','panel_observacion');$('#tramite_apostilla').modal('hide')">Guardar</button>
            </div>

    </div>
</div>
