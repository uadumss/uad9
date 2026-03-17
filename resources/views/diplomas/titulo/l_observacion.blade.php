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
            <div class="col-md-8">

                <table class="col-md-11">
                    <tr class="border-bottom border-dark">
                        <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Lista de observaciones</span><br/><br/></th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($observaciones as $o)
                        <tr class="border-bottom border-dark">
                            <th>{{$i}}</th>
                            <td><div class="ml-2">
                                    <div class="text-justify p-2">
                                        <span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Observación : </span>
                                        <span style="font-size: 0.8em;" class="text-dark font-weight-bold">{{date('d/m/Y',strtotime($o->obs_fecha))}}</span> <br/>
                                        {{$o['obs_observacion']}}
                                    </div>
                                    @if($o->obs_solucion=='')
                                        @can('registrar solucion titulo - dyt')
                                            <div class="text-right">
                                                <a class="btn btn-outline-info btn-sm text-dark" data-toggle="collapse" href="#solucion{{$i}}" role="button"
                                                   aria-expanded="false" aria-controls="collapseExample">
                                                    Solución <i class="fas fa-arrow-alt-circle-down text-primary"></i>
                                                </a>
                                            </div>

                                            <div class="collapse" id="solucion{{$i}}">
                                                <div>

                                                    <form action="{{url('g_obs')}}" method="POST" id="formObs{{$i}}">
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
                                                        <input type="hidden" name="ct" value="{{$titulo[0]->cod_tit}}">
                                                        <input type="hidden" name="co" value="{{$o->cod_obs}}">
                                                    </form>
                                                    <div class=float-right>
                                                        <button class="btn btn-sm btn-primary" onclick="enviarObs({{$i}})">Guardar</button>
                                                    </div>

                                                </div>
                                            </div>
                                        @endcan
                                    @else

                                        <div class="alert-warning rounded p-2">
                                            <div><span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Solucionado el : </span>
                                                <span style="font-size: 0.8em;" class="text-dark font-weight-bold">{{date('d/m/Y',strtotime($o->obs_fecha_solucion))}}</span> </div>
                                            <div class="justify-content-center">{{$o->obs_solucion}}</div>
                                            @can('eliminar observacion titulo - dyt')
                                                <div class="text-right">
                                                    <a class="btn btn-danger btn-sm  text-white " data-toggle="collapse" href="#solucion{{$i}}" role="button"
                                                       aria-expanded="false" aria-controls="collapseExample" onclick="eliminarObs({{$i}})">
                                                        Eliminar <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="eliminarObs{{$i}}" method="POST">
                                                        <input type="hidden" name="co" value="{{$o->cod_obs}}">
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
            @can('crear observacion titulo - dyt')
                <div class="col-md-4">
                    <form action="{{url('g_obs')}}" method="POST" id="formObs0">
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
                        <input type="hidden" name="ct" value="{{$titulo[0]->cod_tit}}">
                    </form>
                    <div class=float-right>
                        <button class="btn btn-sm btn-primary" onclick="enviarObs(0)">Guardar</button>
                    </div>
                </div>
            @endcan
            <script>
                function enviarObs(numero){
                    var link = "{{url('g_obs/')}}";
                    var token = "{{csrf_token()}}";
                    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                    $.ajax({
                        url: link,
                        type: 'POST',
                        data:$('#formObs'+numero).serialize(),
                        success: function (resp) {
                            $('#p_observacion').html(resp);
                            var fila=$('#fila_obs').val();
                            $('#obs'+fila).html('<i class="fas fa-eye text-danger"></i>')
                        },
                        error: function () {
                            $('#p_observacion').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
                        }
                    });
                }
                function eliminarObs(numero){
                    var link = "{{url('e_obs/')}}";
                    var token = "{{csrf_token()}}";
                    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                    $.ajax({
                        url: link,
                        type: 'POST',
                        data:$('#eliminarObs'+numero).serialize(),
                        success: function (resp) {
                            $('#p_observacion').html(resp);
                        },
                        error: function () {
                            $('#p_observacion').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
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
