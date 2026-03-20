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
                        <h5 class=""><i class="fas fa-user-circle"></i>&nbsp;Funcionarios</h5>
                    </div>
                    <div class="col-md-6">
                        <a href="" class="btn btn-sm btn-primary float-right mr-1" data-toggle="modal" data-target="#documento"
                           onclick="cargarDatos('{{url('fe_documento/0/'.$cod_fun)}}','panel_documento')">+ Documento</a>
                        <a href="" class="btn btn-sm btn-primary float-right mr-1" data-toggle="modal" data-target="#documento"
                           onclick="cargarDatos('{{url('fe_documento titularidad/0/'.$cod_fun)}}','panel_documento')">+ Titularidad</a>
                    </div>
                </div>
            </div>
            <div class="card-body" style="font-size: 15px">
                <div class="">
                    <div class="">
                        <?php  $redireccion=$funcionario->fun_doc_adm=='D'?'docente':'administrativo';?>
                        <a href="{{url('listar funcionario/'.$redireccion)}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista Titularidades</h5>
                        </div>
                        <span style="font-size: 0.85em">
                            <span class="text-primary font-italic">Funcionario : </span><span class="text-dark font-weight-bold">{{$funcionario->fun_nombre}}</span> |
                            <span class="text-primary font-italic">Tipo : </span><span class="text-dark font-weight-bold">
                                @php
                                    switch($funcionario->fun_doc_adm){
                                        case 'D': echo 'DOCENTE'; break;
                                        case 'E': echo 'DOCENTE - ADMINISTRATIVO'; break;
                                        case 'A': echo 'ADMINISTRATIVO'; break;
                                    }
                                @endphp
                            </span>
                        </span>

                        <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="">Tipo Titularidad</th>
                                <th class="">Detalle</th>
                                <th class="">Materia</th>
                                <th class="">Carrera</th>
                                <th class="">Facultad</th>
                                <th class="">Fecha emisión</th>
                                <th class="">Resolución</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <?php $j=1;?>

                            <tbody> @foreach($titularidades as $d)
                                    <tr>
                                        <td>{{$j}}</td>
                                        <td>{{$d->dt_categoria}}</td>
                                        <td>{{$d->dt_detalle}}</td>
                                        <td>{{$d->dt_materia}}</td>
                                        <td>{{$d->car_nombre}}</td>
                                        <td>{{$d->fac_nombre}}</td>
                                        <td>
                                            @if($d->dt_fecha!='')
                                                {{date('d/m/Y',strtotime($d->dt_fecha))}}
                                            @endif
                                        </td>
                                        <td>{{$d->dt_numero_resolucion}}</td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_documento titularidad/'.$d->cod_dt.'/'.$d->cod_fun)}}','panel_documento')"
                                               title="Editar titularidad"><i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_eliminar titularidad/'.$d->cod_dt.'/'.$d->cod_fun)}}','panel_documento')"
                                               title="Eliminar titularidad"><i class="text-danger fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $j++;?>
                                    @endforeach
                            </tbody>
                        </table>

                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista de Diplomas y Títulos</h5>
                        </div>
                        <hr class="sidebar-divider">
                                <table class="table table-sm table-hover sortable-table" width="100%" cellspacing="0" style="font-size: 0.8em" id="tablaDocumentos">
                                    <thead>
                                    <tr class="bg-gray-600 text-white">
                                        <th style="cursor: pointer; user-select: none;">Nº <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Tipo <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Tìtulo <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Grado <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Universidad <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Tipo Univ. <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Educación Superior <span class="sort-indicator"></span></th>
                                        <th style="cursor: pointer; user-select: none;">Reválida <span class="sort-indicator"></span></th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $j=1;?>
                                    @foreach($documentos as $d)
                                        @if($d->doc_obs!='t')
                                            <tr>
                                        @else
                                            <tr class="alert-danger">
                                        @endif
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
                                            <td>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_documento/'.$d['cod_doc'].'/'.$d->cod_fun)}}','panel_documento')"
                                                   title="Editar documento"><i class="fas fa-edit"></i>
                                                </a>
                                                @if($d->doc_obs=='t')
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_observacion documento/'.$d['cod_doc'])}}','panel_documento')"
                                                        title="Observar Documento"><i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_observacion documento/'.$d['cod_doc'])}}','panel_documento')"
                                                       title="Observar Documento"><i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('{{url('fe_eliminar documento/'.$d->cod_doc.'/'.$d->cod_fun)}}','panel_documento')"
                                                   title="Eliminar documento"><i class="text-danger fas fa-trash-alt"></i>
                                                </a>

                                            </td>
                                        </tr>
                                        <?php $j++;?>
                                    @endforeach
                                    </tbody>
                                </table>
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
</script>

@endsection
