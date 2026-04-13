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
            <div class="card-header py-3 alert-primary col-md-12">
                <div class="d-sm-flex align-items-center col-md-12">
                    <div class="col-md-6">
                        <h5 class=""><i class="fas fa-user-circle"></i>&nbsp;Funcionario ({{ $funcionario->fun_doc_adm == 'D' ? 'Docente' : 'Administrativo' }})</h5>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
            </div>
            <div class="card-body" style="font-size: 15px">
                <div class="">
                    <div class="">
                        <a href="javascript:history.back()" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Formulario de Conformidad</h5>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="formConformidad" method="POST" action="{{ url('guardar-conformidad') }}">
                                    @csrf
                                    <input type="hidden" name="cod_fun" value="{{ $funcionario->cod_fun }}">
                                    <input type="hidden" name="back_url" value="{{ $backUrl ?? url()->previous() }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Nombre</label>
                                            <input type="text" class="form-control" value="{{$funcionario->fun_nombre}}" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" value="{{$funcionario->fun_telefonos ?? ''}}" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="{{$funcionario->fun_email ?? ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Lugar de trabajo</label>
                                            <select class="form-control" id="lugarTrabajo" name="lugarTrabajo" required>
                                                <option value="">Seleccione lugar de trabajo</option>
                                                <option value="Facultad de Economía" {{ old('lugarTrabajo') == 'Facultad de Economía' ? 'selected' : '' }}>Facultad de Economía</option>
                                                <option value="Facultad de Arquitectura" {{ old('lugarTrabajo') == 'Facultad de Arquitectura' ? 'selected' : '' }}>Facultad de Arquitectura</option>
                                                <option value="Facultad de Derecho" {{ old('lugarTrabajo') == 'Facultad de Derecho' ? 'selected' : '' }}>Facultad de Derecho</option>
                                                <option value="Facultad de Ciencias Jurídicas" {{ old('lugarTrabajo') == 'Facultad de Ciencias Jurídicas' ? 'selected' : '' }}>Facultad de Ciencias Jurídicas</option>
                                                <option value="Facultad de Ingeniería" {{ old('lugarTrabajo') == 'Facultad de Ingeniería' ? 'selected' : '' }}>Facultad de Ingeniería</option>
                                                <option value="Facultad de Humanidades" {{ old('lugarTrabajo') == 'Facultad de Humanidades' ? 'selected' : '' }}>Facultad de Humanidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Carrera</label>
                                            <input type="text" class="form-control" id="carrera" name="carrera" value="{{ old('carrera') }}" placeholder="Ingrese la carrera" required>
                                        </div>
                                    </div>
                                    {{--<div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2">{{ old('observaciones', '') }}</textarea>
                                        </div>
                                    </div>--}}
                                    <div class="form-row mt-3">
                                        <div class="col-md-12 text-right">
                                        </div>
                                    </div>
                                    <!-- TABLA TITULARIDADES -->
                                    @if($funcionario->fun_doc_adm == 'D')
                                        <hr class="sidebar-divider">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">Titularidad añadida al formulario</h6>
                                        </div>
                                        @if(isset($titularidades) && count($titularidades) > 0)
                                            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em" id="tablaTitularidades">
                                                <thead>
                                                    <tr class="bg-success text-white">
                                                        <th>Nº</th>
                                                        <th>Materia</th>
                                                        <th>Carrera</th>
                                                        <th>Facultad</th>
                                                        <th>Categoría</th>
                                                        <th>Gestión</th>
                                                        <th>Fecha</th>
                                                        <th>N° Resolución</th>
                                                        <th>Fecha Resolución</th>
                                                        <th>Verificado</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $k=1; ?>
                                                    @foreach($titularidades as $t)
                                                        <tr>
                                                            <td>{{$k}}</td>
                                                            <td class="font-weight-bold">{{$t->dt_materia}}</td>
                                                            <td>{{$t->car_nombre ?? '-'}}</td>
                                                            <td><span class="badge badge-info">{{$t->fac_abreviacion ?? '-'}}</span></td>
                                                            <td>{{$t->dt_categoria}}</td>
                                                            <td>{{$t->dt_gestion}}</td>
                                                            <td>
                                                                <?php
                                                                    if($t->dt_fecha != ''){
                                                                        echo date('d/m/Y', strtotime($t->dt_fecha));
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>{{$t->dt_numero_resolucion}}</td>
                                                            <td>
                                                                <?php
                                                                    if($t->dt_fecha_resolucion != ''){
                                                                        echo date('d/m/Y', strtotime($t->dt_fecha_resolucion));
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                @php
                                                                    echo $t->dt_verificado == 't'
                                                                        ? "<i class='fas fa-check-circle text-success'></i>"
                                                                        : "<i class='fas fa-minus-circle text-danger'></i>";
                                                                @endphp
                                                            </td>
                                                            <td>
                                                                @can('editar documento - dya')
                                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_documento titularidad/'.$t->cod_dt.'/'.$funcionario->cod_fun)}}','panel_documento')"
                                                                    title="Editar titularidad"><i class="fas fa-edit"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('eliminar documento - dya')
                                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_eliminar titularidad/'.$t->cod_dt.'/'.$funcionario->cod_fun)}}','panel_documento')"
                                                                    title="Eliminar titularidad"><i class="text-danger fas fa-trash-alt"></i>
                                                                    </a>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    <?php $k++; ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-secondary mb-3">No se han añadido titularidades en este formulario.</div>
                                        @endif
                                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento" 
                                                onclick="cargarDatos('{{url('fe_documento titularidad/0/'.$cod_fun)}}','panel_documento')">+ Titularidad</a>
                                    @endif

                                    <!-- TABLA DOCUMENTOS -->
                                    <hr class="sidebar-divider">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Documentos añadidos al formulario</h6>
                                    </div>
                                    @if(isset($documentos) && count($documentos) > 0)
                                        <table class="table table-sm table-hover sortable-table" width="100%" cellspacing="0" style="font-size: 0.8em" id="tablaDocumentos">
                                            <thead>
                                            <tr class="bg-gray-600 text-white">
                                                <th style="cursor: pointer; user-select: none;">Nº <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Tipo <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Título <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Grado <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Universidad <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Tipo Univ. <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Educación Superior <span class="sort-indicator"></span></th>
                                                <th style="cursor: pointer; user-select: none;">Reválida <span class="sort-indicator"></span></th>
                                                <th>Enviado DPA</th>
                                                <th>Opciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $j=1;?>
                                            @foreach($documentos as $d)
                                                <tr class="{{ in_array($d->cod_doc, $pendingObsDocIds) ? 'table-danger' : '' }}">
                                                    <td>{{$j}}</td>
                                                    <td>{{$d->doc_tipo}}</td>
                                                    <td>
                                                        <span class="font-weight-bold text-dark">{{$d->doc_titulo}}</span><br/>
                                                        <span style="font-size: 0.9em">
                                                            <span class="text-primary font-italic">Gestión : </span><span class="text-dark ">{{$d->doc_gestion}}</span> |
                                                            <span class="text-primary font-italic">Legalizado : </span><span class="text-dark font-weight-bold">
                                                            @php
                                                                echo $d->doc_legalizado=='t'?"<i class='fas fa-check-circle text-success'></i>":"<i class='fas fa-minus-circle text-danger'></i>";
                                                            @endphp
                                                            </span> |
                                                            <span class="text-primary font-italic">Verificado : </span><span class="text-dark font-weight-bold">
                                                            @php
                                                                echo $d->doc_verificado=='t'?"<i class='fas fa-check-circle text-success'></i>":"<i class='fas fa-minus-circle text-danger'></i>";
                                                            @endphp
                                                            </span> |
                                                            <span class="text-primary font-italic">Fecha emisión : </span><span class="text-dark">
                                                            <?php
                                                                if($d->doc_fecha_emision!=''){
                                                                    echo date('d/m/Y',strtotime($d->doc_fecha_emision));
                                                                }
                                                            ?>
                                                            </span>
                                                        </span>
                                                    </td>
                                                    <td>{{$d->doc_grado}}</td>
                                                    <td>{{$d->doc_universidad}}</td>
                                                    <td><span class="badge badge-{{ \App\Helpers\UniversidadHelper::getTipoUniversidad($d->doc_universidad) === 'Pública' ? 'success' : (\App\Helpers\UniversidadHelper::getTipoUniversidad($d->doc_universidad) === 'Privada' ? 'warning' : 'info') }}">{{ \App\Helpers\UniversidadHelper::getTipoUniversidad($d->doc_universidad) }}</span></td>
                                                    <td>
                                                        @if($d->doc_edu_superior=='t')
                                                            <span class="bg-success text-white rounded font-italic pr-1 pl-1 font-weight-bold"> Docencia </span>
                                                        @endif
                                                    </td>
                                                    <td>{{$d->doc_numero_revalida}}</td>
                                                    <td class="text-center">
                                                        @if($d->doc_enviado_dpa === true || $d->doc_enviado_dpa === 1 || $d->doc_enviado_dpa === 't')
                                                            <i class='fas fa-check-circle text-success' title="Ya enviado"></i>
                                                        @else
                                                            <i class='fas fa-minus-circle text-danger' title="No enviado"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @can('editar documento - dya')
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_documento/'.$d['cod_doc'].'/'.$d->cod_fun)}}','panel_documento')"
                                                        title="Editar documento"><i class="fas fa-edit"></i>
                                                        </a>
                                                        @endcan

                                                        <a href="#" class="btn btn-light btn-circle btn-sm {{ in_array($d->cod_doc, $pendingObsDocIds) ? 'text-danger' : 'text-primary' }}" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_observacion documento/'.$d['cod_doc'])}}','panel_documento')"
                                                        title="Observar Documento"><i class="fas fa-eye"></i>
                                                        </a>

                                                        @can('eliminar documento - dya')
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_eliminar documento/'.$d->cod_doc.'/'.$d->cod_fun)}}','panel_documento')"
                                                        title="Eliminar documento"><i class="text-danger fas fa-trash-alt"></i>
                                                        </a>
                                                        @endcan
                                                        @if($d->doc_pdf)
                                                            <a href="{{url('ver pdf documento/'.$d->cod_doc)}}" class="btn btn-light btn-circle btn-sm text-success" title="Ver PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                                            <a href="{{url('descargar pdf documento/'.$d->cod_doc)}}" class="btn btn-light btn-circle btn-sm text-info" title="Descargar PDF"><i class="fas fa-download"></i></a>
                                                        @endif

                                                    </td>
                                                </tr>
                                                <?php $j++;?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-secondary mb-3">No se han añadido documentos en este formulario.</div>
                                    @endif
                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento" 
                                    onclick="cargarDatos('{{url('fe_documento/0/'.$cod_fun)}}','panel_documento')">+ Documento</a>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-success">Guardar formulario</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @can('acceder al sistema - dya')
        <!--===========================MODAL DOCENTE===================-->
            <div class="modal fade" id="documento" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document" id="panel_documento">

                </div>
            </div>
            <!--===========================END ==============================-->
    @endcan

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('tablaDocumentos');
    const headers = table.querySelectorAll('th');
    let sortOrder = {};

    headers.forEach((header, index) => {
        if (index < headers.length - 1) {
            header.addEventListener('click', function() {
                sortTable(table, index, header);
            });
        }
    });

    function sortTable(table, columnIndex, header) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const ascending = !sortOrder[columnIndex] || !sortOrder[columnIndex].asc;
        sortOrder[columnIndex] = { asc: ascending };

        rows.sort((a, b) => {
            let aText = a.cells[columnIndex].textContent.trim();
            let bText = b.cells[columnIndex].textContent.trim();

            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);

            if (!isNaN(aNum) && !isNaN(bNum)) {
                return ascending ? aNum - bNum : bNum - aNum;
            }

            if (ascending) {
                return aText.localeCompare(bText, 'es');
            } else {
                return bText.localeCompare(aText, 'es');
            }
        });

        // Limpiar indicadores de todas las columnas
        table.querySelectorAll('.sort-indicator').forEach(indicator => {
            indicator.textContent = '';
        });

        // Agregar indicador a la columna actual
        const indicator = header.querySelector('.sort-indicator');
        indicator.textContent = ascending ? ' ↑' : ' ↓';

        // Re-insertar las filas ordenadas
        rows.forEach(row => tbody.appendChild(row));
    }
});

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

