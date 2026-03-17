<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> SANCIONADOS</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario sancionados -->
    <div class="card shadow">
        <div class="modal-body">
            @if(Session::has('exitoModal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('exitoModal') !!}
                </div>
            @endif
            @if(Session::has('errorModal'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('errorModal') !!}
                </div>
            @endif

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h5 class="text-white text-center">Datos de sancionado </h5>
                    </div>
                    <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos del sancionado</span>
                                <table class="col-md-11 table">
                                    <tbody>
                                    <tr>
                                        <th class="text-right font-italic">CI : </th>
                                        <td class="border-bottom border-dark">{{$sancionado->per_ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Nombres : </th>
                                        <td class="border-bottom border-dark">{{$sancionado->per_apellido." ".$sancionado->per_nombre}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Código SIS: </th>
                                        <td class="border-bottom border-dark">{{$sancionado->per_cod_sis}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br/>
                            </div>
                            <div class="col-md-6 shadow border p-2">
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Detalle de la sanción</span>
                                <div class="overflow-auto" style="height: 450px">
                                    <table class="table-sm table">
                                        <tr>
                                            <th>No.</th>
                                            <th>Descripción</th>
                                            <th>Opciones</th>
                                        </tr>
                                        <?php $i=1;?>
                                        @foreach($detalles as $d)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$d->dsan_detalle}}</td>
                                                <td>
                                                    @can('editar detalle sancionado - noa')
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal2"
                                                           onclick="cargarDatos('{{url("editar detalle sancion/".$sancionado->cod_san."/".$d->cod_dsan)}}','panel_modal2')"
                                                           title="Editar detalle"><i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @can('eliminar detalle sancionado - noa')
                                                        <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Modal2" data-toggle="modal"
                                                           onclick="cargarDatos('{{url("formulario eliminar detalle sancion/".$d->cod_dsan)}}','panel_modal2')"
                                                           title="Eliminar detalle"> <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <br/>
                                @can('crear detalle sancioando - noa')
                                <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#Modal2"
                                   onclick="cargarDatos('{{url('editar detalle sancion/'.$sancionado->cod_san.'/0')}}','panel_modal2')">+ Detalle</a>
                                @endcan
                            </div>
                        </div>
                </div>

            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
