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
                @can('crear tomo - rr')
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm font-weight-bold" data-target="#nuevoTramite" data-toggle="modal">
                    +  Legalización</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de legalizaciones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" style="font-size: 0.85em" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Asociado</th>
                                <th class="text-left">Duración</th>
                                <th class="text-right">Costo</th>
                                <th class="text-left">Tipo</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                                @foreach($tramites as $t)
                                    @if($t['tre_hab']=='t')
                                        <tr>
                                    @else
                                        <tr class="alert-danger">
                                    @endif

                                        <th class="border-right font-weight-bolder">
                                             <span class="text-primary">{{$i}}</span>
                                        </th>
                                        <td class="text-left">{{$t['tre_nombre']}}</td>
                                        <td class="text-left">{{strtoupper($t['tre_buscar_en'])}}</td>

                                        <td class="text-left">{{$t['tre_duracion']}}</td>
                                        <td class="text-right">{{$t['tre_costo']}} Bs.</td>
                                        <td class="text-right">{{$t['tre_tipo']}}</td>
                                        <td class="text-right">
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#editarlegalizacion" data-toggle="modal" onclick="cargarDatos('fe_legalizacion/{{$t["cod_tre"]}}','panel_editar')"
                                                    title="Editar legalización"><i class="fas fa-edit"></i>
                                                </a>

                                                <a href="{{url('habilitar legalizacion/'.$t['cod_tre'])}}" class="btn btn-light btn-circle btn-sm" title="Habilitar legalización">
                                                    @if($t['tre_hab']=='t')
                                                        <i class="fas fa-check-square text-success"></i>
                                                    @else
                                                        <i class="fas fa-minus-circle text-danger"></i>
                                                    @endif
                                                </a>
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminar" data-toggle="modal" onclick="cargarDatos('f_eli_legalizacion/{{$t['cod_tre']}}','panel_eli')"
                                                    title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
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
    <!--===========================MODAL NUEVO TOMO===================-->
    @can('crear tomo - rr')
    <div class="modal fade" id="nuevoTramite" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{url('g_legalizacion')}}" method="POST">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-bookmark"></i> Nueva Legalizacion </h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                            <h6 class="text-white text-center">Formulario para nueva legalización</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="row">
                            <div class="col-sm-5">
                                <span class="text-primary font-italic font-weight-bold">* Datos del trámite</span>
                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Nombre : </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="nombre" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Costo (Bs.): </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="costo" pattern="[0-9]{1,4}"/></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Duración (Hrs): </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="duracion"/></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic border-bottom">Buscar en :</th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0 " name="buscar_en">
                                                <option value="db">DB</option>
                                                <option value="ca">CA</option>
                                                <option value="da">DA</option>
                                                <option value="tp">TP</option>
                                                <option value="di">DI</option>
                                                <option value="tpos">TPOS</option>
                                                <option value="re">RE</option>
                                                <option value="su">SU</option>
                                                <option value="rcu">RCU</option>
                                                <option value="rr">RR</option>
                                                <option value="rvr">RVR</option>
                                                <option value="rs">RS</option>
                                                <option value="db-ant">DB-ANTECEDENTE</option>
                                                <option value="ca-ant">CA-ANTECEDENTE</option>
                                                <option value="da-ant">DA-ANTECEDENTE</option>
                                                <option value="tp-ant">TP-ANTECEDENTE</option>
                                                <option value="di-ant">DI-ANTECEDENTE</option>
                                                <option value="tpos-ant">TPOS-ANTECEDENTE</option>
                                                <option value="re-ant">RE-ANTECEDENTE</option>
                                                <option value="su-ant">SU-ANTECEDENTE</option>
                                                <option value="rcu-ant">RCU-ANTECEDENTE</option>
                                                <option value="rr-ant">RR-ANTECEDENTE</option>
                                                <option value="rvr-ant">RVR-ANTECEDENTE</option>
                                                <option value="rs-ant">RS-ANTECEDENTE</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Descripción : </th>
                                        <td class="border-bottom border-dark">
                                            <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-7">
                                <span class="text-primary font-italic font-weight-bold">* Datos de glosa</span>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic"> Título de glosa: </th>
                                        <td class="border-bottom border-dark">
                                            <textarea class="form-control border-0" rows="2" name="titulo" id="titulo"></textarea>
                                            </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic"> Título de glosa (Interno): </th>
                                        <td class="border-bottom border-dark">
                                            <textarea class="form-control border-0" rows="2" name="titulo_interno" id="titulo_interno"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Glosa: </th>
                                        <td class="border-bottom border-dark">
                                            <textarea class="form-control border-0" rows="5" name="glosa" id="glosa"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>

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

    <!--===========================MODAL EDITAR TOMO===================-->
    <div class="modal fade" id="editarlegalizacion" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_editar">

        </div>
    </div>
    <!--===========================END===================-->


    <!-- =============================== ====================-->
    <!-- ================== MODAL ELIMINAR LEGALIZACION ====================-->
    @can('eliminar tomo - rr')
    <div class="modal fade" id="eliminar"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="{{url('eli_legalizacion')}}" method="post">
                    @csrf
                    <input  type="hidden" name="ct" id="ct" value=""/>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar trámite</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="font-italic">Esta seguro de eliminar el trámite de legalización : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli">

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
        function MsgEliminarTomo(tomo,ct){
            $('#ct').val(ct);
            $('#eliTomo').html("El tomo "+tomo+" de la gestión ");
        }
    </script>
@endsection