// Función para guardar el estado del formulario en localStorage
function saveFormState() {
    const lugarTrabajo = document.getElementById('lugarTrabajo').value;
    const carrera = document.getElementById('carrera').value;
    const observaciones = document.getElementById('observaciones').value;
    
    localStorage.setItem('conformidad_lugarTrabajo', lugarTrabajo);
    localStorage.setItem('conformidad_carrera', carrera);
    localStorage.setItem('conformidad_observaciones', observaciones);
}

// Función para restaurar el estado del formulario desde localStorage
function restoreFormState() {
    // Restaurar solo si los campos están vacíos
    const lugarTrabajoEl = document.getElementById('lugarTrabajo');
    if (!lugarTrabajoEl.value) {
        const lugarTrabajo = localStorage.getItem('conformidad_lugarTrabajo');
        if (lugarTrabajo) {
            lugarTrabajoEl.value = lugarTrabajo;
        }
    }
    const carreraEl = document.getElementById('carrera');
    if (!carreraEl.value) {
        const carrera = localStorage.getItem('conformidad_carrera');
        if (carrera) {
            carreraEl.value = carrera;
        }
    }
    const observacionesEl = document.getElementById('observaciones');
    if (!observacionesEl.value) {
        const observaciones = localStorage.getItem('conformidad_observaciones');
        if (observaciones) {
            observacionesEl.value = observaciones;
        }
    }
}

