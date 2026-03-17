    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('exito') !!}</span>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="text-dark">{!! session('error') !!}</span>
        </div>
    @endif
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

            <ul>
                @foreach($errors->all() as $e)
                    <li class="font-weight-bold te">{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif

        @if($num<9 && $num>=0 && is_numeric($num))
            <div class="d-sm-flex align-items-center justify-content-between">
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/0')}}','panel')" class="d-sm-inline-block btn  <?php echo $vista[0];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-book"></i> </h6>
                    <strong>D y T</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/1')}}','panel')"  class="d-sm-inline-block btn <?php echo $vista[1];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-list-ul"></i> </h6>
                    <strong>RR - RCU</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/6')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[6];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-list-alt"></i> </h6>
                    <strong>RCF - RCC</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/2')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[2];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-university"></i> </h6>
                    <strong>SERVICIOS</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/3')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[3];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-file-import"></i> </h6>
                    <strong>APOSTILLA</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/4')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[4];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-friends"></i> </h5>
                    <strong>D y A</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/7')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[7];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-lock"></i> </h5>
                    <strong>NoA</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/8')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[8];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-check"></i> </h5>
                    <strong>CLAUSTROS</strong>
                </a>
                <a href="#" onclick="cargarDatos('{{url('listar permisos/'.$usuario['id'].'/5')}}','panel')" class="d-sm-inline-block btn <?php echo $vista[5];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-fw fa-cog"></i> </h6>
                    <strong>ADM</strong>
                </a>

            </div>
            @if($num<9 && $num>=0 && is_numeric($num))
                <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#nuevoObjeto" data-toggle="modal">
                    <i class="fas fa-box"></i> Nuevo objeto</a>&nbsp;&nbsp;
                <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#nuevoPermiso" data-toggle="modal">
                    <i class="fas fa-key"></i> Nuevo permiso</a>&nbsp;&nbsp;
            @endif

            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            <span class="text-right"><span class="font-italic font-weight-bold text-dark">Usuario : </span><a href="#" class="text-primary"> {{$usuario['name']}} </a>
                </span>
            <hr class="sidebar-divider"/>
            <div>
                <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                    <h5 class="text-white text-center">Lista de permisos</h5>
                </div>
                <div class="row">
                    @foreach($objetos as $o)
                        <div class="col-md-3 m-2 shadow rounded">
                            <span class="text-danger font-weight-bold" colspan="2">{{$o->obj_nombre}}</span><br/><br/>
                            <table>
                                @foreach($totalPermisos as $t)
                                    @if($o->cod_obj==$t->objeto)
                                        <tr>
                                            <?php $existe=false?>
                                            @foreach($permisosUsuario as $pu)
                                                <?php if($pu->permission_id==$t->id){
                                                    $existe=true;
                                                }
                                                ?>
                                            @endforeach
                                            @if($existe)
                                                    <td valign="top"><input type="checkbox" name="permiso" value="{{$t->name}}" checked onchange="procesar($(this))" /></td>
                                            @else
                                                    <td valign="top"><input type="checkbox" name="permiso" value="{{$t->name}}" onchange="procesar($(this))" /></td>
                                            @endif
                                            <td class="font-italic" valign="top">{{$t->leyenda}}</span></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                            <br/>
                        </div>
                    @endforeach
                    <br/>

                </div>
            </div>
        @else
            <div class="alert-danger">
                 Error de ruta
            </div>
        @endif

    <!--===========================NUEVO PERMISO===================-->
    <div class="modal fade" id="nuevoPermiso" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-key"></i> Permisos</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para nuevo permiso</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div>
                            <form action="{{url('guardar permiso')}}" method="POST" id="form_permiso">
                                @csrf

                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Permiso :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="permiso" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Leyenda :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="leyenda" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Objeto : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm" name="objeto" required>
                                                <option disabled selected hidden></option>
                                                @foreach($objetos as $o)
                                                    <option value="{{$o['cod_obj']}}">{{$o['obj_nombre']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Subsistema : </th>
                                        <td class="border-bottom border-dark">
                                            <span class="text-primary font-weight-bold">{{$subsistema}}</span>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="id" value="{{$usuario['id']}}">
                                <input type="hidden" name="num" value="{{$num}}">
                                <input type="hidden" name="subsistema" value="{{$subsistema}}">
                            </form>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a href="#" class="btn btn-primary" onclick="enviar('form_permiso','{{url("guardar permiso")}}','panel');$('#nuevoPermiso').modal('hide')"> Guardar</a>
                    </div>
                </div>

        </div>
    </div>
    <!--===========================END ==============================-->
    <!--===========================NUEVO OBJETO===================-->
    <div class="modal fade" id="nuevoObjeto" role="dialog" aria-hidden="false">
        <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-box"></i> Objetos</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para nuevo objeto</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div>
                            <form id="form_objeto">
                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Objeto :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="objeto" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Subsistema : </th>
                                        <td class="border-bottom border-dark">
                                            <span class="text-primary font-weight-bold">{{$subsistema}}</span>
                                        </td>
                                    </tr>
                                </table>
                                @csrf
                                <input type="hidden" name="id" value="{{$usuario['id']}}">
                                <input type="hidden" name="num" value="{{$num}}">
                                <input type="hidden" name="subsistema" value="{{$subsistema}}">
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a href="#" class="btn btn-primary" data-toggle="modal" onclick="$('#nuevoObjeto').modal('hide');enviar('form_objeto','{{url("guardar objeto")}}','panel');"> Guardar</a>
                    </div>
                </div>

        </div>
    </div>
    <!--===========================END ==============================-->
    <!--===========================Procesar permiso===================-->
    <div class="modal" id="procesarPermiso" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
                <div class="modal-content border-bottom-danger">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-key"></i> Asignar permiso</h5>
                        <button class="close text-dark" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body centrar_bloque" id="panel_permisos">
                        <div class="spinner-border text-warning text-center col-md-12" role="status" >
                            <span class="sr-only text-center">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        </div>
    </div>
    <!--===========================END ==============================-->
    <script>
        function procesar(check){
            $('#procesarPermiso').modal('show');
            var data="check="+check.prop('checked')+"&val="+check.val()+"&id={{$usuario->id}}";
            var link = "{{url('asignar permiso/')}}";
            var token = "{{csrf_token()}}";

            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                data:data,
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                   $('#procesarPermiso').modal('hide');
                },
                error: function (data) {
                    $('#panel_permisos').html('<span class="text-danger font-weight-bold"> Ocurrió un error, permiso no asignado</span>');
                }
            });
        }
    </script>


