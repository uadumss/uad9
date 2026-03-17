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
                <h5 class=""><i class="fas fa-user"></i>&nbsp;&nbsp; Autoridades</h5>
                @can('crear tomo - dyt')
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#nuevoTitulo" data-toggle="modal">
                        <i class="fas fa-user-plus"></i> Nueva autoridad</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <a class="btn btn-outline-info text-dark shadow-sm" href="{{url('listar autoridades/')}}">Listar autoridades</a>
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de autoridades deshabilitadas</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="">Datos</th>
                                <th class="">Cargo</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <!--<tfoot>
                            <tr class="bg-gradient-secondary text-white">
                                <th>Nº</th>
                                <th>Número de Tomo</th>
                                <th>Rango de documentos</th>
                                <th>Cantidad de registros</th>
                                <th>Observación</th>
                                <th>Opciones</th>
                            </tr>
                            </tfoot>-->
                            <tbody>
                            <?php $i=1;?>
                            @foreach($autoridad as $a)
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                                    <td class="text-left">
                                        {{$a['aut_nombre']}}<br/>
                                    </td>
                                    <td>
                                        <span class="text-left text-dark font-weight-bold">{{$a['aut_cargo']}}</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{url('/hab_autoridad/'.$a['cod_aut'].'/t')}}" class="btn btn-light btn-circle btn-sm text-danger" title="Deshabilitar autoridad"> <i class="fas fa-user-lock"></i>
                                        </a>

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
    <!--===========================MODAL NUEVA AUTORIDAD===================-->
    @can('crear tomo - dyt')
        <div class="modal fade" id="nuevoTitulo" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <form action="{{url('g_autoridad')}}" method="POST">
                    @csrf
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Nueva autoridad</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                <h6 class="text-white text-center">Formulario de nueva autoridad</h6>
                            </div>
                            <br/>
                            <table class="table table-hover table-sm">
                                <tr>
                                    <th class="text-right font-italic">Nombre :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Cargo :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="cargo" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-primary" type="submit" value="Aceptar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <!--===========================END ==============================-->
    <!--===========================MODAL EDITAR AUTORIDAD===================-->
    @can('editar tomo - dyt')
        <div class="modal fade" id="editarAut" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_editar">

            </div>
        </div>
    @endcan
    <!--===========================END===================-->


    <!-- ================== MODAL CERRAR TOMO===============-->
    @can('consolidar tomo - dyt')
        <div class="modal fade" id="cerrarTomo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-success">
                    <form action="{{url('cerrar_tomo/')}}" method="post">
                        @csrf
                        <input  type="hidden" name="ct" id="ct" value=""/>
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-lock"></i>&nbsp;&nbsp;Consolidar tomo</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span class="font-italic text-dark">Esta seguro de consolidar el tomo :</span> <br/><br/>
                            <div class="row">
                                <div class="font-weight-bold alert-success shadow text-center centrar_bloque col-md-8 p-2" id="panel_cerrar_tomo">

                                </div>
                                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                            </div>
                            <br/>
                            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-10" style="font-size: 0.8em">
                                * Ya no se modificaran los títulos de este tomo<br/>
                                * Esta acción se quedará registrado en el sistema
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-success" type="submit" value="Aceptar"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <!-- =============================== ====================-->
    <!-- ================== MODAL ELIMINAR TOMO-->
    @can('eliminar tomo - dyt')
        <div class="modal fade" id="deshabilitarAut"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-danger">
                    <form action="{{url('e_tomo')}}" method="post">
                        @csrf
                        <input  type="hidden" name="ct" id="ct" value=""/>
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar Tomo</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span class="font-italic">Esta seguro de eliminar : </span><br/><br/>
                            <div class="row">
                                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli">

                                </div>
                                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                            </div>
                            <br/>
                            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-danger" type="submit" value="Aceptar"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!-- =============================== ====================-->

    <script>
        function cargarDatos(ruta,panel){
            var link="{{url('/')}}"+"/"+ruta;
            $.ajax({
                url: link,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    $('#'+panel).html("Ocurrio un error, probablemente no tenga permisos para esta acción");
                }
            });
        }
    </script>

@endsection
