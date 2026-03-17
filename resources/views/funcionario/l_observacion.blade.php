<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Observaciones</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Observaciones del título</h6>
        </div>
        <hr class="sidebar-divider"/>

@if(Session::has('exito'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('exito') !!}
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('error') !!}
    </div>
@endif
        <div class="row">
            <div class="col-md-8 border rounded shadow">
                <table class="col-md-11">
                    <tr class="border-bottom border-dark">
                        <th colspan="2"><span class="text-danger font-weight-bold text-right font-italic">Observaciones</span><br/><br/></th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($observaciones as $o)
                        <tr class="border-bottom border-dark">
                            <th>{{$i}}</th>
                            <td><div class="ml-2">
                                    <div class="text-justify p-2">
                                        <span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Observación : </span>
                                        <span style="font-size: 0.8em;" class="text-dark font-weight-bold">{{date('d/m/Y',strtotime($o->od_fecha))}}</span> <br/>
                                        {{$o['od_obs']}}
                                    </div>
                                    @if($o->od_solucion=='')

                                            <div class="text-right">
                                                <a class="btn btn-outline-info btn-sm text-dark" data-toggle="collapse" href="#solucion{{$i}}" role="button"
                                                   aria-expanded="false" aria-controls="collapseExample">
                                                    Solución <i class="fas fa-arrow-alt-circle-down text-primary"></i>
                                                </a>
                                            </div>

                                            <div class="collapse" id="solucion{{$i}}">
                                                <div>

                                                    <form action="{{url('g_obs_documento')}}" method="POST" id="formObs{{$i}}">
                                                        @csrf
                                                        <table class="col-md-12">
                                                            <tr>
                                                                <th class="text-right"><span class="text-primary font-weight-bold text-right font-italic">Registrar solución</span></th>
                                                            </tr>
                                                            <tr>
                                                                <td><textarea name="obs" required class="form-control col-md-12"></textarea></td>
                                                            </tr>
                                                            <tr class="text-right">
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <input type="hidden" name="cd" value="{{$documento->cod_doc}}">
                                                        <input type="hidden" name="co" value="{{$o->cod_od}}">
                                                    </form>
                                                    <div class=float-right>
                                                        <button class="btn btn-sm btn-primary" onclick="enviar('formObs{{$i}}','{{url('g_obs_documento')}}','panel_documento')">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                    @else
                                        <div class="alert-info rounded p-2">
                                            <div><span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Solucionado el : </span>
                                                <span style="font-size: 0.8em;" class="text-dark font-weight-bold">{{date('d/m/Y',strtotime($o->od_fecha_solucion))}}</span> </div>
                                            <div class="justify-content-center">{{$o->od_solucion}}</div>

                                                <div class="text-right">
                                                    <a class="btn btn-danger btn-sm  text-white " data-toggle="collapse" href="#solucion{{$i}}" role="button"
                                                       aria-expanded="false" aria-controls="collapseExample" onclick="enviar('eliminarObs{{$i}}','{{url('e_obs_documento')}}','panel_documento')">
                                                        Eliminar <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="eliminarObs{{$i}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="co" value="{{$o->cod_od}}">
                                                    </form>
                                                </div>

                                        </div>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
            @can('acceder al sistema - dya')
                <div class="col-md-4">
                    <form action="{{url('g_obs_documento')}}" method="POST" id="formObs">
                        @csrf
                        <table class="col-md-12">
                            <tr>
                                <th><span class="text-primary font-weight-bold text-right font-italic">Nueva Observación</span></th>
                            </tr>
                            <tr>
                                <td><textarea name="obs" required class="form-control col-md-12"></textarea></td>
                            </tr>
                            <tr class="text-right">
                                <td></td>
                            </tr>
                        </table>
                        <input type="hidden" name="cd" value="{{$documento->cod_doc}}">
                    </form>
                    <div class=float-right>
                        <button class="btn btn-sm btn-primary" onclick="enviar('formObs','{{url('g_obs_documento')}}','panel_documento')">Guardar</button>
                    </div>
                </div>
            @endcan
            <script>
                function enviar(formulario,ruta,panel){
                    //$.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
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
        </div>

    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
</div>
