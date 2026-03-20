@extends('marco.pagina')
@section('contenido')
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
    @if(Session::has('errores'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('errores') !!}
        </div>
    @endif

    @if(isset($fallas) && count($fallas)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($fallas as $f)
                    <li>
                        <?php echo "Fila: ".$f->row()." - ";?>
                        <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Funcionarios</h5>

                    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#docente"
                    onclick="cargarDatos('{{url('fe_funcionario/0')}}','panel_docente')">+ Funcionario</a>

                        <a href="" class="btn btn-sm btn-outline-info text-dark" data-toggle="modal" data-target="#nuevaImportacion"><i class="fas fa-upload"> Nueva importación</i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="">
                        <a href="{{url('listar funcionario/docente')}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-right"></i> Listar docente</a> &nbsp;&nbsp;
                        <a href="{{url('listar funcionario/administrativo')}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-right"></i> Listar Administrativo</a>
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista de {{$funcionario}}s</h5>
                        </div>

                        <div class="mt-3 mb-3">
                            <input type="text" id="buscadorFuncionarios" class="form-control" placeholder="🔍 Buscar funcionario..." autocomplete="off">
                        </div>

                                <hr class="sidebar-divider">
                                <table class="table table-sm table-hover"  width="100%" cellspacing="0" style="font-size: 0.8em">
                                    <thead>
                                    <tr class="bg-gray-600 text-white">
                                        <th>Nº</th>
                                        <th class="">Nombre</th>
                                        <th class="">CI</th>
                                        <th class="">Sexo</th>
                                        <th class="">Telefonos</th>
                                        <th class="">Correo</th>
                                        <th class="">Fecha Ingreso</th>
                                        <th class="">Nacionalidad</th>
                                        <th class="">Pais Origen</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $j=1;?>
                                    @foreach($funcionarios as $f)
                                        @if($f->fun_obs!='t')
                                            <tr>
                                        @else
                                            @if($f->fun_folder!='t')
                                                <tr class="bg-warning">
                                            @else
                                                <tr class="alert-danger">
                                            @endif

                                        @endif
                                            <td>
                                                {{$j}}

                                            </td>
                                            <td>{{$f->fun_nombre}}
                                                @if($f->fun_folder!='t')
                                                    <span class="bg-danger p-1 rounded text-white">*</span>
                                                @endif
                                            </td>
                                            <td>{{$f->fun_ci}} - {{$f->cod_fun}}</td>
                                            @php $sexo=$f->fun_sexo=='M'?'Masculino':'Femenino' @endphp
                                            <td>{{$sexo}}</td>
                                            <td>{{$f->fun_telefonos}}</td>
                                            <td>{{$f->fun_email}}</td>
                                            <td>
                                                @if($f->fun_fecha_ingreso!='')
                                                    {{date('d/m/Y',strtotime($f->fun_fecha_ingreso))}}
                                                @endif
                                            </td>
                                            @php $nacionalidad=$f->fun_nacionalidad=='B'?'Boliviano':'Extranjero' @endphp
                                                <td>{{$nacionalidad}}</td>
                                            <td>{{$f->cod_nac}}</td>
                                            <td>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('{{url('fe_funcionario/'.$f->cod_fun)}}','panel_docente')"
                                                   title="Editar funcionario"><i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{url('listar documentos funcionario/'.$f->cod_fun)}}" class="btn btn-light btn-circle btn-sm text-primary" title="Mostrar documentos">
                                                    <i class="fas fa-arrow-alt-circle-right"></i>
                                                </a>
                                                @if($f->fun_obs=='t')
                                                    <a href="" class="btn btn-light btn-circle btn-sm text-danger" title="Ver Observacion">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if($f->fun_folder!='t')
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('{{url('fe_presentar folder/'.$f->cod_fun)}}','panel_docente')"
                                                        title="Presentar Folder"><i class="text-primary fas fa-folder-open"></i>
                                                    </a>
                                                @endif

                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('{{url('fe_eliminar funcionario/'.$f->cod_fun)}}','panel_docente')"
                                                   title="Eliminar funcionario"><i class="text-danger fas fa-trash-alt"></i>
                                                </a>

                                            </td>
                                        </tr>
                                        <?php $j++;?>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div id="mensajeSinResultados" class="alert alert-info text-center" style="display: none;">
                                    <i class="fas fa-search"></i> No se encontraron resultados
                                </div>

                    </div>
                </div>
            </div>
        </div>

    @can('acceder al sistema - dya')
        <!--===========================MODAL DOCENTE===================-->
            <div class="modal fade" id="docente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document" id="panel_docente">

                </div>
            </div>
            <!--===========================END ==============================-->
    @endcan
        <div class="modal fade" id="nuevaImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{url('importar nuevos')}}" method="POST" id="form_importar" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nueva importación</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="shadow-sm rounded p-2">
                                <h5 class="text-primary text-center">Importar Archivo</h5>
                                <br/>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Archivo :</th>
                                        <td class="">
                                            <div class="custom-file mb-3">
                                                <input type="file" class="form-control form-control-file" id="archivo" name="archivo" accept=".xlsx,.xls" required>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                            <input class="btn btn-primary" type="submit" value="Enviar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script>
            function cargarDatos(ruta,panel){
                $.ajax({
                    url: ruta,
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

            // Buscador de funcionarios
            document.getElementById('buscadorFuncionarios').addEventListener('keyup', function() {
                const busqueda = this.value.toLowerCase();
                const filas = document.querySelectorAll('table tbody tr');
                let hayCoincidencias = false;
                
                filas.forEach(fila => {
                    const contenido = fila.textContent.toLowerCase();
                    if (contenido.includes(busqueda)) {
                        fila.style.display = '';
                        hayCoincidencias = true;
                    } else {
                        fila.style.display = 'none';
                    }
                });
                
                // Mostrar u ocultar mensaje de sin resultados
                const mensajeResultados = document.getElementById('mensajeSinResultados');
                if (!hayCoincidencias && busqueda.length > 0) {
                    mensajeResultados.style.display = 'block';
                } else {
                    mensajeResultados.style.display = 'none';
                }
            });
        </script>
@endsection
