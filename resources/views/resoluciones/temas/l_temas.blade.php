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
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;TEMAS DE INTERES </h5>
                </div>
                <div class="col-md-6">


                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2" data-target="#Tema" data-toggle="modal"
                               onclick="cargarDatos('{{url("fe_tema/0")}}','panel_tema')">
                                + Tema </a>&nbsp;&nbsp;

                </div>
            </div>
        </div>
        <div class="card-body">

            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">TEMAS</h6>
                        </div>

                        <hr class="sidebar-divider"/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($temas as $t)
                                    <tr>
                                        <td class="">{{$i}}</td>
                                        <td class="">{{$t->tem_titulo}}</td>
                                        <td>{{$t->tem_des}}</td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#Tema" data-toggle="modal"
                                                       onclick="cargarDatosTitulo('{{url('fe_tema/'.$t->cod_tem)}}','panel_tema')" title="Editar resolución">
                                                        <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{url("tema resolucion/".$t->cod_tem)}}" class="btn btn-light btn-circle btn-sm text-primary" title="Mostrar resoluciones">
                                                <i class="fas fa-arrow-circle-right"></i></a>
                                            <a href="{{url("descargar resoluciones temas/".$t->cod_tem)}}" class="btn btn-light btn-circle btn-sm text-primary" title="Descargar resoluciones">
                                                <i class="fas fa-file-download"></i></a>
                                            <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Tema" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("f_eli_tema/".$t->cod_tem)}}','panel_tema')"
                                                   title="Eliminar resolución"><i class="fas fa-trash-alt"></i>
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
        <!--=================================EDITAR RESOLUCION========================-->
        <div class="modal fade" id="Tema" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_tema">

            </div>
        </div>

        <!--=============================END==================================-->
@endsection
