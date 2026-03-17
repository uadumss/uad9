@extends('marco/pagina')
@section('contenido')
    @if(Session::has('exito_importacion'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito_importacion') !!}
        </div>
    @endif
    @if(Session::has('errores'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('errores') !!}
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

    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; Lista de importaciones de Diplomas y títulos</h5>

            </div>
        </div>
        <div class="card-body">
            @can('realizar importación - dyt')
            <div class=" input-group -sm p-2">
                <a href="" class="btn btn-sm btn-outline-info text-dark" data-toggle="modal" data-target="#nuevaImportacion"><i class="fas fa-upload"> Nueva importación</i></a>
            </div>
            @endcan
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Mis importaciones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
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
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary">{{$j}}</th>
                                    <td class="text-right">{{$i['cod_imp']}}</td>
                                    <td class="text-right">{{$i['imp_identificador']}}</td>
                                    <td class="text-left">{{$i['imp_usuario']}}</td>
                                    <td class="text-right">{{date('d/m/Y H:i:s', strtotime($i['imp_fecha']))}}</td>
                                    <td>{{$i['imp_tipo']}}</td>
                                    <td>{{$i['imp_gestion']}}</td>
                                    <td class="text-right">
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#verImportacion" data-toggle="modal" onclick="cargarDatos('{{url("datos importacion/".$i['cod_imp'])}}','panel_ver_importacion')">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $j++;?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('realizar importación - dyt')
    <!--===========================MODAL NUEVA IMPORTACION===================-->
    <div class="modal fade" id="nuevaImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{url('verificar importacion/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nueva importación</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                            <div class="shadow-sm rounded p-2">
                                <h5 class="text-primary text-center">Importar Archivo</h5>
                                <br/>

                                    <table class="col-md-12">
                                        <tr>
                                            <th class="text-dark" style="font-size: 12px">Columnas del archivo:</th>
                                            <td>
                                                <span class="text-danger font-italic font-weight-bold" style="font-size: 12px">[año, año_tit, carrera, Facultad, fecha,	folio, apellido, nombre, numero, Observacion, Tomo, titulo, ci, Pais, pasaporte, referencia, tipo, grado, modalidad, re_fecha, re_universidad. re_pais]</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Archivo :</th>
                                            <td class="">
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="form-control form-control-file" id="archivo" name="archivo" accept=".xlsx,.xls" required>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <input class="btn btn-primary" type="submit" value="Enviar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--===========================END ==============================-->
    @endcan
    <!--===========================MODAL VER IMPORTACION===================-->
    <div class="modal fade" id="verImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Importación</h5>
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
                    alert('No se puede ejecutar la petición');
                }
            });
        }
    </script>
@endsection
