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
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp;   RESOLUCIONES DE LA GESTIÓN {{$gestion}}</h5>
                </div>
                <div class="col-md-6">
                    @can('crear tomo - rr')
                        <a href="#" class="d-none d-sm-inline-block float-right btn btn-sm btn-primary shadow-sm" data-target="#nuevoTitulo" data-toggle="modal">
                            <i class="fas fa-book-medical"></i> Nuevo Tomo</a>
                    @endcan
                    <a  href="{{url('resoluciones sintomo/'.$gestion)}}" class="btn btn-sm btn-primary shadow-sm float-right mr-2">
                        Resoluciones sin tomo
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group -sm p-2">
                <div class="col-md-3 input-group shadow-sm p-1" style="font-size: 0.9em">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm border border-info" name="gestion" onchange="$(location).attr('href','{{url('listar tomos resoluciones')}}/'+this.value);">
                        <option value="{{$gestion}}">{{$gestion}}</option>
                        <?php $año=date('Y');?>
                        @for($i=$año;$i>1840;$i--)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de tomos de resoluciones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">Nº Tomo</th>
                                <th class="text-right">Rango de documentos</th>
                                <th>Observación</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                                @foreach($tomos as $t)

                                    <tr>
                                        <th class="border-right font-weight-bolder">
                                            @if($t['tom_cerrado']=='t')<span class="text-danger font-weight-bold">{{$i}} *</span>
                                            @else <span class="text-primary">{{$i}}</span>

                                            @endif
                                        </th>
                                        <td class="text-right">{{$t['tom_numero']}}</td>
                                        <td class="text-right">{{$t['tom_rango']}}</td>

                                        <td class="justify-content-center">{{$t['tom_obs']}}</td>
                                        <td class="text-right">
                                            @can('editar tomo - rr')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#editarTomo" data-toggle="modal" onclick="cargarDatos('{{url("fe_tomo_res/".$t["cod_tom"])}}','panel_editar')"
                                                    title="Editar Tomo"><i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            <a href="{{url('listar resoluciones/todos/'.$t['cod_tom'])}}" class="btn btn-light btn-circle btn-sm" title="Listar resoluciones">
                                                <i class="fas fa-file"></i>
                                            </a>

                                            @if($t['tom_cerrado']=='t')
                                                &nbsp;<span style="font-size: 0.8em">  <i class="fas fa-lock text-danger"></i>     </span>&nbsp;
                                            @else
                                                    @can('consolidar tomo - rr')
                                                    <a class="btn btn-light btn-circle btn-sm text-success" data-target="#cerrarTomo" data-toggle="modal" onclick="cargarDatos('{{url("cerrar tomo resolucion/".$t['cod_tom'])}}','panel_cerrar_tomo')"
                                                       title="Consolidar Tomo"><i class="fas fa-lock-open"></i>
                                                    </a>
                                                    @endcan
                                            @endif
                                            @can('eliminar tomo - rr')
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminarTomo" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_tomo_res/".$t['cod_tom'])}}','panel_eli_Tomo')"
                                                    title="Eliminar Tomo"> <i class="fas fa-trash-alt"></i>
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
    <!--===========================MODAL NUEVO TOMO===================-->
    @can('crear tomo - rr')
    <div class="modal fade" id="nuevoTitulo" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{url('g_tomo_res')}}" method="POST">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nuevo Tomo de resoluciones </h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                            <h6 class="text-white text-center">Formulario para nuevo tomo de resoluciones</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <table class="table-hover">
                            <tr>
                                <th class="text-right font-italic">Número de tomo : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder="Ingrese el número del tomo"
                                           required name="n_tomo" pattern="[0-9]{1,3}"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Gestión : </th>
                                <td class="border-bottom border-dark">
                                    <select class="custom-select custom-select-sm border-0" name="gestion">
                                        <option value="{{$gestion}}">{{$gestion}}</option>
                                        <?php $año=date('Y');?>
                                        @for($i=$año;$i>1927;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Rango de páginas : </th>
                                <td class="border-bottom border-dark ">
                                    <div class="form-group row">
                                        &nbsp;&nbsp; De: &nbsp;&nbsp;<input name="r_menor" required class="form-control col-md-3 form-control-sm border-0" pattern="[0-9]{1,5}"> &nbsp;&nbsp;
                                        Hasta: &nbsp;&nbsp;<input name="r_mayor" required class="form-control col-md-3 form-control-sm border-0" pattern="[0-9]{1,5}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Observación: </th>
                                <td class="border-bottom border-dark">
                                    <textarea class="form-control border-0" rows="3" name="obs" id="obs"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-primary" type="submit" value="Aceptar"/>
                    </div>
                </div>
                <input type="hidden" name="tipo" value="res">
            </form>
        </div>
    </div>
    @endcan
    <!--===========================END ==============================-->
    @can('editar tomo - rr')
    <!--===========================MODAL EDITAR TOMO===================-->

    <div class="modal fade" id="editarTomo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" id="panel_editar">

        </div>
    </div>
    @endcan
    <!--===========================END===================-->

    <!-- ================== MODAL CERRAR TOMO===============-->
    @can('consolidar tomo - rr')
    <div class="modal fade" id="cerrarTomo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-success">
                <form action="{{url('cerrar_tomo_res/')}}" method="post">
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
                            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                        </div>
                        <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-10" style="font-size: 0.8em">
                            * Ya no se modificaran las resoluciones de este tomo<br/>
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
    <!-- ================== MODAL ELIMINAR TOMO RESOLUCION ====================-->
    @can('eliminar tomo - rr')
    <div class="modal fade" id="eliminarTomo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="{{url('e_tomo_res')}}" method="post">
                    @csrf
                    <input  type="hidden" name="ct" id="ct" value=""/>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar Tomo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="font-italic">Esta seguro de eliminar el tomo de resoluciones : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli_Tomo">

                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
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

        function MsgEliminarTomo(tomo,ct){
            $('#ct').val(ct);
            $('#eliTomo').html("El tomo "+tomo+" de la gestión {{$gestion}}");
        }
    </script>
@endsection
