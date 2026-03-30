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

                    <div>
                        @can('crear funcionarios - dya')
                        <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#docente"
                        onclick="cargarDatos('{{url('fe_funcionario/0')}}','panel_docente')">+ Funcionario</a>
                        @endcan

                        <a href="" class="btn btn-sm btn-success" data-toggle="modal" data-target="#conformidad"
                        onclick="abrirFormularioConformidad()"><i class="fas fa-file-alt"></i> + Formulario de conformidad</a>

                        <a href="" class="btn btn-sm btn-outline-info text-dark" data-toggle="modal" data-target="#nuevaImportacion"><i class="fas fa-upload"> Nueva importación</i></a>
                    </div>
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

                        <form id="formBuscador" action="{{ url('listar funcionario/'.$funcionario) }}" method="GET" class="form-inline mt-3 mb-3">
                            <input type="text" id="buscadorFuncionarios" name="q" value="{{ old('q', $search ?? '') }}" class="form-control mr-2" placeholder="🔍 Buscar funcionario..." autocomplete="off" autofocus>
                            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
                        </form>

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
                                        <th class="">Estado</th>
                                        <th class="">Enviado a la DPA</th>
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
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($f->fun_nombre, $search) !!}
                                                @if($f->fun_folder!='t')
                                                    <span class="bg-danger p-1 rounded text-white">*</span>
                                                @endif
                                            </td>
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($f->fun_ci, $search) !!} - {{$f->cod_fun}}</td>
                                            @php $sexo=$f->fun_sexo=='M'?'Masculino':'Femenino' @endphp
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($sexo, $search) !!}</td>
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($f->fun_telefonos, $search) !!}</td>
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($f->fun_email, $search) !!}</td>
                                            <td>
                                                @if($f->fun_fecha_ingreso!='')
                                                    {{date('d/m/Y',strtotime($f->fun_fecha_ingreso))}}
                                                @endif
                                            </td>
                                            @php $nacionalidad=$f->fun_nacionalidad=='B'?'Boliviano':'Extranjero' @endphp
                                                <td>{!! \App\Helpers\UniversidadHelper::highlightText($nacionalidad, $search) !!}</td>
                                            <td>{!! \App\Helpers\UniversidadHelper::highlightText($f->cod_nac, $search) !!}</td>
                                            <td>
                                                @php
                                                    $estado = $f->fun_habilitado ?? null;
                                                @endphp
                                                @if($estado === null)
                                                    <span class="badge badge-warning">No está actualizado</span>
                                                @elseif($estado === false || $estado === 0 || $estado === 'f')
                                                    <span class="badge badge-danger">Inactivo</span>
                                                @elseif($estado === true || $estado === 1 || $estado === 't')
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-warning">No está actualizado</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($f->fun_env_dpa === true || $f->fun_env_dpa === 1 || $f->fun_env_dpa === 't')
                                                    <i class='fas fa-check-circle text-success' title="Enviado"></i>
                                                @else
                                                    <i class='fas fa-minus-circle text-danger' title="No enviado"></i>
                                                @endif
                                            </td>
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

                                <div class="d-flex justify-content-center">
                                    <style>
                                        .pagination { margin: 0.5rem 0; }
                                        .pagination .page-link { 
                                            font-size: 0.75rem !important; 
                                            padding: 0.25rem 0.4rem !important;
                                        }
                                    </style>
                                    <nav aria-label="Page navigation">
                                        {{ $funcionarios->links('pagination::bootstrap-4') }}
                                    </nav>
                                </div>

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

        <!--===========================MODAL CONFORMIDAD===================-->
        <div class="modal fade" id="conformidad" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content border-bottom-success">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> Formulario de Conformidad</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formConformidad" method="POST" action="{{ url('guardar-conformidad') }}">
                            @csrf
                            <div class="form-group">
                                <label for="nombreConformidad"><strong>Nombre del Funcionario:</strong></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nombreConformidad" name="nombre" placeholder="Buscar funcionario..." autocomplete="off" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" id="btnBuscarConformidad">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Comienza a escribir para buscar...</small>
                            </div>

                            <div id="resultadosBusqueda" class="list-group mt-2" style="max-height: 300px; overflow-y: auto; display: none;">
                            </div>

                            <input type="hidden" id="codFuncionarioConformidad" name="cod_fun" value="">

                            <div class="form-group mt-3">
                                <label for="obsConformidad"><strong>Observaciones:</strong></label>
                                <textarea class="form-control" id="obsConformidad" name="observaciones" rows="4" placeholder="Ingrese observaciones..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" type="button" id="btnGuardarConformidad">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--===========================END ==============================-->

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
            const buscadorInput = document.getElementById('buscadorFuncionarios');
            const formBuscador = document.getElementById('formBuscador');
            
            // Colocar el cursor al final al cargar la página
            if (buscadorInput.value) {
                buscadorInput.focus();
                buscadorInput.setSelectionRange(buscadorInput.value.length, buscadorInput.value.length);
            }
            
            // Si el campo se vacía, recargar automáticamente
            buscadorInput.addEventListener('keyup', function() {
                if (this.value.length === 0) {
                    formBuscador.submit();
                }
            });

            // Búsqueda de funcionarios para conformidad
            const nombreConformidadInput = document.getElementById('nombreConformidad');
            const resultadosBusquedaDiv = document.getElementById('resultadosBusqueda');
            const btnBuscarConformidad = document.getElementById('btnBuscarConformidad');
            let debounceTimerConformidad = null;

            function abrirFormularioConformidad() {
                // Limpiar el formulario cuando se abre
                document.getElementById('formConformidad').reset();
                document.getElementById('codFuncionarioConformidad').value = '';
                resultadosBusquedaDiv.style.display = 'none';
            }

            nombreConformidadInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimerConformidad);
                
                if (this.value.length < 2) {
                    resultadosBusquedaDiv.style.display = 'none';
                    return;
                }

                debounceTimerConformidad = setTimeout(() => {
                    buscarFuncionariosConformidad(this.value);
                }, 300);
            });

            btnBuscarConformidad.addEventListener('click', function() {
                if (nombreConformidadInput.value.length >= 2) {
                    buscarFuncionariosConformidad(nombreConformidadInput.value);
                }
            });

            function buscarFuncionariosConformidad(termino) {
                $.ajax({
                    url: '{{ url("buscar-funcionarios") }}',
                    type: 'GET',
                    data: { q: termino },
                    success: function(data) {
                        resultadosBusquedaDiv.html('');
                        if (data.length > 0) {
                            data.forEach(function(funcionario) {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action';
                                item.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${funcionario.fun_nombre}</h6>
                                            <p class="mb-1"><small>CI: ${funcionario.fun_ci} | Email: ${funcionario.fun_email}</small></p>
                                        </div>
                                    </div>
                                `;
                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    seleccionarFuncionarioConformidad(funcionario.cod_fun, funcionario.fun_nombre);
                                });
                                resultadosBusquedaDiv.appendChild(item);
                            });
                            resultadosBusquedaDiv.style.display = 'block';
                        } else {
                            resultadosBusquedaDiv.innerHTML = '<div class="list-group-item">No se encontraron funcionarios</div>';
                            resultadosBusquedaDiv.style.display = 'block';
                        }
                    },
                    error: function() {
                        resultadosBusquedaDiv.innerHTML = '<div class="list-group-item text-danger">Error en la búsqueda</div>';
                        resultadosBusquedaDiv.style.display = 'block';
                    }
                });
            }

            function seleccionarFuncionarioConformidad(codFun, nombre) {
                document.getElementById('nombreConformidad').value = nombre;
                document.getElementById('codFuncionarioConformidad').value = codFun;
                resultadosBusquedaDiv.style.display = 'none';
            }

            document.getElementById('btnGuardarConformidad').addEventListener('click', function() {
                if (document.getElementById('codFuncionarioConformidad').value === '') {
                    alert('Por favor, selecciona un funcionario de la lista');
                    return;
                }
                document.getElementById('formConformidad').submit();
            });
        </script>
@endsection