// Función para limpiar localStorage al enviar el formulario
function clearFormState() {
    localStorage.removeItem('conformidad_lugarTrabajo');
    localStorage.removeItem('conformidad_carrera');
    localStorage.removeItem('conformidad_observaciones');
}

document.addEventListener('DOMContentLoaded', function() {
    // Restaurar estado al cargar la página
    restoreFormState();
    
    // Limpiar estado si el formulario se guardó exitosamente
    if ({{ Session::has('exito') ? 'true' : 'false' }}) {
        clearFormState();
    }
    
    // Guardar estado al cambiar los campos
    document.getElementById('lugarTrabajo').addEventListener('change', saveFormState);
    document.getElementById('carrera').addEventListener('input', saveFormState);
    document.getElementById('observaciones').addEventListener('input', saveFormState);
    
    // Limpiar estado al enviar el formulario (solo si se envía, pero no si hay errores)
    // Nota: La limpieza se hace solo si hay éxito, arriba
    
    // También guardar antes de abrir el modal
    const buttons = document.querySelectorAll('[data-target="#documento"]');
    buttons.forEach(button => {
        button.addEventListener('click', saveFormState);
    });
});
</script>

@can('acceder al sistema - dya')
    <!--===========================MODAL DOCENTE===================-->
        <div class="modal fade" id="documento" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_documento">

            </div>
        </div>
        <!--===========================END ==============================-->
@endcan

@endsection
