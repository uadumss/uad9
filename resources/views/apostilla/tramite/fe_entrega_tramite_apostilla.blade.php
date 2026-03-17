<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <form method="POST" action="{{url("entrega tramite apostilla")}}">
        @csrf
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header alert-primary">
            <h5 class="modal-title font-weight-bolder text-dark" id="exampleModalLabel"><i class="fas fa-hand-point-right"></i> Entregas de trámites</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-dark" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert-primary centrar_bloque p-1 col-md-9 rounded shadow">
                <h6 class="text-dark text-center font-weight-bold"> Formulario de entrega de trámite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>

                    <table class="col-md-11 text-dark">
                        <tr>
                            <th class="text-right font-italic text-dark">Nro. Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark">UAD{{$tramite_apostilla->apos_numero}}</td>
                        </tr>

                        <tr>
                            <th class="text-right font-italic text-dark" valign="top"><br/>Entregar A : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><br/>
                                @if($tramite_apostilla->cod_apo!='')
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="apo" value="A" checked> {{$apoderado->apo_apellido." ".$apoderado->apo_nombre}} <span class="bg-danger rounded text-white pl-1 pr-1" style="font-size: 0.8em">Apo</span><br/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="apo" value="T"> {{$persona->per_apellido." ".$persona->per_nombre}}<br/>
                                @else
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="apo" value="T"> {{$persona->per_apellido." ".$persona->per_nombre}}<br/>
                                @endif
                                <br/>
                            </td>
                        </tr>

                    </table>
                    <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                    <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema<br/>
                            * Si hace la entrega de este trámite, ya no se podra modificar su estado</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-sm btn-primary">Entregar</button>
        </div>
    </div>
    </form>
</div>
