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
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; TRÁMITES</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                        <div class="input-group float-left">
                            <span class="btn btn-sm btn-primary"><i class="fas fa-search"></i></span>
                            <input class="form-control form-control-sm col-md-3" type="text"
                                   onchange="$(location).attr('href','{{url("buscar tramite legalizacion/")}}'+'/'+this.value);"/>
                            <span class="text-danger font-weight-bold pt-1" style="font-size: .8em;"> &nbsp;Ejm: 123-2022</span>
                        </div>
                </div>
            </div>

            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de Entrega de trámites</h5>
                    </div>
                    <span style="font-size: 0.8em">
                        <span class="font-weight-bold font-italic text-primary">Fecha: </span><span class="font-italic text-dark">{{date('d/m/Y')}}</span>
                    </span>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#Legalizacion" type="button" role="tab" aria-controls="home" aria-selected="true">Legalizaciones</button>
                        </li>
                        @can('entregar tramite - noa')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#No-atentado" type="button" role="tab" aria-controls="profile" aria-selected="false">No-atentado</button>
                        </li>
                        @endcan
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Legalizacion" role="tabpanel" aria-labelledby="home-tab">
                            <div class="table-responsive">
                                <div id="panel_tabla_tramites">
                                    <br/>
                                    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                        <thead>
                                        <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                            <th>Nº</th>
                                            <th class="text-left">Tipo</th>
                                            <th class="text-left">CI</th>
                                            <th class="text-left">Nombre</th>
                                            <th class="text-left">Número Atención</th>
                                            <th class="text-left">Número trámite</th>
                                            <th class="text-left">Fecha solicitud</th>
                                            <th class="text-left">Fecha firma</th>
                                            <th class="text-center">Entrega</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cuerpo">
                                        <?php $i=1;?>
                                        @foreach($l_entregas as $t)
                                            <tr>

                                                <th class="border-right font-weight-bolder">
                                                    <span class="text-primary">{{$i}}</span>
                                                </th>
                                                <td>
                                                    @php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                                    @endphp
                                                    <span class="font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$t->dtra_tipo.'C']}}" style="font-size: 0.75em">
                                                {{$tipo_tramite[$t->dtra_tipo]}}
                                            </span>
                                                </td>
                                                <td class="text-left">{{$t->per_ci}}</td>
                                                <td class="text-left">{{$t->per_apellido." ".$t->per_nombre}}
                                                    @if($t->tra_tipo_apoderado=='p')
                                                        <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                                                    @else
                                                        @if($t->tra_tipo_apoderado=='d')
                                                            <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-left">{{$t->tra_numero}}</td>
                                                <td>{{$t->dtra_numero_tramite." / ".$t->dtra_gestion_tramite}}</td>
                                                <td class="text-right">{{date('d/m/Y',strtotime($t->tra_fecha_solicitud))}}</td>

                                                <td class="text-right">@php if($t->dtra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->dtra_fecha_firma));} @endphp </td>
                                                <td class="text-right">

                                                    <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("panel entrega legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                       title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>

                                                </td>
                                            </tr>
                                                <?php $i++;?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="No-atentado" role="tabpanel" aria-labelledby="profile-tab">
                            <div id="panel_tabla_no-atentado">
                                <div class="table-responsive">
                                    <div id="" class="col-md-12">
                                        <br/>
                                        <table class="table table-sm table-hover" id="dataTable2" width="100%" cellspacing="0" style="font-size: smaller">
                                            <thead>
                                            <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                                <th>Nº</th>
                                                <th class="text-left">Tipo</th>
                                                <th class="text-left">Nro. Trámite</th>
                                                <th>Trámite</th>
                                                <th>Nombres</th>
                                                <th class="text-left">Fecha solicitud</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                            </thead>
                                            <tbody id="cuerpo">
                                            <?php $i=1;?>
                                            @foreach($noatentado as $t)
                                                <tr>
                                                    <th class="border-right font-weight-bolder">{{$i}}</th>
                                                    <td>
                                                        <span class="font-weight-bold rounded pl-2 pr-2 bg-primary text-white" style="font-size: 0.75em">NO-ATENTADO</span>
                                                    </td>
                                                    <td class="text-right"><span class="text-primary font-weight-bold">{{$t->dtra_numero_tramite}}</span>/{{$t->dtra_gestion_tramite}}</td>
                                                    <td class="">{{$t->tre_nombre}}</td>
                                                    <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>
                                                    </td>
                                                    <td class="text-right">{{date('d/m/Y',strtotime($t->dtra_fecha_registro))}}</td>
                                                    <td class="text-right">
                                                        @can('entregar tramite - noa')
                                                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal"
                                                               onclick="cargarDatos('{{url("formulario entrega tramite noatentado/$t->cod_dtra")}}','panel_traleg')"
                                                               title="Entregar trámite"> <i class="fas fa-hand-point-right"></i>
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
                </div>
            </div>
        </div>
    </div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="traleg" role="dialog" style="z-index: 1500"  aria-hidden="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_traleg">

        </div>
    </div>
    <!--===========================END===================-->
    <!-- ================== MODAL DOCLEG ====================-->
    <div class="modal fade" id="docleg" role="dialog" style="z-index: 3000">
        <div class="modal-dialog modal-xl" role="document" id="panel_docleg">

        </div>
    </div>
    <!--===========================END ==============================-->

    <script>
        function guardarDatos(ruta,panel,form){
            $.ajax({
                url: ruta,
                type: 'POST',
                data:$('#'+form).serialize(),
                success: function (resp) {
                    $('#'+panel).html(resp);
                    cargarDatosTabla('{{url("ltl_ajax_entrega/")}}','panel_tabla_tramites');
                },
                error: function (resp) {
                    var obj=resp.responseJSON.errors;
                    var texto='';
                    $.each(obj, function(key,value) {
                        texto=texto+value+'<br/>';
                    });
                    if(texto!=''){
                        $('#error_datos_span').html(texto);
                    }
                    $('#error_datos').show();
                    setTimeout(function () {
                        $('#error_datos').hide(500);
                    }, 4000);

                }
            });
        }
        function cargarDatosTabla(ruta,panel){
            $.ajax({
                url: ruta,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }
        function cargarDatosApoderado(ci){
            var link="{{url('datos_apo/')}}"+"/"+ci;
            $.ajax({
                url: link,
                type: 'GET',
                success: function (resp) {
                    if(resp=="No"){
                        $('#apellido_apoderado').val('');
                        $('#nombre_apoderado').val('');
                    }else{
                        var res=JSON.parse(resp);
                        $('#apellido_apoderado').val(res['apo_apellido']);
                        $('#nombre_apoderado').val(res['apo_nombre']);
                    }
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }
    </script>
@endsection
