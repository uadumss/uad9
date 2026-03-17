
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
                <div class="col-md-8">
                    <div class="justify-content-center text-center">
                        <span class="text-dark text-uppercase font-weight-bold">Carreras y facultades</span><br/><br/>
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <th>Carrera</th>
                            <th>Facultad</th>
                            <th>Eliminar</th>
                        </tr>
                        <?php $i=1;?>
                        @foreach($carrera as $c)
                        <tr>
                            <td style="font-size: 0.8em;">{{$c->car_nombre}}</td>
                            <td style="font-size: 0.8em;">{{$c->fac_nombre}}</td>
                            <td>
                                <!--<a href="#" class="btn btn-light btn-circle btn-sm" data-target="#eliminarCar" data-toggle="modal" onclick="">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>-->
                                @can('eliminar carrera tomo - dyt')
                                <a href="#" class="btn btn-light btn-circle btn-sm" onclick="eliminarCar('e_carTomo/{{$c->cod_tcar}}')">
                                    <i class="fas fa-trash text-danger"></i></a>
                                @endcan
                            </td>
                        </tr>
                            <?php $i++;?>
                        @endforeach
                    </table>
                </div>
            </div>
            @can('asignar carrera - dyt')
            <hr class="sidebar-divider"/>
                <div class="shadow m-5">
                    <table class="table">
                        <tr>
                            <td><span class="pt-1 text-danger font-weight-bold">Nueva carrera :</span></td>
                            <td>
                                <div class="">
                                    <span class="text-dark font-weight-bold">Asignar por carrera.</span>
                                    <div class="col-md-8 input-group">
                                        <select name="n_carrera" class="custom-select" id="n_carrera">
                                            @foreach($n_carrera as $nc)
                                                <option value="{{$nc['cod_car']}}">{{$nc['car_nombre']}}</option>
                                            @endforeach
                                        </select>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <button class="btn btn-primary" onclick="asignarCarrera('a_tomocarrera',$('#n_carrera').val())">Asignar</button>
                                    </div>
                                    <br/>
                                    <br/>
                                    <span class="text-dark font-weight-bold">Asignar por facultad.</span>
                                    <div class="col-md-8 input-group">
                                        <select name="n_carrera" class="custom-select" id="n_fac">
                                            @foreach($facultad as $f)
                                                <option value="{{$f['cod_fac']}}">{{$f['fac_nombre']}}</option>
                                            @endforeach
                                        </select>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <button class="btn btn-primary" onclick="asignarCarrera('a_tomofac',$('#n_fac').val())">Asignar</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                @endcan



<script>
    function asignarCarrera(ruta,cod){
        var link="{{url('/')}}/"+ruta;
        var token = "{{csrf_token()}}";
        var data="cc="+cod+"&ct={{$tomo['cod_tom']}}";
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            data:data,
            success: function (resp) {

                $('#panel_editarcar').html(resp);
            },
            error: function () {
                $('#panel_editarcar').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
            }
        });
    }
    function eliminarCar(ruta){
        var link="{{url('')}}"+"/"+ruta;
        $.ajax({
            url: link,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#panel_editarcar').html(resp);
            },
            error: function () {
                $('#panel_editarcar').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
            }
        });
    }
</script>
