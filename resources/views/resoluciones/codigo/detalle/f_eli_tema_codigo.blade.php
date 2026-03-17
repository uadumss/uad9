<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar tema código de archivado</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
                <span class="font-italic">Esta seguro de eliminar el tema : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                            @if($eliminar==0)
                                <div>
                                    <p> No puede eliminar este tema</p>
                                </div>
                            @else
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
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            @if($eliminar==1)
                <form id="form_eliminar_tema">
                    @csrf
                    <input type="hidden" name="cd" value="{{$detalle->cod_det}}">
                </form>
                <button class="btn btn-danger" data-dismiss="modal" onclick="enviar('form_eliminar_tema','{{url("eliminar tema codigo")}}','panel_detalle_codigo')"> Eliminar</button>
            @endif
        </div>
    </div>
</div>
