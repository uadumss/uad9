<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;&nbsp;Eliminar resolución</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-10" >

                            <span class="font-italic">Esta seguro de eliminar la resolución del tema de interés : </span><br/><br/>
                            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque  rounded p-2">
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">Tema :</th>
                                        <td class="border-bottom border-dark text-left">{{$tema->tem_titulo}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Resolución :</th>
                                        <td class="border-bottom border-dark text-left">{{strtoupper($resolucion->res_tipo)." - ".$resolucion->res_numero."/".$resolucion->res_gestion}} &nbsp;&nbsp;
                                            <?php if($resolucion->res_fecha!=''){echo '  de  : '.date('d/m/Y',strtotime($resolucion->res_fecha));}?></td>
                                    </tr>
                                </table>
                            </div>

                </div>
                <div class="pt-2 col-md-1 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
            </div>
            <br/>
            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <form id="form_eli_res">
                @csrf
                <input type="hidden" name="ctr" value="{{$tema_resolucion->cod_tr}}">
            </form>
            <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal" onclick="enviar('form_eli_res','{{url('eli_resolucion_tema')}}','panel_resolucion')">Eliminar</button>

        </div>

    </div>
</div>
