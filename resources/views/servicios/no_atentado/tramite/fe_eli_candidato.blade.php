<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;ELIMINAR CANDIDATO</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            @if($candidato)
                <div class="text-center text-dark">
                    <span class="font-italic">Está seguro de eliminar al candidato</span> <i class="fas fa-question-circle text-danger" style="font-size: 35px"></i>
                </div><br>

                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    <div class="row">
                        <div class="text-center centrar_bloque">
                            <div>
                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">CI : </th>
                                        <td class="border-bottom border-dark">{{$candidato->per_ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre : </th>
                                        <td class="border-bottom border-dark">{{$candidato->per_nombre." ".$candidato->per_apellido}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Código SIS: </th>
                                        <td class="border-bottom border-dark">{{$candidato->per_cod_sis}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Cargo: </th>
                                        <td class="border-bottom border-dark">{{$candidato->carg_nombre}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar al candidato
                </div>
            @endif
        </div>

        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            @if($candidato)
                <form id="form_eli_candidato" method="POST" action="{{url('eliminar convocatoria')}}">
                    @csrf
                    <input type="hidden" name="cn" value="{{$candidato->cod_noa}}"/>
                </form>
                <button class="btn btn-danger btn-sm" type="button" onclick="enviar('form_eli_candidato','{{url('eliminar candidato noatentado')}}','panel_noatentado');$('#Noatentado_agregar').modal('hide')">Eliminar</button>
            @endif
        </div>
    </div>
</div>
