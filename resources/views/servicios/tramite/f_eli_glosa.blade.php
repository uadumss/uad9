<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger">

            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel"><img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar trámite</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_eliminar_glosa">
                    @csrf
                    <span class="font-italic">Esta seguro de eliminar la glosa : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli">
                            <div>
                                <table class="text-dark col-md-12" >
                                    <tr>
                                        <th class="text-right">Glosa :</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$glosa->glo_titulo}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Trámite :</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$tramite->tre_nombre}}</th>
                                    </tr>
                                </table>
                                <input type="hidden" name="cg" value="{{$glosa->cod_glo}}">
                            </div>

                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="enviar('form_eliminar_glosa','{{url('eliminar_glosa')}}','panel_tramite')">Aceptar</button>
            </div>

    </div>
</div>


