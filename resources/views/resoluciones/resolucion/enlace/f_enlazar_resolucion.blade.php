<div class="modal-dialog modal-xl" role="document" id="panel_enlace">
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;<i class="fas fa-book"></i> &nbsp; Enlazar Resolución </h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div>
                <div class="col-md-12" style="height: 300px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow">
                                <h6 class="text-white text-center">Buscar resolución</h6>
                            </div>
                            <br/>
                            <form action="listar_resolucion" id="form_buscar_resolucion">
                                @csrf
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic border-bottom">Nº Resolución :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0" required name="numero" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic border-bottom">Gestión :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0" required name="gestion" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic border-bottom">Tipo de Resolución :</th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                <option value="rcu">RCU </option>
                                                <option value="rr">RR</option>
                                                <option value="rvr">RVR </option>
                                                <option value="rs">RS </option>
                                                <option value="rc">RC </option>
                                                <option value="rcf">RCF </option>
                                                <option value="rcc">RCC </option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="ct" value="{{$cod_tom}}">
                            </form>
                            <br/>
                            <button class="btn btn-sm btn-primary float-right"
                                    onclick="enviar('form_buscar_resolucion','{{url('buscar_resolucion_enlace/')}}','lista_resolucion');$('#lista_resolucion').html('');$('#ver_resolucion').html('');"> <i class="fas fa-search"></i> Buscar</button>
                        </div>
                        <div class="col-md-8" id="lista_resolucion">

                        </div>
                    </div>
                </div>
                <hr class="sidebar-divider"/>
                <div id="ver_resolucion">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>

        </div>
    </div>
</div>
<script>
    function enviar(formulario,ruta,panel){
        $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
        $.ajax({
            type: "POST",
            url: ruta,
            data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
            success: function(resp)
            {
                $('#'+panel).html(resp);
            }
        });
    }
</script>
