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
    @if(sizeof($tramitas)==0)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">No existe registros</span>
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
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="float-left ">
                            <div class="input-group">
                                <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;"> Buscar fecha :&nbsp; &nbsp;</span>
                                <input class="form-control form-control-sm" type="date" name="fecha" onchange="$(location).attr('href','{{url("listar tramite legalizacion/")}}'+'/'+this.value);" />
                            </div>
                        </div>&nbsp;&nbsp;|&nbsp;&nbsp;
                        @can('crear traleg - srv')
                            @if($fecha==(date('Y-m-d')))
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="generarNumero('L','generar numero','panel_traleg');setTimeout(function () {$('#traleg').modal('hide');}, 1500); ">
                                    <i class="fas fa-plus"></i> Legalización</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="generarNumero('C','generar numero','panel_traleg');setTimeout(function () {$('#traleg').modal('hide');}, 1500); ">
                                    <i class="fas fa-plus"></i> Certificación</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="generarNumero('F','generar numero confrontacion/','panel_traleg')">
                                    <i class="fas fa-plus"></i> Confrontación</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="generarNumero('B','generar numero busqueda/','panel_traleg');setTimeout(function () {$('#traleg').modal('hide');}, 1500); ">
                                    <i class="fas fa-plus"></i> Busqueda</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="generarNumero('E','generar numero/','panel_traleg');setTimeout(function () {$('#traleg').modal('hide');}, 1500); ">
                                    <i class="fas fa-plus"></i> Consejo</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#traleg" data-toggle="modal"
                                   onclick="cargarDatos('{{url('fe_importar Legalizacion')}}','panel_traleg')">
                                    <i class="fas fa-plus"></i> Importar Legalización</a>
                            @endif
                        @endcan
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="input-group float-left">
                        <input class="form-control form-control-sm" type="text" placeholder="Nro. valorado" id="ver_valorado"
                               onchange="if(+this.value!=''){cargarDatos('{{url('buscar valorado/')}}'+'/'+this.value,'panel_docleg');$('#docleg').modal('show');}"/>
                        <span class="btn btn-sm btn-primary" onclick="if($('#ver_valorado').val()!=''){cargarDatos('{{url('buscar valorado/')}}'+'/'+$('#ver_valorado').val(),'panel_docleg');$('#docleg').modal('show');}"><i class="fas fa-check-circle"></i></span>&nbsp;&nbsp;

                            <input class="form-control form-control-sm" type="text" placeholder="Nro. tramite"
                                   onchange="$(location).attr('href','{{url("buscar tramite legalizacion/")}}'+'/'+this.value);"/>
                                <span class="btn btn-sm btn-primary"><i class="fas fa-search"></i></span>
                            <span class="text-danger font-weight-bold pt-1" style="font-size: .8em;">&nbsp;Ejm: 123-2022</span>
                        </div>
                </div>
            </div>

            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Trámites de Legalización</h5>
                    </div>
                    <span style="font-size: 0.8em">
                        <span class="font-weight-bold font-italic text-primary">Fecha: </span><span class="font-italic text-dark">{{date('d/m/Y',strtotime($fecha))}}</span>
                    </span>
                    <hr class="sidebar-divider">
                        <div class="table-responsive">
                            <div id="panel_tabla_tramites">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                <thead>
                                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th class="text-left">Tipo</th>
                                    <th class="text-left">Número</th>
                                    <th class="text-left">CI</th>
                                    <th class="text-left">Nombre</th>
                                    <th class="text-left">Fecha solicitud</th>
                                    <th class="text-left">Fecha firma</th>
                                    <th class="text-right">Fecha recojo</th>
                                    <th class="text-center">Opciones</th>
                                    <th class="text-center">Entrega</th>
                                </tr>
                                </thead>
                                <tbody id="cuerpo">
                                <?php $i=1;?>
                                @foreach($tramitas as $t)
                                    @if($t->tra_anulado=='t')
                                        <tr class="alert-danger">
                                    @else
                                        <tr class="">
                                    @endif
                                            <th class="border-right font-weight-bolder">
                                                <span class="text-primary">{{$i}}</span>
                                            </th>
                                            <td>
                                                @php    $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                        $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                        $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                        $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                        $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                                @endphp
                                                <span class="font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$t->tra_tipo_tramite.'C']}}" style="font-size: 0.75em">
                                                {{$tipo_tramite[$t->tra_tipo_tramite]}}
                                            </span>
                                                @if($t->tra_obs=='t')
                                                    &nbsp;<i class="fas fa-info-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-left">{{$t->tra_numero}}</td>
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
                                            <td class="text-right">{{date('d/m/Y',strtotime($t->tra_fecha_solicitud))}}</td>
                                            <td class="text-right">@php if($t->tra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->tra_fecha_firma));} @endphp </td>
                                            <td class="text-right">@php if($t->tra_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->tra_fecha_recojo));} @endphp </td>
                                            <td class="text-right">

                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('{{url("datos tramite legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                   title="Insertar datos al trámite"><i class="fas fa-pen-alt"></i>
                                                </a>

                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('{{url("f_cambiar tipo tramite/$t->cod_tra")}}','panel_traleg')"
                                                   title="Cambiar tipo de trámite"><i class="fas fa-arrows-alt"></i>
                                                </a>

                                                @can('eliminar traleg - srv')
                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_tra_legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                        title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                            <td class="text-right">
                                                @if($t->id_per!='')
                                                    <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("panel entrega legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                       title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>
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

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="traleg" role="dialog" style="z-index: 1500"  aria-hidden="false" data-backdrop="static">
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
        function enviar1(formulario,ruta,panel){
            $.ajax({
                type: "POST",
                url: ruta,
                data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
                success: function(resp) {
                    $('#'+panel).html(resp);
                    cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
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
        function generarNumero(tipo,url,panel){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-warning'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
            var link = "{{url('/')}}"+"/"+url;
            var token = "{{csrf_token()}}";
            var form ='fecha={{$fecha}}&tipo='+tipo;
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                data:form,
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                    $('#'+panel).html(resp);
                    cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                },
                error: function (data) {
                    $('#'+panel).html("<span class='text-white font-weight-bold bg-danger rounded p-1'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }

        function guardarDatos(ruta,panel,form){
            $.ajax({
                url: ruta,
                type: 'POST',
                data:$('#'+form).serialize(),
                success: function (resp) {
                    $('#'+panel).html(resp);
                    cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                },
                error: function (resp) {
			alert('Error en los datos');
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
    </script>
    <script>
        $('#dataTable').dataTable( {
            "pageLength": 500
        });
    </script>
@endsection
