
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('error') !!}
        </div>
    @endif

    @if(isset($fallas) && count($fallas)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($fallas as $f)
                    <li>
                        <?php echo "Fila: ".$f->row()." - ";?>
                        <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


            <span class="text-right"><span class="font-weight-bold text-dark">Usuario : </span><a href="#" class="text-primary"> {{$usuario['name']}} </a></span>
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de importaciones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gradient-light text-dark">
                                <th>Nº</th>
                                <th class="text-right">Nº Importación</th>
                                <th>Identificador</th>
                                <th class="text-left">Usuario</th>
                                <th class="text-right">Fecha</th>
                                <th>Tipo</th>
                                <th>Gestión</th>
                                <th>Opciones</th>
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
                            <?php $j=1;?>
                            @foreach($importaciones as $i)
                                <tr style="font-size: 0.9em">
                                    <th class="border-right font-weight-bolder text-primary">{{$j}}</th>
                                    <td class="text-right">{{$i['cod_imp']}}</td>
                                    <td class="text-left">{{$i['imp_identificador']}}</td>
                                    <td class="text-left">{{$i['imp_usuario']}}</td>
                                    <td class="text-right">{{date('d/m/Y H:i:s', strtotime($i['imp_fecha']))}}</td>
                                    <td>{{$i['imp_tipo']}}</td>
                                    <td>{{$i['imp_gestion']}}</td>
                                    <td class="text-right">

                                        <a href="{{url('descargar importacion/'.$i['cod_imp'])}}" class="btn btn-light btn-circle btn-sm text-success" target="{{"excel-".rand(0,9999999)}}">
                                            <i class="fas fa-file-excel"></i>
                                        </a>
                                        @if($i['imp_sistema']=='1')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#verImportacion" data-toggle="modal" onclick="cargarDatos('{{url("datos importacion/".$i['cod_imp'])}}','panel_ver_importacion')">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#verImportacion" data-toggle="modal" onclick="cargarDatos('{{url("datos importacion resolucion/".$i['cod_imp'])}}','panel_ver_importacion')">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif

                                        @if($i['imp_deshecho']=='t')
                                            &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-down">  </i>&nbsp;&nbsp;
                                        @else
                                            <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#deshacerImportacion" data-toggle="modal"
                                               data-placement="top" title="Deshacer importación" onclick="cargarDatos('{{url("f_eli_importacion/".$i['cod_imp'])}}','panel_deshacer_importacion')">
                                                <i class="fas fa-arrow-down"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                                <?php $j++;?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    <!--===========================MODAL VER IMPORTACION===================-->
    <div class="modal fade" id="verImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-upload"></i> Ver importación</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="shadow-sm rounded p-2">
                                <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                                    <h5 class="text-white text-center">Datos de la importación</h5>
                                </div>
                                <div id="panel_ver_importacion">
                                    <div class="spinner-border text-primary centrar_bloque text-center" role="status">
                                        <span class="visually-hidden centrar_bloque center"></span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        </div>
    </div>
    <!--===========================END ==============================-->

    <!<!-- ================== MODAL DESHACER IMPORTACION ============================-->
    <div class="modal fade" id="deshacerImportacion"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="{{url('eliminar importacion')}}" method="post">
                    @csrf
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Deshacer importación</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="font-italic">Esta seguro de deshacer la importación :</span> <br/><br/>
                        <div class="row">
                                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_deshacer_importacion">
                                    <div class="spinner-border text-danger" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                        </div>
                        <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-danger" type="submit" value="Aceptar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- =============================== ====================-->
<script>

    $('#dataTable').dataTable( {
        "pageLength": 500
    });

</script>

