<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Editar carrera</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Formulario para editar carrera</h6>
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
                <div class="col-md-4">
                    <span class="text-dark text-uppercase font-weight-bold">Datos del tomo</span>
                    <hr class="sidebar-divider">
                    <table class="text-primary">
                        <tr class="border-bottom">
                            <td class="font-italic">Número de tomo </td>
                            <td>: {{$tomo['tom_numero']}}</td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Gestión </td>
                            <td>: {{$tomo['tom_gestion']}}</td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Tipo de tomo </td>
                            <td>: {{$tipo_completo}}</td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Rago de documentos </td>
                            <td>: {{$tomo['tom_rango']}}</td>
                        </tr>
                    </table>
                </div>
                <div id="panel_editarcar"></div>
                <div class="col-md-8">
                    @can('asignar carrera - dyt')
                        <hr class="sidebar-divider"/>
                        <div class="shadow m-5">
                            <table class="table col-md-12">
                                <tr>
                                    <td><span class="pt-1 text-danger font-weight-bold">Añadir carrera :</span></td>
                                    <td>
                                        <div class="">
                                            <div class="col-md-12 input-group">
                                                <select name="n_carrera" class="custom-select" id="n_carrera">
                                                    @foreach($carreras as $nc)
                                                        <option value="{{$nc->cod_car}}">{{$nc->fac_abreviacion."- ".$nc->car_nombre}}</option>
                                                    @endforeach
                                                </select>
                                                &nbsp;&nbsp;<button class="btn btn-primary" onclick="asignarCarrera('a_tomocarrera',$('#n_carrera').val(),'{{$accion}}')" >Asignar</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
</div>


<script>
    function asignarCarrera(ruta,cod,objeto){
        var link="{{url('/')}}/"+ruta;
        var token = "{{csrf_token()}}";
        var data="cc="+cod+"&ct={{$tomo['cod_tom']}}&c=1";
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            data:data,
            success: function (resp) {
                $("#verObs").modal('hide');
                if(objeto=='fila_e_car'){
                    $('#fila_car').html(resp);
                }
                $('#'+objeto).html(resp);
            },
            error: function () {
                $('#panel_editarcar').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
            }
        });
    }

</script>
