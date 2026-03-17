<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar trámite</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{url('eli_docleg')}}" method="post" id="form_eli_docleg">
                @csrf
                <span class="font-italic">Esta seguro de eliminar el trámite de legalización : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                            @if($docleg->dtra_falso=='t' || $docleg->dtra_obs!='')
                                <div>
                                    <p> No puede eliminar este trámite</p>
                                </div>
                            @else
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right ">Nombre trámite:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$docleg->tre_nombre}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Nro. título:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$docleg->dtra_numero." / ".$docleg->dtra_gestion}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Interesado:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$persona->per_apellido.' '.$persona->per_nombre}}</th>
                                    </tr>
                                </table>
                                <input type="hidden" name="cdtra" value="{{$docleg->cod_dtra}}">
                                <input type="hidden" name="ctra" value="{{$docleg->cod_tra}}">
                            @endif
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
            @if($docleg->dtra_falso!='t')
                <button class="btn btn-danger" data-dismiss="modal" onclick="guardarDatos('{{url("eli_docleg")}}','panel_traleg','form_eli_docleg')"> Eliminar</button>
            @endif
        </div>
    </div>
</div>
