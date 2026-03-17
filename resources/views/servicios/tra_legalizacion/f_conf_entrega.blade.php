<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header alert-primary">
            <h5 class="modal-title font-weight-bolder text-dark" id="exampleModalLabel"><i class="fas fa-hand-point-right"></i> Entregas </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-dark" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert-primary centrar_bloque p-1 col-md-9 rounded shadow">
                <h6 class="text-dark text-center font-weight-bold"> Formulario de entrega de legalizaciones</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form method="post" id="form_g_entrega">
                    @csrf
                    <table class="col-md-11 text-dark">
                        <tr>
                            <th class="text-right font-italic text-dark">Nro. Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark">{{$tramita->tra_numero}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark">
                                @if($varios==1)
                                    {{$docleg->tre_nombre}}
                                @else
                                    @foreach($docleg as $d)
                                        @if($d->dtra_entregado=='')
                                            {{$d->tre_nombre}}<br/>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark" valign="top"><br/>Entregar A : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><br/>
                                @if($tramita->cod_apo!='')
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="a" checked> {{$apoderado->apo_apellido." ".$apoderado->apo_nombre}} <span class="bg-danger rounded text-white pl-1 pr-1" style="font-size: 0.8em">Apo</span><br/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="t"> {{$persona->per_apellido." ".$persona->per_nombre}}<br/>
                                @else
                                    <input type="radio" name="tipo" value="t" checked> {{$persona->per_apellido." ".$persona->per_nombre}}<br/>
                                @endif
                                <br/>
                            </td>
                        </tr>

                    </table>
                    @if($varios==1)
                        <input type="hidden" name="cdtra" value="{{$docleg->cod_dtra}}">
                        <input type="hidden" name="ctra" value="{{$docleg->cod_tra}}">
                    <br/>
                    @else
                        <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                        <input type="hidden" name="todo" value="t">
                    @endif
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema<br/>
                        * Si hace la entrega de este trámite, ya no se podra modificar su estado</div>
                </form>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            @if($varios==1)
                @if($docleg->dtra_falso!='t')
                    <button class="btn btn-sm btn-primary" onclick="guardarDatos('{{url("g_entrega")}}','panel_traleg','form_g_entrega')" data-dismiss="modal">Guardar</button>
                @endif
            @else
                <button class="btn btn-sm btn-primary" onclick="guardarDatos('{{url("g_entrega")}}','panel_traleg','form_g_entrega')" data-dismiss="modal">Guardar</button>
            @endif
        </div>
    </div>

</div>
