<div class="row col-md-12" id="panel_completo">
    <div class="col-md-3">
        <div class="card shadow" id="d_personales">
            <div class="card-header bg-info">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class="text-white">Datos personales</h5>
                    <button onclick="//$('#d_personales').hide(200);$('#btn_personal').show(200);"
                            class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body" id="d_personales">
                <div>
                    <div class="centrar_bloque col-md-7">
                        @if($usu['per_fot']!='')
                            <img src="{{url('img/foto/'.$usu['per_fot'])}}" class="imgRedonda centrar_bloque" width="150" height="150">
                        @else
                            <img src="{{url('img/icon/sin foto'.$usu['per_sexo'].'.png')}}" class="imgRedonda centrar_bloque" width="150" height="150">
                        @endif
                    </div>
                    <br/>
                    <div class="p-1">
                        <table class="">
                            <tr class="border-bottom">
                                <th>Nombre:</th>
                                <td>{{$usu['per_ape']." ".$usu['per_nom']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Ci:</th>
                                <td>{{$usu['per_ci']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Teléfono:</th>
                                <td>{{$usu['per_telf']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Contacto:</th>
                                <td>{{$usu['per_telf_ref']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Sexo:</th>
                                <td>{{$usu['per_sexo']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>fecha ingreso:</th>
                                <td>{{$usu['created_at']}}</td>
                            </tr>

                            <tr class="border-bottom">
                                <th>Email:</th>
                                <td>{{$cuenta['email']}}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Activo:</th>
                                <td>
                                    @if($funcionario['fun_hab']=='t')
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="text-danger font-weight-bolder">X</i>
                                    @endif
                                </td>

                            </tr>
                            <tr class="border-bottom">
                                <th>Responsable:</th>
                                <td>
                                    @if($responsable['res_hab']=='t')
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="text-danger font-weight-bolder">X</i>
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Dirección:</th>
                                <td>{{$usu['per_dir']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="p_trabajo">
        <div class="card shadow">
            <div class="card-header bg-info">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class="text-white">Panel de edición</h5>
                </div>
            </div>
            <div class="card-body">
                <div id="panel">
                    @if(Session::has('exito'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {!! session('exito') !!}
                        </div>
                    @endif
                    @if(count($errors)>0)
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul>
                                @foreach($errors->all() as $e)
                                    <li>{{$e}} - </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <div class="row">
                            <div class="col-md-2">
                                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" onclick="his()">
                                    <i class="fas fa-user-friends fa-sm"></i> Ver historial</a>
                            </div>
                            <div class="col-md-6 alert-info p-2 centrar_bloque rounded shadow">
                                <h5 class="text-dark text-center">Asignación de funcionarios</h5>
                            </div>
                        </div>
                        <br/>
                        <div>
                            @if($responsable['res_hab']=='t')
                                <div class="float-right">
                                    <span class="mensaje">* Historial de Asignación de nuevo funcionarios</span>

                                    <div class="input-group">
                                        <select class="form-control mr-2 form-control-sm shadow-sm" id="fun">
                                            @foreach($personas as $p)
                                                <option value="{{$p->id_per}}">{{$p->per_ape." ".$p->per_nom}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-info btn-sm shadow-sm" id="enviar" onclick="asignar()"><i class="fas fa-user "></i> Asignar</button>
                                    </div>
                                </div>
                            @else
                                <div class="text-danger float-right font-weight-bolder">
                                    <div class="mensaje-peligro">El usuario no esta habilitado como responsable</div>
                                    <button class="btn btn-danger btn-sm float-right" onclick="habRes({{$ip}})"><i class="fas fa-user-check"></i> Habilitar</button>
                                </div>
                            @endif
                            <br/>
                            <br/>
                            <div class="">
                                <span class="mensaje">* Lista de funcionarios asignados</span>
                                <table class="table table-sm shadow-sm rounded">
                                    <tr class="alert-info">
                                        <th>Nº</th>
                                        <th>Nombre</th>
                                        <th>Fecha Asignación</th>
                                        <th>Fecha conclusión</th>
                                        <th>Opciones</th>
                                    </tr>
                                    <?php $i=1;?>
                                    @foreach($fun as $f)
                                        <tr>
                                            <td>{{$i}}</td>
                                            @if($f->per_fot!='')
                                                <td>    <img src="{{url('img/foto/'.$f->per_fot)}}" width="40" height="40" class="imgRedonda"/>
                                                    {{$f->per_ape." ".$f->per_nom}}
                                                </td>
                                            @else
                                                <td class="pl-5">
                                                    {{$f->per_ape." ".$f->per_nom}}
                                                </td>
                                            @endif

                                            <td>@if($f->car_fi!='')
                                                    {{date('d/m/Y',strtotime($f->car_fi))}}
                                                @endif
                                            </td>
                                            <td>@if($f->car_ff!='')
                                                    {{date('d/m/Y',strtotime($f->car_ff))}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($f->car_hab=='t')
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-success" title="Deshabilitar" data-target="#df{{$i}}" data-toggle="modal">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                    <!-- =============MODAL DESHABILITAR ASIGNACION FUNCIONARIO-->
                                                    <div class="modal fade" id="df{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content border-left-danger border-bottom-danger">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Deshabilitar asignación</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Esta seguro de deshabilitar la asignación del funcionario : <br/><br/>
                                                                    <div class="row">
                                                                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2">
                                                                            {{$f->per_ape." ".$f->per_nom}}
                                                                        </div>
                                                                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                                    <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="habilitarAsig({{$f->id_car}},'f')">Aceptar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ====================END ==============-->
                                                @else
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark" title="Habilitar" data-target="#hf{{$i}}" data-toggle="modal">
                                                        <i class="fas fa-user-lock"></i>
                                                    </a>
                                                    <!-- =============MODAL HABILITAR FUNCIONARIO-->
                                                    <div class="modal fade" id="hf{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content border-left-primary border-bottom-primary">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-primary" id="exampleModalLabel">Habilitar funcionario</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Esta seguro de habilitar la asignación del funcionario: <br/><br/>
                                                                    <div class="row">
                                                                        <div class="font-weight-bold alert-primary  shadow text-center centrar_bloque col-md-8 p-3">
                                                                            {{$f->per_ape." ".$f->per_nom}}
                                                                        </div>
                                                                        <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h2>?</h2></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                                    <button class="btn btn-primary" type="button" data-dismiss="modal" onclick="habilitarAsig({{$f->id_car}},'t')">Aceptar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ====================END ==============-->
                                            @endif
                                            <!-- FINALIZACION DE ASIGNACION -->
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#cd{{$i}}" title="Finalizar asignación" data-toggle="modal">
                                                    <i class="fas fa-book"></i>
                                                </a>
                                                <!-- =============MODAL DESHABILITAR ASIGNACION FUNCIONARIO-->
                                                <div class="modal fade" id="cd{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content border-bottom-danger">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-danger" id="exampleModalLabel">Finalizar asignación</h5>
                                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Esta seguro de finalizar la asignación del funcionario : <br/><br/>
                                                                <div class="row">
                                                                    <div class="font-weight-bold alert-danger  shadow text-center centrar_bloque col-md-8 p-2">
                                                                        {{$f->per_ape." ".$f->per_nom}}
                                                                    </div>
                                                                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="finAsig({{$f->id_car}},{{$f->id_per}})">Aceptar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ====================END ==============-->

                                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" title="Eliminar" data-target="#ea{{$i}}" data-toggle="modal">
                                                    <i class="fas fa-trash"></i>
                                                </a>

                                                <!-- =============MODAL ELIMINAR ASIGNACION FUNCIONARIO-->
                                                <div class="modal fade" id="ea{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content border-bottom-danger">
                                                            <div class="modal-header bg-gradient-danger">
                                                                <h5 class="modal-title text-white" id="exampleModalLabel"><img src="{{url('img/icon/eliminar.png')}}"> Eliminar asignación</h5>
                                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Esta seguro de eliminar la asignación del funcionario : <br/><br/>
                                                                <div class="row">
                                                                    <div class="font-weight-bold alert-danger text-center shadow centrar_bloque col-md-8 p-2">
                                                                        {{$f->per_ape." ".$f->per_nom}}
                                                                    </div>
                                                                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="eliAsig({{$f->id_car}},{{$f->id_per}})">Aceptar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ====================END ==============-->
                                            </td>
                                        </tr>
                                        <?php $i++;?>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="advertencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content border-left-danger border-bottom-danger">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Asignar funcionario</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body row">
                                    <div  id="mensaje">
                                        &nbsp;
                                    </div>
                                    <div class="font-weight-bold alert-danger text-center centrar_bloque col-md-8 p-3" >
                                        Esta seguro de asignarlo nuevamente
                                    </div>
                                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="asignar1()">Asignar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function asignar(){
                            var fun = $('#fun').val();
                            var url="{{url('a_v_acargo')}}";
                            var data="ip="+fun;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': token
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    var res="{{$ip}}";
                                    data="fun="+fun+"&res="+res;
                                    if(resp){
                                        $('#mensaje').html(resp);
                                        $('#advertencia').modal({
                                            show: true
                                        });
                                    }else{
                                        $('#advertencia').modal({
                                            show: false
                                        });
                                        asignar1();
                                    }

                                }
                            });
                        }
                        function asignar1(){
                            var fun = $('#fun').val();
                            var res="{{$ip}}";
                            var url="{{url('a_g_acargo')}}";
                            var data="fun="+fun+"&res="+res;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': token
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    $("#panel").html(resp);
                                }
                            });
                        }
                        function his(){
                            var url="{{url('a_v_historialAsigFun')."/".$ip}}";
                            $.ajax({
                                type: "GET",
                                url: url,
                                success: function (resp) {
                                    $("#panel").html(resp);
                                }
                            });
                        }
                        function habilitarAsig(ia,valor){
                            var res="{{$ip}}";
                            var url="{{url('a_h_asig_fun')}}";
                            var data="ia="+ia+"&res="+res+"&v="+valor;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': token
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    $("#panel").html(resp);
                                }
                            });
                        }
                        function finAsig(ia,fun){
                            var res="{{$ip}}";
                            var url="{{url('a_f_asig_fun')}}";
                            var data="ia="+ia+"&res="+res+"&fun="+fun;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    $("#panel").html(resp);
                                }
                            });
                        }
                        function eliAsig(ia,fun){
                            var res="{{$ip}}";
                            var url="{{url('a_e_asig_fun')}}";
                            var data="ia="+ia+"&res="+res+"&fun="+fun;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    $("#panel").html(resp);
                                }
                            });
                        }
                        function habRes(ip){
                            var res="{{$ip}}";
                            var url="{{url('a_hab_res')}}";
                            var data="res="+res;
                            var token = "{{csrf_token()}}";
                            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (resp) {
                                    $("#panel_completo").html(resp);
                                }
                            });
                        }

                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
