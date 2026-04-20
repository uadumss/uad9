@extends('marco/pagina')
@section('contenido')
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
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Facultades</h5>
                </div>
            </div>
        <div class="card-body">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background-color:#e1edff">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="text-dark"><i class="fas fa-university"></i>&nbsp;Lista facultades</h5>
                        @can('crear editar facultad - f')
                            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-white" data-toggle="modal" data-target="#facultad"
                               onclick="cargarDatos('fe_facultad/0','panel_contenido')" >
                                + Facultad
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body" id="panel_lista_facultades">
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="">Nombre</th>
                                <th class="">Nombre corto</th>
                                <th class="text-center align-middle" style="width: 190px; min-width: 190px;">Opciones</th>
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
                            @foreach($facultades as $f)
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary">{{$j}}</th>
                                    <td class="text-left">{{$f['fac_nombre']}}</td>
                                    <td class="text-left">{{$f['fac_abreviacion']}}</td>
                                    <td class="text-center align-middle" style="min-width: 190px;">
                                        <div class="d-inline-flex align-items-center justify-content-center flex-nowrap">
                                            @can('crear editar facultad - f')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary mr-1" data-target="#efacultad" data-toggle="modal"
                                               onclick="cargarDatos('fe_facultad/{{$f['cod_fac']}}','panel_econtenido')" title="Editar facultad">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('eliminar facultad - f')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger mr-1" data-target="#efacultad" data-toggle="modal"
                                               onclick="cargarDatos('f_eli_facultad/{{$f['cod_fac']}}','panel_econtenido')" title="Eliminar facultad">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            @endcan
                                            @canany(['crear editar facultad - f','eliminar facultad - f'])
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-info mr-1" data-target="#efacultad" data-toggle="modal"
                                               onclick="cargarDatos('f_historial_facultad/{{$f['cod_fac']}}','panel_econtenido')" title="Ver historial de nombres">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            @endcanany
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary"
                                               onclick="mostrarCarreras('{{$f['cod_fac']}}'); return false;" title="Ver carreras">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                                <?php $j++;?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background-color:#e1edff">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="text-dark"><i class="fas fa-book"></i>&nbsp;Lista de carreras</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="panel_carrera">

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    @can('acceso al sistema - f')
        <!--===========================MODAL NUEVA FACULTAD===================-->
        <div class="modal fade" id="facultad" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_contenido">

            </div>
        </div>

        <!--===========================END ==============================-->

        <!--===========================MODAL ELIMINAR FACULTAD===================-->
        <div class="modal fade" id="efacultad" role="dialog" style="z-index:1500" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_econtenido">

            </div>
        </div>

        <!--===========================END ==============================-->
    @endcan
    <!-- =============================== ====================-->

    <script>
        function mostrarCarreras(codFacultad){
            cargarDatos('l_carrera/' + codFacultad, 'panel_carrera');

            var panelCarrera = document.getElementById('panel_carrera');
            if(panelCarrera){
                panelCarrera.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>

@endsection
