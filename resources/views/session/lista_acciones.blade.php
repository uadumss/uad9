
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

            <div>
                    <a href="#" onclick="cargarDatos('{{url('lista conexiones/'.$conexion->bit_id)}}','panel')" class="btn btn-outline-info btn-sm text-dark shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Lista de conexiones</a>&nbsp;&nbsp;
                <a href="#" onclick="cargarDatos('{{url('lista acciones/'.$conexion->cod_bit)}}','panel')" class="btn btn-outline-info btn-sm text-dark shadow-sm"><i class="fas fa-recycle"> Actualizar</i></a>&nbsp;&nbsp;
                    <span style="font-size: 0.8em">
                        <span class="text-right"><span class="font-weight-bold text-dark">Usuario : </span><a href="#" class="text-primary"> {{$usuario['name']}} </a></span>
                        <span class="font-weight-bold text-dark">Nro. : </span><span class="text-primary">{{$conexion->cod_bit}}</span> |
                        <span class="font-weight-bold text-dark">Inicio : </span><span class="text-primary">{{$conexion->bit_inicio}}</span> |
                        <span class="font-weight-bold text-dark">Fin :  </span><span class="text-primary">{{$conexion->bit_fin}}</span>
                        <span class="font-weight-bold text-dark">IP :  </span><span class="text-primary">{{$conexion->bit_host}}</span>
                    </span>
            </div>
            <div>
                <hr class="sidebar-divider"/>
                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista de acciones</h5>
                        </div>
                        <hr class="sidebar-divider">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable1" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-light text-dark">
                                    <th>Nº</th>
                                    <th>ID</th>
                                    <th class="text-left">Hora</th>
                                    <th class="text-left">Operación</th>
                                    <th class="text-left">Objeto</th>
                                    <th class="text-right">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($acciones as $c)
                                    <tr>
                                        <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                                        <td>{{$c->cod_eve}}</td>
                                        <td>{{date('d/m/Y H:i:s',strtotime($c->created_at))}}</td>
                                        <td>{{$c->eve_operacion}}</td>
                                        <td>{{strtoupper($c->eve_tabla)}}</td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-toggle="modal" data-target="#verAccion" onclick="cargarDatos('{{url('accion/'.$c->cod_eve)}}','panel_accion')">
                                                <i class="fas fa-eye"></i>
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

        <!--===========================MODAL VER ACCION===================-->
        <div class="modal fade" id="verAccion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-list-alt"></i> Acción</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                                <h5 class="text-white text-center">Detalle de la acción</h5>
                            </div>
                            <br/>
                            <div id="panel_accion">

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-primary" type="submit" value="Guardar"/>
                        </div>
                    </div>
            </div>
        </div>
    <script>
        $('#dataTable1').dataTable( {
            "pageLength": 500
        });
    </script>
