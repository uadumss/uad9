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
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; TRAMITES DE NO ATENTADO</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">

                            <a href="{{url('lista convocatoria noatentado/'.$convocatoria->con_gestion)}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                            @can('crear tramite - noa')
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                    <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#Noatentado" data-toggle="modal"
                                        onclick="cargarDatos('{{url('editar tramite convocatoria/'.$convocatoria->cod_con."/0")}}','panel_noatentado');">
                                        + No-atentado</a>
                                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            @endcan
                    </div>
                </div>
                <div class="col-md-3">
                    @can('buscar trámite - apo')
                        <div class="input-group float-left">
                            <a haref="#" class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#apostilla"
                               onclick="cargarDatos('{{url('formulario busqueda apostilla')}}','panel_apostilla')" >
                                <i class="fas fa-search"></i> Buscar
                            </a>
                        </div>
                    @endcan
                </div>
            </div>

            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Trámites de no atentado</h5>
                    </div>
                    <span style="font-size: 0.8em">
                        <span class="font-weight-bold font-italic text-primary">Convocatoria: </span><span class="font-italic text-dark">{{$convocatoria->con_nombre}}</span>
                    </span>
                    <hr class="sidebar-divider">
                    <div id="panel_lista_tramites">
                        <div>
                            <div class="table-responsive">
                                <div id="panel_tabla_tramites" class="col-md-12">
                                    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                        <thead>
                                        <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                            <th>Nº</th>
                                            <th class="text-left">Nro. Trámite</th>
                                            <th>Trámite</th>
                                            <th>Nombres</th>
                                            <th class="text-left">Fecha solicitud</th>
                                            <th class="text-center">Opciones</th>
                                            <th class="text-center">Entrega</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cuerpo">
                                            <?php $i=1;?>
                                        @foreach($tramites as $t)
                                            <tr>
                                                <th class="border-right font-weight-bolder">{{$i}}</th>
                                                <td class="text-right"><span class="text-primary font-weight-bold">{{$t->dtra_numero_tramite}}</span><span class="text-dark font-weight-bold">/{{$t->dtra_gestion_tramite}}</span></td>
                                                <td class="">{{$t->tre_nombre}}</td>
                                                <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>

                                                </td>

                                                <td class="text-right">{{date('d/m/Y',strtotime($t->dtra_fecha_registro))}}</td>
                                                <td class="text-right">
                                                    @can('editar tramite - noa')
                                                        <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Noatentado"
                                                           onclick="cargarDatos('{{url("editar tramite convocatoria/".$t->cod_con."/".$t->cod_dtra)}}','panel_noatentado')"
                                                           title="Editar trámite"><i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @if($t->dtra_generado!='t')
                                                        @can('generar glosa - noa')
                                                             <a href="#Noatentado" onclick="cargarDatos('{{url("formulario glosa noatentado/$t->cod_dtra")}}','panel_noatentado')"
                                                                class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                                                                  title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        @endcan
                                                    @else
                                                            @can('imprimir pdf - noa')
                                                                <a href="{{url('generar pdf noatentado/'.$t->cod_dtra)}}" target="No-atentado"
                                                                class="btn btn-primary btn-sm btn-circle btn-light text-danger" title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                            @endcan
                                                    @endif
                                                    @can('rehacer tramite - noa')
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-info" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('{{url("formulario corregir tramite noatentado/$t->cod_dtra")}}','panel_noatentado')" title="Corregir tramite">
                                                            <i class="fas fa-arrow-circle-left"></i>
                                                        </a>
                                                    @endcan
                                                    @can('eliminar tramite - noa')
                                                        <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('{{url("formulario eliminar tramite noatentado/$t->cod_dtra")}}','panel_noatentado')"
                                                           title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td class="text-right">
                                                    @if($t->dtra_entregado=='a' && $t->dtra_entregado!='t' && $t->dtra_entregado!='c')
                                                        <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                        <span class="font-weight-bold text-success font-italic">Apoderado </span>
                                                    @else
                                                        @if($t->dtra_entregado=='c')
                                                            <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                            <span class="font-weight-bold text-success font-italic">Apoderado </span>
                                                        @else
                                                            @if($t->dtra_entregado=='t')
                                                                <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                            @endif
                                                        @endif
                                                    @endif
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
            </div>
        </div>
    </div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="Noatentado" style="z-index: 1500" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_noatentado">

        </div>
    </div>
    <!--===========================END===================-->

    <!-- ================== MODAL DOCLEG ====================-->
    <div class="modal fade" id="Noatentado_agregar" role="dialog" style="z-index: 3000; margin-top: 40px;">
        <div class="modal-dialog modal-xl" role="document" id="panel_agregar">

        </div>
    </div>
    <!--===========================END ==============================-->

@endsection
