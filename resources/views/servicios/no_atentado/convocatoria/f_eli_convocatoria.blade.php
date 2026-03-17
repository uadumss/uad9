<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-danger">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;ELIMINAR CONVOCATORIA</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            @if(!$tramite_noatentado)
            <div class="text-center text-dark">
                <span class="font-italic">Está seguro de eliminar la convocatoria</span> <i class="fas fa-question-circle text-danger" style="font-size: 35px"></i>
            </div><br>

            <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                <div class="row">
                    <div class="text-center centrar_bloque">
                        <div>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Convocatoria :</th>
                                    <td class="border-bottom border-dark text-left ">{{$convocatoria->con_nombre}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark" style=" padding-top: 7px">Publicación Convocatoria :</th>
                                    <td class="border-bottom border-dark text-left " style=" padding-top: 7px">{{$convocatoria->con_fecha_publicacion}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark" style=" padding-top: 7px">Entrega Documentos :</th>
                                    <td class="border-bottom border-dark text-left " style=" padding-top: 7px">{{$convocatoria->con_fecha_entrega}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark" style=" padding-top: 7px">Gestión :</th>
                                    <td class="border-bottom border-dark text-left " style=" padding-top: 7px" >{{$convocatoria->con_gestion}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar esta convocatoria, debido a que tiene trámites registrados a la misma
                </div>
            @endif
        </div>

        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
            @if(!$tramite_noatentado)
                <form id="form_eli_convocatoria" method="POST" action="{{url('eliminar convocatoria')}}">
                    @csrf
                    <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}"/>
                    <input class="btn btn-danger btn-sm" type="submit" value="Eliminar"/>
                </form>
            @endif
        </div>
    </div>
</div>
