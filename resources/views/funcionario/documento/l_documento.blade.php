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
                            </span> |
                            <span class="text-primary font-italic">Enviado a la DPA : </span>
                            <span class="text-dark font-weight-bold">
                                @php
                                    $estadoDpaFuncionario = $funcionario->fun_env_dpa === true || $funcionario->fun_env_dpa === 1 || $funcionario->fun_env_dpa === 't';
                                @endphp
                                @if($estadoDpaFuncionario && !$hasDpaCandidates)
                                    <i class='fas fa-check-circle text-success'></i>
                                @else
                                    <i class='fas fa-minus-circle text-danger'></i>
                                @endif
                            </span>
                        </span>
                        @if($enviosDpa->count() > 0)
                            <div class="mt-2" style="font-size: 0.85em">
                                <span class="text-primary font-italic">Fecha de envio a la DPA : </span>
                                <span class="text-dark font-weight-bold">{{ isset($enviosDpa[0]) ? date('d/m/Y H:i',strtotime($enviosDpa[0]->env_fecha)) : '' }}</span>
                            </div>
                        @endif

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
                        <!-- TABLA DOCUMENTOS -->
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
                                                    </span> |
                                                    <span class="text-primary font-italic">Número de Registro : </span><span class="text-dark">
                                                    {{$d->doc_numero_registro ?? 'N/A'}}
                                                    </span>
                                                </span>
                                                @if($d->doc_tesis_titulo)
                                                    <br/><div style="font-size: 0.85em; margin-top: 5px; padding-top: 5px; border-top: 1px solid #e3e6f0;">
                                                        <span class="text-primary font-italic">Tesis : </span><span class="text-dark font-weight-bold">{{$d->doc_tesis_titulo}}</span>
                                                    </div>
                                                @endif
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
                                <div class="mt-3 text-right">
                                    @if($hasDpaCandidates)
                                        <a href="#" class="btn btn-sm btn-success" data-target="#documento" data-toggle="modal"
                                           onclick="cargarDatos('{{url('fe_enviar dpa/'.$cod_fun)}}','panel_documento')">
                                            <i class="fas fa-paper-plane"></i> Nuevo envio a la DPA
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-success" disabled title="No se puede enviar a la DPA con el estado actual de documentos">
                                            <i class="fas fa-paper-plane"></i> Nuevo envio a la DPA
                                        </button>
                                        @if(!$hasPreviousDpaEnvio)
                                            <small class="d-block mt-1 text-muted">Debe subir al menos 1 documento para realizar el primer envío a la DPA</small>
                                        @else
                                            <small class="d-block mt-1 text-muted">No hay documentos pendientes para reenvío (revise observaciones pendientes en caso contrario).</small>
                                        @endif
                                    @endif
                                </div>

                                <hr class="sidebar-divider">
                                <div class="bg-primary centrar_bloque p-1 col-md-4 rounded shadow">
                                    <h5 class="text-white text-center">Historial de envios a la DPA</h5>
                                </div>

                                @if($enviosDpa->count() > 0)
                                    <ul class="nav nav-tabs mt-3" id="enviosDpaTab" role="tablist">
                                        @foreach($enviosDpa as $idx => $env)
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link {{$idx===0?'active':''}}" id="envio-tab-{{$env->cod_env_dpa}}" data-toggle="tab" href="#envio-{{$env->cod_env_dpa}}" role="tab">
                                                    Envio #{{$idx + 1}} - {{date('d/m/Y',strtotime($env->env_fecha))}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content border border-top-0 p-3" id="enviosDpaTabContent">
                                        @foreach($enviosDpa as $idx => $env)
                                            <div class="tab-pane fade {{$idx===0?'show active':''}}" id="envio-{{$env->cod_env_dpa}}" role="tabpanel">
                                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                                    <span class="text-primary font-italic">Fecha de envio: <span class="text-dark font-weight-bold">{{date('d/m/Y H:i',strtotime($env->env_fecha))}}</span></span>
                                                    <span>
                                                        <a href="{{url('ver pdf envio dpa/'.$env->cod_env_dpa)}}" class="btn btn-sm btn-outline-success" target="_blank">
                                                            <i class="fas fa-file-pdf"></i> Ver PDF envio DPA
                                                        </a>
                                                        <a href="{{url('descargar pdf envio dpa/'.$env->cod_env_dpa)}}" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-download"></i> Descargar PDF envio DPA
                                                        </a>
                                                    </span>
                                                </div>

                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered" style="font-size: 0.82em">
                                                        <thead class="bg-gray-600 text-white">
                                                            <tr>
                                                                <th>Nº</th>
                                                                <th>Tipo</th>
                                                                <th>Titulo</th>
                                                                <th>Grado</th>
                                                                <th>Universidad</th>
                                                                <th>Fecha emision</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php $k=1; @endphp
                                                        @foreach(($enviosDpaDocumentos[$env->cod_env_dpa] ?? collect()) as $docEnv)
                                                            <tr>
                                                                <td>{{$k}}</td>
                                                                <td>{{$docEnv->doc_tipo}}</td>
                                                                <td>{{$docEnv->doc_titulo}}</td>
                                                                <td>{{$docEnv->doc_grado}}</td>
                                                                <td>{{$docEnv->doc_universidad}}</td>
                                                                <td>{{ $docEnv->doc_fecha_emision ? date('d/m/Y',strtotime($docEnv->doc_fecha_emision)) : '' }}</td>
                                                            </tr>
                                                            @php $k++; @endphp
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info mt-3 mb-0">No hay envios registrados a la DPA para este funcionario.</div>
                                @endif
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
