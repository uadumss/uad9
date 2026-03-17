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
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; LEGALIZACIONES</h5>
            </div>
        </div>
        <div class="card-body">
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="input-group">
                        @can('crear tramite - srv')
                        <a href="#" class="btn btn-outline-info btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                           onclick="cargarDatos('{{url("fe_tramite/L/0")}}','panel_tramite')">
                            +  Legalización</a>
                        <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        <a href="#" class="btn btn-outline-warning btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                           onclick="cargarDatos('{{url("fe_tramite/C/0")}}','panel_tramite')">
                            +  Certificación</a>
                        <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        <a href="#" class="btn btn-outline-danger btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                           onclick="cargarDatos('{{url("fe_tramite/F/0")}}','panel_tramite')">
                            +  Confrontación</a>
                        <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        <a href="#" class="btn btn-outline-success btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                           onclick="cargarDatos('{{url("fe_tramite/B/0")}}','panel_tramite')">
                            +  Búsqueda</a>
                            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            <a href="#" class="btn btn-outline-primary btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                               onclick="cargarDatos('{{url("fe_tramite/A/0")}}','panel_tramite')">
                                +  No atentado</a>
                            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            <a href="#" class="btn btn-outline-secondary btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                               onclick="cargarDatos('{{url("fe_tramite/E/0")}}','panel_tramite')">
                                +  Consejero</a>
                        @endcan
                    </div>

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de Trámites</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" style="font-size: 0.85em" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-left">Tipo</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">N° Cuenta</th>
                                <th class="text-left">Asociado</th>
                                <th class="text-left">Duración</th>
                                <th class="text-right">Costo</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                                @foreach($tramites as $t)
                                    @if($t['tre_hab']=='t')
                                        <tr style="font-size: 0.90em">
                                    @else
                                        <tr class="alert-danger" style="font-size: 0.90em">
                                    @endif

                                        <th class="border-right font-weight-bolder">
                                             <span class="text-primary">{{$i}}</span>
                                        </th>
                                        <td class="text-left">
                                                @php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['A']='NO-ATENTADO';$tipo_tramite['AC']='bg-primary text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                                @endphp
                                                <span class="font-italic font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$t['tre_tipo'].'C']}}">
                                                {{$tipo_tramite[$t['tre_tipo']]}}
                                            </span>
                                        </td>
                                        <td class="text-left">{{$t['tre_nombre']}} </td>
                                        <td>{{$t['tre_numero_cuenta']}}</td>
                                        <td class="text-left">{{strtoupper($t['tre_buscar_en'])}}</td>

                                        <td class="text-left">{{$t['tre_duracion']}}</td>
                                        <td class="text-right">{{$t['tre_costo']}} Bs.</td>

                                        <td class="text-right">
                                            @can('editar tramite - srv')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#Tramite" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("fe_tramite/".$t["tre_tipo"]."/".$t["cod_tre"])}}','panel_tramite')"
                                                    title="Editar trámite"><i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#Tramite" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("l_glosa/".$t["cod_tre"])}}','panel_tramite')"
                                                   title="Glosas"><i class="fas fa-align-justify"></i>
                                                </a>
                                                @can('habilitar tramite - srv')
                                                    <a href="{{url('habilitar tramite/'.$t['cod_tre'])}}" class="btn btn-light btn-circle btn-sm" title="Habilitar legalización">
                                                        @if($t['tre_hab']=='t')
                                                            <i class="fas fa-check-square text-success"></i>
                                                        @else
                                                            <i class="fas fa-minus-circle text-danger"></i>
                                                        @endif
                                                    </a>
                                                @endcan
                                                @can('eliminar tramite - srv')
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Tramite" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("f_eli_tramite/".$t['cod_tre'])}}','panel_tramite')"
                                                    title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @endcan
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
    <!--===========================MODAL NUEVA LEGALIZACION===================-->
    <div class="modal fade" id="Tramite" style="z-index: 1500;" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_tramite">

            </div>
    </div>
    <div class="modal fade" id="glosa" style="z-index: 1500;" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_glosa">

        </div>
    </div>

    <!-- =============================== ====================-->
    <script>
        function cargarDatos(ruta,panel){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
            $.ajax({
                url: ruta,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    $('#'+panel).html("<span class='text-white bg-dark'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }

        function enviar(formulario,ruta,panel){
            //$.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                type: "POST",
                url: ruta,
                data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
                success: function(resp)
                {
                    $('#'+panel).html(resp);
                }
            });
        }
    </script>
@endsection
