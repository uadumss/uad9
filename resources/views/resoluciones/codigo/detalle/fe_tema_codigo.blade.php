<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white" id="exampleModalLabel"> Editar tema código de archivado</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <span class="font-italic">Datos del tema : </span><br/><br/>
            <div class="row">
                <div class="font-weight-bold alert-info shadow text-center centrar_bloque col-md-9 p-2" >
                    <div>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Plan : </th>
                                    <td class="border-bottom border-dark text-dark">
                                        &nbsp;&nbsp;{{$plan->plan_numero.". - ".$plan->plan_titulo}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Código : </th>
                                    <td class="border-bottom border-dark text-dark">
                                        &nbsp;&nbsp;{{$plan->plan_numero."/".$codigo->carch_numero}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tema : </th>
                                    <td class="border-bottom border-dark text-danger font-italic font-weight-bold">
                                        &nbsp;&nbsp;{{$codigo->carch_titulo}}
                                    </td>
                                </tr>

                                    <tr>
                                        <th class="text-right font-italic text-dark">Tema : </th>
                                        <td class="border-bottom border-dark text-danger font-italic font-weight-bold">
                                            <form id="form_editar_tema">
                                                @csrf
                                                    <input class="form-control form-control-sm border-0" name="tema" value="{{$detalle->det_nombre}}">
                                                    <input type="hidden" name="cd" value="{{$detalle->cod_det}}">
                                                    <input type="hidden" name="cc" value="{{$detalle->cod_carch}}">
                                            </form>
                                        </td>
                                    </tr>
                            </table>
                    </div>
                </div>
            </div>
            <br/>
            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary btn-sm" data-dismiss="modal" onclick="enviar('form_editar_tema','{{url("guardar detalle codigo")}}','panel_detalle_codigo')"> Guardar</button>
        </div>
    </div>
</div>
