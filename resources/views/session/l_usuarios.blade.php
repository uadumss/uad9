@extends('marco/pagina')
@section('contenido')
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
            <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-user-friends"></i>&nbsp;&nbsp; Lista de usuarios</h5>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group -sm p-2">
                <a href="" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm" data-toggle="modal" data-target="#nuevoUsuario"><i class="fas fa-user-plus"></i> Nuevo usuario</a> &nbsp;&nbsp;&nbsp;
                @if($bloqueado=='f')
                    <a href="{{url('l_usuario/t')}}" class="btn btn-outline-danger btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-user-plus"></i> Usuarios Bloqueado</a>
                @else
                    <a href="{{url('l_usuario/f')}}" class="btn btn-outline-success btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-user-plus"></i> Usuarios Habilitados</a>
                @endif

            </div>
            <div class="card  mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de usuarios @if($bloqueado=='t') Bloqueados @endif</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">CI</th>
                                <th class="text-left">Foto</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Cargo</th>
                                <th class="text-left">Login</th>
                                <th class="text-left">Rol</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($usuarios as $u)
                                <tr style="font-size: 0.9em">
                                    <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                                    <td class="text-right">{{$u['ci']}}</td>
                                    <td>@if($u->foto!='')
                                            <img src="{{url('img/foto/'.$u->foto)}}" width="40" height="40" class="imgRedonda float-right"/>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{$u['name']}}
                                    </td>
                                    <td>{{$u['cargo']}}</td>
                                    <td>{{$u['email']}}</td>
                                    <td class="font-weight-bold font-italic">{{$u['rol']}}</td>
                                    <td class="text-right">
                                        @if($u['bloqueado']=='f')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#deshabilitar" data-toggle="modal" onclick="cargarDatos('{{url("habilitar usuario/".$u['id'])}}','panel_habilitar')">
                                                <i class="fas fa-user-check"></i>
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#deshabilitar" data-toggle="modal" onclick="cargarDatos('{{url("habilitar usuario/".$u['id'])}}','panel_habilitar')">
                                                <i class="fas fa-user-lock"></i>
                                            </a>
                                        @endif
                                            <a href="{{url('mostrar cuenta usuario/'.$u->id)}}" class="btn btn-light btn-circle btn-sm text-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>

                                    </td>
                                </tr>
                                <?php $i++;?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========================MODAL NUEVO USUARIO===================-->
    <div class="modal fade" id="nuevoUsuario" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{url('g_usuario')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user-plus"></i> Usuario</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para nuevo usuario</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="row">
                            <div class="col-md-7">
                                <table class="table-hover col-md-10">
                                    <tr>
                                        <th class="text-right font-italic">Apellidos y Nombres :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" name="nombre" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Cédula de Identidad :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" name="ci" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Sexo : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0" name="sexo">
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Login:</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" name="login" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Cargo : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0" name="cargo">
                                                <option disabled selected hidden></option>
                                                <option value="JEFA">JEFA</option>
                                                <option value="ASISTENTE DE JEFATURA">ASISTENTE DE JEFATURA</option>
                                                <option value="RESPONSABLE INFORMATICO UAD">RESPONSABLE INFORMATICO UAD</option>
                                                <option value="RESPONSABLE DE LEGALIZACION">RESPONSABLE DE LEGALIZACION</option>
                                                <option value="RESPONSABLE DE APOSTILLA">RESPONSABLE DE APOSTILLA</option>
                                                <option value="RESPONSABLE DE TRAMITES">RESPONSABLE DE TRAMITES</option>
                                                <option value="RESPONSABLE DE ATENCION AL CLIENTE">RESPONSABLE DE ATENCION AL CLIENTE</option>
                                                <option value="ASISTENTE INFORMATICO UAD">ASISTENTE INFORMATICO UAD</option>
                                                <option value="ENCARGADO DE SISTEMATIZACION">ENCARGADO DE SISTEMATIZACION</option>
                                                <option value="ENCARGADO DE DIGITALIZACION">ENCARGADO DE DIGITALIZACION</option>
                                                <option value="ENCARGADO DE ARCHIVO HISTORICO">ENCARGADO DE ARCHIVO HISTORICO</option>
                                                <option value="ENCARGADO DE ARCHIVO ACADEMICO">ENCARGADO DE ARCHIVO ACADEMICO</option>
                                                <option value="BECARIO IDH">BECARIO IDH</option>
                                                <option value="AUXILIAR">AUXILIAR</option>
                                                <option value="ADSCRITO">ADSCRITO</option>
                                                <option value="PRACTICANTE">PRACTICANTE</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Rol : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0" name="rol">
                                                <option disabled selected hidden></option>
                                                <option value="FUNCIONARIO">FUNCIONARIO</option>
                                                <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Contacto : </th>
                                        <td class="border-bottom border-dark"><textarea class="form-control border-0" rows="1" name="contacto" id="contacto"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Dirección : </th>
                                        <td class="border-bottom border-dark"><textarea class="form-control border-0" rows="2" name="direccion" id="direccion"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <span class="text-dark font-weight-bold"> Foto:</span>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input accept="image/,.png,.jpeg,.jpg" type="file" class="custom-file-input" id="foto" name="foto">
                                        <label class="custom-file-label" for="foto">Seleccionar foto</label>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div id="prevista" class="centrar_bloque col-md-6">
                                    <img id="imgSalida" class="imgRedonda" width="100" height="100" src="{{url('img/icon/fotoM.png')}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-primary" type="submit" value="Guardar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--===========================END ==============================-->

    <!--===========================MODAL EDITAR USUARIO===================-->
    <div class="modal fade" id="editarUsuario" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{url('g_usuario')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user-plus"></i> Usuario</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para editar usuarios</h5>
                        </div>
                        <div id="panel_editar_usuario">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-primary" type="submit" value="Guardar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--===========================END ==============================-->
    <!-- ================== MODAL DESHABILITAR USUARIO==============-->
    <div class="modal fade" id="deshabilitar"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="{{url('habilitar')}}" method="post">
                    @csrf
                    <input  type="hidden" name="ct" id="ct" value=""/>
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-user-check"> </i>&nbsp;&nbsp;Habilitar</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="panel_habilitar">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-danger" type="submit" value="Aceptar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- =============================== ====================-->

    <script>
        function cargarDatos(ruta,panel){
            var link="{{url('/')}}"+"/"+ruta;
            $.ajax({
                url: link,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
        function MsgEliminarTomo(tomo,ct){
            $('#ct').val(ct);
            $('#eliTomo').html("");
        }
    </script>

    <script>
        document.getElementById("foto").onchange = function(e) {
            // Creamos el objeto de la clase FileReader
            let reader = new FileReader();

            // Leemos el archivo subido y se lo pasamos a nuestro fileReader
            reader.readAsDataURL(e.target.files[0]);

            // Le decimos que cuando este listo ejecute el código interno
            reader.onload = function(){
                let preview = document.getElementById('prevista'),
                    image = document.createElement('img');

                image.src = reader.result;
                image.setAttribute('width', '150px');
                image.setAttribute('height', '150px');
                preview.innerHTML = '';
                preview.append(image);
            };
        }
    </script>
@endsection
