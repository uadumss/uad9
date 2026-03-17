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
    @if(sizeof($tramites)==0)
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
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; TRAMITES DE APOSTILLAS</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="float-left ">
                            <div class="input-group">
                                <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;"> Buscar fecha :&nbsp;&nbsp;</span>
                                <input class="form-control form-control-sm" type="date" name="fecha" onchange="$(location).attr('href','{{url("listar tramite apostilla/")}}'+'/'+this.value);" />
                            </div>
                        </div>&nbsp;&nbsp;|&nbsp;&nbsp;
                        @can('crear trámite - apo')
                            @if($fecha==(date('Y-m-d')))
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#apostilla" data-toggle="modal"
                                   onclick="cargarDatos('{{url('editar tramite apostilla/0')}}','panel_apostilla');">
                                     + Apostilla</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            @endif
                        @endcan
                        @can('importar trámite - apo')
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#apostilla" data-toggle="modal">
                                    + Importar trámite</a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        @endcan

                    </div>
                </div>
                <div class="col-md-3">
                    @can('buscar trámite - apo')
                    <div class="input-group float-left">
                        <a haref="#" class="btn btn-sm btn-primary text-white" onclick="cargarDatos('{{url('formulario busqueda apostilla')}}','panel_apostilla')" data-toggle="modal" data-target="#apostilla">
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
                        <h5 class="text-white text-center">Trámites de apostilla</h5>
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
                                @foreach($tramites as $t)
                                        <tr>
                                            <th class="border-right font-weight-bolder">
                                                <span class="text-primary">{{$i}}</span>
                                            </th>
                                            <td class="text-left">UAD{{$t->apos_numero}}</td>
                                            <td class="text-left">{{$t->per_ci}}</td>
                                            <td class="text-left">{{$t->per_apellido." ".$t->per_nombre}}
                                                @if($t->apos_apoderado=='p')
                                                    <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                                                @else
                                                    @if($t->apos_apoderado=='d')
                                                        <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">{{date('d/m/Y',strtotime($t->apos_fecha_ingreso))}}</td>
                                            <td class="text-right">@php if($t->apos_fecha_firma!=''){echo date('d/m/Y',strtotime($t->apos_fecha_firma));} @endphp </td>
                                            <td class="text-right">@php if($t->apos_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->apos_fecha_recojo));} @endphp </td>
                                            <td class="text-right">
                                                @can('editar trámite - apo')
                                                <a href="#apostilla" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("editar tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                                                   title="Insertar datos al trámite"><i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @if($t->apos_estado==1)
                                                    @can('firma trámite - apo')
                                                        <a href="{{url("firmar tramite apostilla/$t->cod_apos")}}" class="btn btn-light btn-circle btn-sm text-primary"
                                                            title="Firmar trámite"> <i class="fas fa-pen-alt"></i>
                                                        </a>
                                                    @endcan
                                                @else
                                                    @if($t->apos_estado==2 || $t->apos_estado==3)
                                                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-success"></i>&nbsp;&nbsp;
                                                    @else
                                                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-dark"></i>&nbsp;&nbsp;
                                                    @endif

                                                @endif
                                                @can('eliminar trámite - apo')
                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('{{url("formulario eliminar tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                                                        title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                            <td class="text-right">
                                                @if($t->apos_estado==2)
                                                    @can('entregar trámite - apo')
                                                        @if($t->cod_apo=='')
                                                            <form method="POST" action="{{url("entrega tramite apostilla")}}" id="{{$t->apos_numero}}">
                                                                @csrf
                                                                <input type="hidden" name="ca" value="{{$t->cod_apos}}">
                                                                <input type="hidden" name="apo" value="T">

                                                            </form>
                                                            <a class="btn btn-light btn-circle btn-sm text-success" onclick="$('#{{$t->apos_numero}}').submit();">
                                                                <i class="fas fa-hand-point-right"></i>
                                                            </a>
                                                        @else
                                                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('{{url("formulario entrega tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                                                               title="Entregar tramite de apostilla"> <i class="fas fa-hand-point-right"></i></a>
                                                        @endif
                                                    @endcan
                                                @else
                                                    @if($t->apos_estado==3)
                                                        <span class="border rounded border-info p-1 text-success">Entregado...</span>
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

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="apostilla" style="z-index: 1500" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_apostilla">

        </div>
    </div>
    <!--===========================END===================-->

    <!-- ================== MODAL DOCLEG ====================-->
    <div class="modal fade" id="tramite_apostilla" role="dialog" style="z-index: 3000; margin-top: 40px;">
        <div class="modal-dialog modal-xl" role="document" id="panel_tramite_apostilla">

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
